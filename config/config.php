<?php

define('DB_NAME','');
define('DB_USER','');
define('DB_PASSWORD','');
define('DB_HOST','');

$config['defaultController'] = 'Item';
$config['defaultAction'] = 'index';
require('db.php');
return $config;