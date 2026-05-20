<?php
/**
 * Plantilla alternativa para Genesis Custom Blocks.
 * Carga la plantilla principal del bloque servicios-zonas.
 */

if (!defined('ABSPATH')) {
    exit;
}

$template = get_template_directory() . '/blocks/servicios-zonas/block.php';

if (file_exists($template)) {
    include $template;
}
