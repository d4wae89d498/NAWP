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

class Banner extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <!-- BannerCol Start-->
        <div data-id="{{id}}" id="minimal-bootstrap-carousel" data-ride="carousel" class="carousel slide carousel-fade shop-slider">
            <!-- Wrapper for slides-->
            <div role="listbox" class="carousel-inner ver_new_3_slider">
                <div class="item active slide-1">
                    <div class="carousel-caption">
                        <div class="thm-container">
                            <div class="box valign-top home3_slide1">
                                <div class="content text-left wdt55 cnt_fl">
                                    <h2 data-animation="animated fadeInUp">Complete Landscape service and maintenance</h2>
                                    <p data-animation="animated fadeInDown">We can turn your front and backyard into a beautiful haven that you can be proud of. If you or someone you know needs landscaping service, contact us today for a free estimate.</p><a data-animation="animated fadeInUp" href="contact.html" data-text="Contact Us" class="button button--winona button--inverted"><span>Contact Us</span></a><a data-animation="animated fadeInUp" href="services.html" data-text="OUr Services" class="button button--winona button--inverted2"><span>OUr Services</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item slide-2">
                    <div class="carousel-caption">
                        <div class="thm-container">
                            <div class="box valign-top home3_slide2">
                                <div class="content text-left pull-right wdt55 cnt_fr">
                                    <h2 data-animation="animated fadeInUp">COMPLETE LANDSCAPE SERVICE AND MAINTENANCE</h2>
                                    <p data-animation="animated fadeInDown">We can turn your front and backyard into a beautiful haven that you can be proud of. If you or someone you know needs landscaping service, contact us today for a free estimate.</p><a data-animation="animated fadeInUp" href="contact.html" data-text="Contact Us" class="button button--winona button--inverted"><span>Contact Us</span></a><a data-animation="animated fadeInUp" href="services.html" data-text="OUr Services" class="button button--winona button--inverted2"><span>OUr Services</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item slide-3">
                    <div class="carousel-caption">
                        <div class="thm-container">
                            <div class="box valign-top home3_slide1">
                                <div class="content text-left wdt55 cnt_fl">
                                    <h2 data-animation="animated fadeInUp">COMPLETE LANDSCAPE SERVICE AND MAINTENANCE</h2>
                                    <p data-animation="animated fadeInDown">We can turn your front and backyard into a beautiful haven that you can be proud of. If you or someone you know needs landscaping service, contact us today for a free estimate.</p><a data-animation="animated fadeInUp" href="contact.html" data-text="Contact Us" class="button button--winona button--inverted"><span>Contact Us</span></a><a data-animation="animated fadeInUp" href="services.html" data-text="OUr Services" class="button button--winona button--inverted2"><span>OUr Services</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Controls--><a href="#minimal-bootstrap-carousel" role="button" data-slide="prev" class="left carousel-control"><i class="fa fa-angle-left"></i><span class="sr-only">Previous</span></a><a href="#minimal-bootstrap-carousel" role="button" data-slide="next" class="right carousel-control"><i class="fa fa-angle-right"></i><span class="sr-only">Next</span></a>
        </div>
        <!-- BannerCol End-->
    <?php
    }
}
