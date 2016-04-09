<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var \User\Model\Entity\User $user */ ?>
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __d('users', 'Actions') ?></li>
        <li><?= $this->Form->postLink(
                __d('users', 'Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __d('users', 'Are you sure you want to delete # {0}?', $user->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__d('users', 'List Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __d('users', 'Edit User') ?></legend>
        <?php
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('is_active');
        ?>
    </fieldset>
    <?= $this->Form->button(__d('users', 'Submit')) ?>
    <?= $this->Form->end() ?>
</div>
