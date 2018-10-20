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

class Footer extends View implements TwigInterface
{
    public $states = [
        "js" => [
            //0 => "/assets/js/jquery.min.js",
            0 => "/assets/js/bootstrap.min.js",
            1 => "/assets/js/jquery.touchSwipe.min.js",
            2 => "/assets/js/theme.js",
            3 => "/assets/js/responsive_bootstrap_carousel.js",
            4 => "/generated_js/app.min.js",
        ]
    ];

    public function twig(): void
    {
        ?>
        <!-- Footer_Wrapper Start-->
        <footer data-id="{{id}}" class="wdt_100">
            <!-- Footer_Container Start-->
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 ftr_txt_div"><img src="images/common_in_all/ftr_logo.png" alt="image">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor asin cididunt ut labore et dolore magna ali qua.
                            Lorem ipsum dolor sit amet.
                        </p>
                        <ul class="ftr_social">
                            <li><a href="#"><i aria-hidden="true" class="fa fa-tumblr"></i></a></li>
                            <li><a href="#"><i aria-hidden="true" class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i aria-hidden="true" class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i aria-hidden="true" class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 ftr_nav">
                        <h6>Usefull Links</h6>
                        <ul>
                            <li><a href="about_us.html"><i aria-hidden="true" class="fa fa-angle-right"></i>About Us</a></li>
                            <li><a href="request_quote.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Make an Appoint</a></li>
                            <li><a href="contact.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Get Free Quote</a></li>
                            <li><a href="#"><i aria-hidden="true" class="fa fa-angle-right"></i>Documentation</a></li>
                            <li><a href="gallery.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Gallery</a></li>
                            <li><a href="blogs.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Blogs</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ftr_nav ftr_pad_30">
                        <h6>Our Services</h6>
                        <ul>
                            <li><a href="planting_removal.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Planting & Removal</a></li>
                            <li><a href="irrigation_drainage.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Irrigation & Drainage</a></li>
                            <li><a href="spring_fallcleanup.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Spring & Fall Cleanup</a></li>
                            <li><a href="stone_hardscaping.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Stone & Hardscaping</a></li>
                            <li><a href="snow_ice_removal.html"><i aria-hidden="true" class="fa fa-angle-right"></i>Snow & Ice Removal</a></li>
                            <li><a href="services.html"><i aria-hidden="true" class="fa fa-angle-right"></i>See all Services</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ftr_nav get_in_touch">
                        <h6>Get In Touch</h6>
                        <ul>
                            <li class="ftr_location_icon"><span class="txt-big">Landscaping & Gardening</span> 42B, Tailstoi Town 5248 MT, Wordwide Country</li>
                            <li class="ftr_phn_icon ftr_call_txt">+ 01865 524 8503</li>
                            <li class="ftr_msg_icon">contact@landscap.com</li>
                            <li class="ftr_clock_icon">Monday - Friday : 800 - 1900</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Footer_Container End-->
            <!-- Copyright Start-->
            <div class="ftr_btm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
                            <p>Copyright © Landscaping 2017. All rights reserved. </p>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-4 col-xs-12 text-right">
                            <p>Created by: DesignArc </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Copyright End-->
        </footer>
        <!-- Footer_Wrapper End-->
        <!-- helper js-->
        {% for jsfile in js %}
            <script src="{{ jsfile }}"></script>
        {% endfor %}
    </body>
</html>
    <?php
    }
}
