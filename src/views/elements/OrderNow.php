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

class OrderNow extends View implements TwigInterface
{
    public function twig(): void { ?>
        <!-- Stop_Location Start-->
        <div data-id="{{id}}" class="stop_location_col wdt_100 stopgreen_bg">
            <div class="container">
                <h3>We are your one stop location for all of your outdoor needs,for both residential and commercial properties.</h3><a href="request_quote.html" class="view-all hvr-bounce-to-right get_request">Get Free Quote</a>
            </div>
        </div>
        <!-- Stop_Location End-->

        <?php
    }
}