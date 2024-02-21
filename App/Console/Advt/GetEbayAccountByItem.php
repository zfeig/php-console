<?php 
/**
*@desc 检查指定item获取广告账号信息
*@exec   php  console.php r=Advt/GetEbayAccountByItem f=itm-for-account.xlsx
*
**/
namespace App\Console\Advt;


use App\Console\Base;
use App\Logic\Advt\GetEbayAccountInfoByItemService;
use App\Lib\ExcelToolService;
use App\Func\UtilsTool;

class GetEbayAccountByItem extends Base{
       

    public function run() {

        $st = microtime(true);

        $fileName = $this->params['f']??'itm-for-account.xlsx';

        $tool_service = new ExcelToolService();
        $data = $tool_service->readFile($fileName);

        
      
        if (empty($data['data']) || $data['ack'] !='success') {
            throw new \Exception("can't get excel data!");
        }

        
        $advtData = $data['data'];

        $obj = new GetEbayAccountInfoByItemService();

        foreach($advtData as $k => $v) {

            try{

              $accountInfo = $obj->getPageAccount($v['item_id']);

              $advtData[$k]['account'] = $accountInfo['account']??'';

            }catch(\Exception $e) {
                echo $e->getMessage().PHP_EOL;
                $advtData[$k]['account'] = "#N/A";
            }
        }

        print_r($advtData);

        UtilsTool::timeCost($st,microtime(true),__METHOD__);

    }
}