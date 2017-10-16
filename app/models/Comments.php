<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\CommentsBase;

class Comments extends CommentsBase
{
    public $studio = false;
    public $audit = false;
    public $notify = false;
    public $action_controller_name = 'comments';

    public function initialize()
    {
        $this->hasOne('user_created_id', Users::class, 'id', ['alias' => 'user']);
        parent::initialize();
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        if (empty($this->attachments)) {
            return [];
        }

        if (strpos($this->attachments, ',') === false) {
            return [$this->attachments];
        }

        return explode(',', $this->attachments);
    }
}