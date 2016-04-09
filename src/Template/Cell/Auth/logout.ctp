<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var \Cake\Controller\Component\AuthComponent $Auth */ ?>
<ul class="right">
    <li><?= $this->Html->link(
                __d('users', 'Hi, {0}!', [$Auth->user('email')]),
                ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'view', $Auth->user('id')]
        ); ?></li>
    <li><?= $this->Form->postLink(__d('users', 'Logout'), ['_name' => 'logout']); ?></li>
</ul>