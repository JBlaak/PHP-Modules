PHP Modules
===

Declare clear dependencies within your application by defining which modules have access to other parts of your
application.

Installation
---

This package will soon be published on Packagist, however it is still in development.

Features
---

Run module checks on your codebase using the `test` command
```shell
./vendor/bin/modules test
```

Generate a dependency graph, make sure you have installed [GraphViz](https://graphviz.org/download/) for this.
```shell
./vendor/bin/modules graph
```

Defining modules
---

Your `modules.php` file in the root of your project will be where your configuration will live. This file should return
an instance of `PhpModules\Lib\Modules`.

Let's say we have a module in the namespace `App\Http` that depends on `App\Persistence`, we can define this as follows:
```php
<?php

$persistence = PhpModules\Lib\Module::create('App\Persistence');
$http = PhpModules\Lib\Module::create('App\Http', [$persistence]);

return Modules::builder('./src')->register([$persistence, $http])
```

It is by design that you can't create cyclic dependencies since this is the thing that we are trying to avoid with this
design pattern.

Strict modules
---

Explicitly define which classes are exported from your modules. Most modules will have an explicit public api
which internally might have a lot of helper classes and other logic. For example a `App\Persistence` module
might have a `App\Persistence\PersistedUser` and `App\Persistence\UserRepository` as public api but might want
to hide the `App\Persistence\Drivers\MySQLDriver` to prevent other modules meddling with database configurations.

Define strict modules using `Module::strict`:
```php
<?php

$persistence = PhpModules\Lib\Module::strict('App\Persistence');
$http = PhpModules\Lib\Module::strict('App\Http', [$persistence]);

return Modules::builder('./src')->register([$persistence, $http])
```

Mark classes as public using PHPDoc:
```php
/**
 * @public
 */
class PersistedUser {}
```

Working with external dependencies or legacy codebases
---

Even though it is encouraged to try and be as explicit as possible in defining your dependencies, external
dependencies (such as frameworks) or legacy codebases require a lot of configuration to get started.

To allow importing from parts of your application not yet defined as a module, or external libraries we can
add `->allowUndefinedModules()` to our `Modules` configuration.

For example, your `App\Persistence` layer might depend on Laravel's Eloquent (if you're into such thing) without you
having to explicitly declare it:
```php
<?php

$persistence = PhpModules\Lib\Module::create('App\Persistence');
$http = PhpModules\Lib\Module::create('App\Http', [$persistence]);

return Modules::builder('./src') 
    ->allowUndefinedModules()
    ->register([$persistence, $http])
```



