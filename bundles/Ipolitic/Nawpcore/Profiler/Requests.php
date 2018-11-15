<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:30 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use Fabfuel\Prophiler\DataCollectorInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProfilerRequest
 * @package App\Ipolitic\Nawpcore\Components
 */
class Requests implements DataCollectorInterface
{
    protected $request;
    /**
     * ProfilerRequest constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Requests';
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
        $headers = [];
        foreach ($this->request->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }
        $data = [ 0 => "<pre><code class=\"json hljs\">" . json_encode([
            'SERVER' => $this->request->getServerParams(),
            'QUERY' => $this->request->getQueryParams(),
            'COOKIES' => $this->request->getCookieParams(),
            'HEADERS' => $headers,
            'ATTRIBUTES' => $this->request->getAttributes()
        ]) . "</code></pre>"];
        return $data;
    }
}
