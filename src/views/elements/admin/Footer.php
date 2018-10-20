<?php declare(strict_type=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/31/2018
 * Time: 9:03 PM
 */
namespace App\Views\Elements\Admin;

use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Components\View;

class Footer extends View implements TwigInterface
{
    public $states = [
        "js" => [
            0 => "/assets/plugins/jquery/jquery.min.js",
            1 => "/assets/plugins/popper/popper.min.js",
            2 => "/assets/plugins/bootstrap/js/bootstrap.min.js",
            3 => "/admin/js/jquery.slimscroll.js",
            4 => "/admin/js/waves.js",
            5 => "/admin/js/sidebarmenu.js",
            6 => "/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js",
            7 => "/assets/plugins/sparkline/jquery.sparkline.min.js",
            8 => "/admin/js/custom.min.js",
            9 => "/assets/plugins/chartist-js/dist/chartist.min.js",
            10 => "/assets/plugins/chartist-js/dist/chartist.min.js",
            11 => "/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js",
            12 => "/assets/plugins/d3/d3.min.js",
            13 => "/assets/plugins/c3-master/c3.min.js",
            14 => "/admin/js/dashboard1.js",
            15 => "/assets/plugins/styleswitcher/jQuery.style.switcher.js",
            16 => "/generated_js/_app.min.js"
        ],
    ];

    public function twig(): void
    {
        ?>
        <section data-id="{{id}}">
            {% for file in js %}
                <script src="{{ file }}"></script>
            {% endfor %}
            <script>
                <?=$this->templateLogger->generateJS(); ?>
            </script>
        </section>
    <?php
    }
}
