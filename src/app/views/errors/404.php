<?php
$title = 'Página no encontrada - ReparaYa';

ob_start();
?>

<h2>Error 404</h2>
<p>La página solicitada no existe.</p>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';