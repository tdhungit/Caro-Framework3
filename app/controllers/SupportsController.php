<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;
use TrueCustomer\Helpers\Utils\FileUpload;

class SupportsController extends BaseController
{
    public function indexAction()
    {
        $this->setTitle('Supports');
    }

    public function iconsAction()
    {
        $this->setTitle('Icons');
    }

    public function uploadAction($upload_type = 'public')
    {
        $data_upload = array();
        // Check if the user has uploaded files
        if ($this->request->hasFiles() == true) {
            // Set upload folder
            $base_location = $this->request->getPost('location');
            if (!$base_location) {
                $base_location = 'files';
            }

            // make folder
            $base_path = $this->utils->makeFolderUpload($base_location, $upload_type);
            $file_upload = new FileUpload($base_path, 'Local', $this->utils->getSetting('upload'));

            // Process upload file
            foreach ($this->request->getUploadedFiles() as $file) {
                $data_upload[] = $file_upload->upload($file);
            }
        }

        $this->responseJson($data_upload);
    }

    public function downloadAction()
    {
        $this->view->disable();

        $file_uri = $this->request->getQuery('uri');
        $file_path = APP_PATH . '/uploads' . $file_uri;
        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
        }
    }

    public function file_pickerAction($source)
    {
        $this->view->disable();

        $ep = $this->request->getQuery('ep');
        echo $this->utils->renderFilePicker($source, $ep);
    }

    public function dpfieldsAction()
    {
        $data = [[
            'id' => '',
            'text' => '--'
        ]];

        $type = $this->request->getQuery('type');
        $selected = $this->request->getQuery('target_value');
        if ($type) {
            if ($type == 'relate' && $this->request->getQuery('model_name') && $this->request->getQuery('source_value')) {
                $fk = $this->request->getQuery('fk');
                if (!$fk) {
                    $fk = 'parent_id';
                }

                $model = $this->getModel($this->request->getQuery('model_name'));
                $result = $model->getMany([
                    'conditions' => "$fk = :parent_id:",
                    'bind' => ['parent_id' => $this->request->getQuery('source_value')]
                ]);

                foreach ($result as $item) {
                    $data[] = [
                        'id' => $item->id,
                        'text' => $item->{$model->detail_view->title},
                        'selected' => (($item->id == $selected) ? true : false),
                        'name' => $item->{$model->detail_view->title}
                    ];
                }
            }

            if ($type == 'dbselect' && $this->request->getQuery('source_value') && $this->request->getQuery('target_table')) {
                $fk = $this->request->getQuery('fk');
                if (!$fk) {
                    $fk = 'parent_id';
                }

                $query = new \TrueCustomer\Common\Query\BuilderNoModel();
                $query->from($this->request->getQuery('target_table'))
                    ->andWhere("$fk = :parent_id:")
                    ->orderBy('name, id');
                $result = $query->execute(['parent_id' => $this->request->getQuery('source_value')]);
                $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

                while ($row = $result->fetch()) {
                    $data[] = [
                        'id' => $row['id'],
                        'text' => $row['name'],
                        'selected' => (($row['id'] == $selected) ? true : false),
                        'name' => $row['name']
                    ];
                }
            }
        }

        return $this->responseJson($data);
    }
}