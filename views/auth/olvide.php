<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form method = "POST" action="/olvide" class="formulario">
            <div class="campo">
                <label for="email">E-mail</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu E-Mail"
                    name="email"
                >
            </div>
            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Áun no tienes una cuenta? Crear una</a>
        </div>
    </div>
</div>