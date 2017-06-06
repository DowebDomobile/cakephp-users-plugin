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

/** @var Cake\View\View $this */
/** @var string $email */
/** @var string $code */
?>
<?= $this->Html->link(
        __d('users', 'Generate new password'),
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'restore', $email, $code, '_full' => true]
);