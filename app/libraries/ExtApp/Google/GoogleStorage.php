<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: GoogleFileUpload.php
 */

namespace TrueCustomer\Libraries\ExtApp\Google;


use TrueCustomer\Libraries\ExtApp\ExtStorage;

class GoogleStorage extends ExtStorage
{
    /**
     * @param \Phalcon\Http\Request\File|\Phalcon\Http\Request\FileInterface $file
     * @param array $base_path
     * @return array
     */
    public function upload($file, $base_path)
    {
        // TODO: Implement upload() method.
    }

    /**
     * @param string $file_path
     * @return string
     */
    public function download($file_path)
    {
        // TODO: Implement download() method.
    }

    /**
     * @param string $file_path
     * @return bool
     */
    public function delete($file_path)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param string $file_path
     * @param array $emails
     * @return bool
     */
    public function share($file_path, $emails)
    {
        // TODO: Implement share() method.
    }
}