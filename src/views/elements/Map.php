<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:23 PM
 */

namespace App\Views\Elements;


use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Map extends View implements TwigInterface
{
    public function twig(): void { ?>
        <!-- Start Map-->
        <div data-id="{{id}}" class="wdt_100">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad_zero">
                <div class="home_contact_map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2381.7399040776495!2d-6.261147484122739!3d53.34791197997939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1      !3m3!3m2!1sen!2sus!4v1462581622087" width="600" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 map_cnt_rght">
                <div class="contact_info contact_info1 wdt_100">
                    <ul>
                        <li class="greencnt_map_icon">
                            <p>Landscape & Gardening, 562, <br>            Mallin Street, New Youk, NY 100 254</p>
                        </li>
                        <li class="greencnt_mail_icon">
                            <p class="cnt_fnt_14">info@landscaper.com <br>            support@landscaper.com</p>
                        </li>
                        <li class="greencnt_call_icon fnt_style">
                            <p class="cnt_fnt_18">+ 1800 562 2487<br>            + 3215 546 8975</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Stop Map-->
        <?php
    }
}