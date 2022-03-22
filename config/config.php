<?php

return [
  'auth' => false, #when you set true it require permissions can
  'sign_out' => '/logout',  #logout url
  #middleware permission
  'middleware' =>[
      'permission' =>[
          'active' => false, #force use permissions
      ],
      'config' => 'config', #all user must have this permission to make configurations
      'extract' => 'extract', #all user must have this permission to make extract
      'view_all'=> 'view_all' #all user must have this permission to access all extracted file
  ],
  #extract database connection
  'db_connection' => "mysql",
   #epreview data limit
  'preview_limit' => "5000",
  #make it true if you plan to use queue process
  'queue' => false,
  #when you set queue true you have a chance to set you email
   'email' =>[
       'from' => 'noreply@dynamicexport.com',
       'name' => 'Dynamic Extract'
   ],
  #prefix your route name and folder name
  'prefix' => 'dynamic-extract',
  #set intervaler time of refresh table view of processed file in min milliseconds
  'interval' => 30000,
  #set list for drop down filter
  'lists' =>[
      'group_1'=>[
            'group_name'=>'Group-1',
            'options'=>[
                        'option-1',
                        'option-2',
                        ]
            ],
      'group_2'=>[
            'group_name'=>'Group-2',
            'options'=>[
                        'option-3',
                        'option-4',
                        ]
            ]
    ],
  #set columuns can be selected filter
  'columuns' =>[
      'group_1'=>[
            'group_name'=>'Group-1',
            'options'=>[
                        'name',
                        'email',
                        ]
            ],
      'group_2'=>[
            'group_name'=>'Group-2',
            'options'=>[
                        'email_verified_at',
                        'created_at',
                        ]
            ]
    ]
];
