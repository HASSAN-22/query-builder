<?php

namespace App\Builder\Traits;

use App\Builder\SetterAndGetter\Join;

trait JoinTrait
{

    /**
     * Add a inner join clause to the query.
     * @param string $table
     * @param string $rCondition
     * @param string $lCondition
     * @return $this
     */
    public function innerJoin(string $table, string $rCondition, string $lCondition){
        $this->addJoin('INNER',$table,$rCondition,$lCondition);
        return $this;
    }

    /**
     * Add a left join to the query.
     * @param string $table
     * @param string $rCondition
     * @param string $lCondition
     * @return $this
     */
    public function leftJoin(string $table, string $rCondition, string $lCondition){
        $this->addJoin('LEFT',$table,$rCondition,$lCondition);
        return $this;
    }

    /**
     * Add a right join to the query.
     * @param string $table
     * @param string $rCondition
     * @param string $lCondition
     * @return $this
     */
    public function rightJoin(string $table, string $rCondition, string $lCondition){
        $this->addJoin('RIGHT',$table,$rCondition,$lCondition);
        return $this;
    }

    /**
     * Add a "cross join" clause to the query.
     * @param string $table
     * @return $this
     */
    public function crossJoin(string $table){
        $this->addJoin('CROSS',$table,'','');
        return $this;
    }

    /**
     * Maintains join queries
     * @param string $type
     * @param string $table
     * @param string $rCondition
     * @param string $lCondition
     * @return void
     */
    private function addJoin(string $type, string $table, string $rCondition, string $lCondition){
        Join::set([
            'type'=>$type,
            'table'=>$table,
            'right'=>$rCondition,
            'left'=>$lCondition
        ]);
    }
}