<a href="<?= base_url('admin') ?>" class="top-link" style="display: inline-block; margin-bottom: 15px; text-decoration: none; color: #2563eb;">← Volver al panel</a>

<h2>Editar Incidencia <small style="font-size:1.2rem;color:#64748b;"><?= htmlspecialchars($incidencia['localizador']) ?></small></h2>

<?php if (isset($error)): ?>
    <p style="color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="<?= base_url('admin/update') ?>" method="POST" style="max-width:680px; background: #fff; padding: 20px; border-radius: 8px;">
    <input type="hidden" name="id" value="<?= $incidencia['id'] ?>">

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Especialidad / Tipo de servicio</label>
    <select name="especialidad_id" required style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block;">
        <?php foreach ($especialidades as $e): ?>
            <option value="<?= $e['id'] ?>" <?= $e['id'] == $incidencia['especialidad_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($e['nombre_especialidad']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tipo de urgencia</label>
    <select name="tipo_urgencia" style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block;">
        <option value="Estandar" <?= $incidencia['tipo_urgencia'] === 'Estandar' ? 'selected' : '' ?>>Estándar (plazo normal)</option>
        <option value="Urgente" <?= $incidencia['tipo_urgencia'] === 'Urgente' ? 'selected' : '' ?>>Urgente (24h)</option>
    </select>

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Estado de la incidencia</label>
    <select name="estado" style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block;">
        <?php foreach (['Pendiente', 'Asignada', 'Finalizada', 'Cancelada'] as $s): ?>
            <option value="<?= $s ?>" <?= $incidencia['estado'] === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
    </select>

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Fecha y hora del servicio</label>
    <input
        type="datetime-local"
        name="fecha_servicio"
        value="<?= date('Y-m-d\TH:i', strtotime($incidencia['fecha_servicio'])) ?>"
        required
        style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block; box-sizing: border-box;">

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Dirección</label>
    <input
        type="text"
        name="direccion"
        value="<?= htmlspecialchars($incidencia['direccion']) ?>"
        required
        style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block; box-sizing: border-box;">

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Teléfono de contacto</label>
    <input
        type="text"
        name="telefono_contacto"
        value="<?= htmlspecialchars($incidencia['telefono_contacto']) ?>"
        required
        style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block; box-sizing: border-box;">

    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Descripción de la avería</label>
    <textarea
        name="descripcion"
        rows="4"
        style="width: 100%; max-width: 100%; padding: 8px; margin-bottom: 15px; display: block; box-sizing: border-box; font-family: inherit;"><?= htmlspecialchars($incidencia['descripcion']) ?></textarea>

    <br>
    <button type="submit" style="background-color: #2563eb; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
        Guardar cambios
    </button>
</form>