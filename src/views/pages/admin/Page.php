<?php
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
            <div data-id="{{id}}">
                {% for element in html_elements %}
                {{ element | raw}}
                {% endfor %}
            </div>
        <?php
    }
}
