---
title:  >-
  MiniFW - Creating router
preview: null
slug: 'creating-router'
categorySlug: 'php-for-beginners'
seriesSlug: 'creating-a-php-mini-framework'
seriesPart: 3
archived: true
author: 'Peter Labos'
published: '29th Mar 2016'
---
# Creating a PHP mini framework (part 3)

## Creating router
In this part of the tutorial, we will create a router for our FW. But first create needed config class. In folder `AntarianMiniFW/Router` create file `RouterConfig.php`, with this content.
```php
<?php
namespace AntarianMiniFW\Router;

class RouterConfig
{
    const MODRW_ENBL = 'enabled';
    const MODRW_DIS = 'disabled';

    /**
     * safer call of methods and functions
     *
     * @param $callback
     * @param array $params
     * @return mixed
     */
    public static function safeCallUserFuncArray($callback, $params = array())
    {
        if (empty($params))
            $params = array();

        // check if callback is valid
        if (!is_callable($callback))
            throw new \InvalidArgumentException("Callback param is invalid");

        // check required parameters
        if (is_array($callback))
            $r = new \ReflectionMethod($callback[0], $callback[1]);
        else
            $r = new \ReflectionFunction($callback);

        if ($r->getNumberOfRequiredParameters() > count($params))
            throw new \InvalidArgumentException("Missing required parameter(s)");

        return call_user_func_array($callback, $params);
    }
}
```

This method is used to call methods throughout the FW. It checks if callback is valid and if the number of required parameters matches.

We also create `RegexRouter` class. This is simple, but powerful router. We check URI string with regular expressions, and if match is found, we get the response from specific classes and methods. In folder `AntarianMiniFW/Router` create file `RegexRouter.php`
```php
<?php
namespace AntarianMiniFW\Router;

class RegexRouter
{
```

We create `route()` method, which fill `routes` property array with pattern and assigned callback.
```php
    private $routes = array();

    /**
     * create routes, array of pattern -> callback pairs for routing
     *
     * @param $pattern
     * @param $callback
     */
    public function route($pattern, $callback)
    {
        $this->routes[$pattern] = $callback;
    }
```

Now we will add `execute()` method. This will execute assigned callback when URI and route pattern match.
```php
    /**
     * executes first matched route in the array
     *
     * @param $uri
     * @return mixed
     */
    public function execute($uri)
    {
        foreach ($this->routes as $pattern => $callback) {
            // get the route pattern, check it with uri
            if (preg_match($pattern, $uri, $params) === 1) {
                // call callback defined in route with selected parameters
                array_shift($params);
                return RouterConfig::safeCallUserFuncArray($callback, array_values($params));
            }
        }
    }
```

As this is the last method in this class, we can close the class.
```php
    }
```

Now we get back to `index.php` and put at the end of the file some routing configuration.

First, we initialize router
```php
// create regex router
$router = new RegexRouter();
```

Then we add some route definitions. These routes will be executed when match is found and are applicable if `mod_rewrite` is active. That mean nice URLs.
```php
    // define some routes - order is important (FIFO method)

    // routes with mod_rewrite enabled

    // for every route of type /module/controller/action/ and everything after is /param_name/param_value/param...
    $router->route('/^\/(\w+)\/(\w+)\/(\w+)\/?(.*)\/?$/', function ($module, $controller, $action, $params) {{'{'}}
        RouteManager::mvcProcess($module, $controller, $action, $params);
    });
    // route for homepage
    $router->route('/^\/$/', function () {
        RouteManager::mvcProcess("main", "main", "homepage", null);
    });
```

Here are other route definitions. If `mod_rewrite` is not active, then these URLs work similar to definitions before.

```php
    // routes with mod_rewrite disabled

    // for every route of type ?module=&controller=&action= and everything after is &param_name=...
    $router->route('/^\/?index\.php(\?module\=[^&]+)(\&controller\=[^&]+)(\&action\=[^&]+)(([\&]{1}[^=]+\=[^&]+)*)$/', function ($module, $controller, $action, $params) {
        RouteManager::mvcProcessOld($module, $controller, $action, $params);
    });
    // route for homepage
    $router->route('/^(\/?index\.php)*$/', function () {
        RouteManager::mvcProcessOld("main", "main", "homepage", null);
    });

    // for every other route return 404
    $router->route('/(.*)/', function() { 
        RouteManager::errorPage($_SERVER['REQUEST_URI'], 404, 'Page not found.');
    });
```

After route definitions, we execute browser URI request to server.
```php
    // execute router with uri as parameter
    $router->execute($_SERVER['REQUEST_URI']);
```

To make this work without an error, we add three other use statements to the top of the `index.php`
```php
use AntarianMiniFW\Router\RouterConfig;
use AntarianMiniFW\Router\RegexRouter;
use AntarianMiniFW\Router\RouteManager;
```

To finish router, we need `RouteManager` class. We create this class in the next part of the tutorial.
