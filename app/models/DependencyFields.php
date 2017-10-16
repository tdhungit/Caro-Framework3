<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\DependencyFieldsBase;

class DependencyFields extends DependencyFieldsBase
{
    public $audit = false;
    public $notify = false;
    public $studio = false;
    public $action_controller_name = 'settings';
    public $action_list = 'dpfields';
    public $action_detail = 'dpfields_edit';
    public $action_edit = 'dpfields_edit';
    public $action_delete = 'dpfields_delete';
}