<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */

namespace App\Views\Elements;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Header extends View implements TwigInterface
{
    public $states = [
        "css" => [
            // main css goes here
            0 => "/assets/css/style.css",
            1 => "/assets/plugins/Stroke-Gap-Icons-Webfont/style.css"
        ],
        "title" => "Ferme de cornaton",
        "page" => "Home",
        "charset" => "UTF-8",
        "viewport" => "width=device-width, initial-scale=1"
    ];

    public function twig(): void
    {
        ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="{{charset}}">
        <title>{{page}} | {{title}}</title>
        <!-- mobile responsive meta-->
        <meta name="viewport" content="{{viewport}}">
        <!-- main stylesheet-->
        {% for file in css %}
            <link rel="stylesheet" href="{{file}}">
        {% endfor %}
    </head>
    <body>
    <?php
    }
}
