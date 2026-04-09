<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Config\Database;

class GoogleDriveService
{
    protected $client;
    protected $drive;
    protected $db;
    protected $userId;
    protected $tokenData;

    public function __construct()
    {
        $config = config('Google');
        $this->client = new Client();
        $this->client->setAuthConfig(
            ROOTPATH . $config->clientSecretPath
        );
        $this->client->addScope('openid');
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->drive = new Drive($this->client);
        $this->db = Database::connect();
    }
    // Google Login URL

    public function getAuthUrl()
    {
        $this->client->setRedirectUri(base_url('google/callback'));
        return $this->client->createAuthUrl();
    }

    // HANDLE LOGIN + TOKEN STORAGE
    public function handleCallback($code)
    {
        $this->client->setRedirectUri(base_url('google/callback'));
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) {
            throw new \Exception('Failed to get access token');
        }
        // Set token
        $this->client->setAccessToken($token);
        // Verify identity
        $payload = $this->client->verifyIdToken();
        if (!$payload) {
            throw new \Exception('Invalid Google authentication.');
        }
        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? '';
        // CHECK IF USER EXISTS (STRICT)
        $user = $this->db->table('users')
            ->where('email', $email)
            ->get()
            ->getRow();
        if (!$user) {
            // BLOCK ACCESS
            throw new \Exception('Unauthorized: This Google account is not registered.');
        }
        $userId = $user->user_id;
        // SET SESSION (LOGIN SUCCESS)
        session()->set('user_id', $userId);
        // SAVE / UPDATE GOOGLE TOKENS
        $data = [
            'user_id' => $userId,
            'google_id' => $googleId,
            'email' => $email,
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'] ?? null,
            'expires_at' => date('Y-m-d H:i:s', time() + $token['expires_in']),
        ];
        $existing = $this->db->table('google_accounts')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
        if ($existing) {
            $this->db->table('google_accounts')
                ->where('user_id', $userId)
                ->update($data);
        } else {
            $this->db->table('google_accounts')->insert($data);
        }
        return $userId;
    }
    // SET USER CONTEXT (for API calls)
    public function setUser($userId)
    {
        $this->userId = $userId;
        $this->tokenData = $this->db->table('google_accounts')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();
        if (!$this->tokenData) {
            throw new \Exception("User not connected to Google.");
        }
        $this->setAccessToken();
    }

    // HANDLE TOKEN + AUTO REFRESH
    private function setAccessToken()
    {
        $token = [
            'access_token' => $this->tokenData['access_token'],
            'refresh_token' => $this->tokenData['refresh_token'],
            'expires_in' => 3600,
            'created' => strtotime($this->tokenData['expires_at']) - 3600,
        ];
        $this->client->setAccessToken($token);
        // Refresh if expired
        if ($this->client->isAccessTokenExpired()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken(
                $this->tokenData['refresh_token']
            );
            if (!isset($newToken['refresh_token'])) {
                $newToken['refresh_token'] = $this->tokenData['refresh_token'];
            }
            // Save updated token
            $this->db->table('google_accounts')
                ->where('user_id', $this->userId)
                ->update([
                    'access_token' => $newToken['access_token'],
                    'expires_at' => date('Y-m-d H:i:s', time() + $newToken['expires_in']),
                ]);
            $this->client->setAccessToken($newToken);
        }
    }

    // Upload file (user-specific)
    public function uploadFile($filePath, $fileName, $folderId = null)
    {
        if (!$this->userId) {
            throw new \Exception("User not set.");
        }
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : []
        ]);
        $content = file_get_contents($filePath);
        $file = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);
        return $file->id;
    }

    private function sanitizeFileName($fileName)
    {
        // remove unsafe chars
        $fileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $fileName);

        // prevent empty names
        return $fileName ?: 'file_' . time();
    }

    public function uploadFileFromContent($content, $fileName, $mimeType, $folderId = null)
    {
        if (!$this->userId) {
            throw new \Exception("User not set.");
        }

        // OPTIONAL: sanitize filename
        $fileName = $this->sanitizeFileName($fileName);

        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : []
        ]);

        $file = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $file->id;
    }


    public function createFolder($name, $parentId = null)
    {
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentId ? [$parentId] : []
        ]);

        $folder = $this->drive->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        return $folder->id;
    }


    public function getFolderIdByName($name, $parentId = null)
    {
        $query = "mimeType='application/vnd.google-apps.folder' and name='{$name}' and trashed=false";

        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $results = $this->drive->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)'
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        return null;
    }



    public function initForCLI()
    {
        $config = config('Google');
        $this->client = new Client();

        $this->client->setAuthConfig(
            ROOTPATH . $config->serviceAccountPath
        );
        $this->client->addScope(Drive::DRIVE);

        $this->drive = new Drive($this->client);
    }
}
