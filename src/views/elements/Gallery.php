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

class Gallery extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <!-- Project Start-->
        <div data-id="{{id}}" class="project_Gal wdt_100">
            <div class="project_gal_left">
                <h3 class="mar_btm23">Projects <span class="green-head">Gallery</span></h3>
                <p>Lorem ipsum dolor sit amet, consecte tur adipisicing elit. Numquam quaerat atque, dolore amido ipsum dolor sit amet, consecte tur adipisicing elit. Lorem ipsum dolor sit amet, consecte tur adipisicing elit.</p><a href="gallery.html" class="checkmor_work">Checkout Our More Work</a>
            </div>
            <div id="our_project" data-ride="carousel" class="carousel slide 4_shows_one_move var_4_slider">
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img1.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img2.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img3.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img1.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img2.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 project_img pad_zero effect-goliath">
                            <div class="shadow_effect black_overlay"><img src="images/home_page2/project_gal-img3.jpg" alt="Project1" class="img-responsive"></div>
                            <div class="project_txt_btn">
                                <h6>Garden / Terrace</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Project End-->
        <?php
    }
}
