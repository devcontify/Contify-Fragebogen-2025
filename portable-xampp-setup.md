# Portable XAMPP Setup für Contify Plugin

## 1. Download
- Gehe zu: https://www.apachefriends.org/download.html
- Wähle die neueste Windows Version (PHP 8.2.12)
- Lade die portable Version herunter

## 2. Installation
1. Entpacke XAMPP in einen Ordner (z.B. `F:\xampp-portable\`)
2. Starte `xampp-control.exe`
3. Starte Apache und MySQL

## 3. WordPress Setup
1. Lade WordPress herunter: https://wordpress.org/download/
2. Entpacke in `F:\xampp-portable\htdocs\test.contify.de\`
3. Erstelle MySQL-Datenbank `contify_test`

## 4. WordPress Konfiguration
1. Kopiere `wp-config-sample.php` zu `wp-config.php`
2. Konfiguriere Datenbankverbindung:
   ```php
   define('DB_NAME', 'contify_test');
   define('DB_USER', 'root');
   define('DB_PASSWORD', '');
   define('DB_HOST', 'localhost');
   ```

## 5. Plugin Installation
1. Kopiere `contify-pdf-Fragebogen-2025/` nach `F:\xampp-portable\htdocs\test.contify.de\wp-content\plugins\`
2. Aktiviere Plugin im WordPress Admin
3. Teste mit Shortcode `[contify_fragebogen_2025]`

## 6. URL-Zugriff
- WordPress: `http://localhost/test.contify.de/`
- Admin: `http://localhost/test.contify.de/wp-admin/`