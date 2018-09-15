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
        "html_header" => null,
        "html_footer" => null,
        "html_elements" => [],
    ];

    public function twig() : void
    {
        ?>
        {{ html_header | raw }}
            <div data-id="{{id}}">
                {% for element in html_elements %}
                {{ element | raw}}
                {% endfor %}
            </div>
        {{ html_footer | raw }}
        <?php
    }
}