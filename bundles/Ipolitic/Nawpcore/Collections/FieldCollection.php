<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\Ipolitic\Nawpcore\Collections;

use App\Ipolitic\Nawpcore\Components\Collection;
use App\Ipolitic\Nawpcore\Components\Field;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled;
use App\Ipolitic\Nawpcore\Interfaces\FieldInterface;
use App\Ipolitic\Nawpcore\Interfaces\ViewLoggerAwareInterface;
use App\Ipolitic\Nawpcore\Kernel;
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
     * @var Kernel
     */
    public $kernel;
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
     * @var callable[]
     */
    public $additionalCallbacks;

    /**
     * FieldCollection constructor.
     * @param Kernel $kernel
     * @param Record $record
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(Kernel &$kernel, Record &$record, array $input = [], int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        $this->kernel = &$kernel;
        $this->record = &$record;
        $this->recordClass = get_class($record);
        parent::__construct($input, $flags, $iterator_class);
    }

    /**
     * @param string $column
     * @param callable $callback
     */
    public function addAdditionalValidityCheck(string $column, callable $callback)
    {
        $this->additionalCallbacks[$column] = $callback;
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

        foreach ($this->record->getArrayCopy() as $k => $v) {
            if (!in_array($k, self::blackListFields)) {
                $className = $recordModelFields[$k][0];
                /**
                 * @var Field $field
                 */
                $field = new $className($this->kernel, $this->record, $k, $v, $recordModelFields[$k][1]);
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

    /**
     * @return bool
     */
    public function checkValidity() : bool
    {
        $result = true;
        /**
         * @var Field $field
         */
        foreach ($this as $k => $field) {
            if (!in_array($field->column, self::blackListFields)) {
                // proceed default check
                $fieldError = $field->checkValidity();
                if ($fieldError !== "") {
                    $field->prop["message"] = $fieldError;
                    $result = false;
                } else {
                    // proceed additional checks if one were given
                    if (isset($this->additionalCallbacks[$field->column])) {
                        $fieldError = $this->additionalCallbacks[$field->column]($field->value);
                        if ($fieldError !== "") {
                            $field->prop["message"] = $fieldError;
                            $result = false;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function equalDatabase(): bool
    {
        $equal = true;
        /**
         * @var Field $field
         */
        foreach ($this->getArrayCopy() as $k => $field) {
            if (!in_array($field->column, self::blackListFields)) {
                $equal = $equal && $field->equalDatabase();
            }
        }
        return $equal;
    }

    public function save()
    {
        /**
         * @var Field $v
         */
        foreach ($this->getArrayCopy() as $k => $v) {
            $v->save();
        }
    }

    public function getViews() : array
    {
        $output = [];
        /**
         * @var FieldInterface $v
         */
        foreach ($this->getArrayCopy() as $k => $v) {
            $output = array_merge($output, $v->getViews());
        }

        return [
            Form::class => [
                "html_elements" => $output
            ]
        ];
    }
}
