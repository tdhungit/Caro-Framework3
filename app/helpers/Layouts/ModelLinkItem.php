<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Layouts;


use TrueCustomer\Helpers\Utils\TCUtils;

class ModelLinkItem
{
    public $label;
    public $icon;
    public $btn_class;
    public $type;
    public $controller;
    public $action;
    public $url;
    public $query;
    public $alert;

    /**
     * ModelLinkItem constructor.
     * @param array $link
     *  label
     *  icon
     *  controller
     *  action
     *  url
     *  query
     */
    public function __construct($link = array())
    {
        $this->label = (empty($link['label'])) ? '' : $link['label'];
        $this->icon = (empty($link['icon'])) ? '' : $link['icon'];
        $this->btn_class = (empty($link['btn_class'])) ? 'btn-default' : $link['btn_class'];
        $this->type = (empty($link['type'])) ? 'default' : $link['type'];
        $this->controller = (empty($link['controller'])) ? '' : $link['controller'];
        $this->action = (empty($link['action'])) ? '' : $link['action'];
        $this->url = (empty($link['url'])) ? '' : $link['url'];
        $this->query = (empty($link['query'])) ? '' : $link['query'];
        $this->alert = (empty($link['alert'])) ? '' : $link['alert'];
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getIcon()
    {
        if ($this->icon) {
            return $this->icon;
        }

        return 'fa fa-chain';
    }

    public function getBtnClass()
    {
        return $this->btn_class;
    }

    public function getUrl($data = null, $custom_data = array())
    {
        if ($this->url) {
            $url = $this->url;
            if ($data || !empty($custom_data)) {
                if (strpos($url, '{') !== false && strpos($url, '}') !== false) {
                    $fields = TCUtils::getInBetweenStrings($url);
                    foreach ($fields as $fieldUp) {
                        $field = strtolower($fieldUp);
                        $rex = '{' . $field . '}';

                        if (!empty($data->{$field})) {
                            $url = str_replace($rex, $data->{$field}, $url);
                        }

                        if (!empty($custom_data[$field])) {
                            $url = str_replace($rex, $custom_data[$field], $url);
                        }
                    }
                }
            }
            return $url;
        }

        if ($data) {
            $query = $this->query;
            if (strpos($query, '{') !== false && strpos($query, '}') !== false) {
                $fields = TCUtils::getInBetweenStrings($query);
                foreach ($fields as $fieldUp) {
                    $field = strtolower($fieldUp);
                    $rex = '{' . $fieldUp . '}';

                    if (!empty($data->{$field})) {
                        $query = str_replace($rex, $data->{$field}, $query);
                    }

                    if (!empty($custom_data[$field])) {
                        $query = str_replace($rex, $custom_data[$field], $query);
                    }
                }
            }
        }

        return '/' . $this->controller . '/' . $this->action . '/' . $query;
    }

    public function getAlert()
    {
        if ($this->alert) {
            return "alert=\"{$this->alert}\"";
        }

        return '';
    }
}