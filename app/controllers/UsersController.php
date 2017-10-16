<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;
use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Helpers\Auth\TCConfigACLAccess;

class UsersController extends BaseController
{
    protected $model_name = 'Users';

    /**
     * @param $name
     * @param $type
     * @param $role_id
     * @return AuthPermissions
     */
    protected function checkExistPermission($name, $type, $role_id)
    {
        $permissionO = $this->getModel('AuthPermissions');
        $permission = $permissionO->getOne("name = '$name' AND type = '$type' AND auth_role_id = $role_id");

        if ($permission) {
            return $permission;
        }

        return false;
    }

    /**
     * @param $role_id
     * @return mixed
     */
    protected function removeOldPermissions($role_id)
    {
        /* @var $permissionO \TrueCustomer\Models\AuthPermissions */
        $permissionO = $this->getModel('AuthPermissions');
        $permissions = $permissionO->getMany("auth_role_id = $role_id");
        foreach ($permissions as $permission) {
            $permission->delete(true);
        }
    }

    public function listAction()
    {
        $this->links->appendListActions2Default(array(
            array(
                'label' => 'Login',
                'icon' => 'fa fa-exchange',
                'controller' => 'users',
                'action' => 'login_by',
                'query' => '{ID}'
            )
        ));
        return parent::listAction();
    }

    public function login_byAction($user_id)
    {
        $this->view->disable();

        $old_session = $this->auth->getAuth();

        /* @var $user \TrueCustomer\Models\Users */
        $user = $this->retrieveModel('Users', $user_id);

        if ($user) {
            $group_id = 0;
            $group_name = 'N/A';
            $role_id = 0;

            /* @var $group \TrueCustomer\Models\UserGroups */
            $group = $this->retrieveModel('UserGroups', $user->user_group_id);

            if ($group) {
                $group_id = $group->id;
                $group_name = $group->name;
                $role_id = $group->role_id;
            }

            $new_session = array(
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'is_admin' => $user->is_admin,
                'group_id' => $group_id,
                'group_name' => $group_name,
                'role_id' => $role_id,
                'login_by' => true
            );

            $this->session->set('old_auth', $old_session);
            $this->session->set('auth', $new_session);
        }

        return $this->redirect('/');
    }

    public function logout_byAction()
    {
        $this->view->disable();

        if ($this->session->has('old_auth')) {
            $old_session = $this->session->get('old_auth');
            $this->session->remove('old_auth');
            $this->session->set('auth', $old_session);
        }

        return $this->redirect('/');
    }

    // Groups manager
    public function groupsAction()
    {
        $this->model_name = 'UserGroups';
        $this->links->setActionsFromModel($this->getModel());
        return parent::listAction();
    }

    public function detail_groupAction($id)
    {
        $this->model_name = 'UserGroups';
        $this->links->setActionsFromModel($this->getModel());
        $this->detailAction($id);
    }

    public function edit_groupAction($id = null)
    {
        $this->model_name = 'UserGroups';
        $this->links->setActionsFromModel($this->getModel());
        $this->editAction($id);
    }

    // Role manager
    public function rolesAction()
    {
        $this->model_name = 'AuthRoles';
        $this->links->setActionsFromModel($this->getModel());
        $this->links->appendListActions2Default(array(
            array(
                'label' => 'Permissions',
                'icon' => 'fa fa-user-secret',
                'controller' => 'users',
                'action' => 'permissions',
                'query' => '{ID}'
            )
        ));

        return parent::listAction();
    }

    public function detail_roleAction($id)
    {
        $this->model_name = 'AuthRoles';
        $this->links->setActionsFromModel($this->getModel());
        $this->detailAction($id);
    }

    public function edit_roleAction($id = null)
    {
        $this->model_name = 'AuthRoles';
        $this->links->setActionsFromModel($this->getModel());
        $this->editAction($id);
    }

    public function permissionsAction($role_id)
    {
        /* @var $roleO \TrueCustomer\Models\AuthRoles */
        /* @var $role \TrueCustomer\Models\AuthRoles */
        $roleO = $this->getModel('AuthRoles');
        $role = $roleO->getOne($role_id);
        $this->setTitle('Permissions');
        $this->view->role_id = $role_id;
        $this->view->role = $role;

        // all controllers
        /* @var $userO \TrueCustomer\Models\Users */
        $userO = $this->getModel('Users');
        $controllers = $userO->getAllControllers();
        $this->view->controllers = $controllers;
        $this->view->access_actions = TCConfigACLAccess::$access_group_action;

        // all permissions
        /* @var $permissionO \TrueCustomer\Models\AuthPermissions */
        $permissionO = $this->getModel('AuthPermissions');
        $permissions = $permissionO->getDBPermissions($role_id);
        $this->view->permissions = $permissions;

        // all models
        $models = $userO->getAllModels();
        $this->view->models = $models;

        $this->view->controller_accesses = array(
            0 => 'Disable',
            1 => 'Enable'
        );
        $this->view->access_types = TCConfigACLAccess::$access_types;
        $this->view->accesses = TCConfigACLAccess::$accesses;
    }

