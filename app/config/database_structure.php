<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

use \Phalcon\Db\Column as Column;

return [
    'users' => [
        'fields' => [
            'avatar' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
                'notNull' => true,
                'after' => 'deleted'
            ],
            'username' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 32,
                'notNull' => true,
            ],
            'email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
                'notNull' => true,
            ],
            'password' => [
                'type' => Column::TYPE_CHAR,
                'size' => 32,
                'notNull' => true,
            ],
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'status' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'is_admin' => [
                'type' => Column::TYPE_BOOLEAN,
                'default' => 0
            ],
            'user_group_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'after' => 'is_admin'
            ],
            'preference' => [
                'type' => Column::TYPE_TEXT,
                'after' => 'user_group_id'
            ],
            // profile
            'title' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'position' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'department' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'phone' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20,
            ],
            'extension' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20,
            ],
            'location' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'description' => [
                'type' => Column::TYPE_TEXT
            ],
            'social' => [
                'type' => Column::TYPE_TEXT
            ],
        ],
        'indexes' => [
            'idx_username' => [
                'type' => 'Unique',
                'fields' => ['username']
            ],
            'idx_email' => [
                'type' => 'Unique',
                'fields' => ['email']
            ],
            'idx_user_group' => [
                'type' => 'Index',
                'fields' => ['user_group_id']
            ]
        ]
    ],
    // ACL
    'user_groups' => [
        'fields' => [
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true
            ],
            'status' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true
            ],
            'description' => [
                'type' => Column::TYPE_TEXT
            ],
            'parent_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ],
            'role_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ]
        ],
        'indexes' => [
            'idx_role' => [
                'type' => 'Index',
                'fields' => ['role_id']
            ]
        ]
    ],
    'auth_roles' => [
        'fields' => [
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true
            ],
            'unique_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true
            ],
            'status' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'description' => [
                'type' => Column::TYPE_TEXT
            ],
        ],
        'indexes' => [
            'idx_unique_name' => [
                'type' => 'Unique',
                'fields' => ['unique_name']
            ]
        ]
    ],
    'auth_permissions' => [
        'fields' => [
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'auth_role_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'access' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ],
        ],
        'indexes' => [
            'idx_unique' => [
                'type' => 'Unique',
                'fields' => ['name', 'type', 'auth_role_id']
            ]
        ]
    ],
    // Menu
    'main_menu' => [
        'fields' => [
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true
            ],
            'icon' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20,
                'default' => 'fa fa-clone'
            ],
            'controller' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30
            ],
            'action' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30
            ],
            'query' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'url' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'target' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 10
            ],
            'weight' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'default' => 0
            ],
            'parent_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'default' => 0
            ],
        ],
        'indexes' => [

        ]
    ],
    // Settings
    'settings' => [
        'fields' => [
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'value' => [
                'type' => Column::TYPE_TEXT
            ],
            'group_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20,
                'default' => 'systems'
            ],
        ],
        'indexes' => [
            'idx_unique' => [
                'type' => 'Unique',
                'fields' => ['name', 'group_name', 'deleted']
            ]
        ]
    ],
    // Field Dependency
    'dependency_fields' => [
        'fields' => [
            'model_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'source_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'source_value' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'target_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'target_value'  => [
                'type' => Column::TYPE_TEXT
            ]
        ],
        'indexes' => [
            'idx_source' => [
                'type' => 'Index',
                'fields' => ['source_name']
            ]
        ]
    ],
    // Audit
    'audit' => [
        'fields' => [
            'username' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 32,
                'notNull' => true,
            ],
            'model_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
            ],
            'record_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'ipaddress' => [
                'type' => Column::TYPE_CHAR,
                'size' => 15,
                'notNull' => true,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
        ],
        'indexes' => [
            'idx_relate' => [
                'type' => 'Index',
                'fields' => ['model_name', 'record_id']
            ]
        ]
    ],
    'audit_detail' => [
        'fields' => [
            'audit_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'field_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'old_value' => [
                'type' => Column::TYPE_TEXT,
            ],
            'new_value' => [
                'type' => Column::TYPE_TEXT,
                'notNull' => true,
            ],
        ],
        'indexes' => [
            'idx_audit' => [
                'type' => 'Index',
                'fields' => ['audit_id']
            ]
        ]
    ],
    // Notifications
    'notifications' => [
        'fields' => [
            'user_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'username' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 32,
                'notNull' => true,
            ],
            'controller_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
                'after' => 'username'
            ],
            'view_detail_action' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
                'after' => 'controller_name'
            ],
            'model_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
            ],
            'record_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'action' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
            ],
            'assigned_user_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'is_read' => [
                'type' => Column::TYPE_BOOLEAN,
                'default' => 0,
            ],
        ],
        'indexes' => [
            'idx_assigned' => [
                'type' => 'Index',
                'fields' => ['assigned_user_id', 'is_read', 'deleted']
            ],
        ]
    ],
    // Comments
    'comments' => [
        'fields' => [
            'subject' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'message' => [
                'type' => Column::TYPE_TEXT,
                'notNull' => true,
            ],
            'relate_type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
                'notNull' => true,
            ],
            'relate_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'parent_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'default' => 0
            ],
            'extend_type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'extend_id' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'attachments' => [
                'type' => Column::TYPE_TEXT
            ],
        ],
        'indexes' => [
            'idx_relate' => [
                'type' => 'Index',
                'fields' => ['relate_type', 'relate_id']
            ]
        ]
    ],
    // activities module
    'activities' => [
        'fields' => [
            'subject' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
                'notNull' => true,
            ],
            'assigned_user_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'relate_type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
            ],
            'relate_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
            ],
            'extend_type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'extend_id' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
                'default' => 'Meeting'
            ],
            'date_start' => [
                'type' => Column::TYPE_DATETIME,
                'notNull' => true,
            ],
            'date_end' => [
                'type' => Column::TYPE_DATETIME,
                'notNull' => true,
            ],
            'send_notification' => [
                'type' => Column::TYPE_BOOLEAN,
                'default' => 1,
            ],
            'duration' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ],
            'location' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'status' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'priority' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
        ],
        'indexes' => [
            'idx_assign_user' => [
                'type' => 'Index',
                'fields' => ['assigned_user_id', 'deleted']
            ],
            'idx_date' => [
                'type' => 'Index',
                'fields' => ['date_start', 'date_end']
            ],
            'idx_status' => [
                'type' => 'Index',
                'fields' => ['status']
            ],
            'idx_relate' => [
                'type' => 'Index',
                'fields' => ['relate_type', 'relate_id']
            ]
        ]
    ],
    'activities_members' => [
        'fields' => [
            'email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
                'notNull' => true,
            ],
            'activity_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'relate_type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 30,
            ],
            'relate_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
            ],
            'required' => [
                'type' => Column::TYPE_BOOLEAN,
                'default' => 0,
            ],
            'status' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'default' => 'Invited',
            ],
        ],
        'indexes' => [
            'idx_activity' => [
                'type' => 'Index',
                'fields' => ['activity_id']
            ],
        ]
    ],
    // customer
    'customers' => [
        'fields' => [
            'logo' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
                'notNull' => true,
            ],
            'assigned_user_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'member_of' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
            ],
            'website' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'primary_email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'secondary_email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'phone' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 15,
            ],
            'fax' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 15,
            ],
            'date_of_birth' => [
                'type' => Column::TYPE_DATE
            ],
            'billing_address1' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'billing_address2' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'billing_city' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'billing_state' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'billing_zip_code' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 10
            ],
            'billing_country' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20
            ],
            'shipping_address1' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'shipping_address2' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'shipping_city' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'shipping_state' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'shipping_zip_code' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 10
            ],
            'shipping_country' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'representative' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'representative_title' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'representative_position' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'tax_code' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'industry' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'employees' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ],
            'annual_revenue' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10
            ],
        ],
        'indexes' => [
            'idx_assigned_user' => [
                'type' => 'Index',
                'fields' => ['assigned_user_id', 'deleted']
            ]
        ]
    ],
    'contacts' => [
        'fields' => [
            'avatar' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255,
            ],
            'salutation' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'first_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'middle_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'last_name' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
                'notNull' => true,
            ],
            'type' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'assigned_user_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'customer_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
                'notNull' => true,
            ],
            'parent_id' => [
                'type' => Column::TYPE_INTEGER,
                'size' => 10,
            ],
            'relate' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50,
            ],
            'primary_email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'secondary_email' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
            ],
            'phone' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 15,
            ],
            'home_phone' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 15,
            ],
            'fax' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 15,
            ],
            'date_of_birth' => [
                'type' => Column::TYPE_DATE
            ],
            'address1' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'address2' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 255
            ],
            'city' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'state' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'zip_code' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 10
            ],
            'country' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 20
            ],
            'title' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'department' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'position' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'lead_source' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 50
            ],
            'description' => [
                'type' => Column::TYPE_TEXT
            ],
            'portal_enable' => [
                'type' => Column::TYPE_BOOLEAN,
                'default' => 0
            ],
            'portal_start' => [
                'type' => Column::TYPE_DATE
            ],
            'portal_end' => [
                'type' => Column::TYPE_DATE
            ],
            'portal_user' => [
                'type' => Column::TYPE_VARCHAR,
                'size' => 32
            ],
            'portal_password' => [
                'type' => Column::TYPE_CHAR,
                'size' => 32
            ]
        ],
        'indexes' => [
            'idx_assigned_user' => [
                'type' => 'Index',
                'fields' => ['assigned_user_id', 'deleted']
            ],
            'idx_customer' => [
                'type' => 'Index',
                'fields' => ['customer_id']
            ],
            /*'idx_portal' => [
                'type' => 'Index',
                'fields' => ['portal_enable', 'portal_user', 'portal_start', 'portal_end']
            ],*/
        ]
    ]
];