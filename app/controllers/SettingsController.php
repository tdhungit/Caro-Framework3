<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: SettingsController.php
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;
use Phalcon\Db\Column;
use Phalcon\Db\Index;
use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Models\MainMenu;

class SettingsController extends BaseController
{
    private function _repairDb()
    {
        $tables = include APP_PATH . '/app/config/database_structure.php';

        foreach ($tables as $table_name => $table_data) {
            $exists = $this->db->tableExists($table_name);

            if ($exists) {
                $current_fields = $this->db->describeColumns($table_name);
                foreach ($table_data['fields'] as $field_name => $options) {
                    $exists_field = true;
                    foreach ($current_fields as $c_field) {
                        if ($c_field->getName() == $field_name) {
                            $exists_field = true;
                            break;
                        } else {
                            $exists_field = false;
                        }
                    }

                    if ($exists_field == true) {
                        $this->db->modifyColumn($table_name, null, new Column($field_name, $options));
                    } else {
                        $this->db->addColumn($table_name, null, new Column($field_name, $options));
                    }
                }

                $current_indexes = $this->db->describeIndexes($table_name);
                foreach ($table_data['indexes'] as $index => $index_data) {
                    $create_index = true;
                    foreach ($current_indexes as $c_index => $c_index_fields) {
                        if ($index == $c_index) {
                            if (
                                $index_data['fields'] != $c_index_fields->getColumns()
                                || $index_data['type'] != $c_index_fields->getType()
                            ) {
                                $this->db->dropIndex($table_name, null, $c_index);
                                $create_index = true;
                            } else {
                                $create_index = false;
                            }
                        }
                    }

                    if ($create_index == true) {
                        if (strtolower($index_data['type']) == 'index') {
                            $this->db->addIndex($table_name, null, new Index($index, $index_data['fields']));
                        } else {
                            $this->db->addIndex($table_name, null, new Index($index, $index_data['fields'], $index_data['type']));
                        }
                    }
                }

            } else {
                $new_columns = array(
                    'columns' => array(
                        new Column(
                            'id',
                            array(
                                'type' => Column::TYPE_INTEGER,
                                'size' => 10,
                                'notNull' => true,
                                'autoIncrement' => true,
                                'unsigned' => true
                            )
                        ),
                        new Column(
                            'created',
                            array(
                                'type' => Column::TYPE_DATETIME,
                                'notNull' => true,
                            )
                        ),
                        new Column(
                            'user_created_id',
                            array(
                                'type' => Column::TYPE_INTEGER,
                                'size' => 10,
                                'notNull' => true
                            )
                        ),
                        new Column(
                            'deleted',
                            array(
                                'type' => Column::TYPE_INTEGER,
                                'size' => 1,
                                'notNull' => true,
                                'default' => 0
                            )
                        )
                    ),
                    'indexes' => array(
                        new Index('PRIMARY', array('id'))
                    )
                );

                foreach ($table_data['fields'] as $field_name => $options) {
                    $new_columns['columns'][] = new Column($field_name, $options);
                }

                foreach ($table_data['indexes'] as $index => $index_data) {
                    if (strtolower($index_data['type']) == 'index') {
                        $new_columns['indexes'][] = new Index($index, $index_data['fields']);
                    } else {
                        $new_columns['indexes'][] = new Index($index, $index_data['fields'], $index_data['type']);
                    }
                }

                $this->db->createTable($table_name, null, $new_columns);
            }
        }
    }

    // Setting Manager
    public function indexAction()
    {
        $this->setTitle('Settings');
        //$this->view->disable();
        //echo '<pre>';
        //print_r($this->utils->getDbDropDownList('customer_city_list'));
    }

    public function repairAction()
    {
        $this->view->disable();
        $this->_repairDb();
        $this->flash->success('Repair database success!');
        $this->redirect('/');
    }

    public function clear_cacheAction()
    {
        // clear cache volt
        $cache_files = glob(APP_PATH . '/app/cache/volt/*.php');
        foreach ($cache_files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // clear cache menu
        $menu = new MainMenu();
        $menu->cacheArrayMainMenu();

        // success
        $this->flash->success('Clear success!');
        $this->redirect('/settings');
    }

    // Main Menu Manager
    public function main_menuAction()
    {
        $this->model_name = 'MainMenu';
        $this->links->setActionsFromModel($this->getModel());
        return parent::listAction();
    }

    public function detail_menuAction($id)
    {
        $this->model_name = 'MainMenu';
        $this->links->setActionsFromModel($this->getModel());
        return parent::detailAction($id);
    }

    public function quick_create_menuAction()
    {
        $this->model_name = 'MainMenu';
        return parent::quick_createAction();
    }

    public function edit_menuAction($id = null)
    {
        $this->model_name = 'MainMenu';
        $this->links->setActionsFromModel($this->getModel());
        return parent::editAction($id);
    }

    public function delete_menuAction($id = null, $model_name = null)
    {
        $this->model_name = 'MainMenu';
        $this->links->setActionsFromModel($this->getModel());
        return parent::deleteAction($id, $model_name);
    }

    // System settings
    public function listAction()
    {
        $this->setTitle('Settings');
        // get settings config
        $settings_config = TCUtils::get_array4file('/app/config/layouts/settings.php');
        $this->view->settings_config = $settings_config;

        $settingO = $this->getModel('Settings');
        $settings = $settingO->getMany();

        $preference = [];
        foreach ($settings as $setting) {
            $preference[$setting->group_name][$setting->name] = $setting->value;
        }

        $this->view->preference = $preference;
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->getPost('model_name')) {
                return parent::saveAction();
            }

            $preference = $this->request->getPost('preference');

            /* @var $setting \TrueCustomer\Models\Settings */
            $setting = $this->getModel('Settings');
            foreach ($preference as $block_name => $fields) {
                foreach ($fields as $field_name => $value) {
                    /* @var $setting_check \TrueCustomer\Models\Settings */
                    $setting_check = $setting->getOne("name = '$field_name' AND group_name = '$block_name'");
                    if ($setting_check) {
                        $setting_check->name = $field_name;
                        $setting_check->group_name = $block_name;
                        $setting_check->value = $value;
                        $setting_check->update();
                    } else {
                        $setting->id = null;
                        $setting->name = $field_name;
                        $setting->group_name = $block_name;
                        $setting->value = $value;
                        $setting->create();
                    }
                }
            }

            TCUtils::write_array2file('/app/config/system.php', $preference);
            $this->flash->success($this->t->_('Success!'));
        }

