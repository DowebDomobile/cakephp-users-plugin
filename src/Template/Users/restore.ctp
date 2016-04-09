<?php /** @var \App\View\AppView $this */ ?>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create(); ?>
    <?= $this->Form->input('email', ['type' => 'email', 'required' => true, 'label' => __d('users', 'Email')]); ?>
    <?= $this->Html->link(__d('users', 'Login'), ['action' => 'login'])?>
    <?= $this->Form->button(__d('users', 'Get restore code')); ?>
    <?= $this->Form->end(); ?>
</div>