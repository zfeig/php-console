<?php 

namespace App\Service\User;

use App\Model\User\User;

class UserService {


    public static function FindOne($uid =0 ) {

        return User::where('id',intval($uid))->first();
    }

}