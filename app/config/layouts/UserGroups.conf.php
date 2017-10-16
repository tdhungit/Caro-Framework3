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
    'parent_id' => 
    array (
      'type' => 'relate',
      'label' => 'Parent',
      'options' => '',
      'model' => 'UserGroups',
      'search' => 0,
      'link' => '0',
    ),
    'role_id' => 
    array (
      'type' => 'relate',
      'label' => 'Role',
      'options' => '',
      'model' => 'AuthRoles',
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
      'parent_id' => 
      array (
        'type' => 'relate',
        'label' => 'Parent',
        'options' => '',
        'model' => 'UserGroups',
        'required' => '0',
      ),
      'role_id' => 
      array (
        'type' => 'relate',
        'label' => 'Role',
        'options' => '',
        'model' => 'AuthRoles',
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
      'parent_id' => 
      array (
        'type' => 'relate',
        'label' => 'Parent',
        'options' => '',
        'model' => 'UserGroups',
      ),
      'role_id' => 
      array (
        'type' => 'relate',
        'label' => 'Role',
        'options' => '',
        'model' => 'AuthRoles',
      ),
    ),
  ),
  'title' => 'name',
  'subpanels' => 
  array (
    'users' => 
    array (
      'type' => 'one-many',
      'current_model' => 'UserGroups',
      'current_field' => 'id',
      'rel_model' => 'Users',
      'rel_field' => 'user_group_id',
      'list' => 
      array (
        'username' => 
        array (
          'type' => 'text',
          'label' => 'Username',
        ),
        'email' => 
        array (
          'type' => 'text',
          'label' => 'Email',
        ),
        'is_admin' => 
        array (
          'type' => 'text',
          'label' => 'Is Admin',
        ),
      ),
    ),
  ),
);