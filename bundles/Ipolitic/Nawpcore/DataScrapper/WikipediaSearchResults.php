<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 21/11/18
 * Time: 18:29
 */

namespace App\Ipolitic\Nawpcore\DataScrapper;

use App\Ipolitic\Nawpcore\Components\Nokogiri;
use App\Ipolitic\Nawpcore\Components\Utils;

class WikipediaSearchResults
{
    public const LANG                   = "en";
    public const URL_SAMPLE             = "https://{l}.wikipedia.org/w/index.php?search=john%20doe&title=Sp%C3%A9cial%3ARecherche&fulltext=1";
    public const BASE_URL               = "https://{l}.wikipedia.org/w/index.php";
    public const BASE_DOMAIN            = "https://{l}.wikipedia.org";
    public const RESULTS_PER_REQUESTS   = 500;
    public const SLEEP_SECONDS          = 2;
    /**
     * @var array
     */
    public $allLinks            = [];
    /**
     * @var int
     */
    public $currentOffset       = 0;
    /**
     * @var int
     */
    public $maxOffset           = 0;
    /**
     * @var int
     */
    public $stopAt              = 10;
    /**
     * @var string
     */
    public $keyWords            = "";
    /**
     * @var string
     */
    public $html                = "";
    /**
     * @var Nokogiri
     */
    public $nokogiri;
    public $currentSearchurl    = "";

    /**
     * WikipediaSearchResults constructor.
     * @param string $keyWords
     * @param int $stopAt
     */
    public function __construct(string $keyWords, int $stopAt = 0)
    {
        $this->keyWords = $keyWords;
        $this->stopAt   = $stopAt;
    }

    /**
     * @return WikipediaSearchResults
     */
    public function setBaseHtml() : self
    {
        $parsed_params = Utils::parseUrlParams(str_replace("{l}", self::LANG, self::URL_SAMPLE));
        $parsed_params["search"]    =   $this->keyWords;
        $parsed_params["limit"]     =   self::RESULTS_PER_REQUESTS;
        $parsed_params["offset"]    =   $this->currentOffset;
        //  var_dump($parsed_params);
        $this->currentSearchurl = Utils::buildUrlParams(str_replace("{l}", self::LANG, self::BASE_URL), $parsed_params);
        // var_dump($new_url);
        $baseHtml =  file_get_contents($this->currentSearchurl);
        $this->html = $baseHtml;
        $this->nokogiri = new Nokogiri($this->html);
        return $this;
    }

    /**
     * @return WikipediaSearchResults
     */
    public function setMaxOffset() : self
    {
        $this->maxOffset = intval(
            $this->nokogiri->get(".results-info")->toArray()
            [0]["data-mw-num-results-total"]
        );
        return $this;
    }

    /**
     * @return int
     */
    public function getMax() : int
    {
        return ($a = count($this->allLinks)) > 0 ?
            $a : $this->stopAt <  $this->maxOffset ? $this->stopAt : $this->maxOffset;
    }

    /**
     * @return WikipediaSearchResults
     */
    public function fill() : self
    {
        $this->allLinks = [];
        $stopAt = $this->getMax();
        for ($i = 0; $i <= $stopAt; $i += self::RESULTS_PER_REQUESTS) {
            echo "Fetching search results url from no " . $i . " to " .$stopAt . " -----> " . $this->currentSearchurl . PHP_EOL;
            $this->setBaseHtml();
            $links = $this->nokogiri->get(".mw-search-result-heading")->get("a")->toArray();
            $gotMinimalLinks = array_map(function ($e) {
                return [$e["href"], $e["title"]];
            }, $links);
            $this->allLinks = array_merge($this->allLinks, $gotMinimalLinks);
            sleep(self::SLEEP_SECONDS);
        }
        return $this;
    }

    /**
     * @return WikipediaSearchResults
     */
    public function fetch() : self
    {
        foreach ($this->allLinks as $k => $v) {
            if (intval($k) > intval($this->getMax())) {
                unset($this->allLinks[$k]);
                continue;
            }
            $url = str_replace("{l}", self::LANG, self::BASE_DOMAIN) . $v[0];
            echo "Fetching page : " . $k . " / " . strval($this->getMax()) . " -> " . $url . PHP_EOL;
            $test = file_get_contents($url);
            $this->allLinks[$k] = $test;
            sleep(self::SLEEP_SECONDS);
        }
        return $this;
    }
}
