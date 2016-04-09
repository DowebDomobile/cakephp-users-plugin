<?php
/**
 *
 */
namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $code
 * @property bool $is_active
 */
class Users extends Entity
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
    protected $_accessible = ['*' => true, 'id' => false, 'code' => false];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = ['password', 'code'];

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