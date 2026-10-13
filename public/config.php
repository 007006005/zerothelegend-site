<?php
/**
 * Configurazione Retro Arcade - HOSTING IONOS (zerothelegend.com)
 * Compila DB_NAME e DB_PASS direttamente sul server (non condividere la password in chat).
 */
define('DB_HOST', 'db5021535656.hosting-data.io');
define('DB_PORT', 3306);
define('DB_NAME', 'dbs16175408');   // es. dbs12345678, lo trovi nel pannello IONOS
define('DB_USER', 'dbu700360');
define('DB_PASS', 'Elpro112!Adriano!');

// Chiave per eseguire install.php da internet. Cambiala con una stringa lunga e casuale.
define('INSTALL_KEY', 'ZeroTheLegendAmazing');

// 'demo' = acquisti/licenza SIMULATI (nessun addebito reale).
define('PAYMENTS_MODE', 'demo');

date_default_timezone_set('Europe/Rome');
