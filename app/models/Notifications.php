<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\NotificationsBase;

class Notifications extends NotificationsBase
{
    public $studio = false;
    public $audit = false;
    public $notify = false;

    public function initialize()
    {
        $this->hasOne('user_id', Users::class, 'id', ['alias' => 'user']);
        $this->hasOne('assigned_user_id', Users::class, 'id', ['alias' => 'assigned_user']);
    }

    public function onConstruct()
    {
        parent::onConstruct();

        $this->user_id = $this->auth->id;
        $this->username = $this->auth->username;
    }
}