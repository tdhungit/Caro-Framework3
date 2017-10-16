<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

use TrueCustomer\Helpers\Layouts\ModelLink;
use TrueCustomer\Helpers\Auth\TCAuthenticate;
use TrueCustomer\Models\MainMenu;

/**
 * Class BaseController
 * @package TrueCustomer\Common
 * @property BaseUrl $url
 * @property \TrueCustomer\Helpers\Utils\TCUtils $utils
 */
class BaseController extends Controller
{
    /**
     * @var TCAuthenticate
     */
    protected $auth;

    protected $model_name;
    protected $controller_name;
    protected $action_name;
    protected $return_url = '';

    /**
     * @var NativeArray translation variable
     */
    protected $t;

    /**
     * @var \TrueCustomer\Helpers\Layouts\ModelLink
     */
    protected $links;

    /**
     * @var string
     */
    protected $where = '';

    protected function initialize()
    {
        $this->tag->setTitle($this->utils->page_title);

        $this->controller_name = $this->dispatcher->getControllerName();
        $this->action_name = $this->dispatcher->getActionName();

        // authenticate
        $auth = $this->session->get('auth');
        $this->auth = new TCAuthenticate($auth);
        $this->view->setVar('current_user', $this->auth);

        // default links
        $this->links = new ModelLink($this->controller_name);

        // default layout
        $this->view->setTemplateAfter('default');

        // language
        $this->t = $this->getTranslation();
        $this->view->setVar('t', $this->t);

        // variable
        $this->view->setVar('controller_name', $this->controller_name);
        $this->view->setVar('action_name', $this->action_name);
        $this->view->setVar('return_url', $this->return_url);

        // get MainMenu
        $main_menu = new MainMenu();
        $this->view->setVar('MainMenu', $main_menu->getMainMenu());
    }

    /**
     * @param null|string $model_name
     * @param null|array $data
     * @return null|\TrueCustomer\Common\BaseModel
     */
    protected function getModel($model_name = null, $data = null)
    {
        $model_focus = $this->model_name;
        if ($model_name) {
            $model_focus = $model_name;
        }

        if ($model_focus) {
            $model_path = '\\TrueCustomer\\Models\\' . $model_focus;
            /* @var  $model \TrueCustomer\Common\BaseModel */
            $model = new $model_path($data);
            return $model;
        }

        return null;
    }

    /**
     * @param $model_name string
     * @param $id integer
     * @return bool|\Phalcon\Mvc\Model|BaseModel
     */
    protected function retrieveModel($model_name, $id)
    {
        $model = $this->getModel($model_name);
        return $model->getOne($id);
    }

    /**
     * @return NativeArray
     */
    protected function getTranslation()
    {
        $language = $this->request->getBestLanguage();

        if (file_exists(APP_PATH . '/app/languages/' . $language . '.php')) {
            $lang = require APP_PATH . '/app/languages/' . $language . '.php';

        } else {
            $lang = require APP_PATH . '/app/languages/en_us.php';

        }

        return new NativeArray(array(
            'content' => $lang
        ));
    }

    /**
     * Set page Title
     *
     * @param $title string
     * @param $translate bool
     */
    protected function setTitle($title, $translate = true)
    {
        if ($translate) {
            $language_title = $this->t->_($title);
        } else {
            $language_title = $title;
        }

        $this->tag->prependTitle($language_title);
    }

    /**
     * redirect
     * @param null $uri
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function redirect($uri = null)
    {
        return $this->response->redirect($uri);
    }

    /**
     * @param $errors array
     * @param $success_message string
     */
    protected function alertMessages($errors, $success_message)
    {
        if (empty($errors)) {
            $this->flash->success($this->t->_($success_message));
        } else {
            foreach ($errors as $error) {
                $this->flash->error($error);
            }
        }
    }

