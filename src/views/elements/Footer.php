<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */
namespace App\Views\Elements;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Footer extends View implements TwigInterface
{
    public $states = [
        "js" => [
            0 => "/vendor/jquery/jquery.min.js",
            1 => "/vendor/bootstrap/js/bootstrap.min.js",
            2 => "/vendor/metisMenu/metisMenu.min.js",
            3 => "/js/app.min.js",
        ],
    ];

    public function twig(): void
    {
        ?>
        <section data-id="{{id}}">
            {% for file in js %}
                <script src="{{ file }}"></script>
            {% endfor %}
            <script>
                <?=$this->templateLogger->generateJS(); ?>
            </script>
        </section>
    <?php
    }
}
