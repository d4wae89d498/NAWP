<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 11/18/18
 * Time: 11:12 PM
 */
require "vendor/autoload.php";

use Exception as BaseException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class TestEx extends BaseException
{
}
try {

$run     = new Run();
$handler = new PrettyPageHandler();
$handler->handleUnconditionally(true);

// Add a custom table to the layout:
/*
$handler->addDataTable('Ice-cream I like', [
    'Chocolate' => 'yes',
    'Coffee & chocolate' => 'a lot',
    'Strawberry & chocolate' => 'it\'s alright',
    'Vanilla' => 'ew',
]); */
$handler->setApplicationPaths([__FILE__]);
$handler->addDataTableCallback('Details', function(\Whoops\Exception\Inspector $inspector) {
    $data = array();
    $exception = $inspector->getException();
    if ($exception instanceof SomeSpecificException) {
        $data['Important exception data'] = $exception->getSomeSpecificData();
    }
    $data['Exception class'] = get_class($exception);
    $data['Exception code'] = $exception->getCode();
    return $data;
});
$run->pushHandler($handler);
// Example: tag all frames inside a function with their function name
$run->pushHandler(function ($exception, $inspector, $run) {
    $inspector->getFrames()->map(function ($frame) {
        if ($function = $frame->getFunction()) {
            $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
        }
        return $frame;
    });
});
$run->register();
function fooBar()
{
    throw new TestEx("Something broke!");
}
function bar()
{
        fooBar();

}
    bar();
}
 catch (Exception $ex) {
    throw $ex;
 }