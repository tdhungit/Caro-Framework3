<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use TrueCustomer\Common\SecurityPlugin;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager as EventsManager;

use TrueCustomer\Common\BaseUrl;

error_reporting(E_ERROR);
ini_set('display_errors', 1);

if (true !== defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__FILE__)));
}

try {
    $system_config = include APP_PATH . '/app/config/system.php';
    $framework = include APP_PATH . '/app/config/framework.php';

    // Register an autoloader
    $loader = new Loader();
    $loader->registerNamespaces($framework['namespace'])->register();

    // Create a DI
    $di = new FactoryDefault();

    $di['dispatcher'] = function () {
        // Security
        $eventsManager = new EventsManager;
        // Check if the user is allowed to access certain action using the SecurityPlugin
        $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('TrueCustomer\Controllers');
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    };

    // Set the database service
    $di['db'] = function() {
        $database = include APP_PATH . '/app/config/database.php';
        return new DbAdapter($database);
    };

    // Setting up the view component
    $di['view'] = function() use ($system_config, $framework) {
        $view = new View();
        $theme = 'default';
        if (!empty($system_config['theme'])) {
            $theme_name = $system_config['theme'];
            if (is_dir(APP_PATH . '/app/themes/' . $theme_name)) {
                $theme = $theme_name;
            }
        }
        $view->setViewsDir(APP_PATH . '/app/themes/' . $theme . '/');
        $view->setLayoutsDir('layouts/');

        $view->registerEngines(array(
            '.twig' => function ($view, $di) use ($framework) {
                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions(array(
                    'compiledPath' => APP_PATH . '/app/cache/volt/'
                ));
                $compiler = $volt->getCompiler();
                foreach ($framework['volt_function'] as $name => $function_name) {
                    $compiler->addFunction($name, $function_name);
                }
                return $volt;
            }
        ));
        return $view;
    };

    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di['url'] = function() use ($system_config) {
        $url = new BaseUrl();

        if (!empty($system_config['systems']['base_url'])) {
            $url->setBaseUri($system_config['systems']['base_url']);
        } else {
            $url->setBaseUri('');
        }

        return $url;
    };

    // Registering a router
    $di['router'] = function () {
        $router = new \Phalcon\Mvc\Router(false);
        $router->removeExtraSlashes(true);

        $routes = include APP_PATH . '/app/config/router.php';
        foreach ($routes as $uri => $route) {
            $router->add($uri, $route);
        }

        return $router;
    };

    // Set Cookies
    $di['cookies'] = function () {
        $cookies = new Phalcon\Http\Response\Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    };

    // Start the session the first time some component request the session service
    $di['session'] = function () {
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        return $session;
    };

    // Set up the flash session service as flash
    $di['flash'] = function () {
        return new Phalcon\Flash\Session([
            'error' => 'alert alert-block alert-danger',
            'success' => 'alert alert-block alert-success',
            'notice' => 'alert alert-block alert-info',
            'warning' => 'alert alert-block alert-warning'
        ]);
    };

    // Setup the tag helpers
    $di['tag'] = function() {
        return new Tag();
    };

    // Setup const var
    $di['utils'] = function () use ($system_config) {
        $tcconst = include APP_PATH . '/app/config/const.php';
        return new \TrueCustomer\Helpers\Utils\TCUtils($system_config, $tcconst);
    };

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
