# Dynamic-extract

## About

Dynamic-extract is a powerful database extract (Excel) manager for PHP laravel framework with elegant view (no code at the and user). This include great features such as:

- Powerful, Simple & fast.
- Robust background job processing (queues).
- Login session (Laravel Auth or built-in verfication token).
- Dynamic filters.
- Notifications.
- Preview data.
## Why I need this
Imagine that you have an application that hosts several tables and you must share reports with several users? This package will help you to manage accesses and perform some of the most complex database tasks on the end user, without him having knowledge of database.
##  [Demo](https://youtu.be/oQK7-5tglKs)

## Filters
- Data range.
- Search field.
- Dropdown field.
- Group by.
- Select table columuns.
- Less than.
- Greater than. 

## Requires
- php ^7.3|^8.0
- laravel/framework ^8.75 
- maatwebsite/excel ^3.1


## Lets Start

- Import
```` 
composer require enhacudima/dynamic-extract
````
- Installation
```` 
php artisan dynamic-extract:install
````
## Commands

- Add new tables to the list
```` 
php artisan dynamic-extract:tables
```` 
- List tables
```` 
php artisan dynamic-extract:tables-list
```` 
- Add new access 
```` 
php artisan dynamic-extract:access
```` 
- List access
```` 
php artisan dynamic-extract:access-list
```` 
- Revoke access
```` 
php artisan dynamic-extract:access-revoke {email}
```` 
- Delete exported files
```` 
php artisan dynamic-extract:delete-exported
````
## Other commands
You don't need to run the commands on first installation
- Migration
```` 
php artisan migrate --path=/packages/enhacudima/dynamic-extract/src/Database/Migration
```` 
- Config
````
php artisan vendor:publish --provider="Enhacudima\DynamicExtract\Providers\DynamicExtractServiceProvider" --tag="config"
````

## Configurations

All configuration well be place on config file at app/config/dynamic-extract.php. after modification please run 
````
php artisan config:cache
````
- Auth
```` 
auth:boolean 
If is true you must implement Laravel Auth 
```` 

- Permission
```` 
middleware:array 
Permission list
```` 

- Prefix Route
```` 
prefix:string 
Prefix your route name and extracted folder name
```` 

- Permissions
```` 
permissions:array 
Set your permissions based on your application premissions
```` 

## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to [kalibredj@outlook.com](mailto:kalibredj@outlook.com). All security vulnerabilities will be promptly addressed.

## License

licensed under the [MIT license](https://opensource.org/licenses/MIT).
