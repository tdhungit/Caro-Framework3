<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Layouts;


class ModelLayoutItem
{
    /**
     * @var string field name
     */
    public $name;

    /**
     * @var string field type
     */
    public $type;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string const app_list_strings key
     */
    public $options;

    /**
     * @var string model name
     */
    public $model;

    /**
     * @var|mixed
     */
    public $default;

    /**
     * @var integer
     */
    public $required;

    /**
     * @var boolean
     */
    public $search;

    /**
     * @var string search operator default =
     */
    public $search_operator;

    /**
     * @var boolean
     */
    public $link;

    /**
     * @var string asc|desc
     */
    public $sort;

    /**
     * @var string
     */
    public $custom_query;

    public $read_only_types = array('password', 'readonly');

    /**
     * ModelLayoutItem constructor.
     * @param $name string
     * @param $option array
     */
    public function __construct($name, $option)
    {
        $this->name = $name;
        $this->type = (empty($option['type'])) ? '' : $option['type'];
        $this->label = (empty($option['label'])) ? '' : $option['label'];
        $this->options = (empty($option['options'])) ? '' : $option['options'];
        $this->model = (empty($option['model'])) ? '' : $option['model'];
        $this->default = (empty($option['default'])) ? '' : $option['default'];
        $this->required = (empty($option['required'])) ? 0 : $option['required'];
        $this->search = (empty($option['search'])) ? false : $option['search'];
        $this->search_operator = (empty($option['operator'])) ? '' : $option['operator'];
        $this->link = (empty($option['link'])) ? false : $option['link'];
        $this->sort = (empty($option['sort'])) ? false : $option['sort'];
        $this->custom_query = (empty($option['custom_query'])) ? '' : $option['custom_query'];
    }

    /**
     * @return bool
     */
    public function isReadonly()
    {
        if (in_array($this->type, $this->read_only_types)) {
            return true;
        }

        return false;
    }

    /**
     * @param $value string
     * @param $auth TCAuthenticate
     * @return mixed
     */
    public function toDb($value, $auth)
    {
        $file_path = APP_PATH . "/app/themes/{$auth->theme}/view_default/fields/{$this->type}/field_settings.php";
        if (file_exists($file_path)) {
            $function = "{$this->type}_field_setting_toDb";
            if (!function_exists($function)) {
                include $file_path;
            }

            return $function($this, $value, $auth);
        }

        return $value;
    }

    /**
     * @param $bean string|\TrueCustomer\Common\BaseModel
     * @return mixed
     */
    public function getVal($bean)
    {
        if (is_string($bean)) {
            return $bean;
        }

        if (is_object($bean)) {
            return $bean->{$this->name};
        }

        return '';
    }

    /**
     * @param $bean string|\TrueCustomer\Common\BaseModel
     * @param $auth TCAuthenticate
     * @return mixed
     */
    public function format($bean, $auth)
    {
        $value = $this->getVal($bean);

        $file_path = APP_PATH . "/app/themes/{$auth->theme}/view_default/fields/{$this->type}/field_settings.php";
        if (file_exists($file_path)) {
            $function = "{$this->type}_field_setting_format";
            if (!function_exists($function)) {
                include $file_path;
            }

            return $function($this, $value, $auth);
        }

        return $value;
    }

}