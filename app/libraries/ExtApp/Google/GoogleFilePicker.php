<?php

/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Libraries\ExtApp\Google;


use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Libraries\ExtApp\ExtFilePicker;

class GoogleFilePicker extends ExtFilePicker
{
    public function render()
    {
        $config = TCUtils::get_array4file('/app/libraries/ExtApp/Google/config.php');
        $html = file_get_contents(APP_PATH . '/app/libraries/ExtApp/Google/filePicker.html');
        // add key
        $html = str_replace(':CLIENT_ID:', $config['client_id'], $html);
        $html = str_replace(':CLIENT_SECRET:', $config['client_secret'], $html);
        $html = str_replace(':DEVELOPER_KEY:', $config['developer_key'], $html);
        $html = str_replace(':APP_ID:', $config['app_id'], $html);
        return $html;
    }
}