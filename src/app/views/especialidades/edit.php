<h2>Editar especialidad</h2>

<?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="<?= base_url('especialidades/update') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($especialidad['id']) ?>">

    <div>
        <label for="nombre_especialidad">Nombre de la especialidad:</label>
        <input
            type="text"
            id="nombre_especialidad"
            name="nombre_especialidad"
            value="<?= htmlspecialchars($especialidad['nombre_especialidad']) ?>"
        >
    </div>

    <br>

    <button type="submit">Actualizar</button>
</form>

<br>

<a href="<?= base_url('especialidades') ?>">Volver al listado</a>