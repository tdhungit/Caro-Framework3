<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: ModelBase.php
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\Model;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

use TrueCustomer\Helpers\Layouts\ModelLayout;
use TrueCustomer\Helpers\Auth\TCAuthenticate;
use TrueCustomer\Helpers\Auth\TCConfigACLAccess;
use TrueCustomer\Models\Users;

class BaseModel extends Model
{
    /**
     * @var TCAuthenticate session
     *  id
     *  username
     *  email
     *  is_admin
     *  role
     */
    protected $auth;

    public $id;
    public $created;
    public $user_created_id;
    public $deleted = 0;
    public $assigned_user_id;

    public $studio = true;
    /**
     * @var ModelLayout
     */
    public $list_view;

    /**
     * @var ModelLayout
     */
    public $detail_view;

    /**
     * @var ModelLayout
     */
    public $edit_view;

    /**
     * set up links
     */
    public $action_controller_name = '';
    public $action_list = 'list';
    public $action_detail = 'detail';
    public $action_edit = 'edit';
    public $action_delete = 'delete';

    /**
     * public model, audit, notify
     */
    public $access_all = false;
    public $audit = true;
    public $notify = true;

    /**
     * save hasMany/hasOne
     */
    public $save_hasMany = [];
    public $save_hasOne = [];

    /**
     * @param $text
     * @return mixed|string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /**
     * Hash pass
     *
     * @param $raw_password
     * @return string
     */
    public static function hashPassword($raw_password)
    {
        return md5($raw_password);
    }

    /**
     * @param $data
     * @param $limit
     * @param $current_page
     * @return \stdclass
     */
    public static function pagination($data, $limit, $current_page)
    {
        $paginator = new PaginatorModel(array(
            "data"  => $data,
            "limit" => $limit,
            "page"  => $current_page > 0 ? $current_page : 1
        ));

        return $paginator->getPaginate();
    }

    /**
     * All Models
     * @return array
     */
    public static function getAllModels()
    {
        $model_path = APP_PATH . '/app/models/*.php';

        $models = array();
        foreach (glob($model_path) as $model) {
            $name = basename($model, '.php');
            $models[$name] = $name;
        }

        return $models;
    }

    /**
     * All Controllers
     * @return array
     */
    public static function getAllControllers()
    {
        $controller_path = APP_PATH . '/app/controllers/*.php';

        $controllers = array();
        foreach (glob($controller_path) as $controller) {
            $name = basename($controller, 'Controller.php');
            $name = strtolower($name);
            $controllers[$name] = $name;
        }

        return $controllers;
    }

    /**
     * All Fields on Database
     *
     * @return mixed
     */
    public static function getAllDatabase()
    {
        $tables = include APP_PATH . '/app/config/database_structure.php';
        return $tables;
    }

    /**
     * All field types
     * @param $theme string
     *
     * @return array
     */
    public static function getFieldTypes($theme = 'default')
    {
        $types = [
            'nodisplay' => 'No Display',
            'readonly' => 'Readonly',
            'text' => 'Text',
        ];

        $core_fields_folder = scandir(APP_PATH . '/app/themes/'. $theme .'/view_default/fields');

        foreach ($core_fields_folder as $type) {
            if (substr($type, 0, 1) != '.' && !in_array($type, ['base', 'config', 'customCode'])) {
                $types[$type] = ucfirst($type);
            }
        }

        return $types;
    }

    /**
     * @return TrueLog
     */
    public static function getLog()
    {
        return new \TrueCustomer\Common\TrueLog();
    }

    /**
     * @param string $dateTime
     * @param null $timezone
     * @param bool $immutableMode
     * @return \Moment\Moment
     */
    public static function getMoment($dateTime = 'now', $timezone = null, $immutableMode = false)
    {
        return new \Moment\Moment($dateTime, $timezone, $immutableMode);
    }

    /**
     * initialize model
     */
    public function onConstruct()
    {
        // authenticate
        $auth = $this->getDI()->getSession()->get('auth');
        $this->auth = new TCAuthenticate($auth);

        // base layout
        $layout_config_list_view = array();
        $layout_config_detail_view = array();
        $layout_config_edit_view = array();

        $class_name = str_replace("TrueCustomer\\Models\\", '', get_class($this));
        $config_name = $class_name . '.conf.php';
        $file_config = APP_PATH . '/app/config/layouts/' . $config_name;

        if (!$this->action_controller_name) {
            $this->action_controller_name = strtolower($class_name);
        }

        if (is_file($file_config)) {
            include $file_config;
        }

        $this->list_view = new ModelLayout($layout_config_list_view, 'list', $this);
        $this->detail_view = new ModelLayout($layout_config_detail_view, 'detail', $this);
        $this->edit_view = new ModelLayout($layout_config_edit_view, 'edit', $this);
    }

    /**
     * initialize model
     */
    public function initialize()
    {
        $this->keepSnapshots(true);
        $this->addBehavior(new Blameable());
    }

    /**
     * @return TCAuthenticate
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * All Fields on Model
     *
     * @param $table
     * @return mixed
     */
    public function allFields($table = '')
    {
        $table = ($table) ? $table : $this->getSource();
        $tables = $this->getAllDatabase();

        return $tables[$table];
    }

    /**
     * check exist field name
     *
     * @param $field string
     * @return bool
     */
    public function existField($field)
    {
        $metadata_fields = $this->allFields($this->getSource());
        $fields = $metadata_fields['fields'];
        if (empty($fields[$field])) {
            return false;
        }

        return true;
    }

