<?php 
/**
*@desc 检查指定站点+item获取广告在线状态
*
**/
namespace App\Console\Advt;

include_once '../Base.php';

use App\Logic\Advt\GetEbayAdvtStatusService;
use App\Lib\ExcelToolService;
use App\Func\UtilsTool;
use App\Console\Base;

class getSiteItemCancelStatus extends Base{
       

    public function run() {

        $st = microtime(true);

        $fileName = $this->params['f']??'itm-for-status.xlsx';
        
        $tool_service = new ExcelToolService();
        $data = $tool_service->readFile($fileName);

        
      
        if (empty($data['data']) || $data['ack'] !='success') {
            throw new \Exception("can't get excel data!");
        }

        
        $advtData = $data['data'];

        $obj = new GetEbayAdvtStatusService();

        foreach($advtData as $k => $v) {

            try{

              $advtStatus = $obj->getAdvtStatus($v['site_code'],$v['item_id']);

              $advtData[$k]['status'] = $advtStatus;

            }catch(\Exception $e) {
                echo $e->getMessage().PHP_EOL;
                $advtData[$k]['status'] = "#N/A";
            }
        }

        print_r($advtData);

        UtilsTool::timeCost($st,microtime(true),__METHOD__);

    }
}


$obj = new getSiteItemCancelStatus($argv);
$obj->run();