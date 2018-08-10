<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:06 AM
 */
namespace App\Views\Pages;


use App\iPolitic\NawpCore\Interfaces\ITwig;
use App\iPolitic\NawpCore\Components\View;
use App\Views\Elements as Elements;

class Home extends View implements ITwig
{
    public $states = ["name" => "default"];

    public function twig() : void
    {
?>
        <?=new Elements\Header($this->templateLogger, [])?>
            <?=new Elements\Menu($this->templateLogger, [])?>

            <?=new Elements\Banner($this->templateLogger, [])?>
            <?=new Elements\BannerBlocks($this->templateLogger, [])?>

            <?=new Elements\Services($this->templateLogger, [])?>

            <?=new Elements\Gallery($this->templateLogger, []) ?>

            <?=new Elements\OrderNow($this->templateLogger, [])?>

            <?=new Elements\Testimonial($this->templateLogger, [])?>

            <?=new Elements\Map($this->templateLogger, [])?>

            <?=new Elements\BlogWrapper($this->templateLogger, [])?>

        <?=new Elements\Footer($this->templateLogger, [])?>

        <?php
    }
}