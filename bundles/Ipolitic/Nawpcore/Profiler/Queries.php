<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:30 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Collections\ProfilerCollection;
use Fabfuel\Prophiler\DataCollectorInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProfilerRequest
 * @package App\Ipolitic\Nawpcore\Components
 */
class Queries extends ProfilerCollection implements DataCollectorInterface
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'SQL Queries';
    }

    /**
     * @return string
     */
    public function getIcon() : string
    {
        return '<i class="fa fa-arrow-circle-o-down"></i>';
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->getArrayCopy();
    }
}
