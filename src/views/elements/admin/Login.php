<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 1:37 PM
 */
namespace App\Views\Elements\Admin;

use App\DataSources\User\User;
use App\iPolitic\NawpCore\Components\View;
use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Interfaces\TwigInterface;
use App\iPolitic\NawpCore\Kernel;

class Login extends View implements TwigInterface
{
    public $states = [
        "email" => "",
        "rand" => 0,
        "message" => "",
        "cookie_on" => "false"
    ];

    public function twig(): void
    {
        ?>
    <section data-id="{{id}}" id="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h1>{{rand}}</h1>
                            <h3 class="panel-title">Please Sign In</h3>
                            <p> Are cookies enabled : {{ cookie_on }} </p>
                            <p> {{ message }} </p>
                            <p> {{ cookiestr }} </p>
                        </div>
                        <div class="panel-body">
                            <form role="form" id="loginform" method="POST" action="">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="submit" class="btn btn-lg btn-success btn-block" />
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <?php
    }
}
