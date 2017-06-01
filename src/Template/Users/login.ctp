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

/** @var \Cake\View\View $this */
?>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create(); ?>
    <?= $this->Form->control('email', ['type' => 'email', 'required' => true, 'label' => __d('users', 'Email')]); ?>
    <?= $this->Form->control('password', ['type' => 'password', 'required' => true, 'label' => __d('users', 'Password')]); ?>
    <?= $this->Html->link(__d('users', 'Restore password'), ['action' => 'restore'])?>
    <?= $this->Form->button(__d('users', 'Login')); ?>
    <?= $this->Form->end(); ?>
</div>
