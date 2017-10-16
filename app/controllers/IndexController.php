<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;
use TrueCustomer\Models\MainMenu;
use TrueCustomer\Models\UserGroups;
use TrueCustomer\Models\Users;

class IndexController extends BaseController
{
    /**
     * @param $user \TrueCustomer\Models\Users
     */
    private function _setSessionUser($user)
    {
        $group_id = 0;
        $group_name = 'N/A';
        $role_id = 0;

        $group = UserGroups::findFirst($user->user_group_id);
        if ($group) {
            $group_id = $group->id;
            $group_name = $group->name;
            $role_id = $group-$role_id;
        }

        $this->session->set('auth', array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'is_admin' => $user->is_admin,
            'group_id' => $group_id,
            'group_name' => $group_name,
            'role_id' => $role_id,
            'login_by' => false,
            'preference' => $user->preference,
        ));
    }

    public function indexAction()
    {
        $this->setTitle('Login');
        // auth
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->redirect('/');
        }

        // login
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $return_url = $this->request->getPost('return_url');

            $user = Users::findFirst(array(
                "(username = :username: OR email = :username:) AND password = :password: AND status = 'Active'",
                'bind' => array('username' => $username, 'password' => md5($password))
            ));

            if ($user != false) {
                $this->_setSessionUser($user);

                // cache menu
                $menu = new MainMenu();
                $menu->cacheArrayMainMenu();

                $this->flash->success('Welcome ' . $user->name);
                if ($return_url) {
                    return $this->redirect($return_url);
                }

                return $this->redirect('/');
            } else {
                $this->flash->error('Wrong email/password');
                return $this->redirect('/login');
            }
        }

        // view
        $this->view->return_url = $this->request->getQuery('return_url');
        $this->view->message = $this->request->getQuery('msg');
        $this->view->setTemplateAfter('empty');
    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->redirect('/login');
    }

    public function show401Action()
    {

    }

    public function show404Action()
    {

    }

    public function show500Action()
    {

    }

    public function authenticateAction($source)
    {
        $this->view->disable();

        $data = $this->request->getQuery();

        switch ($source) {
            case 'Google':
                require_once APP_PATH . "/app/libraries/ExtApp/{$source}/{$source}Authenticate.php";
                $class_name = "{$source}Authenticate";
                break;
            default:
                $class_name = '';
                break;
        }

        if (!$class_name) {
            return $this->redirect('/');
        }

        /* @var $authenticate \TrueCustomer\Libraries\ExtApp\ExtAuthenticate */
        $authenticate = new $class_name();
        $authenticate->authenticate($data);
        if ($authenticate->isLogin()) {
            echo 'Login OK';
        } else {
            if ($authenticate->getRedirectUrl()) {
                $this->redirect($authenticate->getRedirectUrl());
            }
        }
    }
}