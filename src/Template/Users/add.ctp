<?php
/**
 * This file is part of the CakePHP(tm) Users plugin package.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 * @link          https://github.com/DowebDomobile/cakephp-users-plugin CakePHP(tm) Users plugin project
 * @since         0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/** @var \App\View\AppView $this */
/** @var \User\Model\Entity\User $user */
?>
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
