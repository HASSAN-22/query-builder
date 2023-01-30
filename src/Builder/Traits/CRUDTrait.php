<?php

namespace App\Builder\Traits;

use App\Builder\Exception\CrudException;
use App\Builder\SetterAndGetter\Values;


trait CRUDTrait
{

    /**
     * Insert data to database
     * @param array $data
     * @return bool|\Exception
     */
    public function create(array $data){
        $this->CrudException(__METHOD__,$data);
        $data = empty($data[0]) ? [$data] : $data;
        $valuesPart = '';
        $columns = [];
        foreach ($data as $item) {
            $columns = array_keys($item);
            $values = array_values($item);
            Values::merge($values);
            $valuesPart .= sprintf(" (%s),", implode(',', array_fill(0, count($values), '?')));
        }
        $query = sprintf("(%s) VALUES %s", implode(',',$columns),rtrim($valuesPart, ', '));
        $this->addSelect('select', []," INSERT INTO $this->table $query");
        return empty($this->execute());
    }

    /**
     * Update records in the database.
     * @param array $data
     * @param array $condition
     * @return bool
     * @throws CrudException
     */
    public function update(array $data,array $condition=[]){
        $this->CrudException(__METHOD__,$data,"The first parameter in the `:method' must contain values");
        foreach ($condition as $column=>$value){
            $this->where($column,'=',$value);
        }
        $update = '';
        foreach ($data as $column=>$item){
            Values::merge($item);
            $update .= " $column = ?, ";
        }
        $this->addSelect('select', []," UPDATE $this->table SET ".rtrim($update,', '));
        return empty($this->execute());
    }

    /**
     * Delete records from the database.
     * @param array $conditions
     * @return bool
     */
    public function delete(array $conditions = []){
        $this->addSelect('select', []," DELETE FROM $this->table ");
        foreach ($conditions as $column=>$value){
            $this->where($column,'=',$value);
        }
        return empty($this->execute());
    }

    /**
     * Call exception
     * @param string $method
     * @param array $args
     * @param $message
     * @return void
     * @throws CrudException
     */
    private function CrudException(string $method, array $args,$message='')
    {
        if (count($args) <= 0) {
            throw new CrudException($method,$message);
        }
    }
}
