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

class AboutUS extends View implements TwigInterface
{
    public function twig(): void { ?>
        <!-- About_Us Start-->
        <div data-id="{{id}}" class="pad_100 wdt_100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><img src="images/home_page3/welcome_Dream_img.png"></div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 welcome_dream_txt">
                        <h5>Welcome  to our Dream <span class="fnt_bold">
                landscape &
                gardening</span></h5>
                        <p>If you chose to stay with us you will enjoy modern home comforts in a traditional setting. Whether you are looking for a short weekend break or a longer holiday, we offer a range of packages that will cater for all.  stay with us you will enjoy modern home comforts in a traditional setting.</p>
                        <p class="fnt_border">Whether you are looking for a short weekend break or a longer holiday, we offer a range of packages that will cater for all.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- About_Us End-->
    <?php
    }
}