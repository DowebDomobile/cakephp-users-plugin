<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 02.04.16
 * Time: 13:54
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