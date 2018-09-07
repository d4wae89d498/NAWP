<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/6/2018
 * Time: 2:00 PM
 */

namespace App\iPolitic\NawpCore\components;

/**
 * The packet Class
 * Class Packet
 * @package App\iPolitic\NawpCore\components
 */
class Packet implements \ArrayAccess {

    public const DEFAULT_OBJ = [];

    private $container = ["data" => [], "url" => "", "clientVar" => ""];

    public function __construct(array $data = self::DEFAULT_OBJ) {

        $nData = [];
        if (gettype($data) === "array")
        {
            $nData = $data;
        }

        foreach ([$this->container] as $k => $v) {
            if(isset($nData[$k])){
                $this->$this->container[$k] = $nData[$v];
            }
        }
    }

    public function toArray(): array {
        return (array) $this->container;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}
