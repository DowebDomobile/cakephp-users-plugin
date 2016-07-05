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
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Users\Exception;

use Cake\Core\Exception\Exception;

/**
 * Class NotInitializedComponentException
 * @package User\Exception
 */
class NotInitializedComponentException extends Exception
{
    protected $_messageTemplate = 'Seems that %s is not initialized in App\\AppControler.';
} 