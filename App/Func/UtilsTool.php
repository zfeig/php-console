<?php 

namespace App\Func;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
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

            $res = ['status' => 0, 'data' => [], 'msg' => '', 'headers' => []];
            $client = new Client(['timeout' => 10]);
            $request = new Request('GET', $url,[
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
                'allow_redirects' => true
            ]);

            $promise = $client->sendAsync($request)->then(function ($response) use(&$res) 
            {
                $res['data'] = $response->getBody()->getContents();
                
                var_dump($response->getHeader('location'));

                foreach ($response->getHeaders() as $k => $v) {
                    $res['headers'][strtolower($k)] = $v;
                }

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



    
    public static function getRedirectInfo($uri) {

        $uriList = "";

        $client = new Client([
            'allow_redirects'=> true
        ]);

        $client->get(
            $uri,
            [
                'on_stats' => function (TransferStats $stats) use($uri,&$uriList) {
                   
                    $redirctUri = (string) $stats->getEffectiveUri();

                    if ( $redirctUri != $uri )
                    {
                        echo "redirect url is: ".$redirctUri.PHP_EOL;
                        $uriList = strval($redirctUri);
                    }
                }
            ]
        );

        $uriList = explode('_',$uriList);
        $uriList = end($uriList);

        return $uriList;
    }




    public static function getRedirectUrl($uri) {
        
        $client = new Client([
            'allow_redirects' => ['track_redirects' => true],
        ]);


        $response = $client->request('GET', $uri);
        $headersRedirect = $response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER);

        $uri = $headersRedirect[0]??'';
        $uri = explode('_',$uri);
        $uri = end($uri);
        
        return $uri;
    }
}