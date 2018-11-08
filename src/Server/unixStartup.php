<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 07/11/18
 * Time: 21:20
 */
define('unix', true);
require_once "Workers/Http.php";
require_once "Workers/SocketIO.php";

\Workerman\Worker::runAll();
