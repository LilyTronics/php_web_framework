# PHP web framework

Simple web framework based on PHP, for simple projects.

## Features
* PHP version 8.2 or greater
* MVC pattern (model, view, controller)
* Router for pretty URLs: https://mydomain.com/name-of-page
* Use as a submodule in project.
* Easy use of MySQL databases.

## To do

* Add a demo web page with all features

## Using as a submodule
Start a new empty git project. Clone the framework as a submodule in your project:

```
git submodule add https://github.com/LilyTronics/php_web_framework.git php_framework
```

Instead of `php_framework` you can also use another name, but do not use `framework` to prevent confusion with the `framework` folder in this repo.

From the `php_framework` folder copy the folder `application` and it's content to the root of your project.
From the `php_framework` folder copy the folder `.logs` and it's content to the root of your project.
Copy the framework's `.htaccess` to the root of your poject.

Create a new `index.php` in the root of your project and add the following lines:

```
<?php

// Set variable for framework sub module folder
define("SUBMODULE_PATH", "php_framework/");

// Call the framework to process the URI
include(SUBMODULE_PATH . "index.php");

```

Save the file.

Now you should have a project like this:

```
my_project
 |- .logs/              // folder for the log files (should contain a .htaccess file)
 |- application/        // your application, you can edit here what ever you want
 |- php_framework/      // the PHP web framework as a submodule
 |- .gitmodules         // configuration file for git about the submodules
 |- .htaccess           // the .htaccess file of your project
 |- index.php           // the index.php file of your project
```

Now your project is finished and should show the standard application.

From here you can change all files in the `application` folder of your project to your needs.

To show debug information about the framework, put the following line at the top of your `index.php`:

```
define("SHOW_DEBUG", true);
```

Read the git manual about submodules for more information about working with sub modules (https://www.google.com/search?q=git+submodules).
