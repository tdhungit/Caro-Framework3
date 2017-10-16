<?php

$layout_config_list_view = array (
  'fields' => 
  array (
    'subject' => 
    array (
      'type' => 'text',
      'label' => 'Subject',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '1',
    ),
    'assigned_user_id' => 
    array (
      'type' => 'relate',
      'label' => 'Assigned user',
      'options' => '',
      'model' => 'Users',
      'search' => 0,
      'link' => '0',
    ),
    'relate_type' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Relate type',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'relate_id' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Relate',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'extend_type' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Extend type',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'extend_id' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Extend',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'type' => 
    array (
      'type' => 'select',
      'label' => 'Type',
      'options' => 'activity_type_list',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'date_start' => 
    array (
      'type' => 'datetime',
      'label' => 'Date start',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'date_end' => 
    array (
      'type' => 'datetime',
      'label' => 'Date end',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'send_notification' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Send notification',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'duration' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Duration',
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
    'status' => 
    array (
      'type' => 'select',
      'label' => 'Status',
      'options' => 'activity_status_list',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'priority' => 
    array (
      'type' => 'select',
      'label' => 'Priority',
      'options' => 'activity_priority_list',
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
      'subject' => 
      array (
        'type' => 'text',
        'label' => 'Subject',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'assigned_user_id' => 
      array (
        'type' => 'relate',
        'label' => 'Assigned user',
        'options' => '',
        'model' => 'Users',
        'required' => '1',
      ),
      'relate_type' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Relate type',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'relate_id' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Relate',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'extend_type' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Extend type',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'extend_id' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Extend',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'type' => 
      array (
        'type' => 'select',
        'label' => 'Type',
        'options' => 'activity_type_list',
        'model' => '',
        'required' => '1',
      ),
      'date_start' => 
      array (
        'type' => 'datetime',
        'label' => 'Date start',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'date_end' => 
      array (
        'type' => 'datetime',
        'label' => 'Date end',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
      'send_notification' => 
      array (
        'type' => 'select',
        'label' => 'Send notification',
        'options' => 'boolean_list',
        'model' => '',
        'required' => '1',
      ),
      'duration' => 
      array (
        'type' => 'text',
        'label' => 'Duration',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
    ),
    'More' => 
    array (
      'status' => 
      array (
        'type' => 'select',
        'label' => 'Status',
        'options' => 'activity_status_list',
        'model' => '',
        'required' => '1',
      ),
      'priority' => 
      array (
        'type' => 'select',
        'label' => 'Priority',
        'options' => 'activity_priority_list',
        'model' => '',
        'required' => '1',
      ),
      'location' => 
      array (
        'type' => 'text',
        'label' => 'Location',
        'options' => '',
        'model' => '',
        'required' => '1',
      ),
    ),
  ),
  'title' => 'subject',
  'column' => '2',
);

$layout_config_detail_view = array (
  'fields' => 
  array (
    'Information' => 
    array (
      'subject' => 
      array (
        'type' => 'text',
        'label' => 'Subject',
        'options' => '',
        'model' => '',
      ),
      'assigned_user_id' => 
      array (
        'type' => 'relate',
        'label' => 'Assigned user',
        'options' => '',
        'model' => 'Users',
      ),
      'relate_type' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Relate type',
        'options' => '',
        'model' => '',
      ),
      'relate_id' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Relate',
        'options' => '',
        'model' => '',
      ),
      'extend_type' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Extend type',
        'options' => '',
        'model' => '',
      ),
      'extend_id' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Extend',
        'options' => '',
        'model' => '',
      ),
      'type' => 
      array (
        'type' => 'select',
        'label' => 'Type',
        'options' => 'activity_type_list',
        'model' => '',
      ),
      'date_start' => 
      array (
        'type' => 'datetime',
        'label' => 'Date start',
        'options' => '',
        'model' => '',
      ),
      'date_end' => 
      array (
        'type' => 'datetime',
        'label' => 'Date end',
        'options' => '',
        'model' => '',
      ),
      'send_notification' => 
      array (
        'type' => 'select',
        'label' => 'Send notification',
        'options' => 'boolean_list',
        'model' => '',
      ),
      'duration' => 
      array (
        'type' => 'text',
        'label' => 'Duration',
        'options' => '',
        'model' => '',
      ),
    ),
    'More' => 
    array (
      'location' => 
      array (
        'type' => 'text',
        'label' => 'Location',
        'options' => '',
        'model' => '',
      ),
      'status' => 
      array (
        'type' => 'select',
        'label' => 'Status',
        'options' => 'activity_status_list',
        'model' => '',
      ),
      'priority' => 
      array (
        'type' => 'select',
        'label' => 'Priority',
        'options' => 'activity_priority_list',
        'model' => '',
      ),
    ),
  ),
  'title' => 'subject',
  'column' => '2',
  'subpanels' => 
  array (
    'members' => 
    array (
      'type' => 'one-many',
      'current_model' => 'Activities',
      'current_field' => 'id',
      'rel_model' => 'ActivitiesMembers',
      'rel_field' => 'activity_id',
      'list' => 
      array (
        'email' => 
        array (
          'type' => 'text',
          'label' => 'Email',
        ),
      ),
    ),
  ),
);