<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:06 AM
 */
namespace App\Server\Views\Pages\Admin;

use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use App\Ipolitic\Nawpcore\Components\View;

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
                {% for element in html_elements %}
                {{ element | raw}}
                {% endfor %}
            </section>
        <?php
    }
}
