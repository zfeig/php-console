<?php

namespace App\Task;

use \MongoDB\BSON\ObjectId;

class BaseTask{
    
    public $table;

   
    public static function newObjectId($id) {
        return new ObjectId($id);
    }

}