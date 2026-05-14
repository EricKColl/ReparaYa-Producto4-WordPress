<a href="<?= base_url('tecnicos') ?>" class="top-link">← Volver al listado</a>
<h2>Editar técnico</h2>

<?php if (!empty($error)): ?>
    <p class="message-error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="<?= base_url('tecnicos/update') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($tecnico['id']) ?>">

    <div>
        <label for="usuario_id">Cuenta de usuario técnico</label>
        <select id="usuario_id" name="usuario_id" required>
            <option value="">Selecciona una cuenta</option>
            <?php foreach ($usuariosTecnicos as $usuario): ?>
                <option
                    value="<?= htmlspecialchars($usuario['id']) ?>"
                    <?= (int) ($tecnico['usuario_id'] ?? 0) === (int) $usuario['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($usuario['nombre'] . ' (' . $usuario['email'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="nombre_completo">Nombre completo</label>
        <input
            type="text"
            id="nombre_completo"
            name="nombre_completo"
            value="<?= htmlspecialchars($tecnico['nombre_completo']) ?>"
            required
        >
    </div>

    <div>
        <label for="especialidad_id">Especialidad</label>
        <select id="especialidad_id" name="especialidad_id" required>
            <option value="">Selecciona una especialidad</option>
            <?php foreach ($especialidades as $especialidad): ?>
                <option
                    value="<?= htmlspecialchars($especialidad['id']) ?>"
                    <?= (int) $tecnico['especialidad_id'] === (int) $especialidad['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($especialidad['nombre_especialidad']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>
            <input
                type="checkbox"
                name="disponible"
                value="1"
                <?= !empty($tecnico['disponible']) ? 'checked' : '' ?>
            >
            Disponible
        </label>
    </div>

    <button type="submit">Actualizar</button>
</form>