        $this->redirect('/settings/list');
    }

    // Dependency Fields
    public function dpfieldsAction()
    {
        $this->model_name = 'DependencyFields';
        $this->links->setActionsFromModel($this->getModel());
        return parent::listAction();
    }

    public function dpfields_editAction($id = 0)
    {
        // Save
        if ($this->request->isPost()) {
            $model = $this->getModel('DependencyFields');
            if ($id) {
                $focus = $model->getOne($id);
            } else {
                $focus = $model;
            }

            if ($this->request->getPost('type')
                && $this->request->getPost('model_name')
                && $this->request->getPost('source_name')
                && $this->request->getPost('target_name')
            ) {
                /* @var $focus \TrueCustomer\Models\DependencyFields */
                $focus->type = $this->request->getPost('type');
                $focus->model_name = $this->request->getPost('model_name');
                $focus->source_name = $this->request->getPost('source_name');
                $focus->target_name = $this->request->getPost('target_name');
                $focus->source_value = $this->request->getPost('source_value');
                if ($this->request->getPost('target_value')) {
                    $focus->target_value = implode('||', $this->request->getPost('target_value'));
                }
                if (!$focus->save()) {
                    foreach ($focus->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    return $this->redirect($this->url->currentUrl());
                }

                $this->flash->success($this->t->_('Setup dependency fields success'));
                return $this->redirect('/settings/dpfields_edit/' . $focus->id);
            }

            $this->flash->error($this->t->_('Invalid parameter'));
            return $this->redirect($this->url->currentUrl());
        }

        $setting = [];
        if ($id) {
            $model = $this->getModel('DependencyFields');
            $setting = $model->getOne($id)->toArray();
            $setting['target_value'] = explode('||', $setting['target_value']);
        }

        // Load Data
        if ($this->request->getQuery('is_ajax') == '1') {
            $this->view->disable();
            return $this->responseJson($setting);
        }

        // View
        $this->setTitle('Setup Dependency Fields');
        $this->addJs([
            '/plugins/vuejs/vue.min.js',
            '/plugins/vuejs/vue-resource.min.js',
            '/js/vuefunc.js',
            '/js/pages/settings/dpfields_edit.js'
        ]);

        // get models
        $models = array('' => '--');
        $model_path = APP_PATH . '/app/models/*.php';
        foreach (glob($model_path) as $model) {
            $name = basename($model, '.php');
            $modelO = $this->getModel($name);
            if ($modelO->studio) {
                $models[$name] = $name;
            }
        }

        $this->view->id = $id;
        $this->view->models = $models;
        $this->view->setting = $setting;
    }

    public function dpfields_deleteAction($id)
    {
        if ($id) {
            $model = $this->getModel('DependencyFields');
            $row = $model->getOne($id);
            $row->delete(true);
        }
        $this->redirect('/settings/dpfields');
    }

    public function dpfields_dataAction($type, $model_name, $get_type, $field_name = '')
    {
        $model = $this->getModel($model_name);
        $fields = $model->edit_view->fields;

        switch ($get_type) {
            case 'fields':
                $all_fields = [];
                foreach ($fields as $blocks) {
                    foreach ($blocks as $field) {
                        $all_fields[$field->name] = [
                            'id' => $field->name,
                            'text' => $field->name,
                            'name' => $field->name,
                            'label' => $field->label,
                            'type' => $field->type
                        ];
                    }
                }
                return $this->responseJson($fields);
                break;
            case 'select_fields':
                $select_fields = [];
                foreach ($fields as $blocks) {
                    foreach ($blocks as $field) {
                        if (in_array($field->type, [$type])) {
                            $select_fields[$field->name] = [
                                'id' => $field->name,
                                'text' => $field->name,
                                'name' => $field->name,
                                'label' => $field->label,
                                'type' => $field->type
                            ];
                        }
                    }
                }
                return $this->responseJson($select_fields);
                break;
            case 'select_values':
                if (!$field_name) {
                    return $this->responseJson([]);
                }

                if (!$model->edit_view->getField($field_name)) {
                    return $this->responseJson([]);
                }

                $values = [];
                if ($type == 'select') {
                    $option_values = $this->utils->getDropDownList($model->edit_view->getField($field_name)->options);
                    foreach ($option_values as $option_value) {
                        $values[] = [
                            'id' => $option_value,
                            'text' => $option_value,
                            'name' => $option_value
                        ];
                    }
                } else if ($type == 'relate') {
                    $target_model = $model->edit_view->getField($field_name)->model;
                    $target = $this->getModel($target_model);
                    $metadata = $target->getModelsMetaData();
                    $fields = $metadata->getAttributes($target);
                    foreach ($fields as $field) {
                        $values[] = [
                            'id' => $field,
                            'text' => $field,
                            'name' => $field
                        ];
                    }
                }
                return $this->responseJson($values);
                break;
            default:
                return $this->responseJson([]);
                break;
        }
    }
}