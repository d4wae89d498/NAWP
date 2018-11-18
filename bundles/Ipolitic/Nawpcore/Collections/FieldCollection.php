<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\Ipolitic\Nawpcore\Collections;

use App\Ipolitic\Nawpcore\Components\{Collection, Field, ViewLogger};
use App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled;
use App\Ipolitic\Nawpcore\Interfaces\FieldInterface;
use App\Ipolitic\Nawpcore\Interfaces\ViewLoggerAwareInterface;
use App\Ipolitic\Nawpcore\Views\Form;
use App\Server\Models\ModelsFields;
use Atlas\Mapper\Record;

/**
 * Class ControllerCollection
 * Provide storage and match for a controller list
 * @package App\Ipolitic\Nawpcore
 */
class FieldCollection extends Collection implements FieldInterface, ViewLoggerAwareInterface
{
    public const blackListFields = ["row_id", "inserted_at", "updated_at"];
    /**
     * @var ViewLogger|null
     */
    public $viewLogger;
    /**
     * @var string
     */
    public $recordClass;
    /**
     * @var Record
     */
    public $record;

    /**
     * FieldCollection constructor.
     * @param Record $record
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(Record $record,  array $input = [], int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        $this->record = $record;
        $this->recordClass = get_class($record);
        parent::__construct($input, $flags, $iterator_class);
    }

    /**
     * @throws SetViewLoggerNotCalled
     */
    public function fill(): void
    {
        if ($this->viewLogger === null) {
            throw new SetViewLoggerNotCalled();
        }

        $recordModelFields = ModelsFields::getModelsFields()[$this->recordClass];
        $fields =  (
            // remove null values
            array_filter(
            // convert declared class name to controller instance if match, or null value
                array_map(
                    function (string $className) use (&$arguments) {
                        $componentName = "Fields";
                        // if a valid $className was given, we continue
                        if (stristr($className, "\\" . ucfirst($componentName) . "\\") !== false) {
                            // if the $arguments array is not empty, we simply instantiate $componentName
                            return $className;
                        }
                        // else we stop with a null that will be filtered later
                        else {
                            return null;
                        }
                    },
                    // get all declared class names @see http://php.net/manual/pl/function.get-declared-classes.php
                    \get_declared_classes()
                 )
            )
        );

        foreach ($this->record->getArrayCopy() as $k => $v)
        {
            if (!in_array($k, self::blackListFields)) {
                $className = $recordModelFields[$k][0];
                /**
                 * @var Field $field
                 */
                $field = new $className($this->record, $k, $v, $recordModelFields[$k][1]);
                $this->append($field);
            }
        }
    }
    /**
     * @param ViewLogger $viewLogger
     * @return FieldCollection
     */
    public function setViewLogger(ViewLogger &$viewLogger): FieldCollection
    {
        $this->viewLogger = &$viewLogger;
        return $this;
    }

    public function checkValidity()
    {
        // TODO: Implement checkValidity() method.
    }

    public function equalDatabase(): bool
    {
        // TODO: Implement equalDatabase() method.
    }

    public function save()
    {
        /**
         * @var Field $v
         */
        foreach($this->getArrayCopy() as $k => $v) {
            $v->save();
        }
    }

    public function getViews() : array
    {
        $output = [];
        /**
         * @var FieldInterface $v
         */
        foreach($this->getArrayCopy() as $k => $v) {
            $output = array_merge($output,  $v->getViews());
        }

        return [
            Form::class => [
                "html_elements" => $output
            ]
        ];
    }
}
