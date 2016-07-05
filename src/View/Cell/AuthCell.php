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
namespace Users\View\Cell;

use Cake\Controller\Component\AuthComponent;
use Cake\View\Cell;

/**
 * Auth cell
 */
class AuthCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Display user logged in or logged out menu.
     *
     * @param \Cake\Controller\Component\AuthComponent $Auth
     * @param array $options
     * @return void
     */
    public function display(AuthComponent $Auth, array $options = [])
    {
        $this->template = $Auth->user() ? 'logout' : 'login';

        $this->set(compact('Auth'));
        $this->set($options);
    }

    /**
     * Display menu if user logged in,
     *
     * @param \Cake\Controller\Component\AuthComponent $Auth
     * @param array $menu
     * @param array $options
     */
    public function menu(AuthComponent $Auth, array $menu = [], array $options = [])
    {
        $this->set('menu', $Auth->user() ? $menu : []);
        $this->set(compact('Auth'));
        $this->set($options);
    }
}
