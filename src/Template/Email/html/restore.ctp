<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var string $email */ ?>
<?php /** @var string $code */ ?>
<?= $this->Html->link(
        __('Generate new password'),
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'restore', $email, $code, '_full' => true]
);