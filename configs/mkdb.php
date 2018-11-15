<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 11/15/18
 * Time: 7:51 PM
 */

require_once join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "vendor", "autoload.php"));
$kernel = new \App\Ipolitic\Nawpcore\Kernel(false);

$dsn = str_replace("atlas", "sys", getenv("SQL_DSN"));
try {
    $pdo = new PDO($dsn, getenv("SQL_USER"), getenv("SQL_PWD"));
} catch (Exception $ex) {
    echo "Unable to connect to the database server for db creation. Please fix your .env file and type yarn mkdb" . PHP_EOL;
    throw $ex;
}
$driver = explode(":", $dsn)[0];
$fileToOpen = __DIR__ . DIRECTORY_SEPARATOR . "nawpcore-" . $driver . "-database.sql";
if(!file_exists($fileToOpen)) {
    echo "No database creation script was found for your dsn driver : " . $driver . PHP_EOL;
} else {
    $pdo->exec(file_get_contents($fileToOpen));
    echo "Database creation script ran." . PHP_EOL;
}