    /**
     * @param bool $redirect
     * @return bool|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function checkLogin($redirect = false)
    {
        if (!$this->auth->isLogin() && $this->controller_name != 'index') {
            if ($redirect == true) {
                return $this->redirect('/login');
            }
            return false;
        }
        return true;
    }

    /**
     * @param $url_query
     * @param $view_fields
     * @return array
     */
    protected function getFieldsSearch($url_query, $view_fields)
    {
        $conditions = '1';
        $search = array();
        foreach ($url_query as $field => $value) {
            if (!empty($view_fields[$field]->search) && $view_fields[$field]->search == true) {
                $operator = !empty($view_fields[$field]->search_operator) ? $view_fields[$field]->search_operator : '=';
                $operator = strtolower($operator);
                switch ($operator) {
                    case 'like':
                        if ($value) {
                            $conditions .= " AND $field like :$field:";
                            $search[$field] = "%$value%";
                        }
                        break;
                    default:
                        if ($value) {
                            $conditions .= " AND $field = :$field:";
                            $search[$field] = $value;
                        }
                }
            }
        }

        return array(
            'conditions' => ($conditions == '1') ? '' : $conditions,
            'parameters' => $search
        );
    }

    /**
     * @param $sorts_str string query string sort
     * @param $view_fields array fields of object
     * @param $sorts_view
     * @return bool|string
     */
    protected function getFieldsSort($sorts_str, $view_fields, &$sorts_view = [])
    {
        $sorts_str = str_replace(' ', '', $sorts_str);
        if (!$sorts_str) {
            return false;
        }

        if (strpos($sorts_str, '|') !== false) {
            $sorts_arr = explode('|', $sorts_str);
        } else {
            $sorts_arr = array($sorts_str);
        }

        $sorts = [];
        foreach ($sorts_arr as $item) {
            if (strpos($item, '__') !== false) {
                $item_arr = explode('__', $item);
                $field_sort = $item_arr[0];
                $sort_type = strtolower($item_arr[1]);
                if (!empty($view_fields[$field_sort]) && in_array($sort_type, ['asc', 'desc'])) {
                    $sorts[$field_sort] = $field_sort . ' ' . $sort_type;
                    $sorts_view[$field_sort] = $sort_type;
                }
            } else {
                if (!empty($view_fields[$item])) {
                    $sorts[$item] = $item . ' asc';
                    $sorts_view[$item] = 'asc';
                }
            }
        }

        return implode(',', $sorts);
    }

    /**
     * @param $def \TrueCustomer\Helpers\Layouts\ModelLayoutSubpanel
     * @param $data BaseModel
     * @param $paginator_limit
     * @param $currentPage int
     * @param $sort array
     * @return \stdclass
     */
    protected function getSubpanel($def, $data, $paginator_limit, $currentPage, $sort)
    {
        $order_by = implode(' ', $sort);

        if ($def->type == 'one-many') {
            $rel_model = $this->getModel($def->rel_model);
            $panel = $rel_model->getMany([
                'conditions' => $def->rel_field . '=' . $data->id,
                'order' => $order_by
            ]);

            return $rel_model->pagination($panel, $paginator_limit, $currentPage);

        } else if ($def['type'] == 'many-many') {
            $namespace = 'TrueCustomer\Models\\';

            $current_model = $namespace . $def->cur_model;
            $current_field = $current_model . '.' . $def->cur_field;

            $rel_model = $namespace . $def->rel_model;
            $rel_field = $rel_model . '.' . $def->rel_field;

            $mid_model = $namespace . $def->mid_model;
            $mid_field1 = $mid_model . '.' . $def->mid_field1;
            $mid_field2 = $mid_model . '.' . $def->mid_field2;

            $panel = $this->modelsManager->createBuilder()
                ->from($rel_model)
                ->join($mid_model, $mid_field2 . '=' . $rel_field)
                ->join($current_model, $current_field . '=' . $mid_field1)
                ->where($current_field . '=' . $data->id)
                ->orderBy($rel_model . '.' . $order_by)
                ->getQuery()->execute();

            $rel_model = $this->getModel($def->rel_model);
            return $rel_model->pagination($panel, $paginator_limit, $currentPage);
        }
    }

