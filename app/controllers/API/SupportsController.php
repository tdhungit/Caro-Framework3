<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Controllers\API;


use TrueCustomer\Common\BaseAPIController;

class SupportsController extends BaseAPIController
{
    public function indexAction()
    {
        $this->view->disable();
        echo 'API';
    }
}