<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model {

    public function getTableName(): string
    {
        $builder = self::query();
        return $builder->getQuery()->getGrammar()->wrapTable($builder->getQuery()->from);
    }
}