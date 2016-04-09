<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var \User\Model\Entity\User $user */ ?>
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __d('users', 'Actions') ?></li>
        <li><?= $this->Html->link(__d('users', 'List Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __d('users', 'Add User') ?></legend>
        <?php
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('is_active', ['default' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__d('users', 'Submit')) ?>
    <?= $this->Form->end() ?>
</div>
