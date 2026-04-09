<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public string $clientSecretPath;
    public string $serviceAccountPath;

    public function __construct()
    {
        parent::__construct();

        $this->clientSecretPath = env('GOOGLE_CLIENT_SECRET_PATH');
        $this->serviceAccountPath = env('GOOGLE_SERVICE_ACCOUNT_PATH');
    }
}