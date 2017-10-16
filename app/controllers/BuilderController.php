<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: BuilderController.php
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;

class BuilderController extends BaseController
{
    /**
     * @var array database types
     */
    public $types = [
        'int', 'date', 'varchar', 'decimal', 'datetime', 'char', 'text', 'float', 'boolean', 'double', 'tinyblob',
        'blob', 'mediumblob', 'longblob', 'bigint', 'json', 'jsonb'
    ];

    public function indexAction()
    {
        $this->setTitle('Models');
        // get models
        $models = array();
        $model_path = APP_PATH . '/app/models/*.php';

        foreach (glob($model_path) as $model) {
            $name = basename($model, '.php');
            $modelO = $this->getModel($name);
            if ($modelO->studio) {
                $models[] = $name;
            }
        }

        $this->view->models = $models;
    }

    public function create_modelAction()
    {
        $this->setTitle('Create new Model');

        if ($this->request->isPost()) {
            $model_name = $this->request->getPost('model_name');
            if ($model_name) {
                $model_name = ucfirst($model_name);

                $model_base_file = APP_PATH . "/app/models/base/{$model_name}Base.php";
                $model_file = APP_PATH . "/app/models/{$model_name}.php";

                if (file_exists($model_file)) {
                    $this->flash->error('Model is exist!');
                    return $this->redirect('/builder');
                }

                // write base model
                $file = fopen($model_base_file, 'w');
                $content = "<?php\n";
                $content .= "// Auto Generate by TrueCustomer Builder\n\n";
                $content .= "namespace TrueCustomer\\Models\\Base;\n\n\n";
                $content .= "use TrueCustomer\\Common\\BaseModel;\n\n";
                $content .= "class {$model_name}Base extends BaseModel \n{\n\n}";
                fwrite($file, $content);
                fclose($file);

                // write model
                $file = fopen($model_file, 'w');
                $content = "<?php\n";
                $content .= "// Auto Generate by TrueCustomer Builder\n\n";
                $content .= "namespace TrueCustomer\\Models;\n\n\n";
                $content .= "use TrueCustomer\\Models\\Base\\{$model_name}Base;\n\n";
                $content .= "class {$model_name} extends {$model_name}Base \n{\n\n}";
                fwrite($file, $content);
                fclose($file);

                return $this->redirect('/builder');
            }
        }
    }

    public function rebuild_modelAction($model_name)
    {
        $model = $this->getModel($model_name);

        if ($model) {
            $metadata = $model->getModelsMetaData();
            $fields = $metadata->getAttributes($model);

            $model_var = '';
            foreach ($fields as $field) {
                $model_var .= "\tpublic \$$field;\n";
            }

            $model_file = APP_PATH . "/app/models/base/{$model_name}Base.php";

            $file = fopen($model_file, 'w');
            $content = "<?php\n";
            $content .= "// Auto Generate by TrueCustomer Builder\n\n";
            $content .= "namespace TrueCustomer\\Models\\Base;\n\n\n";
            $content .= "use TrueCustomer\\Common\\BaseModel;\n\n";
            $content .= "class {$model_name}Base extends BaseModel \n{\n$model_var\n}";
            fwrite($file, $content);
            fclose($file);
        }

        $this->flash->success("Rebuild $model_name Success!");
        $this->redirect('/builder');
    }

    public function edit_layoutAction($model_name)
    {
        $this->setTitle('Edit Layout Model: ' . $model_name);

        $model = $this->getModel($model_name);

        if (!$model) {
            $this->flash->error('Can not found model: ' . $model_name);
            $this->redirect('/builder');
        }

        $fields = $model->allFields();

        $layout_fields = [];
        foreach ($fields['fields'] as $field => $options) {
            $layout_fields[$field] = $field;
        }

        // define layout type
        $layout_list_fields = $fields['fields'];
        $layout_edit_fields = $model->edit_view->fields;
        if (empty($layout_edit_fields)) {
            $layout_edit_fields = ['Information' => $fields['fields']];
        }
        $layout_detail_fields = $model->detail_view->fields;
        if (empty($layout_detail_fields)) {
            $layout_detail_fields = ['Information' => $fields['fields']];
        }
        $this->view->layout_list_fields = $layout_list_fields;
        $this->view->layout_edit_fields = $layout_edit_fields;
        $this->view->layout_detail_fields = $layout_detail_fields;

        // all fields
        $this->view->fields = $fields['fields'];
        $this->view->edit_blocks = [];
        $this->view->detail_blocks = [];
        $this->view->layout_fields = $layout_fields;

        $this->view->model_name = $model_name;

        // title field
        $this->view->title_field = $model->detail_view->title;

        // selected fields
        $this->view->list_fields = $model->list_view;
        $this->view->edit_fields = $model->edit_view;
        $this->view->detail_fields = $model->detail_view;
        // all type
        $this->view->types = $model->getFieldTypes();
        // all model
        $this->view->all_models = $model->getAllModels();
        // all list dropdown
        $all_lists = array();
        foreach ($this->utils->app_list_strings as $list_dropdown => $value) {
            $all_lists[$list_dropdown] = $list_dropdown;
        }

        $this->view->all_lists = $all_lists;

        // search operations
        $search_operations = array(
            '0' => 'No',
            'like' => 'Like',
            '=' => 'Equal',
            '>' => 'Greater than',
            '>=' => 'Greater than/equal to',
            '<' => 'Less than',
            '<=' => 'Less than/equal to'
        );
        $this->view->search_operations = $search_operations;
    }

