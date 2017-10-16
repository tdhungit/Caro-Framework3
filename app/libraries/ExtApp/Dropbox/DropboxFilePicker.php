<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: DropboxFilePicker.php
 */

namespace TrueCustomer\Libraries\ExtApp\Dropbox;


use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Libraries\ExtApp\ExtFilePicker;

class DropboxFilePicker extends ExtFilePicker
{

    public function render()
    {
        $config = TCUtils::get_array4file('/app/libraries/ExtApp/Dropbox/config.php');

        $html = file_get_contents(APP_PATH . '/app/libraries/ExtApp/Dropbox/filePicker.html');
        $html = str_replace(':APP_KEY:', $config['app_key'], $html);

        return $html;
    }
}