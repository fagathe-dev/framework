<?php 

require_once "settings.php";

define("DOCUMENT_ROOT",dirname(__DIR__));
define("RESSOURCES_DIR",dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
define("TEMPLATE_DIR",dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR);
define("ERROR_TEMPLATE_DIR",dirname(__DIR__) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR.'Exception'. DIRECTORY_SEPARATOR.'views'. DIRECTORY_SEPARATOR);
define("CUSTOM_ERROR_TEMPLATE_DIR",dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'errors'. DIRECTORY_SEPARATOR);