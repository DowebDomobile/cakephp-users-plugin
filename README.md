# Users plugin for CakePHP(tm)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)

The plugin with users basic features:
- login
- logout
- restore password
- manage users

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require DowebDomobile/cakephp-users-plugin
```
or add to composer.json:
```
"require": {
    "DowebDomobile/cakephp-users-plugin": "dev-master"
}
```

## Basic usage

Load plugin to application in config/bootstrap.php file:

```php
Plugin::load('Users', ['bootstrap' => false, 'routes' => true]);
```

Using plugin migrations:

```
bin/cake bake migration migrate -p Users
```

Initialize AuthComponent in your AppController:

```php
$authConfig = [
        'authenticate' => [
                'Form' => [
                        'fields' => ['username' => 'email']
                ]
        ],
        'loginAction' => ['_name' => 'login'],
        'logoutRedirect' => '/',
];

$this->set('Auth', $this->loadComponent('Auth', $authConfig));
```

Adding login/logout links:

```php
<?= $this->cell('Users.Auth', [$Auth]); ?>
```

Adding menu for logged in users:

```php
<?= $this->cell(
        'Users.Auth::menu',
         [$Auth, [__d('users', 'Users') => ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'index']]]
); ?>
```

##Advanced usege
Coming soon