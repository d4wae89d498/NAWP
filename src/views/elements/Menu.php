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

class Menu extends View implements TwigInterface
{
    public function twig(): void { ?>
        <!-- Header Topbar Start-->
        <div data-id="{{id}}" class="hdr_variation2">
            <div class="hdr_col">
                <div class="container">
                    <div class="hdr_top_bar hdr_top_bar_var3">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 col-sm-5 col-xs-12 mobile_none"><span class="landing_gardening_txt land_garden_txtvar2">We are landcaping & Gardnering WordPress Company</span></div>
                            <div class="col-lg-5 col-md-6 col-sm-7 col-xs-12 hdr_cnt">
                                <ul>
                                    <li class="hdr_msg_icon white_msg_icon"><a href="#" class="var3_white">info@landscaping.com </a></li>
                                    <li class="hdr_call_icon var3_white white_call_icon">1800 984 5478</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Topbar End-->
            <!-- Navbar Start-->
            <nav id="main-navigation-wrapper" class="variation2_navbar navbar navbar-default finance-navbar">
                <div class="thm-container">
                    <div class="navbar-header">
                        <div class="logo-menu"><img src="images/common_in_all/logo.png" alt=""></div>
                        <button type="button" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false" class="navbar-toggle variation2_navbar collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    </div>
                    <div id="main-navigation" class="collapse navbar-collapse"><a href="index.html" class="logo fl"><img src="images/common_in_all/index2_logo.png" alt="image"></a><a href="index.html" class="logo-sticky fl"><img src="images/home_page2/logo-stickybar.png" alt=""></a>
                        <ul class="nav navbar-nav small_hgt">
                            <li class="dropdown"><a href="index.html">Home</a></li>
                            <li class="dropdown"><a href="about.html" class="nav_drop_ar">about us</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="about.html">Introduction</a></li>
                                    <li><a href="faqs.html">FAQ</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="services.html" class="nav_drop_ar">Services</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="lawn_gardencare.html">Lawn & Garden Care</a></li>
                                    <li><a href="irrigation_drainage.html">Irrigation & Drainage</a></li>
                                    <li><a href="stone_hardscaping.html">Stone  & Hard Scaping</a></li>
                                    <li><a href="planting_removal.html">Planting & Removal</a></li>
                                    <li><a href="spring_fallcleanup.html">Spring & Fall Cleanup</a></li>
                                    <li><a href="snow_ice_removal.html">Snow & Ice Removal</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="gallery.html" class="nav_drop_ar">Gallery</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="gallery.html">Gallery Classic</a></li>
                                    <li><a href="gallery-category.html">Gallery Category</a></li>
                                    <li><a href="gallery_no_filter.html">Gallery No Filter</a></li>
                                    <li><a href="gallery_lightbox.html">Gallery Lightbox</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="blogs.html" class="nav_drop_ar">Blog</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="blogs.html">Blog</a></li>
                                    <li><a href="blogs_detail.html">Blog Details</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="shop.html" class="nav_drop_ar">Shops</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="shop.html">Shops</a></li>
                                    <li><a href="product_detail.html">Product Detail</a></li>
                                    <li><a href="cart_page.html">Cart</a></li>
                                    <li><a href="checkout_page.html">Checkout</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End-->
    <?php
    }
}