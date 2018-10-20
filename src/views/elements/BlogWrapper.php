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

class BlogWrapper extends View implements TwigInterface
{
    public function twig(): void
    {
        ?>
        <!-- Blog_Wrapper Start-->
        <div data-id="{{id}}" class="pad_94_100 wdt_100">
            <div class="container">
                <h3 class="black-color mar_btm40">Latest <span class="green-head">Blogs</span></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 blog_col">
                        <div class="blog_img"><a href="blogs.html" class="image_hover"><img src="images/home_page/blog_img1.jpg" alt="image" class="zoom_img_effect"></a></div>
                        <div class="blog_info">
                            <h4><a href="blogs.html">A Good Lawn always  Adds Value to Your Property</a></h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa nt ium dolor emque laudantium totam.</p>
                            <ul class="blog_list_icon">
                                <li class="user_icon">Anjori Meyami</li>
                                <li class="comment_icon">Comments: 6</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 blog_col">
                        <div class="blog_img"><a href="blogs.html" class="image_hover"><img src="images/home_page/blog_img2.jpg" alt="image" class="zoom_img_effect"></a></div>
                        <div class="blog_info">
                            <h4><a href="blogs.html">A Good Lawn always  Adds Value to Your Property</a></h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa nt ium dolor emque laudantium totam.</p>
                            <ul class="blog_list_icon">
                                <li class="user_icon">Anjori Meyami</li>
                                <li class="comment_icon">Comments: 6</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 blog_col nomargin">
                        <ul class="good_lawn_list">
                            <li><a href="blogs.html">
                                    <h4>A Good Lawn always  Adds Value to Your Property</h4>
                                    <p>5 days ago     garden, landscaping </p></a></li>
                            <li><a href="blogs.html">
                                    <h4>A Good Lawn always  Adds Value to Your Property</h4>
                                    <p>5 days ago     garden, landscaping </p></a></li>
                            <li><a href="blogs.html">
                                    <h4>A Good Lawn always  Adds Value to Your Property</h4>
                                    <p>5 days ago     garden, landscaping </p></a></li>
                            <li><a href="blogs.html" class="news_read">Read All News</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog_Wrapper End-->
    <?php
    }
}
