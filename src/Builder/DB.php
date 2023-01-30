<?php

namespace App\Builder;

use App\Builder\Exception\BetweenException;
use App\Builder\Exception\SortException;
use App\Builder\SetterAndGetter\Join;
use App\Builder\SetterAndGetter\Select;
use App\Builder\SetterAndGetter\Values;
use App\Builder\SetterAndGetter\Wheres;
use App\Builder\Traits\CRUDTrait;
use App\Builder\Traits\HavingTrait;
use App\Builder\Traits\JoinTrait;
use App\Builder\Traits\WhereTrait;
use App\Database\Connection;


class DB
{
    use WhereTrait, HavingTrait, JoinTrait, CRUDTrait;

    private string $table;
    private $connection;
    private $stmt;

    /**
     * @param string $driver
     */
    public function __construct(string $driver){
        $this->connection = Connection::connect($driver::setInstance());
    }

    /**
     * Execute sql query
     * @param bool $isDebug
     * @return mixed
     */
    protected function execute(bool $single=false, bool $isDebug=false){
        BuildQuery::BuildQuery();
        $this->stmt = $this->connection->prepare(BuildQuery::getQuery());
        foreach (Values::get() as $key=>$value){
            $this->stmt->bindValue(($key+1),$value, is_string($value) ? \PDO::PARAM_STR : \PDO::PARAM_INT);
        }
        $this->stmt->execute();
        if($isDebug){
            return 0;
        }
        return $single ? $this->stmt->fetch() : $this->stmt->fetchAll();
    }

    /**
     * @return mixed
     */
    public function debug(){
        $this->execute(false,true);
        return $this->stmt ? $this->stmt->debugDumpParams() : null;
    }

    /**
     * @return void
     */
    public function beginTransaction(){
        $this->connection->beginTransaction();
    }

    /**
     * @return void
     */
    public function rollback(){
        $this->connection->rollBack();
    }

    /**
     * @return void
     */
    public function commit(){
        $this->connection->commit();
    }

    /**
     * Get database name
     * @return string
     */
    public function getDbName(){
        $this->emptyQuery();
        return $this->connection->query('SELECT database()')->fetchColumn();
    }

    /**
     * Get current id fro empty table
     * @param string $table
     * @return mixed|null
     */
    public function currentId(string $table){
        $this->emptyQuery();
        $db = $this->getDbName();
        $this->addSelect('select', ["*"],"SELECT `AUTO_INCREMENT` AS id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table'");
        return $this->execute(true);
    }

    /**
     * Get the ID of the last recorded record
     * @return mixed
     */
    public function lastInsertId(){
        return $this->connection->lastInsertId();
    }



    /**
     * Get the database table to perform the operation
     * @param string $table
     * @return $this
     */
    public function table(string $table){
        $this->emptyQuery();
        $this->table = $table;
        $this->addSelect('select', ["*"],"SELECT :fillable FROM `$table` ");
        return $this;
    }

    public function emptyQuery(){
        BuildQuery::emptyQuery();
        Values::empty();
        Select::empty();
        Join::empty();
        Wheres::empty();
    }

    /**
     * Get all data in database
     * @param ...$args -> column name like name, password, ....
     * @return mixed
     */
    public function get(...$args){
        $args = count($args) > 0 ? $args : ['*'];
        $this->addSelect('select', $args,"SELECT :fillable FROM `$this->table` ",1);
        return $this->execute();
    }

    /**
     * Get all data in database
     * @return mixed|null
     */
    public function all(){
        return $this->execute();
    }

    /**
     * Execute queries for a single or many record by ID.
     * @param ...$args
     * @return mixed|null
     */
    public function find(...$args){
        $this->whereIn('id',$args);
        return $this->execute(count($args) == 1);
    }

    /**
     * Get first record
     * @return mixed|null
     */
    public function first(){
        $this->take(1);
        return $this->execute(true);
    }

    /**
     * Get last record
     * @return mixed|null
     */
    public function last(){
        $this->orderBy()->take(1);
        return $this->execute(true);
    }

    /**
     * Execute custom query
     * @param string $sql
     * @return mixed|null
     */
    public function newQQuery(string $sql){
        if(empty($sql)){
            return null;
        }
        $this->addSelect('select', [],$sql);
        return $this->execute();
    }

