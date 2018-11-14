<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 3:45 PM
 */

namespace App\Ipolitic\Nawpcore\Components;


use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperQueryFactory;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Orm\Atlas;
use Atlas\Orm\Transaction\AutoCommit;
use Atlas\Orm\Transaction\Transaction;
use Atlas\Pdo\ConnectionLocator;
use Atlas\Table\TableLocator;

class SQL extends Atlas
{
    /**
     * @var Queries
     */
    public $queries = [];

    /**
     * SQL constructor.
     * @param mixed ...$args
     */
    public function __construct( ... $args) {
        $this->queries = new Queries();
        $transactionClass = AutoCommit::CLASS;
        $end = end($args);
        if (is_string($end) && is_subclass_of($end, Transaction::CLASS)) {
            $transactionClass = array_pop($args);
        }
        $connectionLocator = ConnectionLocator::new(...$args);
        $tableLocator = new TableLocator(
            $connectionLocator,
            new MapperQueryFactory()
        );
       parent::__construct(new MapperLocator($tableLocator), new $transactionClass($connectionLocator));
    }

    /**
     * @param Record $record
     */
    public function insert(Record $record): void
    {
        $this->queries->append('<pre><code class="sql hljs">insert into \''
            . $record->getMapperClass() ."' VALUES ('".json_encode($record)."')". '</code></pre>');
        parent::insert($record);
        return;
    }

    /**
     * @param Record $record
     */
    public function delete(Record $record): void
    {
        $this->queries->append('<pre><code class="sql hljs">delete from \'' .
            $record->getMapperClass() ."' where ('".json_encode($record)."')". '</code></pre>');
        parent::delete($record);
        return;
    }

    /**
     * @param Record $record
     */
    public function update(Record $record): void
    {
        $this->queries->append('<pre><code class="sql hljs">' ."update table '"
            . $record->getMapperClass() ."' where ('".json_encode($record)."')". '</code></pre>');
        parent::update($record);
        return;
    }

    /**
     * @param string $mapperClass
     * @param array $whereEquals
     * @return MapperSelect
     */
    public function select(string $mapperClass, array $whereEquals = []): MapperSelect
    {
        $result = parent::select($mapperClass, $whereEquals);
        $query = $result->getStatement();
        if (strlen($query) < 200) {
            $query = str_replace(PHP_EOL,"",str_replace("<br>", "", $query));
        }
        var_dump($query);
        $this->queries->append("<pre><code class=\"sql hljs\">{$query}</code></pre>");
        return $result;
    }
}