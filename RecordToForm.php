<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 17/11/18
 * Time: 12:34
 */
require_once "vendor/autoload.php";
$kernel = new \App\Ipolitic\Nawpcore\Kernel();
// fetching a record
$record =
    $kernel->atlas->select(\App\Server\Models\User\User::class)
        ->where("row_id = ", 1)
        ->fetchRecord();
var_dump($record->getArrayCopy());
// convert it to a field collection
$fieldCollection = new \App\Ipolitic\Nawpcore\Collections\FieldCollection($record);
// append all fields to the collection
$fieldCollection->fill();
var_dump($fieldCollection->getArrayCopy());