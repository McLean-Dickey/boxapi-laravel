<?php

namespace Kaswell\BoxApi;

use Exception;

/**
 * Class BoxApi
 * @package Kaswell\BoxApi
 */
class BoxApi extends ApiAbstract
{
    /**
     * @param string $name
     * @param string $parent_folder_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function createFolder(string $name, string $parent_folder_id = '0')
    {
        try {
            $this->setData([
                'name' => $name,
                'parent' => [
                    'id' => $parent_folder_id,
                ]
            ]);
            $path = 'folders';
            $response = $this->send($path, POST_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getFolderList(string $folder_id = '0')
    {
        try {
            $path = 'folders/' . $folder_id . '/items';
            $response = $this->send($path, GET_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getFolderInfo(string $folder_id = '0')
    {
        try {
            $path = 'folders/' . $folder_id;
            $response = $this->send($path, GET_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @param array $data
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function updateFolder(string $folder_id, array $data = [])
    {
        try {
            $this->setData($data);
            $path = 'folders/' . $folder_id;
            $response = $this->send($path, PUT_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @param string $name
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function renameFolder(string $folder_id, string $name)
    {
        try {
            $this->setData([
                'name' => $name,
            ]);
            $response = $this->updateFolder($folder_id);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @param string $parent_folder_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function replaceFolder(string $folder_id, string $parent_folder_id = '0')
    {
        try {
            $this->setData([
                'parent' => [
                    'id' => $parent_folder_id,
                ],
            ]);
            $response = $this->updateFolder($folder_id);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getFolderCollaborations(string $folder_id)
    {
        try {
            $path = 'folders/' . $folder_id . 'collaborations';
            $response = $this->send($path, GET_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $folder_id
     * @param string $user_email
     * @param string $role
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function createFolderCollaborations(string $folder_id, string $user_email, string $role = ROLE_VIEWER_UPLOADER)
    {
        try {
            $this->setData([
                'item' => [
                    'id' => $folder_id,
                    'type' => 'folder'
                ],
                'accessible_by' => [
                    "type" => "user",
                    'login' => $user_email
                ],
                'role' => $role
            ]);
            $path = 'collaborations';
            $response = $this->send($path, POST_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    public function updateCollaborations(string $collaboration_id, string $role = ROLE_VIEWER_UPLOADER)
    {
        try {
            $this->setData([
                'role' => $role
            ]);
            $path = 'collaborations/' . $collaboration_id;
            $response = $this->send($path, PUT_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }

    /**
     * @param string $collaboration_id
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function deleteFolderCollaborations(string $collaboration_id)
    {
        try {
            $path = 'collaborations/' . $collaboration_id;
            $response = $this->send($path, DELETE_METHOD);
        } catch (Exception $exception) {
            $response = false;
        }
        return $response;
    }
}