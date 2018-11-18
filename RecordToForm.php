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
// convert it to a field collection
    $fieldCollection = new \App\Ipolitic\Nawpcore\Collections\FieldCollection($record);
    $request = new \Jasny\HttpMessage\ServerRequest();
// append all fields to the collection
    $viewLogger = new \App\Ipolitic\Nawpcore\Components\ViewLogger($kernel,$request);
    $fieldCollection->setViewLogger($viewLogger);
    $fieldCollection->fill();
    var_dump($viewLogger->renderOne($fieldCollection->getViews()));
// should render the user form

