<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 11/23/18
 * Time: 8:01 PM
 */

namespace App\Ipolitic\Nawpcore\DataScrapper\AdministrativeDivisions;


use App\Ipolitic\Nawpcore\Components\Nokogiri;
use App\Ipolitic\Nawpcore\DataScrapper\WikipediaSearchResults;

class ListParser
{
    /**
     * @var Nokogiri[]
     */
    public $allLinks = [];
    /**
     * @var Nokogiri
     */
    public $nokogiri;

    /**
     * ListParser constructor.
     */
    public function __construct()
    {
        $searchResults = (new WikipediaSearchResults("List of administrative divisions by country"))
            ->fill()->fetch();
        $this->nokogiri = new Nokogiri($searchResults->allLinks[0]);
        $this->fillCountries();
        var_dump(($this->allLinks[0])->toText());
    }

    public function fillCountries() : void
    {
        $countryTables = $this->findWikiTables();
        $rows = $this->extractRowsFromCountryTables($countryTables);
        foreach ($rows as $row) {
            $this->allLinks[] = $row;
        }
    }

    public function findWikiTables() : Nokogiri {
        return $this->nokogiri->get(".wikitable");
    }

    public function extractRowsFromCountryTables(Nokogiri $tables) : array {
        $rows =  $tables->get('tbody')->get('tr')->get('td');
        $text = ($rows->toArray());
        file_put_contents("testt.txt", print_r($text,true));
    }
}