<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:23 PM
 */
namespace App\Server\Views\Elements;

use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use App\Ipolitic\Nawpcore\Components\View;

class Testimonial extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <!-- Client_Testimonial Start-->
        <div data-id="{{id}}" class="pad_94_100 client_bg wdt_100">
            <div class="container">
                <h3 class="black-color mar_btm40">What Our <span class="lytgreen-head">Client Says</span></h3>
                <div id="client_slider" data-ride="carousel" class="carousel slide two_shows_one_move">
                    <div class="controls pull-right"><a href="#client_slider" data-slide="prev" class="left fa fa-chevron-left"></a><a href="#client_slider" data-slide="next" class="right fa fa-chevron-right"></a></div>
                    <div class="row">
                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="white_client_txt">
                                        <p>Totam rem aperiam, eaque ipsa quae ab illo invent ore veritatis et quasi architecto beatae vitae dict eaque ipsa quae ab.Teritatis et quasi architecto. Sed ut perspi ciatis unde omnis iste natus error sit volu ptatem accusantium dolore mque.</p>
                                    </div>
                                    <div class="client_identity_Col"><span class="client_image"><img src="images/home_page/home_client_img1.png" alt="image"></span>
                                        <div class="client_desc"><span class="client_name">Allien John</span><span class="client_place">California</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="white_client_txt">
                                        <p>Totam rem aperiam, eaque ipsa quae ab illo invent ore veritatis et quasi architecto beatae vitae dict eaque ipsa quae ab.Teritatis et quasi architecto. Sed ut perspi ciatis unde omnis iste natus error sit volu ptatem accusantium dolore mque.</p>
                                    </div>
                                    <div class="client_identity_Col"><span class="client_image"><img src="images/home_page/home_client_img2.png" alt="image"></span>
                                        <div class="client_desc"><span class="client_name">Allien John</span><span class="client_place">California</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Client_Testimonial End-->
    <?php
    }
}
