<?php

namespace App\Console\Advt;

use App\Func\UtilsTool;
use App\Console\Base;


class GetUkCategory extends Base{

    public function run() {
        $cid = $this->params['cid']??183728;
        $url =  sprintf("https://www.ebay.co.uk/b/xx/%d",$cid);
        // $uriList =  UtilsTool::getRedirectInfo($url);
        $uriList =  UtilsTool::getRedirectUrl($url);
    
        var_dump($uriList);
    }

}