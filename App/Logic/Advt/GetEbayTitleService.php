<?php

namespace App\Logic\Advt;

use App\Func\UtilsTool;



class GetEbayTitleService  {
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


    public function getEbayTitle($data){

        $this->parseUrl($data['site'],$data['itm']);

        $html = $this->getEbayInfo();

        usleep(10);

        @$title = explode(" | ", explode("<title>", $html)[1])[0];
        return $title;
    }


    public function getEbayInfo() {
        
        $count = 1;
        $html = '';
        while (true) {
            
            if($count > 1) {
                echo "第".$count."次请求".$this->url.PHP_EOL;
            }
        
           
            $pageInfo = UtilsTool::singelGet($this->url);

            if(!empty($pageInfo['data']) && $pageInfo['status'] == 1) {

                $html = $pageInfo['data'];
                break;
            }

            if($count >= 3) {
                break;
            }

            $count++;
        }

        return $html;

    }


   


    public function batchGetEbayTitle() {

        $st = microtime(true);

        foreach ($this->advtData as $key => $value) {
            try{
                $this->advtData[$key]['title'] = $this->getEbayTitle($value);
            }catch (\Exception $e){
                echo "get error:".$e->getMessage();
            }
        }

        $this->fmter();

        echo "total cost:".round(microtime(true)-$st,3)." second".PHP_EOL;
    }

    public function fmter() {
        $result = '';
        foreach ($this->advtData as $key => $value) {
            $result .= $value['site'] . '|' . $value['itm'] . '|' . $value['title'] . "\n";
        }

        echo $result.PHP_EOL;
        return $result;
    }

}


?>