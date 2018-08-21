<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:06 AM
 */
namespace App\Views\Pages\Admin;

use App\iPolitic\NawpCore\Interfaces\ITwig;
use App\iPolitic\NawpCore\Components\View;
use App\Views\Elements\Admin as Elements;

class Home extends View implements ITwig
{
    public $states = ["name" => "default"];

    public function twig() : void
    { ?>
        <?=new Elements\Header($this->templateLogger, [])?>
            <?=new Elements\Menu($this->templateLogger, [])?>

            <?=new Elements\Login($this->templateLogger, [])?>
        <?=new Elements\Footer($this->templateLogger, [])?>

        <?php
    }
}