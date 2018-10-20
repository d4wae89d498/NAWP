<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:06 AM
 */
namespace App\Views\Pages;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;
use App\Views\Elements as Elements;

class Home extends View implements TwigInterface
{
    public $states = ["name" => "default", "html_elements" => []];

    public function twig() : void
    {
        ?>
        {% for element in elements %}
            {{ element | raw }}
        {% endfor %}
        <?php
    }
}
