<?php

namespace App\Builder\Traits;

trait HavingTrait
{
    private string $type='HAVING';

    /**
     * Add a "having" clause to the query.
     * @param string $column
     * @param string $operator
     * @param string $data
     * @return $this
     */
    public function having(string $column, string $operator, string $data){
        $this->addWhere($column,$operator,$data,'AND',$this->type);
        return $this;
    }

    /**
     * Add an "or having" clause to the query.
     * @param string $column
     * @param string $operator
     * @param string $data
     * @return $this
     */
    public function orHaving(string $column, string $operator, string $data){
        $this->addWhere($column,$operator,$data,'OR',$this->type);
        return $this;
    }

    /**
     * Add a "having null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function havingNull(string $column){
        $this->addWhere($column,'IS NULL','','AND',$this->type);
        return $this;
    }

    /**
     * Add an "or having null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function orHavingNull(string $column){
        $this->addWhere($column,'IS NULL','','OR',$this->type);
        return $this;
    }

    /**
     * Add a "having not null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function havingNotNull(string $column){
        $this->addWhere($column,'IS NOT NULL','','AND',$this->type);
        return $this;
    }

    /**
     * Add an "or having not null" clause to the query.
     * @param string $column
     * @return $this
     */
    public function orHavingNotNull(string $column){
        $this->addWhere($column,'IS NOT NULL','','OR',$this->type);
        return $this;
    }

    /**
     * Add a "having in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function havingIn(string $column, array $data){
        $this->addWhere($column,'IN',$data,'AND', $this->type,true);
        return $this;
    }

    /**
     * Add an "or having in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function orHavingIn(string $column, array $data){
        $this->addWhere($column,'IN',$data,'OR', $this->type,true);
        return $this;
    }

    /**
     * Add a "having not in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function havingNotIn(string $column, array $data){
        $this->addWhere($column,'NOT IN',$data,'AND', $this->type,true);
        return $this;
    }

    /**
     * Add an "or having not in" clause to the query.
     * @param string $column
     * @param array $data
     * @return $this
     */
    public function orHavingNotIn(string $column, array $data){
        $this->addWhere($column,'NOT IN',$data,'OR', $this->type,true);
        return $this;
    }

    /**
     * Add a having between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function havingBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'BETWEEN',$data,'AND', $this->type);
        return $this;
    }

    /**
     * Add an or having between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function orHavingBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'BETWEEN',$data,'OR', $this->type);
        return $this;
    }

    /**
     * Add a having not between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function havingNotBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'NOT BETWEEN',$data,'AND', $this->type);
        return $this;
    }

    /**
     * Add an or having not between statement to the query.
     * @param string $column
     * @param array $data
     * @return $this
     * @throws \App\Builder\Exception\BetweenException
     */
    public function orHavingNotBetween(string $column, array $data){
        $this->betweenException(__METHOD__,$data);
        $this->addWhere($column,'NOT BETWEEN',$data,'OR', $this->type);
        return $this;
    }
}