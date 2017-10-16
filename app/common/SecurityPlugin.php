<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use TrueCustomer\Helpers\Auth\TCAuthenticate;

class SecurityPlugin extends Plugin
{
    private $index_public_actions = array(
        'index',
        'login',
        'logout',
        'show401',
        'show404',
        'show500'
    );

    /**
     * @param $controller
     * @param $action
     * @return bool
     */
    private function _publicAction($controller, $action)
    {
        if ($controller == 'index') {
            if (in_array($action, $this->index_public_actions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $controller
     * @param $action
     * @return bool
     */
    private function _loginByAction($controller, $action)
    {
        if ($controller == 'users' && ($action == 'login_by' || $action == 'logout_by')) {
            return true;
        }

        return false;
    }

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth_array = $this->session->get('auth');
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $current_query = $this->request->getQuery();
        $query = '';
        foreach ($current_query as $p => $q) {
            if ($p != '_url') {
                $query .= $p . '=' . $q . '&';
            }
        }

        $query = substr($query, 0, -1);

        if (empty($current_query['_url'])) {
            $current_query['_url'] = '';
        }

        $current_url = urlencode($current_query['_url'] . '?' . $query);

        // controller public
        if ($this->_publicAction($controller, $action)) {
            return true;
        }

        $auth = new TCAuthenticate($auth_array);

        // check login
        if (!$auth->isLogin()) {
            $this->response->redirect('/login?return_url=' . $current_url . '&msg=Please Login!');
            return false;
        }

        // check is admin
        if ($auth->isAdmin()) {
            return true;
        }

        // check login by users
        if ($this->_loginByAction($controller, $action)) {
            return true;
        }

        // check controller access
        if (!$auth->isAccess($controller, $action)) {
            $dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'show401'
            ));
            return false;
        }

        return true;
    }
}