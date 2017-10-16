<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: TCUtils.php
 */

namespace TrueCustomer\Helpers\Utils;


class TCUtils
{
    public $base_url;
    public $media_url;
    public $logo;
    public $page_title;
    public $theme;
    public $page_limit = 20;
    public $supanel_limit = 10;

    public $settings;
    public $app_list_strings = array();

    public $system_variable = ['base_url', 'media_url', 'logo', 'page_title', 'theme', 'page_limit', 'subpanel_limit'];

    /**
     * @param $str
     * @param string $start
     * @param string $end
     * @return mixed
     */
    public static function getInBetweenStrings($str, $start = '\{', $end = '\}')
    {
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }

    /**
     * @param $file_path string has '/' head
     * @param $array
     * @param string $mode
     */
    public static function write_array2file($file_path, $array, $mode = 'w')
    {
        $handler = fopen(APP_PATH . $file_path, $mode);
        fwrite($handler, "<?php\n\nreturn " . var_export($array, true) . ";\n");
        fclose($handler);
    }

    /**
     * @param string $file_path has '/' head
     * @return mixed
     */
    public static function get_array4file($file_path)
    {
        if (file_exists(APP_PATH . $file_path)) {
            return include APP_PATH . $file_path;
        }

        return array();
    }

    /**
     * Convert php format datetime to Momentjs format
     *
     * @param $format
     * @return string
     */
    public static function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];

        $momentFormat = strtr($format, $replacements);

        return $momentFormat;
    }

    /**
     * @param $model_name
     * @param $data
     * @return \TrueCustomer\Common\BaseModel
     */
    public static function getModel($model_name, $data = null)
    {
        $model_path = '\\TrueCustomer\\Models\\' . $model_name;
        /* @var  $model \TrueCustomer\Common\BaseModel */
        $model = new $model_path($data);
        return $model;
    }

    /**
     * @param $source
     * @param $ep
     * @return mixed
     */
    public static function renderFilePicker($source, $ep)
    {
        $file_upload = new FileUpload([], $source);
        $render = $file_upload->renderPicker();
        $render = str_replace(':ELEMENT_PREFIX:', $ep, $render);
        return $render;
    }

    /**
     * TCUtils constructor.
     * @param $settings array
     * @param $const array
     */
    public function __construct($settings, $const)
    {
        $config = $settings['systems'];

        foreach ($this->system_variable as $variable) {
            $this->{$variable} = (empty($config[$variable])) ? null : $config[$variable];
        }

        if (!$this->media_url) {
            $this->media_url = $this->base_url . '/public/uploads';
        }

        if (!$this->page_title) {
            $this->page_title = ' | True Customer';
        } else {
            $this->page_title = ' | ' . $this->page_title;
        }

        if (!$this->theme) {
            $this->theme = 'default';
        }

        if (!$this->page_limit) {
            $this->page_limit = 20;
        }

        if (!$this->supanel_limit) {
            $this->supanel_limit = 10;
        }

        $this->settings = (empty($settings)) ? array() : $settings;
        $this->app_list_strings = (empty($const['app_list_strings'])) ? array() : $const['app_list_strings'];
    }

    /**
     * @param $category
     * @param $name
     * @return string|array
     */
    public function getSetting($category, $name = '')
    {
        if (!$name) {
            if (!empty($this->settings[$category])) {
                return $this->settings[$category];
            }
        } else {
            if (!empty($this->settings[$category][$name])) {
                return $this->settings[$category][$name];
            }
        }

        return '';
    }

    /**
     * @param $name
     * @param string $value
     * @return array|string
     */
    public function getDropDownList($name, $value = '')
    {
        if ($value) {
            if (!empty($this->app_list_strings[$name][$value])) {
                return $this->app_list_strings[$name][$value];
            }
            return '';
        }

        if (!empty($this->app_list_strings[$name])) {
            return $this->app_list_strings[$name];
        }
        return ['' => 'N/A'];
    }

    /**
     * @param $model_name
     * @param $field_title
     * @param string $value
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getModelDropDownList($model_name, &$field_title, $value = '')
    {
        $model = $this::getModel($model_name);
        $field_select = $model->getFieldName();
        $field_title = $model->detail_view->title;
        if ($value) {
            return $model::findFirst($value);
        }

        return $model::find([
            'conditions' => 'deleted = 0',
            'columns' => "id, {$field_select} AS {$field_title}",
            'order' => $field_title
        ]);
    }

    /**
     * @param $app_list_string_key
     * @param $table_name
     * @param null $id
     * @return array
     */
    public function getDbDropDownList($app_list_string_key, $table_name = null, $id = null)
    {
        if (!$table_name) {
            if (!empty($this->app_list_strings[$app_list_string_key])) {
                $table_name = $this->app_list_strings[$app_list_string_key];
            }
        }

        if (!$table_name) {
            return [['' => 'N/A']];
        }

        $query = new \TrueCustomer\Common\Query\BuilderNoModel();

        if ($id) {
            $query->from($table_name)
                ->andWhere('id = :id:');
            $result = $query->execute(['id' => $id]);
            $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
            $row = $result->fetch();
            if ($row) {
                return ['id' => $id, 'name' => $row['name']];
            }
            return ['id' => 0, 'name' => ''];
        } else {
            $query->from($table_name)
                ->orderBy('name, id');
            $result = $query->execute();
            $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
            $data = [];
            while ($row = $result->fetch()) {
                $data[$row['id']] = $row['name'];
            }
            return $data;
        }
    }

    /**
     * @param $model_name
     * @param $record_id
     * @return array
     */
    public function getRelateRecord($model_name, $record_id)
    {
        $model_class = "\\TrueCustomer\\Models\\$model_name";
        /* @var $model \TrueCustomer\Common\BaseModel */
        $model = new $model_class();
        $result = $model::findFirst($record_id);

        if (!$result) {
            return [
                'id' => '0',
                'name' => '--',
                'detail_link' => 'javascript:void(0)'
            ];
        }

        $controller_name = $model->action_controller_name;

        return [
            'id' => $result->id,
            'name' => $result->{$model->detail_view->title},
            'detail_link' => $this->base_url . '/' . $controller_name . '/' . $model->action_detail . '/' . $result->id
        ];
    }

    /**
     * Create folder upload with time
     * @param $folder
     * @param $upload_type string public|private
     * @return array
     */
    public function makeFolderUpload($folder, $upload_type = 'public')
    {
        $folder = !empty($folder) ? $folder : '';
        $sub_folder = $folder . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

        if ($upload_type == 'public') {
            $path_uri = '/public/uploads/' . $sub_folder;
            $full_uri = '/uploads/' . $sub_folder;
        } else {
            $path_uri = '/uploads/' . $sub_folder;
            $full_uri = '/' . $sub_folder;
        }

        $uri = '/' . $sub_folder;;

        $path_full = APP_PATH . $path_uri;

        if (!is_dir($path_full)) {
            mkdir($path_full, 0777, true);
        }

        return [
            'media_url' => $this->media_url,
            'uri' => $uri,
            'full_uri' => $full_uri,
            'sub_folder' => $path_uri,
            'folder' => $path_full
        ];
    }

    /**
     * @param $url
     * @return string
     */
    public function getFileUrl($url)
    {
        if (strpos($url, 'http://') === false
            && strpos($url, 'https://') === false) {
            return $this->media_url . $url;
        }
        return $url;
    }

    /**
     * @param null|string $model_name
     * @return array
     */
    public function getDependencyFields($model_name = null)
    {
        $model = $this::getModel($model_name);

        $result = [];

        $dp = $this::getModel('DependencyFields');
        $dpfields = $dp->getMany([
            'conditions' => "model_name = :model_name:",
            'bind' => ['model_name' => $model_name]
        ]);

        /* @var $dpfield \TrueCustomer\Models\DependencyFields */
        foreach ($dpfields as $dpfield) {
            if ($dpfield->type == 'select') {
                $option_key = $model->edit_view->getField($dpfield->target_name)->options;
                $options = $this->getDropDownList($option_key);

                $target_value = explode('||', $dpfield->target_value);
                $target = [];
                foreach ($target_value as $value) {
                    $target[$value] = $options[$value];
                }

                $result[$dpfield->type][$dpfield->target_name]['data'][$dpfield->source_value] = $target;
                $result[$dpfield->type][$dpfield->target_name]['source'] = $dpfield->source_name;
                $result[$dpfield->type][$dpfield->target_name]['source_value'] = $dpfield->source_value;
                $result[$dpfield->type][$dpfield->target_name]['target'] = $dpfield->target_name;
            } else {
                if ($dpfield->type == 'dbselect') {
                    $option_key = $model->edit_view->getField($dpfield->target_name)->options;
                    $target_table = $this->app_list_strings[$option_key];
                    $result[$dpfield->type][$dpfield->target_name]['target_table'] = $target_table;
                } else if ($dpfield->type == 'relate') {
                    $target_model = $model->edit_view->getField($dpfield->target_name)->model;
                    $result[$dpfield->type][$dpfield->target_name]['target_model'] = $target_model;
                }
                $result[$dpfield->type][$dpfield->target_name]['source'] = $dpfield->source_name;
                $result[$dpfield->type][$dpfield->target_name]['source_value'] = $dpfield->source_value;
                $result[$dpfield->type][$dpfield->target_name]['target'] = $dpfield->target_name;

            }
        }

        return $result;
    }

}
