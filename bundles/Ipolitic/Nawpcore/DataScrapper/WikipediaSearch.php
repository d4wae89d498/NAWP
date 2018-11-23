<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 23/11/18
 * Time: 08:32
 */

namespace App\Ipolitic\Nawpcore\DataScrapper;


use App\Ipolitic\Nawpcore\Components\Nokogiri;
use App\Ipolitic\Nawpcore\Components\Utils;

class WikipediaSearch
{
    public const SEARCH_STRUCT          = [
        "search"    => "John Doe",
        "title"     => "Spécial:Recherche",
        "fulltext"  => "1",
        "limit"     => 500,
        "offset"    => 0
    ];
    public const BASE_DOMAIN            = "https://{l}.wikipedia.org";
    public const SEARCH_URL             = "/w/index.php";

    /**
     * @var mixed
     */
    public $baseDomain = "";
    /**
     * @var string
     */
    public $keywords = "";
    /**
     * @var array
     */
    public $searchLinks = [];

    /**
     * WikipediaSearch constructor.
     * @param string $keywords
     * @param string $lang
     * @param array $options
     */
    public function __construct(string $keywords, $lang = "en", $options = [])
    {
        $this->baseDomain = str_replace("{l}", $lang, self::BASE_DOMAIN);
        $this->keywords = $keywords;
        $url = self::SEARCH_STRUCT;
        $url["search"] = $keywords;
        $url = array_merge($url, $options);
        $searchUrl = Utils::buildUrlParams(
            $this->baseDomain . self::SEARCH_URL,
            self::SEARCH_STRUCT);
        echo "Fetching search results from " . $searchUrl . PHP_EOL;
        $html = file_get_contents($searchUrl);
        $nokogiri = new Nokogiri($html);
        $this->searchLinks = array_map(function($e){
            return $e["href"];
        }, $nokogiri->get(".mw-search-results")->get("a")->toArray());
    }

    /**
     * Will fetch a wikipedia search page using a link id
     * @param int $linkID
     * @return Nokogiri
     */
    public function fetch(int $linkID = 0): Nokogiri {
        return new Nokogiri(file_get_contents(
            $this->baseDomain .
            $this->searchLinks[$linkID])
        );
    }
}