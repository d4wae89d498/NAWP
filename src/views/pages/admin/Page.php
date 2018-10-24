<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:06 AM
 */
namespace App\Views\Pages\Admin;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;
use App\Views\Elements\Admin as Elements;

class Page extends View implements TwigInterface
{
    public $states = [
        "name" => "default",
        "html_elements" => [],
    ];

    public function twig() : void
    {
        ?>
            <section data-id="{{id}}">
                TESTTTTTTT BANANE
                {% for element in html_elements %}
                {{ element | raw}}
                {% endfor %}
            </section>
        <?php
    }
}
