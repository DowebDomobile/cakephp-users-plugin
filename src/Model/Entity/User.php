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
namespace Dwdm\Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $code
 * @property bool $is_active
 *
 * @property \Dwdm\Users\Model\Entity\Contact[] $contacts
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = ['password' => true, 'contacts' => true];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = ['password', 'code', '_matchingData'];

    /**
     * Hash not empty password before assign to user.
     *
     * @param string $password
     * @return bool|string
     */
    protected function _setPassword($password)
    {
        return empty($password) ? '' : (new DefaultPasswordHasher())->hash($password);
    }

    /**
     * Generate new code or unset code
     *
     * @param boolean $value
     * @return null|string
     */
    public function _setCode($value)
    {
        return $value ? Text::uuid() : null;
    }
}