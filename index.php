<?php
define('APP_PATH',__DIR__.'/');

define('APP_DEBUG',true);

require(APP_PATH.'fastphp/Fastphp.php');


$config = require(APP_PATH.'config/config.php');

(new Fastphp($config))->run();;;
zhaosen2