    /**
     * Get where deleted = 0 and is owner
     *
     * @param $type string
     * @param $table_alias string
     * @return string
     */
    public function getWhere($type = 'list', $table_alias = '')
    {
        $alias = '';
        if ($table_alias) {
            $alias = $table_alias . '.';
        }

        $where = "{$alias}deleted = 0";

        if ($this->auth->is_admin || $this->access_all) {
            return $where;
        }

        $model_name = str_replace('TrueCustomer\Models\\', '', get_class($this));
        $access_type = $this->auth->levelAccessData($model_name, $type);
        $users_group = Users::getUsersInGroup($this->auth->group_id);

        switch ($access_type) {
            case TCConfigACLAccess::ALL:
                return $where;
                break;
            case TCConfigACLAccess::GROUP:
                $users = array_merge($users_group['siblings'], $users_group['children']);
                //$users[] = $this->auth->id;
                break;
            case TCConfigACLAccess::CHILD:
                $users = $users_group['children'];
                $users[] = $this->auth->id;
                break;
            case TCConfigACLAccess::OWNER:
            default:
                $users = array($this->auth->id);
                break;
        }

        $users_string_in_where = '(' . implode(',', $users) . ')';

        if ($this->existField('assigned_user_id')) {
            $where .= " AND {$alias}assigned_user_id IN {$users_string_in_where}";
        } else {
            $where .= " AND {$alias}user_created_id IN {$users_string_in_where}";
        }

        // users
        if ($model_name == 'Users') {
            $where .= " OR {$alias}id = {$this->auth->id}";
        }

        return $where;
    }

    /**
     * Set default some data
     */
    public function setDefaultValueObject()
    {
        // default data
        if (empty($this->created)) {
            $created = $this::getMoment()->format('Y-m-d H:i:s');
            $this->created = $created;
        }

        if (empty($this->user_created_id)) {
            $this->user_created_id = $this->auth->id;
        }

        if (empty($this->deleted)) {
            $this->deleted = 0;
        }

        if (empty($this->assigned_user_id)) {
            $this->assigned_user_id = $this->auth->id;
        }

        if (empty($this->slug) && !empty($this->name)) {
            $this->slug = $this->slugify($this->name);
        }

        if (empty($this->id) && !empty($this->password)) {
            $this->password = $this->hashPassword($this->password);
        }
    }

    /**
     * adjust data before save
     */
    public function adjustDataSave()
    {
        /* @TODO */
    }

    /**
     * Call logic hook function
     *
     * @param $type string
     */
    public function logicHookFunction($type)
    {
        /* @TODO */
    }

    /**
     * Inserts or updates a model instance. Returning true on success or false otherwise.
     * <code>
     * //Creating a new robot
     * $robot = new Robots();
     * $robot->type = 'mechanical';
     * $robot->name = 'Astro Boy';
     * $robot->year = 1952;
     * $robot->save();
     * //Updating a robot name
     * $robot = Robots::findFirst("id=100");
     * $robot->name = "Biomass";
     * $robot->save();
     * </code>
     *
     * @param array $data
     * @param array $whiteList
     * @return boolean
     */
    public function save($data = null, $whiteList = null)
    {
        $this->setDefaultValueObject();
        $this->adjustDataSave();
        return parent::save($data, $whiteList);
    }

    /**
     * @param null|mixed $data
     * @param null|mixed $whiteList
     * @return bool
     */
    public function create($data = null, $whiteList = null)
    {
        $this->setDefaultValueObject();
        $this->adjustDataSave();
        return parent::create($data, $whiteList);
    }

    /**
     * @param null|mixed $data
     * @param null|mixed $whiteList
     * @return bool
     */
    public function update($data = null, $whiteList = null)
    {
        $this->setDefaultValueObject();
        $this->adjustDataSave();
        return parent::update($data, $whiteList);
    }

    /**
     * @param bool $permanently
     * @return bool
     */
    public function delete($permanently = false)
    {
        if ($permanently == true) {
            return parent::delete();
        }

        $this->deleted = 1;
        return parent::update();
    }

    /**
     * @return mixed|string
     */
    public function getFieldName()
    {
        return $this->detail_view->title;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        if ($this->detail_view->title) {
            return $this->{$this->detail_view->title};
        }
        return '';
    }

    /**
     * Get one record
     *
     * @param $parameters
     * @return bool|Model|BaseModel
     */
    public function getOne($parameters)
    {
        if (empty($parameters)) {
            return false;
        }

        $where = $this->getWhere('view');

        if (is_numeric($parameters)) {
            $parameters = $where . " AND id = $parameters";
        } else if (is_string($parameters)) {
            $parameters .= " AND $where";
        }

        if (is_array($parameters)) {
            if (empty($parameters['conditions'])) {
                if (empty($parameters[0])) {
                    $parameters[0] = $where;
                } else {
                    $parameters[0] .= " AND $where";
                }
            } else {
                $parameters['conditions'] .= " AND $where";
            }
        }

        return parent::findFirst($parameters);
    }

    /**
     * Get All records
     *
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getMany($parameters = null)
    {
        $where = $this->getWhere('list');

        if (is_numeric($parameters)) {
            $parameters = $where . " AND id = $parameters";
        } else if (is_string($parameters)) {
            $parameters .= " AND $where";
        }

        if (is_array($parameters)) {
            if (empty($parameters['conditions'])) {
                if (empty($parameters[0])) {
                    $parameters[0] = $where;
                } else {
                    $parameters[0] .= " AND $where";
                }
            } else {
                $parameters['conditions'] .= " AND $where";
            }
        }

        return parent::find($parameters);
    }

}