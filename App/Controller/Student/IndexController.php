<?php

namespace App\Controller\Student;
use Context;
use App\Controller\Base;
use App\Response\Response;






class IndexController extends Base{
 
    public function IndexAction()
    {
 
        $sid = $this->params['sid']??1;

        return Context::get(Response::class)->success(
            'hello,world', 
            [
                'sid' => $sid,
                'context' => Context::$map
            ]
          );
    }
}