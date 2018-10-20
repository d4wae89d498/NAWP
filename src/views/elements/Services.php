<?php declare(strict_type=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:23 PM
 */
namespace App\Views\Elements;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Services extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <!-- Services Start-->
        <div data-id="{{id}}" class="service_bg wdt_100 pad_100_196">
            <div class="container">
                <div class="wdt_100 service_mrbtm service_mrbtm1">
                    <h3 class="black-color service_head_br">Our <span class="lytgreen-head">Services</span></h3><a href="#" class="view_Service">View All Services</a>
                </div>
                <div id="service_slider" data-ride="carousel" class="carousel slide three_shows_one_move">
                    <!-- Wrapper for slides-->
                    <div class="quality-list quality-list1">
                        <div class="row">
                            <div class="carousel-inner">
                                <div class="item active">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="planting_removal.html" class="image_hover mbot_38"><img src="images/home_page/planting_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="planting_removal.html">Planting & Removal</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="planting_removal.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="lawn_gardencare.html" class="image_hover mbot_38"><img src="images/home_page/lawn_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="lawn_gardencare.html">Lawn & Garden care</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="lawn_gardencare.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="irrigation_drainage.html" class="image_hover mbot_38"><img src="images/home_page/irrigation_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="irrigation_drainage.html">Irrigation & Drainage</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="irrigation_drainage.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="stone_hardscaping.html" class="image_hover mbot_38"><img src="images/home_page/hardscaping_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="stone_hardscaping.html">Stone & hard scaping</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="stone_hardscaping.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="lawn_gardencare.html" class="image_hover mbot_38"><img src="images/home_page/lawn_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="lawn_gardencare.html">Lawn & Garden care</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="lawn_gardencare.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="irrigation_drainage.html" class="image_hover mbot_38"><img src="images/home_page/irrigation_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="irrigation_drainage.html">Irrigation & Drainage</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="irrigation_drainage.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 quality-list_marbtm"><a href="stone_hardscaping.html" class="image_hover mbot_38"><img src="images/home_page/hardscaping_img.jpg" alt="image" class="zoom_img_effect"></a>
                                        <h5><a href="stone_hardscaping.html">Stone & hard scaping</a></h5>
                                        <p>Lorem ipsum dolor sit amet, consecte turelit. Vestibulum nec odio ipsumer Suspe ndisse cursus malesuada.</p><a href="stone_hardscaping.html" class="view-all hvr-bounce-to-right read_btn">rEAD MORE</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="controls pull-right"><a href="#service_slider" data-slide="prev" class="left fa fa-chevron-left"></a><a href="#service_slider" data-slide="next" class="right fa fa-chevron-right"></a></div>
                </div>
            </div>
        </div>
        <!-- Services End-->
        <?php
    }
}
