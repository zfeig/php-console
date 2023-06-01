<?php 

namespace App\Model\User;

use Carbon\Carbon;
use App\Model\AbstractModel;


/**
 * @property int $id
 * @property string $name
 * @property string $cn_name
 * @property string $passwd
 * @property int $gid
 * @property int $parent_id
 * @property string $phone
 * @property int $status
 * @property Carbon $create_time 
 * @property Carbon $create_time
 */
class User extends AbstractModel{

    public $table = 'user';


    public  $guarded = [];


    public  $casts = ['id' => 'integer', 'status' => 'integer', 'parent_id' => 'integer', 'gid' => 'integer',  'create_time' => 'datetime', 'update_time' => 'datetime'];

 
}