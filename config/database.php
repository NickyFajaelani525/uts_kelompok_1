<?php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'database' => 'blog_db',
            'username' => 'root',
            'password' => 'Admin@1234!',   // VULNERABILITY: Hardcoded credential
        ],
        'backup' => [
            'driver'   => 'mysql',
            'host'     => '10.0.0.5',
            'database' => 'blog_backup',
            'username' => 'backup_user',
            'password' => 'BackupPass99',  // VULNERABILITY: Hardcoded credential
        ],
    ],
];
