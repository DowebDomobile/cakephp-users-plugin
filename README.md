# User plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require DowebDomobile/cakephp-users-plugin
```

## Usage

Load plugin to application in config/bootstrap.php file:

```
Plugin::load('Users', ['bootstrap' => false, 'routes' => true]);
```

Using plugin migrations:

```
bin/cake bake migration migrate -p User
```

Initialize AuthComponent in your AppController:

```
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

```
<?= $this->cell('User.Auth', [$Auth]); ?>
```

Adding menu for logged in users:

```
<?= $this->cell('User.Auth::menu', [$Auth, [__d('users', 'Users') => ['plugin' => 'Users', 'controller' => 'Users']]]); ?>
```