    /**
     * save/update a record
     *
     * @param $model BaseModel
     * @param array $data fields value, can use post data from form. This function filter same edit_view and save to db
     * @param array errors message
     * @return bool|null|object record
     */
    protected function saveRecord($model, $data, &$errors_msg = array())
    {
        $metadata_fields = $model->allFields();
        $fields = $metadata_fields['fields'];

        $id = !empty($data['id']) ? $data['id'] : null;

        if (!empty($id)) { // update a record
            // get record
            $row = $model->getOne($id);

            if (!$row) {
                $this->flash->error($this->t->_('This record was deleted or You can not access it'));
                return $this->redirect('/index/show401');
            }

            // check access
            if (!$this->auth->isAccessData($row, 'edit')) {
                $this->flash->error($this->t->_('You can not access this record'));
                return $this->redirect('/index/show401');
            }

            // set data update
            foreach ($fields as $field => $options) {
                if (!$model->edit_view->getField($field)->isReadonly()) {
                    if (isset($data[$field])) {
                        $row->{$field} = $model->edit_view->getField($field)->toDb($data[$field], $this->auth);
                    }
                }
            }

            // set save relationship
            $this->saveHasOne($model, $row, $data);
            $this->saveHasMany($model, $row, $data);

            if ($row->update() == false) {
                $errors_msg = $row->getMessages();
                return false;
            }

            return $row;

        } else { // save new record
            // set data save
            foreach ($fields as $field => $options) {
                if (!$model->edit_view->getField($field)->isReadonly()) {
                    $model->{$field} = $model->edit_view->getField($field)->toDb($data[$field], $this->auth);
                }
            }

            // set save relationship
            $row = false;
            $this->saveHasOne($model, $row, $data);
            $this->saveHasMany($model, $row, $data);

            // save
            if ($model->save() == false) {
                $errors_msg = $model->getMessages();
                return false;
            }

            return $model;
        }
    }

    /**
     * @param $model \TrueCustomer\Common\BaseModel
     * @param $record bool|\TrueCustomer\Common\BaseModel
     * @param $data array
     */
    protected function saveHasMany(&$model, &$record, $data)
    {
        if (!empty($model->save_hasMany)) {
            foreach ($model->save_hasMany as $alias) {
                if (!empty($data[$alias]) && !empty($data[$alias]['model_name']) && !empty($data[$alias]['fields'])) {
                    $alias_items = [];
                    foreach ($data[$alias]['fields'] as $fields) {
                        $aliasO = $this->getModel($data[$alias]['model_name']);
                        foreach ($fields as $field => $value) {
                            $aliasO->{$field} = $value;
                        }
                        $alias_items[] = $aliasO;
                    }

                    if (!$record) {
                        $model->{$alias} = $alias_items;
                    } else {
                        $record->{$alias} = $alias_items;
                    }
                }
            }
        }
    }

    /**
     * @param $model \TrueCustomer\Common\BaseModel
     * @param $record bool|\TrueCustomer\Common\BaseModel
     * @param $data array
     */
    protected function saveHasOne(&$model, &$record, $data) {
        if (!empty($model->save_hasOne)) {
            foreach ($model->save_hasOne as $alias) {
                if (!empty($data[$alias]) && !empty($data[$alias]['model_name']) && !empty($data[$alias]['fields'])) {
                    $aliasO = $this->getModel($data[$alias]['model_name']);
                    foreach ($data[$alias]['fields'] as $field => $value) {
                        $aliasO->{$field} = $value;
                    }

                    if (!$record) {
                        $model->{$alias} = $aliasO;
                    } else {
                        $record->{$alias} = $aliasO;
                    }
                }
            }
        }
    }

    /**
     * Delete Record
     *
     * @param $id
     * @param $model_name
     * @return mixed
     */
    protected function deleteRecord($id, $model_name = null)
    {
        // get model
        $model = $this->getModel($model_name);
        $data = $model->getOne($id);

        if ($data) {
            $data->deleted = 1;
            return $data->update();
        }

        return false;
    }

