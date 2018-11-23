<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 20/11/18
 * Time: 14:12
 */

namespace App\Ipolitic\Nawpcore\Traits;

use App\Ipolitic\Nawpcore\Components\Nokogiri;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Server\PsrFactories;
use function GuzzleHttp\Psr7\parse_query;

/**
 * Trait DataScrapper (usable in kernel only)
 * @package App\Ipolitic\Nawpcore\Components
 */
trait DataScrapper
{
    public static $URL_SAMPLE = "https://fr.wikipedia.org/w/index.php?search=john%20doe&title=Sp%C3%A9cial%3ARecherche&fulltext=1";
    public static $BASE_URL = "https://fr.wikipedia.org/w/index.php";

    public function search(string $keyWords) : string
    {
        $parsed_params = (Utils::parseUrlParams(self::$URL_SAMPLE));
        $parsed_params["search"]    = "pomme de terre";
        $parsed_params["limit"]     = 500;
        $parsed_params["offset"]    = 0;
        //  var_dump($parsed_params);
        $new_url = Utils::buildUrlParams("https://fr.wikipedia.org/w/index.php", $parsed_params);
        // var_dump($new_url);
        return file_get_contents($new_url);
    }

    /**
     * @param string $what
     * @param array $params
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function get(string $what, array $params = [])
    {
        $nokogiri = new Nokogiri($this->search($what));
        $nbResults = intval($nokogiri
            ->get(".results-info")
            ->toArray()[0]["data-mw-num-results-total"]);
        var_dump("NO : ");
        var_dump($nbResults);
        //var_dump($nokogiri->get("div.searchresults")->get("a")->toArray());
        /**
         * @var PsrFactories $cacheFactory
         */
    }
}
