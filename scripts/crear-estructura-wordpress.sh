#!/usr/bin/env bash
set -euo pipefail

echo "== ReparaYa Producto 4 =="
echo "Creando y configurando la estructura institucional base de WordPress..."

WP_NETWORK=$(docker inspect p4-wordpress -f '{{range $name, $_ := .NetworkSettings.Networks}}{{println $name}}{{end}}' | head -1)

if [ -z "$WP_NETWORK" ]; then
  echo "ERROR: no se ha podido detectar la red Docker de p4-wordpress."
  exit 1
fi

docker run --rm \
  --network "$WP_NETWORK" \
  -v "$PWD/wordpress:/var/www/html" \
  -e WORDPRESS_DB_HOST="p4-wp-db:3306" \
  -e WORDPRESS_DB_NAME="wp_producto4" \
  -e WORDPRESS_DB_USER="wpuser" \
  -e WORDPRESS_DB_PASSWORD="wppass" \
  --entrypoint bash \
  wordpress:cli \
  -lc '
set -e

cd /var/www/html

wp core is-installed --allow-root

get_or_create_page() {
  SLUG="$1"
  TITLE="$2"
  CONTENT="$3"

  ID=$(wp post list \
    --post_type=page \
    --name="$SLUG" \
    --field=ID \
    --format=ids \
    --allow-root | awk "{print \$1}")

  if [ -z "$ID" ]; then
    ID=$(wp post create \
      --post_type=page \
      --post_status=publish \
      --post_title="$TITLE" \
      --post_name="$SLUG" \
      --post_content="$CONTENT" \
      --porcelain \
      --allow-root)

    echo "CREADA: $TITLE (ID=$ID)" >&2
  else
    wp post update "$ID" \
      --post_status=publish \
      --post_title="$TITLE" \
      --post_content="$CONTENT" \
      --allow-root >/dev/null

    echo "ACTUALIZADA: $TITLE (ID=$ID)" >&2
  fi

  printf "%s" "$ID"
}

HOME_ID=$(get_or_create_page "home" "Home" "Página principal institucional de ReparaYa Producto 4. Esta sección actúa como portada de la web y presenta la identidad del proyecto, los servicios principales y el acceso a las secciones principales.")

SERVICIOS_ID=$(get_or_create_page "nuestros-servicios" "Nuestros servicios" "Sección destinada a mostrar los servicios realizados por ReparaYa. En esta página se integrará posteriormente el bloque personalizado desarrollado con Genesis Custom Blocks para leer y presentar información dinámica procedente del JSON generado en el Producto 3.")

FLOTA_ID=$(get_or_create_page "nuestra-flota" "Nuestra flota" "Página de contenido fijo destinada a presentar la flota, recursos técnicos y medios disponibles para la prestación de servicios de reparación y asistencia.")

NOTICIAS_ID=$(get_or_create_page "noticias" "Noticias" "Sección configurada como página de entradas del blog institucional. Aquí se mostrarán las noticias creadas para el Producto 4.")

wp option update show_on_front page --allow-root
wp option update page_on_front "$HOME_ID" --allow-root
wp option update page_for_posts "$NOTICIAS_ID" --allow-root

echo ""
echo "Estructura configurada correctamente."
echo ""
echo "Páginas institucionales:"
wp post list --post_type=page --fields=ID,post_title,post_name,post_status --format=table --allow-root

echo ""
echo "Opciones de lectura:"
echo "show_on_front=$(wp option get show_on_front --allow-root)"
echo "page_on_front=$(wp option get page_on_front --allow-root)"
echo "page_for_posts=$(wp option get page_for_posts --allow-root)"
'

echo ""
echo "Creando noticias institucionales base..."

docker run --rm \
  --network "$WP_NETWORK" \
  -v "$PWD/wordpress:/var/www/html" \
  -e WORDPRESS_DB_HOST="p4-wp-db:3306" \
  -e WORDPRESS_DB_NAME="wp_producto4" \
  -e WORDPRESS_DB_USER="wpuser" \
  -e WORDPRESS_DB_PASSWORD="wppass" \
  --entrypoint bash \
  wordpress:cli \
  -lc '
set -e

cd /var/www/html

wp core is-installed --allow-root

get_or_create_post() {
  SLUG="$1"
  TITLE="$2"
  CONTENT="$3"

  ID=$(wp post list \
    --post_type=post \
    --name="$SLUG" \
    --field=ID \
    --format=ids \
    --allow-root | awk "{print \$1}")

  if [ -z "$ID" ]; then
    ID=$(wp post create \
      --post_type=post \
      --post_status=publish \
      --post_title="$TITLE" \
      --post_name="$SLUG" \
      --post_content="$CONTENT" \
      --porcelain \
      --allow-root)

    echo "CREADA: $TITLE (ID=$ID)"
  else
    wp post update "$ID" \
      --post_status=publish \
      --post_title="$TITLE" \
      --post_content="$CONTENT" \
      --allow-root >/dev/null

    echo "ACTUALIZADA: $TITLE (ID=$ID)"
  fi
}

get_or_create_post \
  "reparaya-transforma-su-web-institucional" \
  "ReparaYa transforma su web institucional con WordPress" \
  "ReparaYa inicia una nueva fase de evolución digital mediante la creación de una web institucional desarrollada sobre WordPress. El objetivo es presentar los servicios realizados, mejorar la comunicación del proyecto y disponer de una estructura web clara, mantenible y preparada para integrar información dinámica."

get_or_create_post \
  "nuevo-sistema-de-servicios-conectado-a-json" \
  "Nuevo sistema de servicios conectado a una fuente JSON" \
  "Dentro del Producto 4 se plantea la integración de una sección dinámica capaz de leer información procedente del JSON generado en el Producto 3. Esta funcionalidad permite relacionar el trabajo previo de Laravel con la nueva web institucional creada en WordPress."

get_or_create_post \
  "wordpress-fse-y-tema-de-bloques-personalizado" \
  "WordPress FSE y tema de bloques personalizado" \
  "El proyecto incorpora un tema de bloques creado específicamente para ReparaYa Producto 4. Mediante Full-Site Editing se podrán adaptar la cabecera, el pie de página, las plantillas principales y los estilos visuales para construir una identidad coherente con el grupo."

echo ""
echo "Noticias actuales:"
wp post list --post_type=post --fields=ID,post_title,post_name,post_status --format=table --allow-root
'
