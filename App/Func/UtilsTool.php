<?php 

namespace App\Func;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class UtilsTool {

    public static function uReplace($desc) {
        $desc = preg_replace('/<(.*?)>/','‖',$desc);//标签替换成占位符
        return $desc;
    }


    public static function timeCost($stime, $etime, $msg)
    {
        echo  sprintf("执行%s,共耗时%s 秒" . PHP_EOL, $msg, round($etime - $stime, 3));
    }


    public static function singelGet($url){
        try{

            $res = ['status' => 0, 'data' => [], 'msg' => ''];
            $client = new Client(['timeout' => 10]);
            $request = new Request('GET', $url,[
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            ]);

            $promise = $client->sendAsync($request)->then(function ($response) use(&$res) 
            {
                $res['data'] = $response->getBody()->getContents();
                $res['status'] = 1;
                
            },function(RequestException $err) use (&$res) {
                 $msg = mb_substr($err->getResponse()->getBody(),0,100);
                 echo "========================".$msg.PHP_EOL;
                 $res['msg'] = $msg;
            });

            $promise->wait();
        } catch( \Exception $e) {
           
        }       
        return $res;
    }


    public static function getSiteUri($siteCode,$itemId)
    {
     
        switch(strtoupper($siteCode))
        {
            case 'UK':
                $uri = "https://www.ebay.co.uk";
                break;
            case 'AU':
                $uri = "https://www.ebay.com.au";
                break;
            case 'CA':
                $uri = "https://www.ebay.ca";
                break;
            case 'FR':
                $uri = "https://www.ebay.fr";
                break;
            case 'DE':
                $uri = "https://www.ebay.de";
                break;
            case 'ES':
                $uri = "https://www.ebay.es";
                break;       
            case 'IT':
                $uri = "https://www.ebay.it";
                break; 
            default:
            $uri = 'https://www.ebay.com';
        }

        return sprintf("%s/itm/%d",$uri,$itemId);
    }
}