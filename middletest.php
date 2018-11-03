<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/30/2018
 * Time: 7:07 PM
 */
require_once "vendor/autoload.php";
try {
    $arr = include join(DIRECTORY_SEPARATOR, [__DIR__, ".atlas-config.php"]);

    $kernel = new \App\Ipolitic\Nawpcore\Kernel();

    $atlas = new \App\Ipolitic\Nawpcore\Components\Query(
        $arr['pdo'][0],
        $arr['pdo'][1],
        $arr['pdo'][2]
    );

    $atlas->select(\App\Server\Models\User\User::class)
        ->where('email = ', "admin@ipolitic.org")
        ->fetchRecord();
} catch(Throwable $ex) {
    var_dump($ex);
}