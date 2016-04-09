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
/** @var \Cake\Controller\Component\AuthComponent $Auth */
?>
<ul class="right">
    <li><?= $this->Html->link(
                __d('users', 'Hi, {0}!', [$Auth->user('email')]),
                ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'view', $Auth->user('id')]
        ); ?></li>
    <li><?= $this->Form->postLink(__d('users', 'Logout'), ['_name' => 'logout']); ?></li>
</ul>