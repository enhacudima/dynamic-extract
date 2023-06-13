
# Dynamic-extract

Dynamic-extract is a powerful database query extract to Excel and CSV manager for laravel PHP framework with elegant view without additional code.


## Features

 - Powerful, Simple & fast
 - Robust background job processing (queues)
 - Login session (Laravel Auth or built-in verfication token)
 - Dynamic filters
- Notifications
- Preview data
## Demo

https://youtu.be/oQK7-5tglKs


## FAQ

#### Why I need this?
Imagine that you have an application with a database and you are responsible for creating reports via SQL queries for different users or departments. This package will help you manage access and perform some of the more complex database tasks on the end user, without the end user having database knowledge.





## Requires

#### Get all items

| Parameter | Verson     | Description                |
| :-------- | :------- | :------------------------- |
| `php` | `^7.3 or ^8.0` | **Required**|
| `laravel/framework` | `^8.75` | **Required**|
| `maatwebsite/excel` | `^3.1` | **Required**|




## Installation

Install with composer

```require
  composer require enhacudima/dynamic-extract
```

```Installation
  php artisan dynamic-extract:install
```
    
## Filter Function

- Date range
- Search field
- Dropdown field
- Group by
- Select table columuns
- Less than
- Greater than


## Commands

#### Add new tables to the list
```` 
php artisan dynamic-extract:tables
```` 
#### List tables
```` 
php artisan dynamic-extract:tables-list
```` 
#### Add new access 
```` 
php artisan dynamic-extract:access
```` 
#### List access
```` 
php artisan dynamic-extract:access-list
```` 
#### Revoke access
```` 
php artisan dynamic-extract:access-revoke {email}
```` 
#### Delete exported files
```` 
php artisan dynamic-extract:delete-exported
````
## Other commands
You don't need to run the commands on first installation
#### Migration
```` 
php artisan migrate --path=/vendor/enhacudima/dynamic-extract/src/DataBase/Migration
```` 
#### Config
````
php artisan vendor:publish --provider="Enhacudima\DynamicExtract\Providers\DynamicExtractServiceProvider" --tag="config"
````
## Configurations
All configuration well be place on config file at app/config/dynamic-extract.php. after modification please run 
````
php artisan config:cache
````
 

#### List

| Parameter | type     | Description                |
| :-------- | :------- | :------------------------- |
| `auth` | `boolean` | If is true you must implement Laravel Auth |
| `prefix` | `array` | Prefix your route name and extracted folder name|
| `(Permission)- permissions` | `string` | Example: table_view |
| `(Permission) - middleware` | `array` | Example: web|
| `permissions` | `array` | Set your permissions based on your application premissions |



## Security Vulnerabilities
If you discover a security vulnerability, please send an e-mail to [kalibredj@outlook.com](mailto:kalibredj@outlook.com). All security vulnerabilities will be promptly addressed.
## License

licensed under the [MIT license](https://opensource.org/licenses/MIT).
