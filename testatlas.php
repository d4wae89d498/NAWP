<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/9/2018
 * Time: 2:44 PM
 */

require_once "vendor/autoload.php";

use Atlas\Orm\Mapper\Mapper;
use Atlas\Orm\AtlasContainer;


use App\DataSources\{
    Categorie\CategorieMapper,
    Log\LogMapper,
    ContentsCategories\ContentsCategoriesMapper,
    Translation\TranslationMapper,
    User\UserMapper,
    Content\ContentMapper
};
$arr = include "atlas-config.php";

$atlasContainer = new AtlasContainer($arr[0], $arr[1], $arr[2]);

$atlasContainer->setMappers([
    UserMapper::CLASS,
    TranslationMapper::CLASS,
    CategorieMapper::class,
    ContentMapper::class,
    LogMapper::class,
    ContentsCategoriesMapper::class,
]);

$atlas = $atlasContainer->getAtlas();


$categoryRecord = $atlas->fetchRecord(\App\DataSources\User\UserMapper::CLASS, '2');

var_dump($categoryRecord);