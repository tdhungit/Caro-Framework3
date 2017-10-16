<?php

$layout_config_list_view = array (
  'fields' => 
  array (
    'name' => 
    array (
      'type' => 'text',
      'label' => 'Name',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => 1,
    ),
    'icon' => 
    array (
      'type' => 'text',
      'label' => 'Icon',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'controller' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Controller',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'action' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Action',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'query' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Query',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'url' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Url',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'target' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Target',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'weight' => 
    array (
      'type' => 'text',
      'label' => 'Weight',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'parent_id' => 
    array (
      'type' => 'relate',
      'label' => 'Parent',
      'options' => '',
      'model' => 'MainMenu',
      'search' => 0,
      'link' => '0',
    ),
  ),
);

$layout_config_edit_view = array (
  'fields' => 
  array (
    'Information' => 
    array (
      'name' => 
      array (
        'type' => 'text',
        'label' => 'Name',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'icon' => 
      array (
        'type' => 'text',
        'label' => 'Icon',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'controller' => 
      array (
        'type' => 'text',
        'label' => 'Controller',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'action' => 
      array (
        'type' => 'text',
        'label' => 'Action',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'query' => 
      array (
        'type' => 'text',
        'label' => 'Query',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'url' => 
      array (
        'type' => 'text',
        'label' => 'Url',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'target' => 
      array (
        'type' => 'text',
        'label' => 'Target',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'weight' => 
      array (
        'type' => 'text',
        'label' => 'Weight',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'parent_id' => 
      array (
        'type' => 'relate',
        'label' => 'Parent',
        'options' => '',
        'model' => 'MainMenu',
        'required' => '0',
      ),
    ),
  ),
  'title' => 'name',
);

$layout_config_detail_view = array (
  'fields' => 
  array (
    'Information' => 
    array (
      'name' => 
      array (
        'type' => 'text',
        'label' => 'Name',
        'options' => '',
        'model' => '',
      ),
      'icon' => 
      array (
        'type' => 'text',
        'label' => 'Icon',
        'options' => '',
        'model' => '',
      ),
      'controller' => 
      array (
        'type' => 'text',
        'label' => 'Controller',
        'options' => '',
        'model' => '',
      ),
      'action' => 
      array (
        'type' => 'text',
        'label' => 'Action',
        'options' => '',
        'model' => '',
      ),
      'query' => 
      array (
        'type' => 'text',
        'label' => 'Query',
        'options' => '',
        'model' => '',
      ),
      'url' => 
      array (
        'type' => 'text',
        'label' => 'Url',
        'options' => '',
        'model' => '',
      ),
      'target' => 
      array (
        'type' => 'text',
        'label' => 'Target',
        'options' => '',
        'model' => '',
      ),
      'weight' => 
      array (
        'type' => 'text',
        'label' => 'Weight',
        'options' => '',
        'model' => '',
      ),
      'parent_id' => 
      array (
        'type' => 'relate',
        'label' => 'Parent',
        'options' => '',
        'model' => 'MainMenu',
      ),
    ),
  ),
  'title' => 'name',
);