<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: ActivitiesMembers.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\ActivitiesMembersBase;

class ActivitiesMembers extends ActivitiesMembersBase
{
    public $studio = false;
    public $audit = false;
    public $notify = false;
    public $access_all = true;

    public function initialize()
    {
        $this->belongsTo('activity_id', Activities::class, 'id', ['alias' => 'activity']);
        parent::initialize();
    }

    public function setDefaultValueObject()
    {
        if (!$this->status) {
            $this->status = 'Invited';
        }
        parent::setDefaultValueObject();
    }
}