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
        "header" => null,
        "footer" => null,
        "pageElement" => null,
    ];

    public function twig() : void
    {?>

        {{ header | raw }}
            <div data-id="{{id}}">
                {{ pageElement | raw }}
            </div>
        {{ footer | raw %}}

<?php}
}