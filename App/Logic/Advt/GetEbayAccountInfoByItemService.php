<?php 
/**
*@desc 指定item获取广告账号信息
*
**/
namespace App\Logic\Advt;

use App\Func\UtilsTool;


class GetEbayAccountInfoByItemService {


	public $url;
    
    public function getPageAccount($itemId) {

		try{

            $accountInfo = [
				'item_id' => $itemId,
				'account' => ''
			];
			
            $this->url = sprintf("https://www.ebay.com/itm/%s",$itemId);

			$res = UtilsTool::singelGet($this->url);

			if ($res['status'] == 0 || empty($res['data'])) {
				throw new \Exception($res['msg']);
			}

			//https://www.ebay.com/usr/lingyoucraft?_trksid=p2047675.m3561.l2559 data-testid=ux-action
			$regex = "#https://www.ebay.com/usr/(.*)+?#";
			preg_match($regex,$res['data'],$links);
			
			if(!empty($links)) {
				$links = explode('?',$links[0]);
				$account = explode('/',$links[0]);
				$account = end($account);
				echo sprintf("item:%s account:%s\n",$itemId,$account);

				$accountInfo['account'] = $account;
			} else{
				throw new \Exception("empty links!");
			}
		
		} catch(\Throwable $e) {
			echo $e->getMessage().PHP_EOL;
		}
		return $accountInfo;
    }


}








