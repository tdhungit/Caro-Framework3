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
    'unique_name' => 
    array (
      'type' => 'text',
      'label' => 'Unique Name',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'status' => 
    array (
      'type' => 'select',
      'label' => 'Status',
      'options' => 'status_list',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'description' => 
    array (
      'type' => 'text',
      'label' => 'Description',
      'options' => '',
      'model' => '',
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
        'required' => '0',
      ),
      'unique_name' => 
      array (
        'type' => 'text',
        'label' => 'Unique Name',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'status' => 
      array (
        'type' => 'select',
        'label' => 'Status',
        'options' => 'status_list',
        'model' => '',
        'required' => '0',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'Description',
        'options' => '',
        'model' => '',
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
      'unique_name' => 
      array (
        'type' => 'text',
        'label' => 'Unique Name',
        'options' => '',
        'model' => '',
      ),
      'status' => 
      array (
        'type' => 'select',
        'label' => 'Status',
        'options' => 'status_list',
        'model' => '',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'Description',
        'options' => '',
        'model' => '',
      ),
    ),
  ),
  'title' => 'name',
);