<?php 
/**
 * @sedc 通过excel提供site,item信息，批量获取广告标题
 */
namespace App\Console\Advt;

include_once '../Base.php';

use App\Console\Base;

use App\Lib\ExcelToolService;
use App\Logic\Advt\GetEbayTitleService;
use App\Func\UtilsTool;

class getBatchTitle extends Base{


    public function run() {

        $st = microtime(true);

        $fileName = $this->params['f']??'itms.xlsx';

        $tool_service = new ExcelToolService();
        $data = $tool_service->readFile($fileName);

        
      
        if (empty($data['data']) || $data['ack'] !='success') {
            throw new \Exception("can't get excel data!");
        }

        
        $obj = new GetEbayTitleService($data['data']);
        $obj->batchGetEbayTitle();

        UtilsTool::timeCost($st,microtime(true),__METHOD__);

    }

}


$obj = new getBatchTitle($argv);
$obj->run();