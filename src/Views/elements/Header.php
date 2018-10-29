<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */
namespace App\Views\Elements;

use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use App\Ipolitic\Nawpcore\Components\View;

class Header extends View implements TwigInterface
{
    public $states = [
        "css" => [
            // main css goes here
            0 => "/vendor/bootstrap/css/bootstrap.min.css",
            1 => "/vendor/metisMenu/metisMenu.min.css",
            2 => "/css/sb-admin-2.css",
            3 => "/vendor/font-awesome/css/font-awesome.min.css",
        ],
        "js" => [
            0 => "https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js",
            1 => "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js",
        ],
        "title" => "Ferme de cornaton",
        "page" => "Home",
        "url" => "",
        "cookies" => "",
    ];

    public function twig(): void
    {
        ?>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="Somewho webapp">
            <meta name="author" content="Marc FAUSSURIER">
            <meta name="data-url" content="{{ url }}">
            <meta name="data-cookies" content="{{ cookies }}">
            <title>{{ title }} - {{ page }}</title>
            <!-- CSS loop -->
            {% for cssFile in css %}
                <link href="{{ cssFile }}" rel="stylesheet">
            {% endfor %}
            <!-- JS loop -->
            {% for jsFile in js %}
                <script src="{{ jsFile }}"></script>
            {% endfor %}
        </head>
    <?php
    }
}
