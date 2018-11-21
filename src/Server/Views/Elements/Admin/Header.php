<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */
namespace App\Server\Views\Elements\Admin;

use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use App\Ipolitic\Nawpcore\Components\View;

class Header extends View implements TwigInterface
{
    public $states = [
        "css" => [
            // main css goes here
            0 => "/vendor/bootstrap/css/bootstrap.min.css",
            1 => "/vendor/metisMenu/metisMenu.min.css",
            2 => "/css/SbAdmin2.css",
            3 => "/vendor/font-awesome/css/font-awesome.min.css",
            4 => "//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css",
            5 => "//highlightjs.org/static/demo/styles/vs2015.css"
        ],
        "js" => [
            0 => "https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js",
            1 => "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js",
            2 => "//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"
        ],
        "title" => "Ferme de cornaton",
        "page" => "Admin",
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
            {% for jsFile in css %}
                <link href="{{ jsFile }}" rel="stylesheet">
            {% endfor %}
            <!-- JS loop -->
            {% for cssFile in js %}
                <script src="{{ cssFile }}"></script>
            {% endfor %}
            <script>hljs.initHighlightingOnLoad();</script>
            <link rel="stylesheet" href="/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css" />
        </head>
    <?php
    }
}
