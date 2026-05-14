<h2>Panel de Administración</h2>

<?php if (isset($_GET['ok'])): ?>
    <p class="message-success">
        <?php
        $msgs = [
            'creada'      => 'Incidencia creada.',
            'actualizada' => 'Incidencia actualizada.',
            'cancelada'   => 'Incidencia cancelada.',
            'eliminada'   => 'Incidencia eliminada definitivamente.',
            'asignado'    => 'Técnico asignado.',
            'error'       => 'No se pudo completar la operación.'
        ];
        echo htmlspecialchars($msgs[$_GET['ok']] ?? 'Operación completada.');
        ?>
    </p>
<?php endif; ?>

<p>
    <a href="<?= base_url('admin/create') ?>" class="top-link">+ Nueva incidencia</a>
    <a href="<?= base_url('admin/calendario') ?>" class="top-link" style="margin-left:10px">📅 Calendario</a>
</p>

<table style="width:100%;border-collapse:collapse;font-size:1rem;">
    <thead>
        <tr style="background:#e0ecff;text-align:left;">
            <th style="padding:10px 12px;">Código</th>
            <th style="padding:10px 12px;">Cliente</th>
            <th style="padding:10px 12px;">Especialidad</th>
            <th style="padding:10px 12px;">Fecha</th>
            <th style="padding:10px 12px;">Urgencia</th>
            <th style="padding:10px 12px;">Estado</th>
            <th style="padding:10px 12px;">Asignar técnico</th>
            <th style="padding:10px 12px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($incidencias as $inc): ?>
            <?php
                $filaStyle = $inc['estado'] === 'Cancelada'
                    ? 'background:#fff1f2;border-bottom:1px solid #fecdd3;'
                    : 'border-bottom:1px solid #e2e8f0;';
            ?>
            <tr style="<?= $filaStyle ?>">

                <td style="padding:10px 12px;font-weight:700;">
                    <?= htmlspecialchars($inc['localizador']) ?>
                </td>

                <td style="padding:10px 12px;">
                    <?= htmlspecialchars($inc['cliente_nombre']) ?>
                </td>

                <td style="padding:10px 12px;">
                    <?= htmlspecialchars($inc['nombre_especialidad']) ?>
                </td>

                <td style="padding:10px 12px;">
                    <?= htmlspecialchars(date('d/m/Y H:i', strtotime($inc['fecha_servicio']))) ?>
                </td>

                <td style="padding:10px 12px;">
                    <?php if ($inc['tipo_urgencia'] === 'Urgente'): ?>
                        <span style="background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:8px;font-weight:700;">Urgente</span>
                    <?php else: ?>
                        <span style="background:#dcfce7;color:#166534;padding:3px 8px;border-radius:8px;font-weight:700;">Estándar</span>
                    <?php endif; ?>
                </td>

                <td style="padding:10px 12px;">
                    <?php if ($inc['estado'] === 'Pendiente'): ?>
                        <span style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:8px;font-weight:700;">Pendiente</span>
                    <?php elseif ($inc['estado'] === 'Asignada'): ?>
                        <span style="background:#dbeafe;color:#1d4ed8;padding:3px 8px;border-radius:8px;font-weight:700;">Asignada</span>
                    <?php elseif ($inc['estado'] === 'Finalizada'): ?>
                        <span style="background:#dcfce7;color:#166534;padding:3px 8px;border-radius:8px;font-weight:700;">Finalizada</span>
                    <?php else: ?>
                        <span style="background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:8px;font-weight:700;">Cancelada</span>
                    <?php endif; ?>
                </td>

                <td style="padding:6px 12px;">
                    <?php if ($inc['estado'] !== 'Cancelada' && $inc['estado'] !== 'Finalizada'): ?>
                        <?php $tecnicosDisp = $tecnicosPorEspecialidad[$inc['especialidad_id']] ?? []; ?>
                        <form action="<?= base_url('admin/asignar') ?>" method="POST" style="display:flex;gap:6px;align-items:center;">
                            <input type="hidden" name="incidencia_id" value="<?= $inc['id'] ?>">
                            <select name="tecnico_id" style="margin:0;padding:6px 10px;font-size:0.9rem;width:auto;max-width:200px;">
                                <option value="">— Sin asignar —</option>
                                <?php foreach ($tecnicosDisp as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= $inc['tecnico_id'] == $t['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['nombre_completo']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (empty($tecnicosDisp)): ?>
                                    <option disabled>No hay técnicos disponibles</option>
                                <?php endif; ?>
                            </select>
                            <button type="submit" style="padding:6px 14px;font-size:0.95rem;">✓</button>
                        </form>
                        <?php if ($inc['tecnico_nombre']): ?>
                            <small style="color:#64748b;margin-top:4px;display:block;">
                                Actual: <?= htmlspecialchars($inc['tecnico_nombre']) ?>
                            </small>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color:#94a3b8;font-size:0.9rem;">
                            <?= htmlspecialchars($inc['tecnico_nombre'] ?: '—') ?>
                        </span>
                    <?php endif; ?>
                </td>

                <td style="padding:10px 12px; white-space:nowrap;">
                    <a href="<?= base_url('admin/edit') ?>?id=<?= urlencode($inc['id']) ?>" class="action-link" style="margin-right:10px;">Editar</a>

                    <?php if ($inc['estado'] !== 'Cancelada'): ?>
                        <form action="<?= base_url('admin/delete') ?>" method="POST" class="inline-form"
                              onsubmit="return confirm('¿Cancelar esta incidencia?')"
                              style="display:inline-block;margin-right:8px;">
                            <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                            <button type="submit">Cancelar</button>
                        </form>
                    <?php else: ?>
                        <span style="display:inline-block;margin-right:10px;color:#b91c1c;font-weight:700;">Ya cancelada</span>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/destroy') ?>" method="POST" class="inline-form"
                          onsubmit="return confirm('¿Eliminar definitivamente esta incidencia? Esta acción no se puede deshacer.')"
                          style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                        <button type="submit" style="background:#7f1d1d;">Eliminar</button>
                    </form>
                </td>

            </tr>
        <?php endforeach; ?>

        <?php if (empty($incidencias)): ?>
            <tr>
                <td colspan="8" style="padding:20px;text-align:center;color:#64748b;">
                    No hay incidencias registradas.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>