<?php

namespace Kaswell\BoxApi\Facades;

use Illuminate\Support\Facades\Facade;
use Kaswell\BoxApi\BoxApi as BoxApiMethods;

/**
 * Class BoxApi
 * @package Kaswell\BoxApi\Facades
 *
 * @method static void asArray()
 *
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void createFolder(string $name, string $parent_folder_id = '0')
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void getFolderList(string $folder_id = '0')
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void getFolderInfo(string $folder_id = '0')
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void updateFolder(string $folder_id, array $data = [])
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void renameFolder(string $folder_id, string $name)
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void replaceFolder(string $folder_id, string $parent_folder_id = '0')
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void deleteFolder(string $folder_id, bool $recursive = true)
 *
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void getFolderCollaborations(string $folder_id)
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void createFolderCollaborations(string $folder_id, string $user_email, string $role = ROLE_VIEWER_UPLOADER)
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void updateCollaborations(string $collaboration_id, string $role = ROLE_VIEWER_UPLOADER)
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void deleteFolderCollaborations(string $collaboration_id)
 *
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void getFileInfo(string $file_id)
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void uploadFile(string $filepath, string $name, string $parent_folder_id = '0')
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void deleteFile(string $file_id)
 *
 * @method static array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void getUser(string $user_id = 'me')
 */
class BoxApi extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return BoxApiMethods::class;
    }
}