<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: Activities.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\ActivitiesBase;

class Activities extends ActivitiesBase
{
    public function initialize()
    {
        $this->hasMany('id', ActivitiesMembers::class, 'activity_id', ['alias' => 'members']);

        parent::initialize();
    }
}