<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

use Cake\Routing\DispatcherFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

$_SERVER['PHP_SELF'] = '/';

\Cake\Core\Configure::write(\Cake\Utility\Security::getSalt());

DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');
