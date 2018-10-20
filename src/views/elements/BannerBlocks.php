<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:23 PM
 */

namespace App\Views\Elements;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class BannerBlocks extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <div data-id="{{id}}" class="green_bg wdt_100">
            <ul class="bnr_btm_services">
                <li class="service_img1">
                    <h5>Excellent Service</h5>
                    <p>Lorem ipsum dolor sit amet, consecte tur adipisicing elit. Numquam quaerat atque, dolore amido</p><a href="#" class="knw_more">Know more</a>
                </li>
                <li class="service_img2">
                    <h5>Clean Working</h5>
                    <p>Lorem ipsum dolor sit amet, consecte tur adipisicing elit. Numquam quaerat atque, dolore amido</p><a href="#" class="knw_more">Know more</a>
                </li>
                <li class="service_img3">
                    <h5>Quality And Reliability</h5>
                    <p>Lorem ipsum dolor sit amet, consecte tur adipisicing elit. Numquam quaerat atque, dolore amido</p><a href="#" class="knw_more">Know more</a>
                </li>
                <li class="service_img4">
                    <h5>Quality And Reliability</h5>
                    <p>Lorem ipsum dolor sit amet, consecte tur adipisicing elit. Numquam quaerat atque, dolore amido</p><a href="#" class="knw_more">Know more</a>
                </li>
            </ul>
        </div>
    <?php
    }
}
