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

class Carousel extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <section data-id="{{id}}">
           [CAROUSEL]
        </section>
    <?php
    }
}
