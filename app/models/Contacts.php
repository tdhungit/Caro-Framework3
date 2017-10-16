<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: Contacts.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\ContactsBase;

class Contacts extends ContactsBase
{
    public $action_controller_name = 'contacts';

    public function getFieldName()
    {
        return 'CONCAT(first_name, " ", middle_name, " ", last_name)';
    }

    public function getName()
    {
        $name = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        return trim($name);
    }
}