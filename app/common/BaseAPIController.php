<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\Controller;

class BaseAPIController extends Controller
{
    protected $auth;

    protected $model_name;
    protected $controller_name;
    protected $action_name;

    protected function initialize()
    {
        $this->controller_name = $this->dispatcher->getControllerName();
        $this->action_name = $this->dispatcher->getActionName();

        // default layout
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
    }

    /**
     * @param $data
     * @return bool
     */
    protected function response($data)
    {
        echo json_encode($data);
        return true;
    }
}