---
title:  >-
  MiniFW - Adding debug
preview: null
slug: 'adding-debug'
categorySlug: 'php-for-beginners'
seriesSlug: 'creating-a-php-mini-framework'
seriesPart: 2
archived: true
author: 'Peter Labos'
published: '27th Mar 2016'
---
# Creating a PHP mini framework (part 2)

## Adding debug
As I mentioned in first part of this tutorial, we will now create `DebugConfig` class. In base folder of the project create folder structure `AntarianMiniFW\Debug`. Within `Debug` folder create file `DebugConfig.php` and place this content into it.
```php
<?php
/* \AntarianMiniFW\Debug\DebugConfig.php */
namespace AntarianMiniFW\Debug;

/**
 * basic debug configuration class
 */
class DebugConfig
{
    const DEV_ENV = 'development';
    const PROD_ENV = 'production';

    public static function load()
    {
        // in development enable error report for better error detection
        if (APPLICATION_ENV == self::DEV_ENV) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(-1);
        }

       // setup exception error handler to change errors to exceptions
       set_error_handler(array('\AntarianMiniFW\Debug\Debug', 'exceptionErrorHandler'));

       // exception handler to uncaught exceptions
       set_exception_handler(array('\AntarianMiniFW\Debug\Debug', 'exceptionHandler'));
  }
}
```

This class has namespace definition which is one-to-one to folder structure. And name of class is same as filename without `.php` part. There are also two constants, which we will use through the FW. There is `load()` method, which will change error level detection settings based on environment type. This method also setup callbacks for converting errors to exceptions and for handling uncaught exceptions.

To reach this class, we use autoload function in `index.php`.

Now we open the `index.php` file and add `use namespace` statement to the top of the file, just after the `<?php`.
```php
use AntarianMiniFW\Debug\DebugConfig;
```

Next we create `Debug.php` file in `AntarianMiniFW/Debug` folder. Place this code to the start of the file.
```php
<?php
/* \AntarianMiniFW\Debug\Debug.php */
namespace AntarianMiniFW\Debug;

class Debug
{
```

First in this file, we create method `exceptionErrorHandler()` which will change PHP error messages into exceptions.
```php
/**
 * method to change error to exception, for better use in try/catch
 *
 * @param $severity
 * @param $message
 * @param $file
 * @param $line
 * @throws \ErrorException
 */
public static function exceptionErrorHandler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }

    throw new \ErrorException($message, 0, $severity, $file, $line);
}
```

Second, we create a method to handle uncaught exceptions.
```php
/**
 * handler for uncaught exceptions
 *
 * @param $exception \Exception
 */
public static function exceptionHandler(\Exception $exception)
{
    // if dev environment display error on screen
    if (APPLICATION_ENV == DebugConfig::DEV_ENV)
        print(Debug::generateCallTrace($exception));
    }
}
```

This method calling `generateCallTrace()` method, in which we show details of exception.
```php
/**
 * adding more description to exceptions
 *
 * @param \Exception $e
 * @return string
 */
public static function generateCallTrace(\Exception $e)
{
    $result[] =
      "Msg: " . $e->getMessage() . PHP_EOL .
      "File: " . $e->getFile() . " (" . $e->getLine() . ")" . PHP_EOL .
      "Backtrace: ";

    $result[] = print_r($e->getTraceAsString(), true);

    $result = implode(PHP_EOL, $result);

    // not development, then output goes to log file
    if (APPLICATION_ENV != DebugConfig::DEV_ENV)
      return $result;

    // html output to screen
    return nl2br($result);
}
```

As this is last method in our Debug class, we should close class with
```php
}
```

As we now have classes for handling errors, we can change a little our `spl_autoload_register` function in `index.php`. We add print debug into `catch` part.
```php
// autoload classes through autoload function
spl_autoload_register( function($class) {
      ...
    } catch(\Exception $e) {
        // if dev environment display error on screen
        if (APPLICATION_ENV == DebugConfig::DEV_ENV)
            print(Debug::generateCallTrace($e));
    }
});
```

and add 'use statement' to the top of the file
```php
use AntarianMiniFW\Debug\Debug;
```

In the next part of our tutorial, we will create router classes for our mini framework.

