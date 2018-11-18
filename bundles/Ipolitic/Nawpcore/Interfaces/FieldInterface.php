<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/9/2018
 * Time: 1:02 PM
 */
namespace App\Ipolitic\Nawpcore\Interfaces;

/**
 * Interface ControllerInterface
 * @package App\Ipolitic\Nawpcore\Interfaces
 */
interface FieldInterface
{
    public function checkValidity();

    public function equalDatabase() : bool ;

    public function getViews() : array ;
}
