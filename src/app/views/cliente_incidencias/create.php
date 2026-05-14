<style>
    .cli-form-wrap {
        display: grid;
        gap: 28px;
    }

    .cli-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 34px 30px 38px;
        background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.06);
        text-align: center;
    }

    .cli-form-hero::before {
        content: '';
        position: absolute;
        top: -90px;
        right: -90px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.14), transparent 70%);
    }

    .cli-form-hero::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.10), transparent 70%);
    }

    .cli-form-hero > * {
        position: relative;
        z-index: 1;
    }

    .cli-form-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 999px;
        background: #e8f1ff;
        border: 1px solid #cfe0ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.94rem;
        margin-bottom: 18px;
    }

    .cli-form-title {
        margin: 0;
        font-size: clamp(2.8rem, 4.8vw, 4.6rem);
        line-height: 0.98;
        letter-spacing: -0.03em;
        color: #0f172a;
        font-weight: 900;
    }

    .cli-form-subtitle {
        margin: 18px auto 0;
        max-width: 980px;
        color: #475569;
        font-size: 1.1rem;
        line-height: 1.95;
        text-wrap: balance;
    }

    .cli-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 26px;
        padding: 28px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .cli-section-head {
        margin-bottom: 22px;
    }

    .cli-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eef4ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .cli-section-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #2563eb;
        display: inline-block;
        flex-shrink: 0;
    }

    .cli-section-title {
        margin: 0 0 10px 0;
        font-size: clamp(1.9rem, 2.7vw, 2.5rem);
        line-height: 1.08;
        color: #0f172a;
    }

    .cli-section-text {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        line-height: 1.85;
        max-width: 980px;
    }

    .cli-info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .cli-info-card {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 22px 20px;
        border: 1px solid #dbeafe;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.45);
    }

    .cli-info-card::after {
        content: '';
        position: absolute;
        top: -36px;
        right: -36px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        opacity: 0.35;
    }

    .cli-info-card.client {
        background: linear-gradient(180deg, #eff6ff 0%, #f8fbff 100%);
    }

    .cli-info-card.client::after {
        background: radial-gradient(circle, rgba(37,99,235,0.30), transparent 70%);
    }

    .cli-info-card.mail {
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 100%);
    }

    .cli-info-card.mail::after {
        background: radial-gradient(circle, rgba(99,102,241,0.24), transparent 70%);
    }

    .cli-info-card.phone {
        background: linear-gradient(180deg, #eefcf4 0%, #f8fffb 100%);
        border-color: #ccefdc;
    }

    .cli-info-card.phone::after {
        background: radial-gradient(circle, rgba(22,163,74,0.24), transparent 70%);
    }

    .cli-info-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: #0f172a;
        font-weight: 800;
        font-size: 1.02rem;
    }

    .cli-info-icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
        color: #ffffff;
        flex-shrink: 0;
    }

    .cli-info-card.client .cli-info-icon {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    .cli-info-card.mail .cli-info-icon {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    }

    .cli-info-card.phone .cli-info-icon {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    }

    .cli-info-value {
        color: #334155;
        line-height: 1.7;
        font-size: 1.08rem;
        word-break: break-word;
    }

    .cli-form-note {
        margin-top: 18px;
        padding: 16px 18px;
        border-radius: 16px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e3a8a;
        font-weight: 600;
        line-height: 1.75;
    }

    .cli-rule-note {
        margin-top: 18px;
        padding: 16px 18px;
        border-radius: 16px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        font-weight: 700;
        line-height: 1.75;
    }

    .cli-request-form {
        margin-top: 4px;
    }

    .cli-request-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 18px;
        align-items: start;
    }

    .cli-field {
        min-width: 0;
    }

    .cli-col-6 {
        grid-column: span 6;
    }

    .cli-col-4 {
        grid-column: span 4;
    }

    .cli-col-8 {
        grid-column: span 8;
    }

    .cli-col-12 {
        grid-column: span 12;
    }

    .cli-form-box input[type="text"],
    .cli-form-box input[type="email"],
    .cli-form-box input[type="password"],
    .cli-form-box input[type="number"],
    .cli-form-box input[type="date"],
    .cli-form-box input[type="time"],
    .cli-form-box input[type="datetime-local"],
    .cli-form-box select,
    .cli-form-box textarea {
        width: 100%;
        max-width: none;
        margin-bottom: 0;
        background: #fbfdff;
        border: 1px solid #cfe0f5;
        border-radius: 16px;
        padding: 15px 16px;
        font-size: 1.02rem;
        color: #0f172a;
        transition: 0.2s ease;
    }

    .cli-form-box input:focus,
    .cli-form-box select:focus,
    .cli-form-box textarea:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 4px rgba(147, 197, 253, 0.22);
        background: #ffffff;
    }

    .cli-form-box textarea {
        min-height: 170px;
        resize: vertical;
    }

    .cli-form-box label {
        display: block;
        margin-bottom: 8px;
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .cli-field-help {
        margin-top: 8px;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .cli-dynamic-help {
        margin-top: 10px;
        padding: 12px 14px;
        border-radius: 14px;
        font-weight: 700;
        line-height: 1.7;
    }

    .cli-dynamic-help.standard {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
    }

    .cli-dynamic-help.urgent {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e3a8a;
    }

    .cli-form-divider {
        margin: 24px 0 22px;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #dbeafe 18%, #dbeafe 82%, transparent 100%);
    }

    .cli-form-actions {
        margin-top: 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .cli-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 12px 22px;
        border-radius: 16px;
        border: none;
        text-decoration: none;
        font-weight: 800;
        font-size: 1rem;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.18);
        cursor: pointer;
    }

    .cli-secondary-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 12px 20px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 800;
        font-size: 1rem;
        color: #1d4ed8;
        background: #eaf2ff;
        border: 1px solid #dbeafe;
    }

    @media (max-width: 1100px) {
        .cli-info-grid {
            grid-template-columns: 1fr;
        }

        .cli-col-6,
        .cli-col-4,
        .cli-col-8,
        .cli-col-12 {
            grid-column: span 12;
        }
    }

    @media (max-width: 900px) {
        .cli-form-hero,
        .cli-section {
            padding: 22px 18px;
        }

        .cli-form-title {
            font-size: 2.6rem;
        }

        .cli-form-subtitle {
            font-size: 1rem;
        }

        .cli-request-grid {
            gap: 16px;
        }
    }
