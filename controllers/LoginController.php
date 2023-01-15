<?php 

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController{
    public static function login(Router $router){
        $alertas = [];

        // Cuando es POST
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();
            if (empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    // El usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {
                        // Inicar sesión
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'El Password es incorrecto');
                    }
                }
                
            }
        }

        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/login', [
            'titulo' => "Iniciar Sesión",
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;

        // Cuando es POST
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar token
                    $usuario->crearToken();

                    // Crear un nuevo Usuario
                    $resultado = $usuario->guardar();

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        // Render a la vista
        $router->render('auth/crear', [
            'titulo' => "Crear Cuenta",
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];

        // Cuando es POST
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            
            if (empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado) {
                    // Existe usuario
                    // Generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email= new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();


                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                }else{
                    // No existe
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confimado');    
                }
            }
        }


        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/olvide', [
            'titulo' => "Olvide mi Password",
            'alertas' => $alertas
        ]);
    }

    
    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $mostrar = true;

        if (!$token) {
            header('Location: /');
        }

        //Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);
        
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }


        // Cuando es POST
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Valida el password
            $alertas=$usuario->validarPassword();
            if (empty($alertas)) {
                // Hashear el password
                $usuario->hashPassword();
                unset($usuario->password2);

                //Eliminar el token
                $usuario->token='';

                // Guardar el usuario
                $resultado=$usuario->guardar();

                // Redireccionar
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas=Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Restablece tu password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada'
        ]);
    }

    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        if (!$token) {
            header('Location: /');
        }

        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // No se encontro un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            // Confirmar la cuenta
            $usuario->confirmado=1;
            $usuario->token= '';
            unset($usuario->password2);
            
            // Guardar el usuario confirmado
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Usuario Confirmado');
        }

        $alertas = Usuario::getAlertas();


        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta confirmada',
            'alertas' => $alertas
        ]);
    }
}