<?php

return [
    'prefix' => 'dynamic-extract',#prefix your route name and folder name
    'sign_out' => '/logout', #Singout route
    'sign_out_method' => 'post',
    'my_home_page' => '/', #You home page route
    'auth' => false, #when you set true it require permissions via can
    #middleware permission
    'middleware' =>[
        'model' => App\User::class,
        'permission' =>[
            'active' => false, #force use permissions
        ],
        'config' => 'config', #all user must have this permission to make configurations
        'extract' => 'extract', #all user must have this permission to make extract
        'view_all'=> 'view_all' #all user must have this permission to access all extracted file
    ],
    'db_connection' => "mysql", #extract database connection
    'preview_limit' => "5000",#epreview data limit
    'interval' => 30000, #set intervaler time of refresh table view of processed file in min milliseconds
    'queue' => false,#make it true if you plan to use queue process
    #when you set queue true you have a chance to set you email
    'email' =>[
        'from' => 'noreply@dynamicexport.com',
        'name' => 'Dynamic Extract'
    ],
    #set list for drop down filter
    'lists' =>[
        'group_1'=>[
                'group_name'=>'Group-1',
                'options'=>[
                            'option-1',
                            'option-2',
                            ]
                ],
        ],
    #set columuns can be selected filter
    'columuns' =>[
        'group_1'=>[
                'group_name'=>'Group-1',
                'options'=>[
                            'option-1',
                            'option-2',
                            ]
                ],
        ]
];
