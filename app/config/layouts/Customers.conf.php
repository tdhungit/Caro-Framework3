<?php

$layout_config_list_view = array (
  'fields' => 
  array (
    'logo' => 
    array (
      'type' => 'image',
      'label' => 'Logo',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '1',
    ),
    'name' => 
    array (
      'type' => 'text',
      'label' => 'Name',
      'options' => '',
      'model' => '',
      'search' => 1,
      'link' => 1,
      'operator' => 'like',
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
    'type' => 
    array (
      'type' => 'select',
      'label' => 'Type',
      'options' => 'customer_type_list',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'member_of' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Member of',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'website' => 
    array (
      'type' => 'text',
      'label' => 'Website',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'primary_email' => 
    array (
      'type' => 'text',
      'label' => 'Primary email',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'secondary_email' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Secondary email',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'phone' => 
    array (
      'type' => 'text',
      'label' => 'Phone',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'fax' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Fax',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'date_of_birth' => 
    array (
      'type' => 'date',
      'label' => 'Date of birth',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'billing_address1' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Billing address1',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'billing_address2' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Billing address2',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'billing_city' => 
    array (
      'type' => 'dbselect',
      'label' => 'Billing city',
      'options' => 'customer_city_list',
      'model' => '',
      'search' => 1,
      'link' => '0',
      'operator' => '=',
    ),
    'billing_state' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Billing state',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'billing_zip_code' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Billing zip code',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'billing_country' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Billing country',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_address1' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping address1',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_address2' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping address2',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_city' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping city',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_state' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping state',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_zip_code' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping zip code',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'shipping_country' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Shipping country',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'representative' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Representative',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'representative_title' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Representative title',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'representative_position' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Representative position',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'tax_code' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Tax code',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'industry' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Industry',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'employees' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Employees',
      'options' => '',
      'model' => '',
      'search' => 0,
      'link' => '0',
    ),
    'annual_revenue' => 
    array (
      'type' => 'nodisplay',
      'label' => 'Annual revenue',
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
      'logo' => 
      array (
        'type' => 'image',
        'label' => 'Logo',
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
      'assigned_user_id' => 
      array (
        'type' => 'relate',
        'label' => 'Assigned user',
        'options' => '',
        'model' => 'Users',
        'required' => '1',
      ),
      'type' => 
      array (
        'type' => 'select2',
        'label' => 'Type',
        'options' => 'customer_type_list',
        'model' => '',
        'required' => '0',
      ),
      'member_of' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Member of',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'website' => 
      array (
        'type' => 'text',
        'label' => 'Website',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'primary_email' => 
      array (
        'type' => 'text',
        'label' => 'Primary email',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'secondary_email' => 
      array (
        'type' => 'text',
        'label' => 'Secondary email',
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
      'fax' => 
      array (
        'type' => 'text',
        'label' => 'Fax',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'date_of_birth' => 
      array (
        'type' => 'date',
        'label' => 'Date of birth',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'billing_address1' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing address1',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'billing_address2' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing address2',
        'options' => 'customer_address2_list',
        'model' => '',
        'required' => '0',
      ),
      'billing_city' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing city',
        'options' => 'customer_city_list',
        'model' => '',
        'required' => '0',
      ),
      'billing_state' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing state',
        'options' => 'customer_state_list',
        'model' => '',
        'required' => '0',
      ),
      'billing_zip_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing zip code',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'billing_country' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing country',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_address1' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping address1',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_address2' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping address2',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_city' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping city',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_state' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping state',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_zip_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping zip code',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'shipping_country' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping country',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'representative' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'representative_title' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative title',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'representative_position' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative position',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'tax_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Tax code',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'industry' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Industry',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'employees' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Employees',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
      'annual_revenue' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Annual revenue',
        'options' => '',
        'model' => '',
        'required' => '0',
      ),
    ),
  ),
  'title' => 'name',
  'column' => '1',
);

$layout_config_detail_view = array (
  'fields' => 
  array (
    'Information' => 
    array (
      'logo' => 
      array (
        'type' => 'image',
        'label' => 'Logo',
        'options' => '',
        'model' => '',
      ),
      'name' => 
      array (
        'type' => 'text',
        'label' => 'Name',
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
      'type' => 
      array (
        'type' => 'select',
        'label' => 'Type',
        'options' => 'customer_type_list',
        'model' => '',
      ),
      'member_of' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Member of',
        'options' => '',
        'model' => '',
      ),
      'website' => 
      array (
        'type' => 'text',
        'label' => 'Website',
        'options' => '',
        'model' => '',
      ),
      'primary_email' => 
      array (
        'type' => 'text',
        'label' => 'Primary email',
        'options' => '',
        'model' => '',
      ),
      'secondary_email' => 
      array (
        'type' => 'text',
        'label' => 'Secondary email',
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
      'fax' => 
      array (
        'type' => 'text',
        'label' => 'Fax',
        'options' => '',
        'model' => '',
      ),
      'date_of_birth' => 
      array (
        'type' => 'date',
        'label' => 'Date of birth',
        'options' => '',
        'model' => '',
      ),
      'billing_address1' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing address1',
        'options' => '',
        'model' => '',
      ),
      'billing_address2' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing address2',
        'options' => 'customer_address2_list',
        'model' => '',
      ),
      'billing_city' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing city',
        'options' => 'customer_city_list',
        'model' => '',
      ),
      'billing_state' => 
      array (
        'type' => 'dbselect',
        'label' => 'Billing state',
        'options' => 'customer_state_list',
        'model' => '',
      ),
      'billing_zip_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing zip code',
        'options' => '',
        'model' => '',
      ),
      'billing_country' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Billing country',
        'options' => '',
        'model' => '',
      ),
      'shipping_address1' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping address1',
        'options' => '',
        'model' => '',
      ),
      'shipping_address2' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping address2',
        'options' => '',
        'model' => '',
      ),
      'shipping_city' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping city',
        'options' => '',
        'model' => '',
      ),
      'shipping_state' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping state',
        'options' => '',
        'model' => '',
      ),
      'shipping_zip_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping zip code',
        'options' => '',
        'model' => '',
      ),
      'shipping_country' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Shipping country',
        'options' => '',
        'model' => '',
      ),
      'representative' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative',
        'options' => '',
        'model' => '',
      ),
      'representative_title' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative title',
        'options' => '',
        'model' => '',
      ),
      'representative_position' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Representative position',
        'options' => '',
        'model' => '',
      ),
      'tax_code' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Tax code',
        'options' => '',
        'model' => '',
      ),
      'industry' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Industry',
        'options' => '',
        'model' => '',
      ),
      'employees' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Employees',
        'options' => '',
        'model' => '',
      ),
      'annual_revenue' => 
      array (
        'type' => 'nodisplay',
        'label' => 'Annual revenue',
        'options' => '',
        'model' => '',
      ),
    ),
  ),
  'title' => 'name',
  'column' => '1',
  'subpanels' => 
  array (
    'contacts' => 
    array (
      'type' => 'one-many',
      'current_model' => 'Customers',
      'current_field' => 'id',
      'rel_model' => 'Contacts',
      'rel_field' => 'customer_id',
      'list' => 
      array (
        'salutation' => 
        array (
          'type' => 'text',
          'label' => 'Salutation',
        ),
        'first_name' => 
        array (
          'type' => 'text',
          'label' => 'First Name',
        ),
        'middle_name' => 
        array (
          'type' => 'text',
          'label' => 'Middle Name',
        ),
        'last_name' => 
        array (
          'type' => 'text',
          'label' => 'Last Name',
        ),
      ),
    ),
  ),
);