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
namespace Dwdm\Users\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contacts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Dwdm\Users\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \Dwdm\Users\Model\Entity\Contact findOrCreate($search, callable $callback = null)
 */
class ContactsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('contacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Dwdm/Users.Users'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $repository = $this;
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->notEmpty(
                'contact',
                null,
                function ($context) {
                    return empty($context['data']['replace']);
                }
            )->add('contact', 'length', ['rule' => ['lengthBetween', 12, 12], 'on' => [$this, 'isPhone']])
            ->email('contact', false, null, [$this, 'isEmail']);

        $validator
            ->notEmpty(
                'replace',
                null,
                function ($context) {
                    return empty($context['data']['contact']);
                }
            )
            ->add(
                'replace',
                'unique',
                [
                    'rule' => function ($value, $context) use ($repository) {
                        return !$repository->exists(['contact' => $value]);
                    },
                    'message' => __d('users', 'Contact already registered'),
                ]
            )
            ->add('replace', 'length', ['rule' => ['lengthBetween', 12, 12], 'on' => [$this, 'isPhone']])
            ->email('replace', false, null, [$this, 'isEmail']);

        $validator
            ->allowEmpty('code');

        $validator
            ->boolean('is_login')
            ->requirePresence('is_login', 'create')
            ->notEmpty('is_login');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['type', 'contact']));
        return $rules;
    }

    public function isPhone($context)
    {
        return 'phone' == $context['data']['type'];
    }

    public function isEmail($context)
    {
        return 'email' == $context['data']['type'];
    }
}
