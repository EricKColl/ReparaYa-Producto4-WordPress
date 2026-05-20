<?php
/**
 * Bloque Genesis Custom Blocks: Servicios por zona.
 *
 * Lee el JSON generado por el Producto 3 y muestra estadísticas
 * de servicios agrupados por zona dentro de la página Nuestros servicios.
 *
 * Funcionamiento previsto:
 * - En local intenta consultar primero el Producto 3 local.
 * - Si el endpoint local no responde, usa el endpoint del servidor UOC.
 * - En servidor UOC usa directamente el endpoint público del Producto 3.
 */

if (!defined('ABSPATH')) {
    exit;
}

$endpoint_uoc = 'https://fp064.techlab.uoc.edu/~uocx3/producto3/api/servicios/zonas';
$endpoint_local = 'http://host.docker.internal:8000/api/servicios/zonas';

$host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
$is_local_environment = (
    strpos($host, 'localhost') !== false ||
    strpos($host, '127.0.0.1') !== false ||
    strpos($host, ':8084') !== false
);

/**
 * Permite sobrescribir el endpoint desde wp-config.php o variables de entorno.
 *
 * Ejemplo wp-config.php:
 * define('REPARAYA_SERVICIOS_ZONAS_ENDPOINT', 'https://...');
 *
 * Ejemplo variable de entorno:
 * REPARAYA_SERVICIOS_ZONAS_ENDPOINT=https://...
 */
$configured_endpoint = '';

if (defined('REPARAYA_SERVICIOS_ZONAS_ENDPOINT')) {
    $configured_endpoint = (string) REPARAYA_SERVICIOS_ZONAS_ENDPOINT;
} elseif (getenv('REPARAYA_SERVICIOS_ZONAS_ENDPOINT')) {
    $configured_endpoint = (string) getenv('REPARAYA_SERVICIOS_ZONAS_ENDPOINT');
}

$endpoints = array();

if (!empty($configured_endpoint)) {
    $endpoints[] = $configured_endpoint;
} elseif ($is_local_environment) {
    $endpoints[] = $endpoint_local;
    $endpoints[] = $endpoint_uoc;
} else {
    $endpoints[] = $endpoint_uoc;
}

$selected_endpoint = '';
$last_error = '';
$status_code = 0;
$data = null;

foreach ($endpoints as $candidate_endpoint) {
    $candidate_endpoint = esc_url_raw($candidate_endpoint);

    if (empty($candidate_endpoint)) {
        continue;
    }

    $timeout = ($candidate_endpoint === $endpoint_local) ? 3 : 12;

    $response = wp_remote_get($candidate_endpoint, array(
        'timeout' => $timeout,
        'headers' => array(
            'Accept' => 'application/json',
        ),
    ));

    if (is_wp_error($response)) {
        $last_error = $response->get_error_message();
        continue;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $decoded = json_decode($body, true);

    if ($status_code === 200 && is_array($decoded)) {
        $selected_endpoint = $candidate_endpoint;
        $data = $decoded;
        break;
    }

    $last_error = 'Respuesta no válida. Código HTTP recibido: ' . $status_code;
}

if (!is_array($data)) : ?>
    <div class="ry-json-services__error">
        <strong>No se ha podido cargar la información del Web Service.</strong>
        <p><?php echo esc_html($last_error ?: 'No se ha recibido una respuesta JSON válida.'); ?></p>
    </div>
<?php
    return;
endif;

$total_global = isset($data['total_global']) ? (int) $data['total_global'] : 0;
$zonas = isset($data['zonas']) && is_array($data['zonas']) ? $data['zonas'] : array();
?>

<div class="ry-json-services" data-endpoint="<?php echo esc_url($selected_endpoint); ?>">
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
