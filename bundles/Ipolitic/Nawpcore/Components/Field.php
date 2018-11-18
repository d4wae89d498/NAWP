<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 16:06
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Interfaces\FieldInterface;
use App\Ipolitic\Nawpcore\Kernel;
use Atlas\Mapper\Record;

/**
 * Class Field
 * @package App\Ipolitic\Nawpcore\Components
 */
class Field implements FieldInterface
{
    /**
     * @var Kernel
     */
    public $kernel;
    /**
     * @var Record
     */
    public $record;
    /**
     * @var mixed
     */
    public $value;
    /**
     * @var string
     */
    public $column;
    /**
     * @var array
     */
    public $prop;

    /**
     * Field constructor.
     * @param Kernel $kernel
     * @param Record $record
     * @param string $column
     * @param mixed $value
     * @param array $prop
     */
    public function __construct(Kernel &$kernel, Record &$record, string $column, $value = null, array $prop = [])
    {
        $this->kernel = &$kernel;
        $this->record = &$record;
        $this->prop = $prop;
        $this->set($value, $column);
    }

    /**
     * @param mixed $value
     * @param string $column
     */
    public function set($value, $column = "")
    {
        $this->value = $value;
        $this->column = $column != "" ? $column : $this->column;
        $this->prop["value"] = $this->value;
        $this->prop["column"] = $this->column;
    }

    /**
     * @return bool
     */
    public function equalDatabase() : bool
    {
        if ($this->record->has($this->column)) {
            $col = $this->column;
            return $this->value === $this->record->$col;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function checkValidity()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function save() : void
    {
        $col = $this->column;
        $this->record->$col = $this->value;
    }
}
