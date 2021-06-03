<?php

namespace Kaswell\BoxApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class BoxApi
 * @package Kaswell\BoxApi\Facades
 *
 * @method static void asArray()
 *
 * @method static array|object|void createFolder(string $name, string $parent_folder_id = '0')
 * @method static array|object|void getFolderList(string $folder_id = '0')
 * @method static array|object|void getFolderInfo(string $folder_id = '0')
 * @method static array|object|void updateFolder(string $folder_id, array $data = [])
 * @method static array|object|void renameFolder(string $folder_id, string $name)
 * @method static array|object|void replaceFolder(string $folder_id, string $parent_folder_id = '0')
 * @method static array|object|void deleteFolder(string $folder_id, bool $recursive = true)
 *
 * @method static array|object|void getFolderCollaborations(string $folder_id)
 * @method static array|object|void createFolderCollaborations(string $folder_id, string $user_email, string $role = ROLE_VIEWER_UPLOADER)
 * @method static array|object|void updateCollaborations(string $collaboration_id, string $role = ROLE_VIEWER_UPLOADER)
 * @method static array|object|void deleteFolderCollaborations(string $collaboration_id)
 *
 * @method static array|object|void getFileInfo(string $file_id)
 * @method static array|object|void uploadFile(string $filepath, string $name, string $parent_folder_id = '0')
 * @method static array|object|void deleteFile(string $file_id)
 *
 * @method static array|object|void getUser(string $user_id = 'me')
 */
class BoxApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BoxApi';
    }
}