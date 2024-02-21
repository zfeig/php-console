<?php 

namespace App\Console\Advt;


use App\Func\Config;
use App\Console\Base;
use App\Model\User\User;
use App\Service\User\UserService;
use Illuminate\Database\Capsule\Manager as DB;


class TestDB extends Base{


    public function run() {

       
        $db = Config::get('db');
        var_dump($db);

        $group = DB::table('group')->where('id','>',1)->get();
        print_r($group);

        // $user = $this->getUserInfoByModel($this->params['uid']?:1);
        $user = $this->getUserInfoByService($this->params['uid']??1);

        print_r($user);


        //test cahce
        // $cateData = $this->redis->getset('test',123);
        // var_dump($cateData);

    }


    public function getUserInfoByModel($uid=1) {

        $userInfo = User::where('id',$uid)->first();
        return  [
            'uid' => $userInfo->id,
            'uname' => $userInfo->cn_name
        ];
    }


    public function getUserInfoByService($uid) {

        $userInfo = UserService::FindOne($uid);

        return  [
            'uid' => $userInfo->id,
            'uname' => $userInfo->cn_name
        ];
    }
}
