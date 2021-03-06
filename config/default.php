<?php

namespace Transitive;

/*
 * Architecture-related
 */
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('SELF', ('/' == dirname($_SERVER['PHP_SELF']) ? '' : dirname($_SERVER['PHP_SELF'])));
define('PRESENTERS', ROOT_PATH.'/presenters/');
define('MODELS', ROOT_PATH.'/models/');
define('VIEWS', ROOT_PATH.'/views/');
define('PRIVATE_DATA', ROOT_PATH.'/data/');
define('PUBLIC_DATA', SELF.'data/');

/*
 * Logs
 */
define('LOG', ROOT_PATH.'/log/');

define('CHANGELOG', ROOT_PATH.'/CHANGELOG.md');

/*
 * Database
 */
// Utils\Database::addDatabase('data', new Utils\Database('dbName', 'dbUser', 'dbPassword')); // Add database configuration to pool. The connection is established only later when Database::getInstanceById is called.

/*
 * Locales
 */
setlocale(LC_ALL, 'fr_FR.utf8', 'fr', 'fr_FR', 'fr_FR@euro', 'fr-FR', 'fra');
