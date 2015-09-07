<?php

error_reporting(E_ALL);

if (isset($_REQUEST['xhprof'])) {
    xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}

defined('ROOT') || define('ROOT', dirname( dirname( __FILE__ ) ) );
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

if (!defined('ENVIRONMENT')) {
    $environment = getenv('BHUNTR_ENV');
    if (!$environment || !in_array($environment, array('development', 'staging', 'production'), true)) {
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', $environment);
    }
    unset($environment);
}

require (ROOT . DS .'vendor'. DS .'autoload.php');


use \Phalcon\Config\Adapter\Ini as configIniAdapter,
    \Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new \Phalcon\DI\FactoryDefault();

$config = new configIniAdapter(ROOT . DS .'configs'. DS .'config.ini');
$di->set('config', $config, true);

/**
 * Whoops with phalcon DI
 */
if (ENVIRONMENT !== 'production') {
    $whoops = new \Whoops\Provider\Phalcon\WhoopsServiceProvider($di);
}

try {

    $sitename = '';
    foreach( $config->hostname[ENVIRONMENT] as $site => $hostname ) {
        if ($hostname === $_SERVER['HTTP_HOST']) {
            $sitename = $site;
            break;
        }
    }

    // if not lininliao or backend, force redirect to lininliao application.
    if (empty($sitename)) {
        $sitename = 'frontend';
    }

    /**
     * If cannot load the site configuration, throw it!
     */
    if (empty($sitename)) {
        throw new \Exception('Cannot load site configuration.');
    }

    defined('SITENAME') || define('SITENAME', ucfirst($sitename));

    /**
     * Registering a set of directories taken from the configuration file
     */
    $loader = new \Phalcon\Loader();

    $loader->registerNamespaces(array(
        'Lininliao\Library' => ROOT . DS . 'library',
        'Lininliao\Models' => ROOT . DS . 'models',
        'Lininliao\Plugins' => ROOT . DS . 'plugins',
    ));

    /**
     * Loader registe directories
     */
    $loader->registerDirs(array(
        ROOT . DS . 'library',
        ROOT . DS . 'models',
        ROOT . DS . 'plugins',
    ))->register();


    /**
     * Set the global encryption key.
     */
    $di->set('crypt', function() use ($config) {
        $crypt = new \Phalcon\Crypt();
        $crypt->setKey($config->cookie->crypt);

        return $crypt;
    }, true);

    /**
     * Database connection is created based in the parameters defined in the configuration file
     */
    $di->set('db', function() use ($config) {
        $db_config = $config->database[ENVIRONMENT];
        return new DbAdapter(array(
            'host'      => $db_config->dbhost,
            'username'  => $db_config->dbusername,
            'password'  => $db_config->dbpassword,
            'dbname'    => $db_config->dbname
        ));
    }, true);

    /**
     * Start the session the first time some component request the session service
     */

    // $di->set('cookies', function() use ($config) {
    //     $cookies = new \Phalcon\Http\Response\Cookies();
    //     $cookies->useEncryption(true);

    //     return $cookies;
    // }, true);

    // $di->set('session', function() use ($config) {
    //     $session = new Session(array(
    //         'servers' => array(array(
    //             'host' => $config->memcached[ENVIRONMENT]->host,
    //             'port' => (int) $config->memcached->port,
    //             'weight' => 1,
    //         )),
    //         'client' => array(
    //             \Memcached::OPT_HASH => \Memcached::HASH_MD5,
    //             \Memcached::OPT_PREFIX_KEY => $config->memcached->prefix,
    //             \Memcached::OPT_RECV_TIMEOUT => 1000,
    //             \Memcached::OPT_SEND_TIMEOUT => 1000,
    //             \Memcached::OPT_TCP_NODELAY => true,
    //             \Memcached::OPT_SERVER_FAILURE_LIMIT => 50,
    //             \Memcached::OPT_CONNECT_TIMEOUT => 500,
    //             \Memcached::OPT_RETRY_TIMEOUT => 300,
    //             \Memcached::OPT_DISTRIBUTION => \Memcached::DISTRIBUTION_CONSISTENT,
    //             \Memcached::OPT_REMOVE_FAILED_SERVERS => true,
    //             \Memcached::OPT_LIBKETAMA_COMPATIBLE => true
    //         ),
    //         'lifetime' => (int) $config->memcached->lifetime,
    //         'prefix' => $config->memcached->prefix
    //     ));
    //     if ($session->status() !== $session::SESSION_ACTIVE) {
    //         $domain = Domain::get();
    //         session_set_cookie_params((int) $config->memcached->lifetime, '/', '.'.$domain, (boolean) $config->cookie->secure, (boolean) $config->cookie->httponly);
    //         $session->start();
    //     }
    //     return $session;
    // }, true);

    // $di->set('csrf_session_bag', function() use ($di) {
    //     $csrf_session_bag = new \Phalcon\Session\Bag('CSRF_SESSION_BAG');
    //     $csrf_session_bag->setDI($di);
    //     return $csrf_session_bag;
    // }, true);

    $di->set('url', function() {
        return new \Phalcon\Mvc\Url();
    }, true);

    /**
     * Router
     */
    $di->set('router', function() {
        require ROOT . DS . 'configs' . DS . 'routes' . DS . ucfirst(SITENAME) . '.php';
        return $router;
    }, true);

    $di->set('url', function() {
        return new \Phalcon\Mvc\Url();
    }, true);

    /**
     * Setting up view, use Volt Template Engine
     */
    $di->set('view', function() use ($di, $config) {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(ROOT . DS . 'apps' . DS . SITENAME . DS . 'Views' . DS);

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
                    'compileAlways' => true, //ENVIRONMENT === 'production' ? false : true,
                ));
                $compiler = $volt->getCompiler();
                $compiler->addFunction('_', function ($resolvedArgs, $exprArgs) use ($di) {
                    $translate = $di->get('translate');
                    return $translate->trans($exprArgs, $resolvedArgs);
                });
                $compiler->addFunction('ng', function($input) {
                    return '"{{".' . $input . '."}}"';
                });
                $compiler->addFilter('truncate', function ($resolvedArgs, $exprArgs) {
                    $string = $exprArgs[0]['expr']['value'];
                    $length = (int) $exprArgs[1]['expr']['value'];
                    $end = isset($exprArgs[2]) ? $exprArgs[2]['expr']['value'] : '...';
                    return "mb_strimwidth($string, 0, $length, '$end', 'UTF-8')";
                });
                $compiler->addFilter('shift', function ($resolvedArgs, $exprArgs) {
                    return "array_shift($resolvedArgs)";
                });
                $compiler->addFilter('number_format', function ($resolvedArgs, $exprArgs) {
                    return "number_format($resolvedArgs)";
                });

                return $volt;
            }
        ));
        // $view->setVar('FACEBOOK_ADMIN_ID', $config->facebook[ENVIRONMENT]->admin);
        // $view->setVar('GOOGLE_ANALYTICS_KEY', $config->ga[ENVIRONMENT]->key);
        return $view;
    }, true);

    $eventsManager = new \Phalcon\Events\Manager();
    $eventsManager->attach("application:afterHandleRequest", function($event, $application) {
        $datetime = gmdate("D, d M Y H:i:s").' GMT';
        $application->response->setHeader('Last-Modified', $datetime);
        return true;
    });
    $application = new \Phalcon\Mvc\Application($di);
    $application->setEventsManager($eventsManager);
    $application->registerModules(array(
        SITENAME => array(
            'className' => 'Lininliao\\' . SITENAME . '\Module',
            'path' => ROOT . DS .'apps'. DS . SITENAME . DS .'Module.php'
        )
    ));


    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
