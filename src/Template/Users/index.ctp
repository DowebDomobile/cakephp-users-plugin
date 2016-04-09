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
/** @var \Cake\ORM\Query $users */
?>
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __d('users', 'Actions') ?></li>
        <li><?= $this->Html->link(__d('users', 'New User'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users index large-10 medium-8 columns content">
    <h3><?= __d('users', 'Users') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('is_active') ?></th>
                <th class="actions"><?= __d('users', 'Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Number->format($user->id) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= $user->is_active ? __d('users', 'Yes') : __d('users', 'No'); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__d('users', 'View'), ['action' => 'view', $user->id]); ?>
                    <?= $this->Html->link(__d('users', 'Edit'), ['action' => 'edit', $user->id]); ?>
                    <?= $this->Form->postLink(
                            __d('users', $user->is_active ? 'Deactivate' : 'Activate'),
                            ['action' => 'edit', $user->id],
                            ['data' => ['is_active' => (int)!$user->is_active]]
                    ); ?>
                    <?= $this->Form->postLink(
                            __d('users', 'Delete'),
                            ['action' => 'delete', $user->id],
                            ['confirm' => __d('users', 'Are you sure you want to delete # {0}?', $user->id)]
                    ); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __d('users', 'previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('users', 'next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