    /**
     * @param array $path
     */
    protected function addCss($path)
    {
        foreach ($path as $item) {
            if (strpos($item, 'http') === false || strpos($item, 'https') === false) {
                $this->assets->addCss('/themes/' . $this->auth->theme . $item);
            } else {
                $this->assets->addCss($item);
            }
        }
    }

    /**
     * @param array $path
     */
    protected function addJs($path)
    {
        foreach ($path as $item) {
            if (strpos($item, 'http') === false || strpos($item, 'https') === false) {
                $this->assets->addJs('/themes/' . $this->auth->theme . $item);
            } else {
                $this->assets->addJs($item);
            }
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function responseJson($data)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        echo json_encode($data);
        return true;
    }

    /**
     * @return TrueLog
     */
    protected function getLog()
    {
        return new \TrueCustomer\Common\TrueLog();
    }

    public function indexAction()
    {
        $this->listAction();
    }

    public function listAction()
    {
        $this->setTitle('List ' . $this->model_name);
        $this->view->title = $this->t->_('List ' . $this->model_name);

        $model = $this->getModel();

        if (is_null($model)) {
            $this->redirect('/dashboard');
        }

        $query_urls = $this->url->currentQuery($this->request->getQuery(), ['page']);

        // search
        $search_opt = $this->getFieldsSearch($query_urls, $model->list_view->fields);
        $conditions = $search_opt['conditions'];
        $parameters = $search_opt['parameters'];

        // custom where
        if ($this->where) {
            $conditions .= ' ' . $this->where;
        }
        // query parameters
        $query_parameters = array(
            $conditions,
            'bind' => $parameters
        );

        // sort
        $sorts_view = [];
        $sorts = $this->getFieldsSort($this->request->getQuery('sort'), $model->list_view->fields, $sorts_view);
        if ($sorts) {
            $query_parameters['order'] = $sorts;
        }

        $list_data = $model->getMany($query_parameters);

        // pagination
        $currentPage = $this->request->getQuery('page');
        $paginator_limit = 20; // @TODO
        $page = $model->pagination($list_data, $paginator_limit, $currentPage);

        $this->view->page = $page;
        $this->view->data = $page->items;
        $this->view->list_view = $model->list_view;
        $this->view->search = $query_urls;
        $this->view->sorts = $sorts_view;

        $query_urls = empty($query_urls) ? array('nosearch' => 1) : $query_urls;
        $this->view->current_url = $this->url->currentUrl($query_urls);

        $this->view->links = $this->links;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->model_name = $this->model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/list');
        }
    }

    public function popupAction($rel_model, $current_model, $current_id, $subpanel_name)
    {
        $this->view->setTemplateAfter('ajax');

        $title = $this->t->_('List ') . $this->t->_($rel_model);
        $this->view->title = $title;

        $model = $this->getModel($rel_model);

        $query_urls = $this->url->currentQuery($this->request->getQuery(), ['page']);

        // search
        $search_opt = $this->getFieldsSearch($query_urls, $model->list_view->fields);
        $conditions = $search_opt['conditions'];
        $parameters = $search_opt['parameters'];

        // custom where
        if ($this->where) {
            $conditions .= ' ' . $this->where;
        }
        // query parameters
        $query_parameters = array(
            $conditions,
            'bind' => $parameters
        );

        // sort
        $sorts_view = [];
        $sorts = $this->getFieldsSort($this->request->getQuery('sort'), $model->list_view->fields, $sorts_view);
        if ($sorts) {
            $query_parameters['order'] = $sorts;
        }

        $list_data = $model->getMany($query_parameters);

        // pagination
        $currentPage = (int) $this->request->getQuery('page');
        $paginator_limit = 20; // @TODO
        $page = $model->pagination($list_data, $paginator_limit, $currentPage);

        $this->view->page = $page;
        $this->view->data = $page->items;
        $this->view->list_view = $model->list_view;
        $this->view->search = $query_urls;
        $this->view->sorts = $sorts_view;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);

