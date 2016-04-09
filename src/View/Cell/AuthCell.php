<?php
/**
 *
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
