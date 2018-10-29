<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/9/2018
 * Time: 2:38 PM
 */

use Symfony\Component\Dotenv\Dotenv;

// if dotenv is not already loaded
if (!isset($_ENV["SYMFONY_DOTENV_VARS"])) {
    // we load it
    $dotEnv = new Dotenv();
    $dotEnv->load(join(DIRECTORY_SEPARATOR, [__DIR__, "configs", ".env"]));

}

// return an array using env vars
return [
    'pdo' => [
        $_ENV["SQL_DSN"],
        $_ENV["SQL_USER"],
        $_ENV["SQL_PWD"],
    ],
    'namespace' => 'App\\Server\\Models',
    'directory' => join(DIRECTORY_SEPARATOR, ['.', 'src','server', 'models'])
];