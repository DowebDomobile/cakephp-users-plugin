<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var \User\Model\Entity\User $user */ ?>
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __d('users', 'Actions') ?></li>
        <li><?= $this->Html->link(__d('users', 'Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__d('users', 'Delete User'), ['action' => 'delete', $user->id], ['confirm' => __d('users', 'Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__d('users', 'List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__d('users', 'New User'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-10 medium-8 columns content">
    <h3><?= h($user->email) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __d('users', 'Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th><?= __d('users', 'Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __d('users', 'Is Active') ?></th>
            <td><?= $user->is_active ? __d('users', 'Yes') : __d('users', 'No'); ?></td>
        </tr>
    </table>
</div>
