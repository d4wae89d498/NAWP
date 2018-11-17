<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 16:06
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Exceptions\IncompatibleFieldAndRecordType;
use Atlas\Mapper\Record;

class Field
{
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
     * Field constructor.
     * @param Record $record
     * @param string $column
     * @param mixed $value
     * @param array $prop
     */
    public function __construct(Record &$record, string $column, $value = null, array $prop = [])
    {
        $this->record = &$record;
        $this->value = $value;
        $this->column = $column;
        $this->prop = $prop;
    }

    public function set($value, $column = "")
    {
        $this->value = $value;
        $this->column = $column != "" ? $column : $this->column;
    }

    public function equalDatabase() : bool
    {
        if ($this->record->has($this->column)) {
            return $this->value === $this->record->$this->collumn;
        } else {
            return false;
        }
    }

    public function save()
    {
        $this->record->$this->column = $this->value;
    }
}
