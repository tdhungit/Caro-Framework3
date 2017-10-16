<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Layouts;


class ModelLayout
{
    /**
     * @var null|\TrueCustomer\Common\BaseModel
     */
    public $model;

    /**
     * @var string field name which you choose it as record title
     */
    public $title;

    /**
     * @var string column edit/detail
     */
    public $column;

    /**
     * @var array fields
     *  array field_name => options
     */
    public $fields = array();

    /**
     * @var array subpanels
     *  array subpanel_name => options
     */
    public $subpanels = array();

    /**
     * ModelLayout constructor.
     * @param array $data
     *  fields array field_name => options
     *  title
     * @param string $layout_type
     * @param null|\TrueCustomer\Common\BaseModel $model
     */
    public function __construct($data = array(), $layout_type, $model = null)
    {
        if (!empty($data)) {
            $this->title = (empty($data['title'])) ? 'id' : $data['title'];
            $this->column = (empty($data['column'])) ? 1 : $data['column'];
            if ($layout_type == 'list') {
                foreach ($data['fields'] as $field_name => $options) {
                    $this->fields[$field_name] = new ModelLayoutItem($field_name, $options);
                }
            } else {
                foreach ($data['fields'] as $block_name => $fields) {
                    foreach ($fields as $field_name => $options) {
                        $this->fields[$block_name][$field_name] = new ModelLayoutItem($field_name, $options);
                    }
                }
            }

            if (!empty($data['subpanels'])) {
                foreach ($data['subpanels'] as $subpanel_name => $subpanel) {
                    $this->subpanels[$subpanel_name] = new ModelLayoutSubpanel($subpanel_name, $subpanel);
                }
            }
        }

        $this->model = $model;
    }

    /**
     * @param $model null|\TrueCustomer\Common\BaseModel
     * @return mixed
     */
    public function getTitle($model = null)
    {
        if ($model && is_object($model)) {
            return $model->getName();
        }

        if ($this->model && is_object($this->model)) {
            return $this->model->getName();
        }

        return '';
    }

    /**
     * Get Attribute a field
     *
     * @param $field_name
     * @param $attr
     * @param $block_name
     * @return string
     */
    public function getFieldAttr($field_name, $attr, $block_name = '')
    {
        if (!$field_name) {
            return '';
        }

        if ($block_name) {
            if (empty($this->fields[$block_name][$field_name])) {
                if ($attr == 'label') {
                    $field_name = str_replace('_id', '', $field_name);
                    $field_name = str_replace('_', ' ', $field_name);
                    return ucfirst($field_name);
                } else {
                    return '';
                }
            }

            return $this->fields[$block_name][$field_name]->{$attr};
        }

        if (empty($this->fields[$field_name])) {
            if ($attr == 'label') {
                $field_name = str_replace('_id', '', $field_name);
                $field_name = str_replace('_', ' ', $field_name);
                return ucfirst($field_name);
            } else {
                return '';
            }
        }

        return $this->fields[$field_name]->{$attr};
    }

    /**
     * @param $field_name
     * @return bool|ModelLayoutItem
     */
    public function getField($field_name)
    {
        if (!empty($this->fields[$field_name])) {
            if (is_object($this->fields[$field_name])) {
                return $this->fields[$field_name];
            }

            if (is_array($this->fields[$field_name]) && !empty($this->fields[$field_name][$field_name])) {
                return $this->fields[$field_name][$field_name];
            }

            return false;
        }

        foreach ($this->fields as $block_name => $fields) {
            if (!empty($fields[$field_name])) {
                return $fields[$field_name];
            }
        }

        return false;
    }
}