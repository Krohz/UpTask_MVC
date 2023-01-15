<div class="contenedor reestablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Nuevo Password</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if ($mostrar) { ?>

        <form method="POST" class="formulario">
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                >
            </div>
            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <?php } ?>
        <div class="acciones">
            <a href="/crear">¿Áun no tienes una cuenta? Crear una</a>
            <a href="/olvide">¿Olvidaste tu Password? Recuperar</a>
        </div>
    </div>
</div>