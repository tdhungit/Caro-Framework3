<?php
// Auto Generate by TrueCustomer Builder

namespace TrueCustomer\Models\Base;


use TrueCustomer\Common\BaseModel;

class AuditDetailBase extends BaseModel 
{
	public $id;
	public $created;
	public $user_created_id;
	public $deleted;
	public $audit_id;
	public $field_name;
	public $old_value;
	public $new_value;

}