<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */
namespace App\Views\Elements\Admin;

use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Header extends View implements TwigInterface
{
    public $states = [
        "css" => [
            // main css goes here
            0 => "/assets/plugins/bootstrap/css/bootstrap.min.css",
            1 => "/assets/plugins/chartist-js/dist/chartist.min.css",
            2 => "/assets/plugins/chartist-js/dist/chartist-init.css",
            3 => "/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css",
            4 => "/assets/plugins/c3-master/c3.min.css",
            5 => "/admin/css/style.css",
            6 => "/admin/css/colors/default-dark.css",
        ],
        "js" => [
            0 => "https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js",
            1 => "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js",
        ],
        "title" => "Ferme de cornaton",
        "page" => "Admin",
        "charset" => "UTF-8",
        "viewport" => "width=device-width, initial-scale=1"
    ];

    public function twig(): void { ?>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!-- Tell the browser to be responsive to screen width -->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="">
            <meta name="author" content="">
            <!-- Favicon icon -->
            <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
            <title>{{ title }} - {{ page }}</title>
            <!-- CSS loop -->
            {% for file in css %}
                <link href="{{file}}" rel="stylesheet">
            {% endfor %}
            <!-- JS loop -->
            {% for file in css %}
                <script src="{{file}}"></script>
            {% endfor %}
        </head>
    <?php }
}