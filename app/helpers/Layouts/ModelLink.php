<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Layouts;


class ModelLink
{
    public $controller_name = null;

    /**
     * setup actions
     */
    public $action_list = 'list';
    public $action_detail = 'detail';
    public $action_edit = 'edit';
    public $action_delete = 'delete';

    /**
     * @var null|array
     */
    public $multi_actions = array();

    /**
     * @var null|array
     */
    public $list_actions = null;

    public $list = array();

    public $view = array();

    public $edit = array();

    /**
     * ModelLink constructor.
     * @param $controller_name
     */
    public function __construct($controller_name)
    {
        $this->controller_name = $controller_name;
        $this->defaultMultiActions();
    }

    /**
     * @param $model \TrueCustomer\Common\BaseModel
     */
    public function setActionsFromModel($model)
    {
        $this->action_list = $model->action_list;
        $this->action_detail = $model->action_detail;
        $this->action_edit = $model->action_edit;
        $this->action_delete = $model->action_delete;
    }

    /**
     * Set Links
     *
     * @param string $type
     * @param array $links
     */
    public function setLinks($type = 'list', $links = array())
    {
        if ($type == 'list') {
            $this->list = array();
            foreach ($links as $link) {
                $this->list[] = new ModelLinkItem($link);
            }
        } else if ($type == 'view') {
            $this->view = array();
            foreach ($links as $link) {
                $this->view[] = new ModelLinkItem($link);
            }
        } else if ($type == 'edit') {
            $this->edit = array();
            foreach ($links as $link) {
                $this->edit[] = new ModelLinkItem($link);
            }
        } else if ($type == 'list_actions') {
            $this->list_actions = array();
            foreach ($links as $link) {
                $this->list_actions[] = new ModelLinkItem($link);
            }
        }
    }

    /**
     * Append new link
     *
     * @param string $type
     * @param array $link
     */
    public function appendLink($type = 'list', $link = array())
    {
        if ($type == 'list') {
            $this->list[] = new ModelLinkItem($link);
        } else if ($type == 'view') {
            $this->view[] = new ModelLinkItem($link);
        } else if ($type == 'edit') {
            $this->edit[] = new ModelLinkItem($link);
        } else if ($type == 'list_actions') {
            $this->list_actions = new ModelLinkItem($link);
        }
    }

    /**
     * @param array $links
     */
    public function appendListActions2Default($links = array())
    {
        $default_actions = array(
            array(
                'label' => 'Edit',
                'icon' => 'fa fa-edit',
                'controller' => $this->controller_name,
                'action' => $this->action_edit,
                'query' => '{ID}'
            ),
            array(
                'label' => 'Delete',
                'icon' => 'fa fa-remove',
                'controller' => $this->controller_name,
                'action' => $this->action_delete,
                'query' => '{ID}'
            ),
        );

        $full_links = array_merge($default_actions, $links);
        $this->setLinks('list_actions', $full_links);
    }

    /**
     * default list multi actions
     */
    public function defaultMultiActions()
    {
        $this->multi_actions = array(
            new ModelLinkItem(array(
                'label' => 'Quick Edit',
                'icon' => 'fa fa-edit',
                'url' => 'javascript:quick_edit(\'{c_controller}\');'
            )),
            new ModelLinkItem(array(
                'label' => 'Delete',
                'icon' => 'fa fa-remove',
                'url' => 'javascript:delete_all(\'{c_controller}\');'
            ))
        );
    }

    /**
     * @param array $links
     */
    public function appendMultiActions($links = array())
    {
        foreach ($links as $link) {
            $this->multi_actions[] = new ModelLinkItem($link);
        }
    }

    /**
     * get Links
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = 'list')
    {
        if ($type == 'list') {
            return $this->list;
        } else if ($type == 'view') {
            return $this->view;
        } else if ($type == 'edit') {
            return $this->edit;
        } else if ($type == 'list_actions') {
            return $this->list_actions;
        }

        return array();
    }
}