    public function save_permissionsAction()
    {
        $this->view->disable();

        if ($this->request->isPost() && $this->request->getPost('role_id')) {
            $role_id = $this->request->getPost('role_id');
            $post_permissions = $this->request->getPost('permissions');

            $this->removeOldPermissions($role_id);
            foreach ($post_permissions as $post_permission => $access_type) {
                foreach ($access_type as $type => $access) {
                    $data = array(
                        'name' => $post_permission,
                        'auth_role_id' => $role_id,
                        'type' => $type,
                        'access' => $access
                    );

                    /* @var $permission \TrueCustomer\Models\AuthPermissions */
                    $permission = $this->getModel('AuthPermissions', $data);
                    $permission->create();
                }
            }

            // set cache
            /* @var $all_permissions \TrueCustomer\Models\AuthPermissions */
            $all_permissions = $this->getModel('AuthPermissions');
            $all_permissions->cachePermissions($role_id);

            $this->flash->success($this->t->_('Success!'));
            return $this->redirect('/users/permissions/' . $role_id);
        }

        return $this->redirect('/users/roles');
    }

    public function profileAction()
    {
        // Save
        if ($this->request->isPost()) {
            $save_type = $this->request->getPost('save_type');
            if ($save_type == 'profile') {
                $model = $this->getModel();
                $data = $this->request->getPost();
                $data['id'] = $this->auth->id;
                $this->saveRecord($model, $data, $errors);
                $this->alertMessages($errors, 'Update Your Profile Success!');
                return $this->redirect('/users/profile?tab=profile');
            } else {
                $preference = $this->request->getPost('preference');
                $user = $this->retrieveModel('Users', $this->auth->id);
                $user->preference = json_encode($preference);
                $user->save();
            }

            return $this->redirect('/users/profile');
        }

        // View
        $this->setTitle($this->auth->username, false);

        $tab_active = $this->request->getQuery('tab');
        if (!$tab_active || !in_array($tab_active, ['timeline', 'profile', 'settings'])) {
            $tab_active = 'timeline';
        }
        $this->view->tab_active = $tab_active;

        $user = $this->retrieveModel('Users', $this->auth->id);
        if ($user->social) {
            $user->social = @json_decode($this->social, true);
        } else {
            $user->social = [];
        }
        $this->view->user = $user;

        $preference = @json_decode($user->preference, true);
        $this->view->preference = $preference;

        $group = null;
        if (!$this->auth->isAdmin()) {
            $group = $this->retrieveModel('UserGroups', $this->auth->group_id);
        }
        $this->view->group = $group;

        // preference
        $preference_config = TCUtils::get_array4file('/app/config/layouts/user_preference.php');
        $this->view->preference_config = $preference_config;

        // timeline
        /* @var $auditO \TrueCustomer\Models\Audit */
        $auditO = $this->getModel('Audit');
        $audits_raw = $auditO::query()
            ->where("deleted = 0 AND user_created_id = {$this->auth->id}")
            ->orderBy('created desc')
            ->execute();
        $audits = $auditO->pagination($audits_raw, $this->utils->page_limit, $this->request->getQuery('page'));

        $this->view->audits = $audits;
        $query_urls = empty($query_urls) ? array('nosearch' => 1) : $query_urls;
        $this->view->current_url = $this->url->currentUrl($query_urls);

        // profile
        $model = $this->getModel();
        $this->view->model_name = $this->model_name;
        $this->view->model = $model;
        $this->view->hidden_fields = [
            'password',
            'is_admin',
            'user_group_id',
            'status'
        ];

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->controller = $controller;
        $this->view->action = $action;
    }

    public function mentionsAction()
    {
        $result = [];
        $q = strip_tags($this->request->getQuery('q'));
        if ($q) {
            $model = $this->getModel();
            $users = $model->getMany([
                'conditions' => 'username LIKE :keyword:',
                'bind' => ['keyword' => "%$q%"]
            ]);
            foreach ($users as $user) {
                $result[] = $user->username;
            }
        }
        $this->responseJson($result);
    }

    // Notifications
    public function notificationsAction()
    {
        $this->view->setTemplateAfter('ajax');

        $notifyO = $this->getModel('Notifications');
        $notifications = $notifyO->getMany('assigned_user_id = ' . $this->auth->id . ' AND is_read = 0');

        $this->view->notifications = $notifications;
        $this->view->count_notifications = count($notifications);
    }

    public function view_notificationAction($id)
    {
        /* @var $notify \TrueCustomer\Models\Notifications */
        $notify = $this->retrieveModel('Notifications', $id);
        if (!$notify->is_read) {
            $notify->is_read = 1;
            $notify->update();
        }

        $this->redirect("/{$notify->controller_name}/{$notify->view_detail_action}/{$notify->record_id}");
    }
}