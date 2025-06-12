<?php

// app/Services/DoctrineConnectionFactory.php
namespace App\Services;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class DoctrineConnectionFactory
{
    public static function make(): Connection
    {
        return DriverManager::getConnection([
            'dbname'   => env('DB_DATABASE', 'customer_uploader'),
            'user'     => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'host'     => env('DB_HOST', '127.0.0.1'),
            'driver'   => 'pdo_mysql'
        ]);
    }
}
