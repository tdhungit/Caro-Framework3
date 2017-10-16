<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Libraries\ExtApp\Local;


use TrueCustomer\Libraries\ExtApp\ExtStorage;

class LocalStorage extends ExtStorage
{
    /**
     * @param \Phalcon\Http\Request\File|\Phalcon\Http\Request\FileInterface $file
     * @param array $base_path
     * @return array
     */
    public function upload($file, $base_path)
    {
        $file_name = $file->getName();
        $upload_path = $base_path['folder'];

        // check size
        $file_size = $file->getSize();
        if ($this->max_size && $file_size > $this->max_size) {
            return [
                'status' => -1,
                'name' => $file_name,
                'file_size' => $file_size,
                'message' => 'File Too Large'
            ];
        }

        // check type
        $file_type = $file->getRealType();
        if ($this->allow_file_types && !in_array($file_type, $this->allow_file_types)) {
            return [
                'status' => -2,
                'name' => $file_name,
                'file_type' => $file_type,
                'message' => 'Error File Type'
            ];
        }

        // Move the file into the application
        $file_upload_name = $file_name;

        // hash file name
        if ($this->is_hash) {
            $file_upload_name = md5($file_name) . '.' . $file->getExtension();
        }

        //file path
        $path_file = $upload_path . $file_upload_name;

        // upload
        $upload_result = $file->moveTo($path_file);

        // result upload
        if ($upload_result) {
            return [
                'status' => 1,
                'name' => $file_name,
                'size' => $file_size,
                'type' => $file_type,
                'uri' => $base_path['uri'],
                'full_uri' => $base_path['full_uri'],
                'sub_folder' => $base_path['sub_folder'],
                'path' => $base_path['media_url'] . $base_path['uri'] . $file_upload_name
            ];
        } else {
            return [
                'status' => 0,
                'name' => $file_name,
                'message' => 'Upload Error'
            ];
        }
    }

    /**
     * @param string $file_path
     * @return string
     */
    public function download($file_path)
    {
        return APP_PATH . '/uploads' . $file_path;
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