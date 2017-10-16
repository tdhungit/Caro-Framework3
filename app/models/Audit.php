<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: Audit.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\AuditBase;

class Audit extends AuditBase
{
    public $studio = false;
    public $audit = false;
    public $notify = false;
    public $access_all = true;

    public function initialize()
    {
        $this->hasMany('id', AuditDetail::class, 'audit_id', ['alias' => 'details']);
    }

    /**
     * Executes code to set audits all needed data, like ipaddress, username etc
     */
    public function onConstruct()
    {
        parent::onConstruct();

        $this->username = $this->auth->username;
        /** @var Request $request */
        $request = $this->getDI()->get('request');
        //The client IP address
        $this->ipaddress = $request->getClientAddress();
    }
}