<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: AuditDetail.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\AuditDetailBase;

class AuditDetail extends AuditDetailBase
{
    public $studio = false;
    public $audit = false;
    public $notify = false;
    public $access_all = true;

    public function initialize()
    {
        $this->belongsTo('audit_id', Audit::class, 'id', ['alias' => 'audit']);
    }
}