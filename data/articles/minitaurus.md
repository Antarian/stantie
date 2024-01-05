---
title:  >-
  MiniFW - Basic setup
preview: null
slug: 'basic-setup'
categorySlug: 'php-for-beginners'
seriesSlug: 'creating-a-php-mini-framework'
seriesPart: 1
archived: true
author: 'Peter Labos'
published: '22nd Mar 2016'
---
# Creating a PHP mini framework

If you're using some big framework (Symfony, Zend, CakePHP, Nette etc.) together with GitHub, to show some of your work, it is great. It’s good for showing how you write the code, documentation and handle other things.

But it can be complicated for some small working example. Because people on the other side must use the same framework too and install it somehow, to see your code in action. Some of the frameworks can have really complicated setup and install.

This inspired me to create some small custom FW. And also to learn more behind the basics of large frameworks.

MiniFW FW *must have* basics:
- PHP5 >= 5.3.0 (namespaces and functions)
- environments
- modules and layered architecture
- autoloading of classes
- routing with '404 page'
- debug, logs

MiniFW FW *nice to have* attributes:
- simple ACL
- database access
- template system

I hope you have some environment (LAMP, WAMP) to develop in. I am using AMP with Ubuntu Linux. It’s not in scope of this tutorial to set up a PHP environment.

## Basic setup
Create some base folder at server for work, for me, it is `/var/www/minifw` and URL for it is [http://localhost/](http://localhost/).

We will create at this folder file `.htaccess`. It is Apache config file.
```apacheconf
# .htaccess

# setup environment between development/production
# comment line or change "development" to "production" on production server
SetEnv APPLICATION_ENV development

# if mod_rewrite exists use rules
<IfModule mod_rewrite.c>
    # set parameter to detect mod rewrite in PHP
    SetEnv APPLICATION_REWRITE enabled

    # turn on rewrite
    RewriteEngine On
    # for all relative path are taken as basement /
    RewriteBase /

    # if path is index.php do not change it and do not process further
    RewriteRule ^index\.php$ - [L]

    # if request is not real file
    RewriteCond %{REQUEST_FILENAME} !-f
    # and if request is not real directory
    RewriteCond %{REQUEST_FILENAME} !-d
    # then change request to index.php in path / and do not process further
    RewriteRule . /index.php [L]
</IfModule>
```

I think comments explain everything in this file. It is used to redirect every request to one PHP file, which is `index.php`. Then we process every request and change it to something meaningful.

In same folder as `.htaccess` we create file <code>index.php</code> and define some constants for whole application.
```php
<?php 
/* index.php */

// define location of index.php as BASEDIR constant for easier manipulation with files
defined('BASEDIR') || define("BASEDIR", dirname(__FILE__));

// for using directory separator you must have installed Directories - PHP file system related extensions
// if you don't have it installed, replace DIRECTORY_SEPARATOR by "/" for Linux or "\\" for Windows
defined('DIRSEP') || define("DIRSEP", DIRECTORY_SEPARATOR);
```

We will need also one function which will make our life with PHP namespaces easier.
```php
// autoload classes through autoload function
spl_autoload_register( function($class) {
  // convert namespace to the full file path
  $class = BASEDIR . DIRSEP . str_replace('\\', DIRSEP, $class) . '.php';
  try
  {
    // check if the file exists
    if (!file_exists($class))
    {
      throw new RuntimeException($class . ' file does not exist.');
    } else
    {
      require_once($class);
    }
  } catch(\Exception $e)
  {
    // handle the error
    echo $e->getMessage();
  }
});
```

This function is class autoloader. It converts class name with namespace, one to one, to folder structure with class named file.

As last for this part of tutorial we add to the `index.php` two other constants.
```php
// define application environment constant value from .htaccess or production otherwise
defined('APPLICATION_ENV') || define('APPLICATION_ENV',
  (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : DebugConfig::PROD_ENV));
// load debug settings
DebugConfig::load();

// define if application uses apache mod_rewrite
$mod_rewrite = (getenv('APPLICATION_REWRITE') ? true : false);
$mod_rewrite = (!$mod_rewrite && in_array('mod_rewrite', apache_get_modules()) ? true : false);
defined('APPLICATION_REWRITE') || define('APPLICATION_REWRITE',
  ($mod_rewrite ? RouterConfig::MODRW_ENBL : RouterConfig::MODRW_DIS));
```

Here we set environment and detect mod rewrite for router from `.htaccess` file. We will create `DebugConfig` and `RouterConfig` in next parts of this tutorial.
