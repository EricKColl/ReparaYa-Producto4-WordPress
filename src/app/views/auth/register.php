<h2>Registro de usuario</h2>

<?php if (isset($_GET['error'])): ?>
    <div style="color: red; margin-bottom: 15px;">
        <?php if ($_GET['error'] === 'campos_vacios'): ?>
            <p>Debes rellenar todos los campos obligatorios.</p>
        <?php elseif ($_GET['error'] === 'email_invalido'): ?>
            <p>El formato del email no es válido.</p>
        <?php elseif ($_GET['error'] === 'email_duplicado'): ?>
            <p>Ya existe un usuario registrado con ese email.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('register') ?>" method="POST" autocomplete="off">
    <div>
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <br>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required autocomplete="off">
    </div>

    <br>

    <div>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required autocomplete="new-password">
    </div>

    <br>

    <div>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono">
    </div>

    <br>

    <button type="submit">Registrarse</button>
</form>

<br>

<p><a href="<?= base_url('login') ?>">Ir al login</a></p>
<p><a href="<?= base_url() ?>">Volver al inicio</a></p>