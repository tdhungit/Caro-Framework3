<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Utils;


class FileUpload
{
    private $base_path;
    private $source; // Local|Google|Dropbox
    private $settings;

    public function __construct($base_path = [], $source = 'Local', $settings = [])
    {
        $this->source = $source;
        $this->base_path = $base_path;
        $this->settings = $settings;
    }

    public function getIsHash()
    {
        if (!empty($this->settings['hash_file_name_upload'])) {
            return $this->settings['hash_file_name_upload'];
        }
        return false;
    }

    public function getMaxSize()
    {
        if (!empty($this->settings['max_size'])) {
            return $this->settings['max_size'];
        }
        return false;
    }

    public function getFileTypes()
    {
        if (!empty($this->settings['file_types'])) {
            return $this->settings['file_types'];
        }
        return false;
    }

    /**
     * @param $file \Phalcon\Http\Request\File|\Phalcon\Http\Request\FileInterface
     * @return array
     */
    public function upload($file)
    {
        $class_name = "\\TrueCustomer\\Libraries\\ExtApp\\{$this->source}\\{$this->source}Storage";
        /* @var $object \TrueCustomer\Libraries\ExtApp\ExtStorage */
        $object = new $class_name();
        $object->is_hash = $this->getIsHash();
        $object->allow_file_types = $this->getFileTypes();
        $object->max_size = $this->getMaxSize();
        return $object->upload($file, $this->base_path);
    }

    public function renderPicker()
    {
        $class_name = "\\TrueCustomer\\Libraries\\ExtApp\\{$this->source}\\{$this->source}FilePicker";
        /* @var $object \TrueCustomer\Libraries\ExtApp\ExtFilePicker */
        $object = new $class_name();
        return $object->render();
    }
}