    public function update_layoutAction()
    {
        if ($this->request->isPost()) {
            //$this->view->disable(); echo '<pre>'; print_r($this->request->getPost()); die();
            $model_name = $this->request->getPost('model_name');
            //$model = $this->getModel($model_name);

            // detail view / edit view layout
            $detail_view['fields'] = $this->request->getPost('detail_fields');
            $edit_view['fields'] = $this->request->getPost('edit_fields');

            // title
            $detail_view['title'] = $edit_view['title'] = $this->request->getPost('title_field');
            $detail_view['column'] = $this->request->getPost('detail_column');
            $edit_view['column'] = $this->request->getPost('edit_column');

            // list view layout
            $list_fields = $this->request->getPost('list_fields');
            $list_view = array();
            foreach ($list_fields as $field => $options) {
                if ($options['type']) {
                    if ($options['search']) {
                        $options['operator'] = $options['search'];
                        $options['search'] = 1;
                    } else {
                        $options['search'] = 0;
                    }

                    if ($field == 'name') {
                        $options['link'] = 1;
                    }

                    $list_view['fields'][$field] = $options;
                }
            }

            // subpanel layout
            $subpanels = $this->request->getPost('subpanels');
            foreach ($subpanels as $subpanel) {
                if ($subpanel['name']) {
                    $detail_view['subpanels'][$subpanel['name']] = [
                        'type' => $subpanel['type'],
                        'current_model' => $subpanel['current_model'],
                        'current_field' => $subpanel['current_field'],
                        'rel_model' => $subpanel['rel_model'],
                        'rel_field' => $subpanel['rel_field']
                    ];

                    // mid rel model
                    if ($subpanel['type'] == 'many-many') {
                        $detail_view['subpanels'][$subpanel['name']]['mid_model'] = $subpanel['mid_model'];
                        $detail_view['subpanels'][$subpanel['name']]['mid_field1'] = $subpanel['mid_field1'];
                        $detail_view['subpanels'][$subpanel['name']]['mid_field2'] = $subpanel['mid_field2'];
                    }

                    // list layout
                    foreach ($subpanel['list'] as $subpanel_list_field => $subpanel_list) {
                        if ($subpanel_list['label']) {
                            $detail_view['subpanels'][$subpanel['name']]['list'][$subpanel_list_field] = $subpanel_list;
                        }
                    }
                }
            }

            $config_name = $model_name . '.conf.php';
            $file_config = APP_PATH . '/app/config/layouts/' . $config_name;
            $file = fopen($file_config, "w");
            $content = "<?php";
            $content .= "\n\n\$layout_config_list_view = " . var_export($list_view, true) . ";";
            $content .= "\n\n\$layout_config_edit_view = " . var_export($edit_view, true) . ";";
            $content .= "\n\n\$layout_config_detail_view = " . var_export($detail_view, true) . ";";
            fwrite($file, $content);
            fclose($file);

            return $this->redirect('/builder/edit_layout/' . $model_name);
        }
    }

    public function load_subpanelAction()
    {
        $this->view->setTemplateAfter('ajax');

        $type = $this->request->get('type');
        $model_name = $this->request->get('model_name');

        $current_model = $this->getModel($model_name);
        $current_fields = $current_model->allFields();

        // option all current model fields
        $options_current_fields = '<option value="id">id</option>';
        foreach ($current_fields['fields'] as $field => $options) {
            $options_current_fields .= '<option value="' . $field . '">' . $field . '</option>';
        }
        $this->view->options_current_fields = $options_current_fields;

        $this->view->type = $type;
        $this->view->model_name = $model_name;
        $this->view->models = $current_model->getAllModels();
        $this->view->i = $this->request->get('i');
    }

    public function load_relmodelfieldsAction()
    {
        $this->view->setTemplateAfter('ajax');

        $model_name = $this->request->get('model_name');
        $model = $this->getModel($model_name);
        $fields = $model->allFields();

        // options fields
        $options_fields = '<option value="id">id</option>';
        foreach ($fields['fields'] as $field => $options) {
            $options_fields .= '<option value="' . $field . '">' . $field . '</option>';
        }
        $this->view->options_fields = $options_fields;

        // list view for subpanel
        $this->view->fields = $fields['fields'];

        // view
        $this->view->model_name = $model_name;
        $this->view->type = $this->request->get('type');
        $this->view->field_types = $model->getFieldTypes();
        $this->view->i = $this->request->get('i');
    }
}