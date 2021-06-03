<p align="center"><img src="/art/socialcard.png" alt="Social Card of Laravel BoxApi"></p>

[![License](https://img.shields.io/github/license/kaswell/laravel-boxapi?style=flat-square)](license.md)
[![Total size](https://img.shields.io/github/repo-size/kaswell/laravel-boxapi?style=flat-square)](https://packagist.org/packages/kaswell/laravel-boxapi)
[![Last Version](https://img.shields.io/github/v/release/kaswell/laravel-boxapi?style=flat-square)](https://packagist.org/packages/kaswell/laravel-boxapi)

BoxAPI for Laravel
======

Library for use Box API for Laravel Framework (with JWT authenticate). Box API documentation You'll find on
[Box API Reference](https://developer.box.com/reference/)

Documentation
------

You'll find the documentation on [https://docs.nonium.by/laravel-boxapi](https://docs.nonium.by/laravel-boxapi).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the activity log? Feel free to create an issue on GitHub, we'll try to address it as soon as possible.

Installation
------

You can install the package via composer:
```bash 
composer require kaswell/laravel-boxapi
```

The package will automatically register itself.

Download from [Box Developers](https://gdmg.app.box.com/developers/console) config json file and save into `/storage/app/box_app_config.json` and add into env file next optional constants
```bash 
BOX_APP_DEV_MODE=false
BOX_APP_CONFIG_FILE="box_app_config.json"
BOX_APP_DEV_TOKEN=""
BOX_APP_USER_ID=""
BOX_APP_USER_LOGIN=""
```

or You can optionally publish the config file with:
```bash 
php artisan vendor:publish --provider="Kaswell\BoxApi\BoxApiServiceProvider" --tag="config"
```

Usage
------

You can use class:
```bash 
use Kaswell\BoxApi\BoxApi;
$response = app(BoxApi::class)->getFolderInfo();
```
or use facade:
```bash 
use Kaswell\BoxApi\Facades\BoxApi;
$response = BoxApi::getFolderInfo();
```

### Methods

- `createFolder(string $name, string $parent_folder_id = '0')`
- `getFolderList(string $folder_id = '0')`
- `getFolderInfo(string $folder_id = '0')`
- `updateFolder(string $folder_id, array $data = [])`
- `renameFolder(string $folder_id, string $name)`
- `replaceFolder(string $folder_id, string $parent_folder_id = '0')`
- `deleteFolder(string $folder_id, bool $recursive = true)`

- `getFolderCollaborations(string $folder_id)`
- `createFolderCollaborations(string $folder_id, string $user_email, string $role = 'viewer uploader')`
- `updateCollaborations(string $collaboration_id, string $role = 'viewer uploader')`
- `deleteFolderCollaborations(string $collaboration_id)`

- `getFileInfo(string $file_id)`
- `uploadFile(string $filepath, string $name, string $parent_folder_id = '0')`
- `deleteFile(string $file_id)`

- `getUser(string $user_id = 'me')`


Changelog
------

Please see [changelog](changelog.md) for more information about recent changes.

Credits
------

Special thanks to [everyone](../../contributors) for all the work that was done in `v1`.

License
------

The MIT License (MIT). Please see [License File](license.md) for more information.