</style>

<?php
$old = $old ?? [];
$fechaMinima = date('Y-m-d\TH:i');
$tipoSeleccionado = $old['tipo_urgencia'] ?? 'Estandar';
?>

<div class="cli-form-wrap">
    <section class="cli-form-hero">
        <div class="cli-form-chip">Área cliente · Alta de solicitudes</div>
        <h2 class="cli-form-title">Nueva solicitud</h2>
        <p class="cli-form-subtitle">
            Utiliza este formulario para registrar una nueva incidencia desde tu cuenta. La solicitud quedará asociada automáticamente a tu perfil de cliente y se enviará al sistema para su gestión.
        </p>
    </section>

    <?php if (!empty($error)): ?>
        <div class="message-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <section class="cli-section">
        <div class="cli-section-head">
            <div class="cli-section-kicker">
                <span class="cli-section-dot"></span>
                Datos asociados al cliente
            </div>
            <h3 class="cli-section-title">Cuenta vinculada a la solicitud</h3>
            <p class="cli-section-text">
                La incidencia se guardará con tu usuario autenticado como propietario del aviso. Estos datos sirven como referencia principal para la gestión y seguimiento del servicio.
            </p>
        </div>

        <div class="cli-info-grid">
            <article class="cli-info-card client">
                <div class="cli-info-label">
                    <span class="cli-info-icon">C</span>
                    Cliente
                </div>
                <div class="cli-info-value"><?= htmlspecialchars($usuario['nombre'] ?? 'No disponible') ?></div>
            </article>

            <article class="cli-info-card mail">
                <div class="cli-info-label">
                    <span class="cli-info-icon">@</span>
                    Correo electrónico
                </div>
                <div class="cli-info-value"><?= htmlspecialchars($usuario['email'] ?? 'No disponible') ?></div>
            </article>

            <article class="cli-info-card phone">
                <div class="cli-info-label">
                    <span class="cli-info-icon">T</span>
                    Teléfono actual
                </div>
                <div class="cli-info-value"><?= htmlspecialchars($usuario['telefono'] ?? 'No disponible') ?></div>
            </article>
        </div>

        <div class="cli-form-note">
            El teléfono de contacto del formulario aparecerá cargado automáticamente con el dato actual de tu cuenta, aunque podrás modificarlo si necesitas indicar otro número para esta intervención concreta.
        </div>
    </section>

    <section class="cli-section cli-form-box">
        <div class="cli-section-head">
            <div>
                <div class="cli-section-kicker">
                    <span class="cli-section-dot"></span>
                    Registro de incidencia
                </div>
                <h3 class="cli-section-title">Formulario de solicitud</h3>
                <p class="cli-section-text">
                    Completa los datos del servicio que necesitas solicitar y ten en cuenta la regla de negocio aplicada a los servicios estándar.
                </p>
            </div>
        </div>

        <div class="cli-rule-note">
            Regla activa del sistema: las solicitudes de tipo <strong>Estándar</strong> deben registrarse con al menos <strong>48 horas de antelación</strong>. Además, ningún aviso podrá cancelarse si faltan menos de 48 horas para la fecha del servicio.
        </div>

        <form action="<?= base_url('nueva-solicitud/store') ?>" method="POST" class="cli-request-form">
            <div class="cli-request-grid">
                <div class="cli-field cli-col-6">
                    <label for="especialidad_id">Tipo de servicio / especialidad</label>
                    <select id="especialidad_id" name="especialidad_id" required>
                        <option value="">Selecciona una especialidad</option>
                        <?php foreach ($especialidades as $especialidad): ?>
                            <option
                                value="<?= htmlspecialchars($especialidad['id']) ?>"
                                <?= (string) ($old['especialidad_id'] ?? '') === (string) $especialidad['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($especialidad['nombre_especialidad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="cli-field-help">
                        Selecciona el tipo de intervención que mejor encaja con la incidencia que quieres comunicar.
                    </div>
                </div>

                <div class="cli-field cli-col-6">
                    <label for="tipo_urgencia">Tipo de urgencia</label>
                    <select id="tipo_urgencia" name="tipo_urgencia" required>
                        <option value="Estandar" <?= $tipoSeleccionado === 'Estandar' ? 'selected' : '' ?>>Estándar</option>
                        <option value="Urgente" <?= $tipoSeleccionado === 'Urgente' ? 'selected' : '' ?>>Urgente</option>
                    </select>
                    <div class="cli-field-help">
                        Elige entre servicio estándar o urgente según la prioridad real de la solicitud.
                    </div>
                    <div id="cliUrgenciaHelp" class="cli-dynamic-help <?= $tipoSeleccionado === 'Urgente' ? 'urgent' : 'standard' ?>">
                        <?= $tipoSeleccionado === 'Urgente'
                            ? 'Los avisos urgentes pueden solicitarse dentro de las próximas 48 horas, siempre que la fecha indicada sea futura.'
                            : 'Los avisos estándar deben solicitarse con un mínimo de 48 horas de antelación.' ?>
                    </div>
                </div>

                <div class="cli-field cli-col-4">
                    <label for="fecha_servicio">Fecha y hora del servicio</label>
                    <input
                        type="datetime-local"
                        id="fecha_servicio"
                        name="fecha_servicio"
                        min="<?= htmlspecialchars($fechaMinima) ?>"
                        value="<?= htmlspecialchars($old['fecha_servicio'] ?? '') ?>"
                        required
                    >
                    <div class="cli-field-help">
                        Indica el momento solicitado para la asistencia técnica. La fecha debe ser posterior al momento actual.
                    </div>
                </div>

                <div class="cli-field cli-col-8">
                    <label for="direccion">Dirección del servicio</label>
                    <input
                        type="text"
                        id="direccion"
                        name="direccion"
                        value="<?= htmlspecialchars($old['direccion'] ?? '') ?>"
                        placeholder="Ejemplo: Calle Mayor, 12, Girona"
                        required
                    >
                    <div class="cli-field-help">
                        Introduce la dirección completa donde deberá realizarse la intervención.
                    </div>
                </div>

                <div class="cli-field cli-col-6">
                    <label for="telefono_contacto">Teléfono de contacto</label>
                    <input
                        type="text"
                        id="telefono_contacto"
                        name="telefono_contacto"
                        value="<?= htmlspecialchars($old['telefono_contacto'] ?? ($usuario['telefono'] ?? '')) ?>"
                        placeholder="Ejemplo: 600123123"
                        required
                    >
                    <div class="cli-field-help">
                        Puedes mantener el teléfono de tu cuenta o indicar otro número de contacto específico para este aviso.
                    </div>
                </div>

                <div class="cli-field cli-col-6">
                    <label for="descripcion">Resumen de la avería</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="5"
                        placeholder="Describe con detalle la incidencia o el servicio que necesitas solicitar."
                        required
                    ><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                    <div class="cli-field-help">
                        Añade toda la información relevante que pueda ayudar a preparar mejor la intervención.
                    </div>
                </div>
            </div>

            <div class="cli-form-divider"></div>

            <div class="cli-form-actions">
                <button type="submit" class="cli-primary-btn">Registrar solicitud</button>
                <a href="<?= base_url('mis-avisos') ?>" class="cli-secondary-link">Ir a Mis avisos</a>
            </div>
        </form>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoUrgencia = document.getElementById('tipo_urgencia');
    const ayuda = document.getElementById('cliUrgenciaHelp');

    function actualizarAyudaUrgencia() {
        if (tipoUrgencia.value === 'Urgente') {
            ayuda.className = 'cli-dynamic-help urgent';
            ayuda.textContent = 'Los avisos urgentes pueden solicitarse dentro de las próximas 48 horas, siempre que la fecha indicada sea futura.';
        } else {
            ayuda.className = 'cli-dynamic-help standard';
            ayuda.textContent = 'Los avisos estándar deben solicitarse con un mínimo de 48 horas de antelación.';
        }
    }

    tipoUrgencia.addEventListener('change', actualizarAyudaUrgencia);
    actualizarAyudaUrgencia();
});
</script>