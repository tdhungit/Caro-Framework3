<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Common;


class TrueLog
{
    public function folderLog()
    {
        return APP_PATH . '/logs/';
    }

    public function log($message, $log_file = 'truecustomer')
    {
        if (is_array($message) || is_object($message)) {
            $message = json_encode($message);
        }

        $file = fopen($this->folderLog() . $log_file . '.log', "a+");
        fwrite($file, "$message\n");
        fclose($file);
    }

    public function logObject($object, $log_file = 'truecustomer')
    {
        $file = fopen($this->folderLog() . $log_file . '.log', "a+");
        fwrite($file, var_export($object, true) . "\n");
        fclose($file);
    }
}