    /**
     * Retrieve the "count" result of the query.
     * @return mixed|null
     */
    public function count(){
        $this->addSelect('select', ['*'],"SELECT COUNT(:fillable) as Count FROM $this->table ",1);
        return $this->execute(true);
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     * @param string $column
     * @return mixed
     */
    public function latest(string $column='created_at'){
        return $this->orderBy($column);
    }

    /**
     * Add a raw "order by" clause to the query.
     * @param string $column
     * @param string $flag
     * @param $isRandom
     * @return $this
     * @throws SortException
     */
    public function orderBy(string $column='id', string $flag="DESC", $isRandom=false){
        $flag = strtoupper($flag);
        if(!in_array($flag,['DESC','ASC'])){
            throw new SortException();
        }

        $column = $isRandom ? ['RAND()',$column] : [$column];
        $this->addSelect('orderBy',$column," ORDER BY :fillable $flag ", 3, true);
        return $this;
    }


    /**
     * Get random records
     * @return $this
     * @throws SortException
     */
    public function rand(){
        return $this->orderBy('id','DESC',true);
    }


    /**
     * Add a "group by" clause to the query.
     * @param ...$args
     * @return $this
     */
    public function groupBy(...$args){
        $this->addSelect('groupBy',$args," GROUP BY :fillable ", 2, true);
        return $this;
    }


    /**
     * Set the "limit" value of the query.
     * @param int $offset
     * @param int $limit
     * @return $this
     */
    public function limit(int $offset=0, int $limit=10){
        $this->addSelect('limit', [], " LIMIT $offset, $limit ",4,true);
        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     * @param int $limit
     * @return $this
     */
    public function take(int $limit=10){
        return $this->limit(0,$limit);
    }

    /**
     * Retrieve the maximum value of a given column.
     * @param $column
     * @return mixed|null
     */
    public function max($column)
    {
        $this->addSelect('select', [$column],"SELECT MAX(:fillable) as Max FROM `$this->table` ",1);
        return $this->execute(true);
    }

    /**
     * Retrieve the minimum value of a given column.
     * @param $column
     * @return mixed|null
     */
    public function min($column)
    {
        $this->addSelect('select', [$column],"SELECT MIN(:fillable) as Min FROM `$this->table` ",1);
        return $this->execute(true);
    }

    /**
     * Retrieve the sum of the values of a given column.
     * @param $column
     * @return mixed|null
     */
    public function sum($column)
    {
        $this->addSelect('select', [$column],"SELECT SUM(:fillable) as Sum FROM `$this->table` ",1);
        return $this->execute(true);
    }

    /**
     * Retrieve the average of the values of a given column.
     * @param $column
     * @return mixed|null
     */
    public function avg($column)
    {
        $this->addSelect('select', [$column],"SELECT AVG(:fillable) as Avg FROM `$this->table` ",1);
        return $this->execute(true);
    }

    /**
     * Maintains select queries
     * @param string $ype
     * @param array $column
     * @param string $query
     * @param int $position
     * @param bool $append
     * @return void
     */
    private function addSelect(string $ype, array $column, string $query='', int $position=0, bool $append=false){
        Select::set([
            'type'=>$ype,
            'query'=>$query,
            'column'=>$column,
            'append'=>$append,
            'position'=>$position,
        ]);
    }

    /**
     * Maintains where queries
     * @param string $column
     * @param string $operator
     * @param $data
     * @param string $boolean
     * @param $type
     * @param bool $wrapParentheses
     * @return void
     */
    private function addWhere(string $column, string $operator, $data,string $boolean, $type='WHERE', bool $wrapParentheses=false){
        Wheres::set([
            'column'=>$column,
            'operator'=>$operator,
            'value'=>$data,
            'boolean'=>$boolean,
            'wrapParentheses'=>$wrapParentheses,
        ],$type);
    }

    /**
     * Call exception
     * @param array $data
     * @return void
     * @throws BetweenException
     */
    private function betweenException(string $method, array $data): void
    {
        if (count($data) != 2) {
            throw new BetweenException($method);
        }
    }
}