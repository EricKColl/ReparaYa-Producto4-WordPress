<a href="<?= base_url('tecnicos') ?>" class="top-link">← Volver al listado</a>
<h2>Crear técnico</h2>

<?php if (!empty($error)): ?>
    <p class="message-error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($usuariosTecnicos)): ?>
    <p class="message-error">
        No hay usuarios con rol técnico disponibles para vincular. Primero crea un usuario con rol <strong>tecnico</strong> en el módulo de usuarios.
    </p>
<?php endif; ?>

<form action="<?= base_url('tecnicos/store') ?>" method="POST">
    <div>
        <label for="usuario_id">Cuenta de usuario técnico</label>
        <select id="usuario_id" name="usuario_id" required>
            <option value="">Selecciona una cuenta</option>
            <?php foreach ($usuariosTecnicos as $usuario): ?>
                <option value="<?= htmlspecialchars($usuario['id']) ?>">
                    <?= htmlspecialchars($usuario['nombre'] . ' (' . $usuario['email'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="nombre_completo">Nombre completo</label>
        <input type="text" id="nombre_completo" name="nombre_completo" required>
    </div>

    <div>
        <label for="especialidad_id">Especialidad</label>
        <select id="especialidad_id" name="especialidad_id" required>
            <option value="">Selecciona una especialidad</option>
            <?php foreach ($especialidades as $especialidad): ?>
                <option value="<?= htmlspecialchars($especialidad['id']) ?>">
                    <?= htmlspecialchars($especialidad['nombre_especialidad']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>
            <input type="checkbox" name="disponible" value="1" checked>
            Disponible
        </label>
    </div>

    <button type="submit">Guardar</button>
</form>