        $this->view->controller = $controller;
        $this->view->action = $action;

        $this->view->rel_model = $rel_model;
        $this->view->current_model = $current_model;
        $this->view->current_id = $current_id;
        $this->view->subpanel_name = $subpanel_name;

        $query_urls = empty($query_urls) ? array('nosearch' => 1) : $query_urls;
        $this->view->current_uri = $this->router->getRewriteUri();
        $this->view->current_url = $this->url->get($this->view->current_uri, $query_urls);

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/popup');
        }
    }

    public function detailAction($id)
    {
        $model = $this->getModel();
        $data = $model->getOne($id);

        if (!$data) {
            return $this->redirect('/index/show401');
        }

        $title = 'Detail ' . $this->model_name . ': ' . $model->detail_view->getTitle($data);
        $this->setTitle($title);
        $this->view->title = $title;

        $this->addJs([
            '/js/pages/detail.js'
        ]);

        $this->view->data = $data;
        $this->view->detail_view = $model->detail_view;
        $this->view->links = $this->links;

        // subpanel
        $this->view->subpanels = null;
        if (!empty($model->detail_view->subpanels)) {
            $this->view->subpanels = $model->detail_view->subpanels;
        }

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->model_name = $this->model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;

        $this->addJs([
            '/js/pages/subpanel.js'
        ]);

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/detail');
        }
    }

    public function subpanelAction($model_name, $id, $subpanel_name)
    {
        $this->view->setTemplateAfter('ajax');

        $no_data = false;
        $no_def = false;

        $model = $this->getModel($model_name);
        $data = $model->getOne($id);

        if (!$data) {
            $no_data = true;
        }

        $def = (empty($model->detail_view->subpanels[$subpanel_name])) ? null : $model->detail_view->subpanels[$subpanel_name];
        if (!$def) {
            $no_def = true;
        }

        if ($data && $def) {
            $currentPage = $this->request->getQuery('page_' . $subpanel_name);
            $sort_str = $this->request->getQuery('sort');
            // get sort
            $sort = ['field' => 'created', 'type' => 'desc'];
            if ($sort_str) {
                if (strpos($sort_str, '__') === false) {
                    $sort = ['field' => $sort_str, 'type' => 'asc'];
                } else {
                    $sort_arr = explode('__', $sort_str);
                    $sort = ['field' => $sort_arr[0], 'type' => $sort_arr[1]];
                }
            }

            $subpanel = $this->getSubpanel($def, $data, $this->utils->supanel_limit, $currentPage, $sort);
            $this->view->subpanel_def = $def;
            $this->view->subpanel = $subpanel;
            $this->view->sort = $sort;
        }

        $this->view->model_name = $model_name;
        $this->view->id = $id;
        $this->view->subpanel_name = $subpanel_name;
        $this->view->no_data = $no_data;
        $this->view->no_def = $no_def;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->model_name = $this->model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/subpanel');
        }
    }

    public function auditAction($id, $model_name)
    {
        $this->view->setTemplateAfter('ajax');

        $auditO = $this->getModel('Audit');
        $audits = $auditO->getMany([
            'conditions' => "model_name = '$model_name' AND record_id = $id",
            'order' => 'created desc'
        ]);
        $this->view->audits = $audits;

        $model = $this->getModel($model_name);
        $this->view->fields = $model->edit_view->fields;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->model_name = $this->model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/audit');
        }
    }

    public function editAction($id = null)
    {
        // get model
        $model = $this->getModel();
        $this->return_url = '/' . $this->controller_name . '/' . $this->links->action_detail . '/{ID}';

        // edit view data
        $data = null;

        if ($id) {
            /* @var $data BaseModel */
            $data = $model->getOne($id);
            if (!$data) {
                return $this->redirect('/index/show401');
            }

            $title = 'Edit ' . $this->model_name . ': ' . $model->edit_view->getTitle($data);
        } else {
            $title = 'Create ' . $this->model_name;
        }

        $this->setTitle($title);
        $this->view->title = $title;

        $this->addJs([
            '/js/pages/edit.js'
        ]);

        $this->view->edit_view = $model->edit_view;
        $this->view->model = $model;
        $this->view->data = $data;
        $this->view->links = $this->links;

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);
        $this->view->model_name = $this->model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;
        $this->view->return_url = $this->return_url;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/edit');
        }
    }

    public function quick_createAction()
    {
        $this->view->setTemplateAfter('ajax');

        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);

        $model_name = $this->request->getQuery('model_name');
        if (!$model_name) {
            $model_name = $this->model_name;
        }
        $model = $this->getModel($model_name);
        $fields = array();

        $data = [];
        $params = $this->request->getQuery();
        foreach ($model->edit_view->fields as $block_name => $block_fields) {
            foreach ($block_fields as $name => $field) {
                if ($field->required >= 1) {
                    $fields[$block_name][$name] = $field;
                    $data[$field->name] = empty($params[$field->name]) ? '' : $params[$field->name];
                }
            }
        }

        $submit_callback = '';
        if (!empty($params['submit_callback'])) {
            $submit_callback = $params['submit_callback'];
        }

        if (empty($field)) {
            $this->view->disable();
            echo '0';
            return false;
        }

        $this->view->fields = $fields;
        $this->view->data = (object) $data;
        $this->view->model_name = $model_name;
        $this->view->controller = $controller;
        $this->view->action = $action;
        $this->view->submit_callback = $submit_callback;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/quick_create');
        }
    }

    public function quick_editAction($id = null)
    {
        $this->view->setTemplateAfter('ajax');
        $controller = strtolower($this->controller_name);
        $action = strtolower($this->action_name);

        $title = $this->t->_('Quick Edit');
        if (!$id && !$this->request->isPost()) {
            $this->flash->error($this->t->_('Error Params'));
            return $this->redirect("/{$controller}/index");
        }

        $this->view->title = $title;
        $this->setTitle($title, false);

        $ids = array();

        if ($id) {
            $ids = array($id);
        } else if ($this->request->isPost() && $this->request->getPost('choose_records')) {
            $ids = $this->request->getPost('choose_records');
        }

        $this->view->ids = $ids;

        $model = $this->getModel();

        $this->view->model_name = $this->model_name;
        $this->view->fields = $model->edit_view->fields;
        $this->view->data = null;
        $this->view->controller = $controller;

        $return_url = "/{$controller}/index";
        if ($this->request->isPost()) {
            $return_url = $this->request->getPost('return_url');
        }
        $this->view->return_url = $return_url;

        $exists = $this->view->exists($controller . '/' . $action);
        if (!$exists) {
            $this->view->pick('view_default/quick_edit');
        }
    }

    public function saveAction()
    {
        // save data
        if ($this->request->isPost()) {
            $model_name = $this->request->getPost('model_name');
            // get model
            $model = $this->getModel($model_name);

            $record = $this->saveRecord($model, $this->request->getPost(), $errors);

            // ajax post
            if ($this->request->isAjax()) {
                $this->view->disable();
                if ($record) {
                    return $this->responseJson([
                        'status' => 1,
                        'message' => $this->t->_('Create ' . $model_name . ' successful!'),
                        'data' => $record->toArray()
                    ]);
                } else {
                    $messages = '';
                    foreach ($errors as $error) {
                        $messages .= $this->t->_($error) . '<br>';
                    }
                    return $this->responseJson([
                        'status' => 0,
                        'message' => $messages
                    ]);
                }
                return true;
            }

            $return_url = $this->request->getPost('return_url');
            if (!$return_url) {
                $return_url = '/' . $this->controller_name . '/' . $this->links->action_detail . '/' . $record->id;
            }

            $return_url = str_replace('{ID}', $record->id, $return_url);

            $this->alertMessages($errors, 'Save ' . $model_name . ' Success!');
            return $this->redirect($return_url);
        }
    }

    public function save_quick_editAction()
    {
        $this->view->disable();
        $controller = strtolower($this->controller_name);
        $return_url = "/{$controller}/{$this->links->action_list}";

        if ($this->request->isPost()) {
            $model = $this->getModel();
            $return_url = $this->request->getPost('return_url');

            $post = $this->request->getPost();
            $ids = $this->request->getPost('ids');

            $data_update = array();
            foreach ($model->edit_view->fields as $field) {
                if (isset($post[$field->name])) {
                    if ($post[$field->name] || $post[$field->name] === '0') {
                        $data_update[$field->name] = $post[$field->name];
                    }
                }
            }

            $ids_str = '(' . implode(',', $ids) . ')';
            $data = $model->getMany("id IN {$ids_str}");
            // update
            if (!empty($data) && !empty($data_update)) {
                $data->update($data_update);
            }
        }

        $this->redirect($return_url);
    }

    public function save_relateAction()
    {
        $this->view->disable();

        if ($this->request->isPost()) {
            $rel_model = $this->request->getPost('rel_model');
            $rel_id = $this->request->getPost('rel_id');
            $current_model = $this->request->getPost('current_model');
            $current_id = $this->request->getPost('current_id');
            $subpanel_name = $this->request->getPost('subpanel_name');
            $func = $this->request->getPost('func');

            if ($rel_model && $rel_id
                && $current_model && $current_id
                && $subpanel_name
            ) {
                $focus = $this->getModel($current_model);
                $subpanel_def = $focus->detail_view->subpanels[$subpanel_name];
                $type = $subpanel_def->type;

                if ($type == 'one-many') {
                    $save_model = $this->getModel($rel_model);
                    $save_data = $save_model->getOne($rel_id);
                    $save_data->{$subpanel_def->rel_field} = $current_id;

                    if ($func == 'ins' && $save_data->update() == false) {
                        $this->flash->error($this->t->_('Sorry, can not add this record relate'));
                    }

                    $save_data->{$subpanel_def->rel_field} = 0;
                    if ($func == 'del' && $save_data->update() == false) {
                        $this->flash->error($this->t->_('Sorry, can not remove this record relate'));
                    }

                } else if ($type == 'many-many') {
                    $mid_model = $this->getModel($subpanel_def->mid_model);
                    $mid_model->{$subpanel_def->mid_field1} = $current_id;
                    $mid_model->{$subpanel_def->mid_field2} = $rel_id;

                    if ($func == 'ins' && $mid_model->save() == false) {
                        $this->flash->error($this->t->_('Sorry, can not add this record relate'));
                    }

                    if ($func == 'del') {
                        $mid_data = $mid_model->getOne(array(
                            "conditions" => $subpanel_def->mid_field1 . '=' . $current_id . " and " . $subpanel_def->mid_field2 . '=' . $rel_id
                        ));
                        if ($mid_data->delete() == false) {
                            $this->flash->error($this->t->_('Sorry, can not remove this record relate'));
                        }
                    }

                }
            }
        }
    }

    public function deleteAction($id = null, $model_name = null)
    {
        if ($id) {
            $result = $this->deleteRecord($id, $model_name);

            if ($result == false) {
                $this->flash->error($this->t->_('Fail, record was not deleted successfully!'));
            } else {
                $this->flash->success($this->t->_('Great, record was deleted successfully!'));
            }

            if ($this->request->getQuery('return_url')) {
                return $this->redirect($this->request->getQuery('return_url'));
            }

            return $this->redirect("/{$this->controller_name}/{$this->links->action_list}");

        } else {
            $return_url = '/' . strtolower($this->controller_name) . '/' . $this->links->action_list;
            if ($this->request->isPost()) {
                $ids = $this->request->getPost('choose_records');
                $return_url = $this->request->getPost('return_url');

                $ids_str = '(' . implode(',', $ids) . ')';
                $model = $this->getModel($model_name);
                $data = $model->getMany("id IN {$ids_str}");
                $data->delete();
            }

            return $this->redirect($return_url);
        }
    }
}