<?php /** @var \App\View\AppView $this */ ?>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create(); ?>
    <?= $this->Form->input('email', ['type' => 'email', 'required' => true, 'label' => __d('users', 'Email')]); ?>
    <?= $this->Form->input('password', ['type' => 'password', 'required' => true, 'label' => __d('users', 'Password')]); ?>
    <?= $this->Html->link(__d('users', 'Restore password'), ['action' => 'restore'])?>
    <?= $this->Form->button(__d('users', 'Login')); ?>
    <?= $this->Form->end(); ?>
</div>
