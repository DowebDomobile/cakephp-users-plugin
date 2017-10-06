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

namespace Dwdm\Users\Auth;

use Cake\Auth\FormAuthenticate;
use Cake\Controller\Component\AuthComponent;
use Cake\Http\CallbackStream;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Network\Exception\ForbiddenException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Class ContactsAuth
 * @package Users\Auth
 */
class ContactsAuthenticate extends FormAuthenticate
{
    /**
     * Default config for this object.
     *
     * - `fields` The fields to use to identify a user by.
     * - `userModel` The alias for users table, defaults to Users.
     * - `contactModel` The alias for contacts table, defaults to Contacts.
     * - `finder` The finder method to use to fetch user record. Defaults to 'all'.
     *   You can set finder name as string or an array where key is finder name and value
     *   is an array passed to `Table::find()` options.
     *   E.g. ['finderName' => ['some_finder_option' => 'some_value']]
     * - `passwordHasher` Password hasher class. Can be a string specifying class name
     *    or an array containing `className` key, any other keys will be passed as
     *    config to the class. Defaults to 'Default'.
     * - Options `scope` and `contain` have been deprecated since 3.1. Use custom
     *   finder instead to modify the query to fetch user record.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            'username' => 'contact',
            'password' => 'password',
        ],
        'contactsFields' => [
            'type' => 'type',
        ],
        'userModel' => 'Dwdm/Users.Users',
        'contactModel' => 'Dwdm/Users.Contacts',
        'contactTypes' => ['email'],
        'scope' => [],
        'finder' => 'all',
        'contain' => null,
        'passwordHasher' => 'Default'
    ];

    /**
     * {@inheritDocs}
     */
    protected function _query($username)
    {
        $config = $this->_config;
        $table = TableRegistry::get($config['userModel']);
        $tableContacts = TableRegistry::get($config['contactModel']);

        $options = ['conditions' => [],];

        if (!empty($config['scope'])) {
            $options['conditions'] = array_merge($options['conditions'], $config['scope']);
        }
        if (!empty($config['contain'])) {
            $options['contain'] = $config['contain'];
        }

        $finder = $config['finder'];
        if (is_array($finder)) {
            $options += current($finder);
            $finder = key($finder);
        }

        if (!isset($options['username'])) {
            $options['username'] = $username;
        }

        $conditions = [
            $tableContacts->aliasField($config['contactsFields']['type']) . ' IN' => $config['contactTypes'],
            $tableContacts->aliasField($config['fields']['username']) => $username,
            'is_login' => true,
        ];


        $query = $table->find($finder, $options)
            ->contain([$tableContacts->getAlias()])
            ->matching(
                $tableContacts->getAlias(),
                function (Query $q) use ($conditions) {
                    return $q->where($conditions);
                }
            );

        return $query;
    }

    /**
     * {@inheritDocs}
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        $result = null;
        /** @var AuthComponent $Auth */
        $Auth = $this->_registry->get('Auth');
        if (!$Auth->getConfig('unauthorizedRedirect')) {
            $result = $response->withStatus(403, __d('users', 'Forbidden'))
                ->withBody(
                    new CallbackStream(
                        function () use ($Auth) {
                            return json_encode(['success' => false, 'message' => $Auth->getConfig('authError')]);
                        }
                    )
                );
        }

        return $result;
    }

    /**
     * {@inheritDocs}
     */
    protected function _findUser($username, $password = null)
    {
        $result = $this->_query($username)->first();

        if (empty($result)) {
           throw new ForbiddenException(__d('users', 'Contact not registered in system.'));
        }

        if ($password !== null) {
            $hasher = $this->passwordHasher();
            $hashedPassword = $result->get($this->_config['fields']['password']);
            if (!$hasher->check($password, $hashedPassword)) {
                throw new ForbiddenException(__d('users', 'Invalid contact or password.'));
            }

            $this->_needsPasswordRehash = $hasher->needsRehash($hashedPassword);
            $result->unsetProperty($this->_config['fields']['password']);
        }

        return $result->toArray();
    }
}