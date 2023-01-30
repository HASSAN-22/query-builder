<?php

namespace App\Builder\Traits;


trait WhereTrait
{

    /**
     * Add a basic where clause to the query.
     * @param string $column
     * @param string $operator
     * @param string $data
     * @return $this
     */
    public function where(string $column, string $operator, string $data){
        $this->addWhere($column,$operator,$data,'AND');
        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     * @param string $column
     * @param string $operator
     * @param string $data
     * @return $this
     */
    public function orWhere(string $column, string $operator, string $data){
        $this->addWhere($column,$operator,$data,'OR');
        return $this;
    }

    /**
     * Add a "where null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function whereNull(string $column){
        $this->addWhere($column,'IS NULL','','AND');
        return $this;
    }

    /**
     * Add an "or where null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function orWhereNull(string $column){
        $this->addWhere($column,'IS NULL','','OR');
        return $this;
    }

    /**
     * Add a "where not null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function whereNotNull(string $column){
        $this->addWhere($column,'IS NOT NULL','','AND');
        return $this;
    }

    /**
     * Add an "or where not null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function orWhereNotNull(string $column){
        $this->addWhere($column,'IS NOT NULL','','OR');
        return $this;
    }

    /**
     * Add a "where in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function whereIn(string $column, array $data){
        $this->addWhere($column,'IN',$data,'AND', 'WHERE',true);
        return $this;
    }

    /**
     * Add an "or where in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function orWhereIn(string $column, array $data){
        $this->addWhere($column,'IN',$data,'OR', 'WHERE',true);
        return $this;
    }

    /**
     * Add a "where not in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function whereNotIn(string $column, array $data){
        $this->addWhere($column,'NOT IN',$data,'AND', 'WHERE',true);
        return $this;
    }

    /**
     * Add an "or where not in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function orWhereNotIn(string $column, array $data){
        $this->addWhere($column,'NOT IN',$data,'OR', 'WHERE',true);
        return $this;
    }

    /**
     * Add a where between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function whereBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'BETWEEN',$data,'AND');
        return $this;
    }

    /**
     * Add an or where between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function orWhereBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'BETWEEN',$data,'OR');
        return $this;
    }

    /**
     * Add a where not between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function whereNotBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'NOT BETWEEN',$data,'AND');
        return $this;
    }

    /**
     * Add an or where not between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function orWhereNotBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'NOT BETWEEN',$data,'OR');
        return $this;
    }

    /**
     * Determine if any rows exist for the current query.
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function exists(string $table, string $condition){
        $this->addWhere($table,'EXISTS',$condition,'AND');
        return $this;
    }

    /**
     * Add an or exists statement to the query.
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function orExists(string $table, string $condition){
        $this->addWhere($table,'EXISTS',$condition,'OR');
        return $this;
    }

    /**
     * Add a not exists statement to the query.
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function notExists(string $table, string $condition){
        $this->addWhere($table,'NOT EXISTS',$condition,'AND');
        return $this;
    }

    /**
     * Add an or not exists statement to the query.
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function orNotExists(string $table, string $condition){
        $this->addWhere($table,'NOT EXISTS',$condition,'OR');
        return $this;
    }


}