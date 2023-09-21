<?php

namespace App\Controller\Index;
use Context;
use App\Controller\Base;
use App\Response\Response;






class IndexController extends Base{
 
    public function IndexAction()
    {
 
        $uid = $this->params['uid']??1;

        return Context::get(Response::class)->success(
          'hello,world', 
          [
              'uid' => $uid,
              'context' => Context::$map
          ]
        );

    }
}