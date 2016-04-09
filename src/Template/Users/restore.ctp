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
?>
<div class="users form large-10 medium-8 columns content">
    <?= $this->Form->create(); ?>
    <?= $this->Form->input('email', ['type' => 'email', 'required' => true, 'label' => __d('users', 'Email')]); ?>
    <?= $this->Html->link(__d('users', 'Login'), ['action' => 'login'])?>
    <?= $this->Form->button(__d('users', 'Get restore code')); ?>
    <?= $this->Form->end(); ?>
</div>