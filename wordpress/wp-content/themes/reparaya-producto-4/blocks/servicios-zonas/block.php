<?php
/**
 * Bloque Genesis Custom Blocks: Servicios por zona.
 *
 * Lee el JSON generado por el Producto 3 y muestra estadísticas
 * de servicios agrupados por zona dentro de la página Nuestros servicios.
 */

if (!defined('ABSPATH')) {
    exit;
}

$endpoint = 'https://fp064.techlab.uoc.edu/~uocx3/producto3/api/servicios/zonas';

$response = wp_remote_get($endpoint, array(
    'timeout' => 12,
    'headers' => array(
        'Accept' => 'application/json',
    ),
));

if (is_wp_error($response)) : ?>
    <div class="ry-json-services__error">
        <strong>No se ha podido conectar con el Web Service.</strong>
        <p><?php echo esc_html($response->get_error_message()); ?></p>
    </div>
<?php
    return;
endif;

$status_code = wp_remote_retrieve_response_code($response);
$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);

if ($status_code !== 200 || !is_array($data)) : ?>
    <div class="ry-json-services__error">
        <strong>La respuesta del Web Service no es válida.</strong>
        <p>Código HTTP recibido: <?php echo esc_html((string) $status_code); ?></p>
    </div>
<?php
    return;
endif;

$total_global = isset($data['total_global']) ? (int) $data['total_global'] : 0;
$zonas = isset($data['zonas']) && is_array($data['zonas']) ? $data['zonas'] : array();
?>

<div class="ry-json-services" data-endpoint="<?php echo esc_url($endpoint); ?>">
    <div class="ry-json-services__summary">
        <span>Web Service REST · Producto 3</span>
        <strong><?php echo esc_html((string) $total_global); ?></strong>
        <p>
            Servicios registrados en el sistema y recuperados dinámicamente desde el JSON generado en el Producto 3.
        </p>
    </div>

    <?php if (!empty($zonas)) : ?>
        <div class="ry-json-services__grid">
            <?php foreach ($zonas as $zona) :
                $nombre_zona = isset($zona['zona']) ? (string) $zona['zona'] : 'Sin zona';
                $total_servicios = isset($zona['total_servicios']) ? (int) $zona['total_servicios'] : 0;
                $porcentaje = isset($zona['porcentaje']) ? (float) $zona['porcentaje'] : 0;
            ?>
                <article class="ry-json-zone">
                    <div class="ry-json-zone__name">
                        <?php echo esc_html($nombre_zona); ?>
                    </div>

                    <div class="ry-json-zone__total">
                        <?php echo esc_html((string) $total_servicios); ?>
                    </div>

                    <div class="ry-json-zone__percent">
                        <?php echo esc_html(number_format($porcentaje, 0, ',', '.')); ?>% del total
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="ry-json-services__error">
            <strong>El Web Service responde, pero no contiene zonas disponibles.</strong>
        </div>
    <?php endif; ?>
</div>
