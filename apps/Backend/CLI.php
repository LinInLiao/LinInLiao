<?php

define('VERSION', '0.1.0');
defined('ROOT') || define('ROOT', realpath(dirname(dirname(dirname(__FILE__)))));
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

if (!defined('ENVIRONMENT')) {
    $environment = getenv('BHUNTR_ENV');
    if (!$environment || !in_array($environment, array('development', 'staging', 'production'), true)) {
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', $environment);
    }
    unset($environment);
}
define('SITENAME', 'Backend');

require (ROOT . DS .'vendor'. DS .'autoload.php');

/**
 * Registering a set of directories taken from the configuration file
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        ROOT . DS . 'library',
        ROOT . DS . 'models',
        ROOT . DS . 'plugins',
        APPLICATION_PATH . DS . 'Library',
        APPLICATION_PATH . DS . 'Plugins',
        APPLICATION_PATH . DS . 'Models',
        APPLICATION_PATH . DS . 'Tasks',
    )
);

$loader->registerNamespaces(array(
    'Lininliao\Library' => ROOT . DS . 'library',
    'Lininliao\Models' => ROOT . DS . 'models',
    'Lininliao\Plugins' => ROOT . DS . 'plugins',
    'Lininliao\Backend\Library' => APPLICATION_PATH . DS . 'Library',
    'Lininliao\Backend\Plugins' => APPLICATION_PATH . DS . 'Plugins',
    'Lininliao\Backend\Models' => APPLICATION_PATH . DS . 'Models',
))->register();

$di = new \Phalcon\DI\FactoryDefault\CLI();

$config = new \Phalcon\Config\Adapter\Ini(ROOT . DS .'configs'. DS .'config.ini');
$di->set('config', $config, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
    $db_config = $config->database[ENVIRONMENT];

    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        'host'      => $db_config->dbhost,
        'username'  => $db_config->dbusername,
        'password'  => $db_config->dbpassword,
        'dbname'    => $db_config->dbname
    ));
}, true);

$di->set('acl', function() use ($di, $config) {
    return new Phalcon\Acl\Adapter\Database(array(
        'db'                  => $di->get('db'),
        'roles'               => 'roles',
        'rolesInherits'       => 'roles_inherits',
        'resources'           => 'resources',
        'resourcesAccesses'   => 'resources_accesses',
        'accessList'          => 'access_list',
    ));
}, true);

$di->set('translate', function() {
    return new Lininliao\Library\Locale\Translate(SITENAME);
}, true);
$di->set('simpleView', function() use ($di, $config) {
    $view = new \Phalcon\Mvc\View\Simple();
    $view->setDI($di);
    $view->registerEngines(array(
        '.volt' => function($view, $di) {
            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            $compiledPath = ROOT . DS .'cache'. DS . SITENAME . DS . 'volt' . DS;
            if (!file_exists($compiledPath)) {
                mkdir($compiledPath, 0777, true);
            }
            $volt->setOptions(array(
                'compiledPath' => $compiledPath,
                'compiledExtension' => '.cache',
                'compiledSeparator' => '%',
                'stat' => true,
                'compileAlways' => ENVIRONMENT === 'production' ? false : true,
            ));
            $compiler = $volt->getCompiler();
            $compiler->addFunction('_', function ($resolvedArgs, $exprArgs) use ($di) {
                $translate = $di->get('translate');
                return $translate->trans($exprArgs, $resolvedArgs);
            });
            return $volt;
        }
    ));
    return $view;
}, true);

$di->set('mail', function() {
    return new Lininliao\Library\Swift\Mail();
}, true);

$console = new \Phalcon\CLI\Console();
$console->setDI($di);

$arguments = array();

foreach($argv as $k => $arg) {
    if($k == 1) {
       $arguments['task'] = $arg;
    } elseif($k == 2) {
       $arguments['action'] = $arg;
    } elseif($k >= 3) {
      array_push($arguments, $arg);
    }
}

define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

$di->setShared('console', $console);

try {
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo '[ERROR] '.$e->getMessage()."\n".$e->getTraceAsString()."\n";
    exit(255);
} catch (\Exception $e) {
    echo '[ERROR] '.$e->getMessage()."\n".$e->getTraceAsString()."\n";
    exit(255);
}
