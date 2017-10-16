<?php

$layout_config_list_view = array (
  'fields' => 
  array (
    'avatar' => 
    array (
      'type' => 'image',
      'label' => 'Avatar',
      'options' => 'status_list',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'username' => 
    array (
      'type' => 'text',
      'label' => 'Username',
      'options' => '',
      'model' => '',
      'search' => 1,
      'link' => '1',
      'operator' => 'like',
    ),
    'email' => 
    array (
      'type' => 'text',
      'label' => 'Email',
      'options' => '',
      'model' => '',
      'search' => 1,
      'link' => '0',
      'operator' => 'like',
    ),
    'password' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Password',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'name' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Name',
      'options' => '',
      'model' => '',
      'search' => 1,
      'link' => 1,
      'operator' => 'like',
    ),
    'status' => 
    array (
      'type' => 'select',
      'label' => 'Status',
      'options' => 'status_list',
      'model' => '',
      'search' => 1,
      'link' => '0',
      'operator' => '=',
    ),
    'is_admin' => 
    array (
      'type' => 'text',
      'label' => 'Is admin',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'user_group_id' => 
    array (
      'type' => 'relate',
      'label' => 'User group',
      'options' => '',
      'model' => 'UserGroups',
      'search' => 1,
      'link' => '0',
      'operator' => 'like',
    ),
    'preference' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Preference',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'title' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Title',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'position' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Position',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'department' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Department',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'phone' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Phone',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'extension' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Extension',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'location' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Location',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'description' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Description',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'social' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Social',
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
      'avatar' => 
      array (
        'type' => 'text',
        'label' => 'Avatar',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'username' => 
      array (
        'type' => 'text',
        'label' => 'Username',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'email' => 
      array (
        'type' => 'text',
        'label' => 'Email',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'password' => 
      array (
        'type' => 'text',
        'label' => 'Password',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'name' => 
      array (
        'type' => 'text',
        'label' => 'Name',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'status' => 
      array (
        'type' => 'select',
        'label' => 'Status',
        'options' => 'status_list',
        'model' => '',
        'required' => '1',
      ),
      'is_admin' => 
      array (
        'type' => 'text',
        'label' => 'Is Admin',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'user_group_id' => 
      array (
        'type' => 'relate',
        'label' => 'Group',
        'options' => '',
        'model' => 'UserGroups',
        'required' => '1',
      ),
      'preference' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Preference',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'title' => 
      array (
        'type' => 'text',
        'label' => 'Title',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'position' => 
      array (
        'type' => 'text',
        'label' => 'Position',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'department' => 
      array (
        'type' => 'text',
        'label' => 'Department',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'phone' => 
      array (
        'type' => 'text',
        'label' => 'Phone',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'extension' => 
      array (
        'type' => 'text',
        'label' => 'Extension',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'location' => 
      array (
        'type' => 'text',
        'label' => 'Location',
        'options' => '',
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
      'social' => 
      array (
        'type' => 'text',
        'label' => 'Social',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
    ),
  ),
  'title' => 'username',
);

$layout_config_detail_view = array (
  'fields' => 
  array (
    'Information' => 
    array (
      'avatar' => 
      array (
        'type' => 'image',
        'label' => 'Avatar',
        'options' => '',
        'model' => '',
      ),
      'username' => 
      array (
        'type' => 'text',
        'label' => 'Username',
        'options' => '',
        'model' => '',
      ),
      'email' => 
      array (
        'type' => 'text',
        'label' => 'Email',
        'options' => '',
        'model' => '',
      ),
      'password' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Password',
        'options' => '',
        'model' => '',
      ),
      'name' => 
      array (
        'type' => 'readonly',
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
      'is_admin' => 
      array (
        'type' => 'text',
        'label' => 'Is Admin',
        'options' => '',
        'model' => '',
      ),
      'user_group_id' => 
      array (
        'type' => 'relate',
        'label' => 'Group',
        'options' => '',
        'model' => 'UserGroups',
      ),
      'preference' => 
      array (
        'type' => 'readonly',
        'label' => 'Preference',
        'options' => '',
        'model' => '',
      ),
      'title' => 
      array (
        'type' => 'text',
        'label' => 'Title',
        'options' => '',
        'model' => '',
      ),
      'position' => 
      array (
        'type' => 'text',
        'label' => 'Position',
        'options' => '',
        'model' => '',
      ),
      'department' => 
      array (
        'type' => 'text',
        'label' => 'Department',
        'options' => '',
        'model' => '',
      ),
      'phone' => 
      array (
        'type' => 'text',
        'label' => 'Phone',
        'options' => '',
        'model' => '',
      ),
      'extension' => 
      array (
        'type' => 'text',
        'label' => 'Extension',
        'options' => '',
        'model' => '',
      ),
      'location' => 
      array (
        'type' => 'text',
        'label' => 'Location',
        'options' => '',
        'model' => '',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'Description',
        'options' => '',
        'model' => '',
      ),
      'social' => 
      array (
        'type' => 'text',
        'label' => 'Social',
        'options' => '',
        'model' => '',
      ),
    ),
  ),
  'title' => 'username',
);