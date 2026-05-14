<h2>Crear especialidad</h2>

<?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="<?= base_url('especialidades/store') ?>" method="POST">
    <div>
        <label for="nombre_especialidad">Nombre de la especialidad:</label>
        <input type="text" id="nombre_especialidad" name="nombre_especialidad">
    </div>

    <br>

    <button type="submit">Guardar</button>
</form>

<br>

<a href="<?= base_url('especialidades') ?>">Volver al listado</a>