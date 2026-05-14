<h2>Mi perfil</h2>

<?php if (isset($_GET['error'])): ?>
    <div style="color: red; margin-bottom: 15px;">
        <?php if ($_GET['error'] === 'campos_vacios'): ?>
            <p>Debes rellenar los campos obligatorios.</p>
        <?php elseif ($_GET['error'] === 'email_invalido'): ?>
            <p>El formato del email no es válido.</p>
        <?php elseif ($_GET['error'] === 'email_duplicado'): ?>
            <p>Ya existe otro usuario registrado con ese email.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'perfil_actualizado'): ?>
    <div style="color: green; margin-bottom: 15px;">
        <p>Perfil actualizado correctamente.</p>
    </div>
<?php endif; ?>

<form action="<?= base_url('perfil/update') ?>" method="POST" autocomplete="off">
    <div>
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    </div>

    <br>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required autocomplete="off">
    </div>

    <br>

    <div>
        <label for="password">Nueva contraseña:</label><br>
        <input type="password" id="password" name="password" value="" autocomplete="new-password">
        <p style="margin: 5px 0 0 0; font-size: 14px;">Déjala vacía si no quieres cambiar la contraseña.</p>
    </div>

    <br>

    <div>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
    </div>

    <br>

    <button type="submit">Actualizar perfil</button>
</form>