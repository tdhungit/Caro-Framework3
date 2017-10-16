<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: DashboardController.php
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;

class DashboardController extends BaseController
{
    public function indexAction()
    {
        $this->setTitle('Dashboard');
        $this->addCss(array(
            '/plugins/morris/morris.css',
            '/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
            '/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'
        ));
        $this->addJs(array(
            '/plugins/sparkline/jquery.sparkline.min.js',
            '/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
            '/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
            '/plugins/knob/jquery.knob.js',
            'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
            '/plugins/morris/morris.min.js',
            '/js/pages/dashboard.js'
        ));

        //$this->view->disable();
        //$model = $this->getModel('Users');
        //$m = $model::getMoment('2017-07-06 13:40:00', 'Asia/Ho_Chi_Minh');
        //$m->setTimezone('UTC');
        //echo $m->format();
    }
}