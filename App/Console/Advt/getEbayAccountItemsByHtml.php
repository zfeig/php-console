<?php 
/**
*@desc 通过本地账号网页数据获取广告item信息
*
**/
namespace App\Console\Advt;

include_once '../Base.php';

use App\Console\Base;

class GetEbayAccountItemsByHtml  extends Base{

	
	public $linkArr = [];


    public $outPath;


    public $files;



	public function run() {

        try{
            $this->initData();
            foreach ($this->files as $k => $v) {

                echo "即将处理文件：".basename($v).PHP_EOL;

                $this->getItmByFile($v);
            }
            $this->writeFile();
            return $this->linkArr;

        } catch(\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
	}




    public function initData() {

        //设置输出文件
        $this->setOutPath();

        //设置数据源参数

        $this->files = explode(',',$this->params["f"]);

   

        if (empty($this->files)) {
            throw new \Exception('invalis  parasms');
        }

        array_walk($this->files,function(&$v,$k) {
            $v = sprintf("%s/%s",$this->getStaticPath(),$v);
        });

    }


    /**
     * 获取静态目录
     */
    public function getStaticPath() {
        return sprintf("%s/statics/",$this->basePath);
    }



    /**
     * 设置输出文件
     */
    public function setOutPath() {
        $this->outPath = sprintf("%s/statics/%s",$this->basePath,'idealsgarden.csv');

        echo $this->outPath.PHP_EOL;
    }




	public function getItmByFile($file) {
       
        //获取本地数据
        $html = file_get_contents($file);


        preg_match_all("/Item: \d{12}/", $html, $links);

        foreach ($links[0] as $k => $v) {
            $v = str_replace('Item: ','',$v);
            //echo $v.PHP_EOL;
			$this->linkArr[] = $v;
		}

        print_r($this->linkArr);
	}




	public function writeFile() {
        foreach ($this->linkArr as $k => $v) {
          file_put_contents($this->outPath,sprintf("%s\n",$v),FILE_APPEND);
        }
	}
}



$obj = new GetEbayAccountItemsByHtml($argv);
$obj->run();
