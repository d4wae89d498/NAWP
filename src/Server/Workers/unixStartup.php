<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 07/11/18
 * Time: 21:20
 */
define('unix', true);
require_once "Http.php";
require_once "SocketIO.php";

\Workerman\Worker::runAll();
