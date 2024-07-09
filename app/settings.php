<?php

// Set the application environnement : 'dev' | 'preprod' | 'prod'
define('APP_ENV', 'dev');
// Set true to enable DEBUG_MODE or false otherwise
define('APP_DEBUG', true);

define('DEFAULT_DATE_TIMEZONE', 'Europe/Paris');

// LOGGER Configuration
define('LOGGER_ENABLED', true);
define('LOGGER_DIR', DOCUMENT_ROOT . '/logs/');
define('LOGGER_ROTATION', true);// boolean 
define('LOGGER_ROTATION_DURATION', 15);

define('PASSWORD_HASH_ALGO', PASSWORD_ARGON2ID);