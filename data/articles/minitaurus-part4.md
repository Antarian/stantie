---
title:  >-
  MiniFW - Processing routes
preview: null
slug: 'processing-routes'
categorySlug: 'php-for-beginners'
seriesSlug: 'creating-a-php-mini-framework'
seriesPart: 4
archived: true
author: 'Peter Labos'
published: '29th Mar 2016'
---
# Creating a PHP mini framework (part 4)

## Processing routes
In the previous part of this tutorial, we made our first steps in creating router and attached classes. Now we create `RouteManager` class, and first absolute basic module.

In `AntarianMiniFW/Router` folder we create file `RouteManager.php` with starting lines.
```php
<?php
namespace AntarianMiniFW\Router;

use AntarianMiniFW\Debug\Debug;
use AntarianMiniFW\Debug\DebugConfig;

class RouteManager
{
```

The first method we create is for handling 404 error page.
```php
    /**
     * creates error page with the message if in development
     *
     * @param $uri
     * @param $code
     * @param $message
     */
    public static function errorPage($uri, $code, $message)
    {
        if ($code == '404') {
            header("Location: http://" . $uri);
            header('HTTP/1.0 404 Not Found');
            echo "&lt;h1&gt;404 Not Found&lt;/h1&gt;";
            echo "&lt;p&gt;".$message."&lt;/p&gt;";
            exit();
        }
    }
```

Next are the methods to control assigning of URL parameters to a specific class and method. One for nice URLs.
```php
    /**
     * Convert nice URL to call target controller and method in specified module
     *
     * @param $module
     * @param $controller
     * @param $action
     * @param $params
     */
    public static function mvcProcess($module, $controller, $action, $params)
    {
        // must be a fully qualified namespace with class for dynamic names, because of PHP compilation order
        // php.net/manual/en/language.namespaces.dynamic.php
        $controller = ucfirst($module) . "\\Controller\\" . ucfirst($controller) . "Controller";

        // create method name from second parameter
        $action = $action . "Action";

        // explode params to array
        if (!empty($params))
            $params = explode('/', $params);

        // instantiate object and call defined method
        $controllerObject = new $controller();

        try {
            // call method ($action) in class ($controllerObject) with selected parameters ($params)
            RouterConfig::safeCallUserFuncArray(array($controllerObject, $action), $params);
        } catch(\ErrorException $e) {
            // if dev environment display error on screen
            if (APPLICATION_ENV == DebugConfig::DEV_ENV)
                print(Debug::generateCallTrace($e));
            else
            // handle the error
            RouteManager::errorPage($_SERVER['REQUEST_URI'], 404, '');
        }
    }
```

And the second one for classic URLs.
```php
    /**
     * Convert classic URL to call target controller and method in specified module
     *
     * @param $module
     * @param $controller
     * @param $action
     * @param $params
     */
    public static function mvcProcessOld($module, $controller, $action, $params)
    {
        // must be a fully qualified namespace with class for dynamic names, because of PHP compilation order
        // php.net/manual/en/language.namespaces.dynamic.php
        $module = ucfirst( str_replace("?module=", "", $module) );
        $controller = ucfirst( str_replace("&controller=", "", $controller) );
        $controller = $module . "\\Controller\\" . $controller . "Controller";

        // create method name from second parameter
        $action = str_replace("&action=", "", $action);
        $action = $action . "Action";

        // explode params to array
        if (!empty($params)) {
            $params = explode('&', $params);
            unset($params[0]);
            $params = array_map(function($item) {
                if (!empty($item))
                    return substr($item, strpos($item, '=')+1);
            }, $params);
        }

        // instantiate object and call defined method
        $controllerObject = new $controller();

        try {
            // call method ($action) in class ($controllerObject) with selected parameters ($params)
            RouterConfig::safeCallUserFuncArray(array($controllerObject, $action), $params);
        } catch(\ErrorException $e) {
            // if dev environment display error on screen
            if (APPLICATION_ENV == DebugConfig::DEV_ENV)
                print(Debug::generateCallTrace($e));
            else
                // handle the error
                RouteManager::errorPage($_SERVER['REQUEST_URI'], 404, '');
            }
        }
```

And not to forget to close the class.
```php
}
```

We have the basics of our MiniFW FW for development. For some presentation of functionality, we will create one very basic module. In folder where we have `index.php` we create folder structure Main\Controller. In this folder we create `MainController.php` file. Here we place class with one method.
```php
<?php
namespace Main\Controller;

class MainController
{
    public function homepageAction()
    {
        echo "&lt;h1&gt;MiniFW FW welcomes you&lt;/h1&gt;";
    }
}
```

We can see this welcome sentence, when we look at [http://localhost](http://localhost) in browser or whatever your local URL is. If we go by first URL check, this content will be available on [http://localhost/main/main/homepage](http://localhost/main/main/homepage) URL. From the must-have basics of our FW we are still missing the Log. This we will create in the next parts of the tutorial.
