<?php

namespace App\Logic\Advt;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;


//slow than GetEbayTitleService, so not recommend
class BatchGetItemTitleService {
    public $urlPrefix = "https://www.ebay.%s/itm/%d";

    public $advtData;

    public $url;

    public function __construct($advtData){
        $this->advtData = $advtData;
    }

    public function  parseUrl($site,$itmid){

        $siteSUffix = 'com';

        switch($site) {
            case 'US':
                $siteSUffix = 'com';
                break;
            case 'UK':
                $siteSUffix = 'co.uk';
                break;
            case 'AU':
                $siteSUffix = 'com.au';
                break;
            case 'FR':
                $siteSUffix = 'fr';
                break;
            case 'DE':
                $siteSUffix = 'de';
                break;
            case 'ES':
                $siteSUffix = 'es';
                break;
            case 'IT':
                $siteSUffix = 'it';
                break;
        }

        $this->url = sprintf($this->urlPrefix, $siteSUffix , $itmid);
        $this->url .= "?".($siteSUffix == 'com' ? "lang=en-US&":"")."t=".time();
        return $this->url;
    }





    public function run() {

        $st = microtime(true);
        $client = new Client(['timeout' => 20 ]);

        foreach($this->advtData as $k => $v) {
            try{

                $uri =  $this->parseUrl($v['site'],$v['itm']);
                $promise[$k] = $client->getAsync($uri,[
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'
                ]); 
            } catch(\Exception $e) {
                echo "set getAsync:".$e->getMessage().PHP_EOL;
            }
        }

        $results = Utils::unwrap($promise);

        foreach($results as $k => $response) {
            try{
                $html = $response->getBody()->getContents();
                $this->advtData[$k]['title'] =  explode(" | ", explode("<title>", $html)[1])[0];
            } catch(\Exception $e) {
                echo "getResponse:".$e->getMessage().PHP_EOL;
            }
        }

        

        

        //othe rmethod for batch get
        // $requests = function ($total, $client) {
        //     foreach($this->advtData as $k => $v) {
        //         $uri = $this->parseUrl($v['site'],$v['itm']);            
        //         yield function () use ($uri, $client) {
        //             return $client->requestAsync('GET', $uri, ['User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36']);
        //         };
        //     }

        // };


        // $total = 10;
        // $pool = new Pool($client, $requests($total, $client), [
        //     'concurrency' => $total,
        //     'fulfilled'   => function (Response $response, $index) {
        //         // this is delivered each successful response
        //         // print_r(json_decode($response->getBody()->getContents(), true));  
        //         $html = $response->getBody()->getContents();
        //         $this->advtData[$index]['title'] =  explode(" | ", explode("<title>", $html)[1])[0];
        //     },
        //     'rejected'    => function (\Exception $reason, $index) {
        //         // this is delivered each failed request
        //         echo $reason->getMessage().PHP_EOL;
        //     },
        // ]);
        
        // // Initiate the transfers and create a promise
        // $promise = $pool->promise();
        // $promise->wait();


        $this->fmt();
        
        echo "total cost:".round(microtime(true)-$st,3)." second".PHP_EOL;
    }



    public function fmt() {
        $result = '';
        foreach ($this->advtData as $key => $value) {
            $result .= $value['site'] . '|' . $value['itm'] . '|' . $value['title'] . "\n";
        }

        echo $result.PHP_EOL;
        return $result;
    }

}


?>