<div class="contenedor login">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Inicar Sesión</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form method = "POST" action="/" class="formulario">
            <div class="campo">
                <label for="email">E-mail</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu E-Mail"
                    name="email"
                >
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                >
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
            <p>C.A</p>
        </form>
        <div class="acciones">
            <a href="/crear">¿Áun no tienes una cuenta? Crear una</a>
            <a href="/olvide">¿Olvidaste tu Password? Recuperar</a>
        </div>
    </div>
</div>