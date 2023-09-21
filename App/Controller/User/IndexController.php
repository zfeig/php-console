<?php

namespace App\Controller\User;

use Context;
use App\Controller\Base;
use App\Service\Mongo\ProductService;
use App\Service\User\UserService;
use App\Response\Response;


use Illuminate\Database\Capsule\Manager as DB;


class IndexController extends Base{

    public function IndexAction()
    {

        $uid = $this->params['uid']??1;

        return Context::get(Response::class)->success(
            '获取成功',
            [
                'params' => $this->params,
                'context' => Context::$map,
                'group' => DB::table('group')->where('id','>',1)->get(),
                'mongo' => (new ProductService())->getConf(),
                'user' => UserService::FindOne($uid)
            ]
        );

    }
}
