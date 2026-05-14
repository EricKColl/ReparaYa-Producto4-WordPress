<h2>Iniciar sesión</h2>

<?php if (isset($_GET['error'])): ?>
    <div style="color: red; margin-bottom: 15px;">
        <?php if ($_GET['error'] === 'credenciales_invalidas'): ?>
            <p>Email o contraseña incorrectos.</p>
        <?php elseif ($_GET['error'] === 'campos_vacios'): ?>
            <p>Debes rellenar email y contraseña.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('login') ?>" method="POST" autocomplete="off">
    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required autocomplete="off">
    </div>

    <br>

    <div>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>

    <br>

    <button type="submit">Entrar</button>
</form>

<br>

<p><a href="<?= base_url('register') ?>">¿No tienes cuenta? Regístrate aquí</a></p>
<p><a href="<?= base_url() ?>">Volver al inicio</a></p>