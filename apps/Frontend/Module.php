<?php

namespace Lininliao\Frontend;

use Lininliao\Frontend\Plugins\Middleware;

use \Phalcon\Dispatcher,
    \Phalcon\Mvc\Dispatcher as MvcDispatcher,
    \Phalcon\Mvc\Dispatcher\Exception as DispatchException,
    \Phalcon\Loader,
    \Phalcon\Events\Manager,
    \Phalcon\Cache\Frontend\Json as DataFrontend,
    \Phalcon\Cache\Backend\File as FileCache,
    \Phalcon\Cache\Backend\Libmemcached as MemCached;

final class Module {
    public function registerAutoloaders($di) {
        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Lininliao\Frontend\Library' => ROOT . DS . 'apps' . DS . SITENAME . DS . 'Library',
            'Lininliao\Frontend\Controllers' => ROOT . DS . 'apps' . DS . SITENAME . DS . 'Controllers',
            'Lininliao\Frontend\Models' => ROOT . DS . 'apps' . DS . SITENAME . DS . 'Models',
            'Lininliao\Frontend\Plugins' => ROOT . DS . 'apps' . DS . SITENAME . DS . 'Plugins',
            'Lininliao\Frontend\Views' => ROOT . DS . 'apps' . DS . SITENAME . DS . 'Views',
        ));
        $loader->registerDirs(array(
            ROOT . DS . 'apps' . DS . SITENAME . DS . 'Controllers',
            ROOT . DS . 'apps' . DS . SITENAME . DS . 'Library',
            ROOT . DS . 'apps' . DS . SITENAME . DS . 'Models',
            ROOT . DS . 'apps' . DS . SITENAME . DS . 'Plugins',
            ROOT . DS . 'apps' . DS . SITENAME . DS . 'Views',
        ))->register();
    }

    public function registerServices($di) {
        $config = $di->get('config');

        /**
         * Set multiple cache
         */
        $di->set('cache', function() use ($config) {
            if (false === is_dir(ROOT . DS .'cache'. DS . 'data_cache' . DS . date('Ym'))) {
                mkdir(ROOT . DS .'cache'. DS . 'data_cache' . DS . date('Ym'), 0755, true);
            }
            return new FileCache(new DataFrontend(array(
                'lifetime' => 3600,
            )), array(
                'prefix' => 'bountyHunterCache.',
                'cacheDir' => ROOT . DS .'cache'. DS . 'data_cache' . DS . date('Ym') . DS
            ));
        });

        // $di->set('memcache', function() use ($config) {
        //     return new MemCached(new DataFrontend(array(
        //         'lifetime' => 3600,
        //     )), array(
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
        // });

        $di->set('dispatcher', function() use ($di, $config) {
            $eventsManager = new Manager();
            $eventsManager = $di->getShared('eventsManager');

            /**
             * Middleware
             * @var Middleware
             */
            // $eventsManager->attach('dispatch', new Middleware());

            $dispatcher = new MvcDispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace('Lininliao\Frontend\Controllers\\');

            return $dispatcher;
        });
    }
}
