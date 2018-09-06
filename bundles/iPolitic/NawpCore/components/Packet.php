<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/6/2018
 * Time: 2:00 PM
 */

namespace App\iPolitic\NawpCore\components;


class Packet
{
    public const DEFAULT_OBJ = [];

    public $data = [];

    public $url = "";

    public $clientVar = "";

    /**
     * The packet constructor
     * Packet constructor.
     * @param array $data
     */
    public function __construct($data = self::DEFAULT_OBJ)
    {
        $nData = [];
        if (gettype($data) === "array")
        {
            $nData = $data;
        }

        foreach (["data", "url", "clientVar"] as $v) {
            if(isset($data[$v])){
                $this->$v = $nData[$v];
            }
        }
    }
}