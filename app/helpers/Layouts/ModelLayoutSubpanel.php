<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: ModelLayoutSubpanel.php
 */

namespace TrueCustomer\Helpers\Layouts;


class ModelLayoutSubpanel
{
    public $name;
    public $type;
    public $cur_model;
    public $cur_field;
    public $rel_model;
    public $rel_field;
    public $mid_model;
    public $mid_field1;
    public $mid_field2;
    public $buttons = true;
    public $disable_delete = false;

    /**
     * @var array ModelLayoutItem
     */
    public $fields = array();

    public function __construct($name, $data)
    {
        $this->name = $name;
        $this->type = (empty($data['type'])) ? '' : $data['type'];
        $this->cur_model = (empty($data['current_model'])) ? '' : $data['current_model'];
        $this->cur_field = (empty($data['current_field'])) ? '' : $data['current_field'];
        $this->rel_model = (empty($data['rel_model'])) ? '' : $data['rel_model'];
        $this->rel_field = (empty($data['rel_field'])) ? '' : $data['rel_field'];
        $this->mid_model = (empty($data['mid_model'])) ? '' : $data['mid_model'];
        $this->mid_field1 = (empty($data['mid_field1'])) ? '' : $data['mid_field1'];
        $this->mid_field2 = (empty($data['mid_field2'])) ? '' : $data['mid_field2'];
        $this->buttons = (empty($data['buttons'])) ? true : $data['buttons'];
        $this->disable_delete = (empty($data['disable_delete'])) ? false : $data['disable_delete'];

        if (!empty($data['list'])) {
            foreach ($data['list'] as $field_name => $field_options) {
                $this->fields[$field_name] = new ModelLayoutItem($field_name, $field_options);
            }
        }
    }
}