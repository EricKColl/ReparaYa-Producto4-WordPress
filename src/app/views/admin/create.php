<a href="<?= base_url('admin') ?>" class="top-link">← Volver al panel</a>
<h2>Nueva Incidencia</h2>

<?php if (isset($error)): ?>
    <p class="message-error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="<?= base_url('admin/store') ?>" method="POST" style="max-width:680px;">

    <label>Cliente</label>
    <select id="cliente_id" name="cliente_id" required style="max-width:100%;">
        <option value="">— Selecciona cliente —</option>
        <?php foreach ($clientes as $c): ?>
            <option
                value="<?= $c['id'] ?>"
                data-telefono="<?= htmlspecialchars($c['telefono'] ?? '') ?>"
            >
                <?= htmlspecialchars($c['nombre'] . ' (' . $c['email'] . ')') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Especialidad / Tipo de servicio</label>
    <select name="especialidad_id" required style="max-width:100%;">
        <option value="">— Selecciona especialidad —</option>
        <?php foreach ($especialidades as $e): ?>
            <option value="<?= $e['id'] ?>">
                <?= htmlspecialchars($e['nombre_especialidad']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tipo de urgencia</label>
    <select name="tipo_urgencia" style="max-width:100%;">
        <option value="Estandar">Estándar (plazo normal)</option>
        <option value="Urgente">Urgente (24h)</option>
    </select>

    <label>Fecha y hora del servicio</label>
    <input
        type="datetime-local"
        name="fecha_servicio"
        required
        style="max-width:100%; display:block; margin-bottom:15px;"
    >

    <label>Dirección</label>
    <input
        type="text"
        name="direccion"
        placeholder="Calle, número, ciudad"
        required
        style="max-width:100%; display:block;"
    >

    <label>Teléfono de contacto</label>
    <input
        type="text"
        id="telefono_contacto"
        name="telefono_contacto"
        placeholder="Ejemplo: 600123123"
        required
        style="max-width:100%; display:block;"
    >

    <label>Descripción de la avería</label>
    <textarea
        name="descripcion"
        rows="4"
        required
        placeholder="Describe el problema..."
        style="max-width:100%;"
    ></textarea>

    <br>
    <button type="submit">Crear incidencia</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const clienteSelect = document.getElementById('cliente_id');
    const telefonoInput = document.getElementById('telefono_contacto');

    function actualizarTelefonoCliente() {
        const opcionSeleccionada = clienteSelect.options[clienteSelect.selectedIndex];
        const telefono = opcionSeleccionada ? (opcionSeleccionada.dataset.telefono || '') : '';
        telefonoInput.value = telefono;
    }

    clienteSelect.addEventListener('change', actualizarTelefonoCliente);

    if (clienteSelect.value) {
        actualizarTelefonoCliente();
    }
});
</script>