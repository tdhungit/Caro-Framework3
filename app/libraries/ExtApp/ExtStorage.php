<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Libraries\ExtApp;


abstract class ExtStorage
{
    public $is_hash = null;
    public $allow_file_types = null;
    public $max_size = null;

    /**
     * @param \Phalcon\Http\Request\File|\Phalcon\Http\Request\FileInterface $file
     * @param array $base_path
     * @return array
     */
    abstract public function upload($file, $base_path);

    /**
     * @param string $file_path
     * @return string
     */
    abstract public function download($file_path);

    /**
     * @param string $file_path
     * @return bool
     */
    abstract public function delete($file_path);

    /**
     * @param string $file_path
     * @param array $emails
     * @return bool
     */
    abstract public function share($file_path, $emails);
}