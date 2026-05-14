<h2>Crear nuevo usuario</h2>

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

<form action="<?= base_url('usuarios/store') ?>" method="POST" autocomplete="off">
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
        <label for="rol">Rol:</label><br>
        <select id="rol" name="rol" required>
            <option value="particular">Particular</option>
            <option value="tecnico">Técnico</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <br>

    <div>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono">
    </div>

    <br>

    <button type="submit">Guardar usuario</button>
</form>

<br>

<a href="<?= base_url('usuarios') ?>">Volver al listado</a>