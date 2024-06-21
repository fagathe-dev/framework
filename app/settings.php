<?php

// Set the application environnement : 'dev' | 'preprod' | 'prod'
define('APP_ENV','dev');
// Set true to enable DEBUG_MODE or false otherwise
define('APP_DEBUG',true);

define('DEFAULT_DATE_TIMEZONE', 'Europe/Paris');

// LOGGER Configuration
define('LOGGER_ENABLED', true);
define('LOGGER_DIR', '');
define('LOGGER_LEVELS', ['level', 'debug', 'error']); // 'info' | 'debug' | 'warning' | 'error'
define('LOGGER_ROTATION', '');// boolean 
define('LOGGER_ROTATION_DURATION', 3);