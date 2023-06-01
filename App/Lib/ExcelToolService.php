<?php
namespace App\Lib;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\writer\Exception AS ExcelOutPutException;

class ExcelToolService
{


    /**
     * @默认上传路径
     */

    public  $defaultPath = ROOT_PATH.DIRECTORY_SEPARATOR."statics".DIRECTORY_SEPARATOR;
    /**
     * @var  标题数组
     */
    public $titleFields = [];

    /**
     * @var int
     */
    public $maxCol = 0;

    /**
     * @var int
     */
    public $maxRow = 0;

    /**
     * @var
     */
    public $filePath;

    /**
     * @var int
     */
    public $titleLine = 1;

    /**
     * 设置文件读取路径
     * @param $path
     * @return string
     */
    public function setPath($path)
    {
        $path = empty($path) ? $this->defaultPath : $path;
        $this->filePath = $path;
        return $this->filePath;
    }


    /**
     * 读取文件内容
     * @param $fileName
     * @param $filePath
     * @param string $type
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readFile($fileName, $filePath = '', $type = 'Xlsx')
    {
        $res = ['ack' => 'fail', 'msg' => 'read file error', 'data' => []];
        try {
            $path = $this->setPath($filePath);
            $fileName = $path . $fileName;
            $reader = IOFactory::createReader($type);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $this->maxRow = $worksheet->getHighestRow();    //获取总行数
            $this->maxCol = $worksheet->getHighestColumn(); //获取总列数
            //获取首行标题栏值
            for ($j = $this->getAlphaNum('A'); $j <= $this->getAlphaNum($this->maxCol); $j++) {
                $cellStr = $this->getAlphaValue($j);//第一行为标题栏
                $cellName = $cellStr . $this->titleLine;
                $this->titleFields[$cellStr] = $worksheet->getCell($cellName)->getValue();
            }
            //获取关联数据
            for ($i = $this->titleLine + 1; $i <= $this->maxRow; $i++) {
                $tmp = [];
                for ($j = $this->getAlphaNum('A'); $j <= $this->getAlphaNum($this->maxCol); $j++) {
                    $cellStr = $this->getAlphaValue($j);
                    $cellName = $cellStr . $i;
                    $cellValue = $worksheet->getCell($cellName)->getValue();
                    $tmp[$this->titleFields[$cellStr]] = $cellValue;
                }
                $res['data'][] = $tmp;
            }
            $res['ack'] = 'success';
            $res['msg'] = '';
        } catch (Exception $e) {
            $res['msg'] = $e->getMessage();
        } finally {
            unset($reader, $spreadsheet, $worksheet);
        }
        return $res;
    }


    /**
     * @param $data
     * @param int $type
     * @param string $path
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeFile($data, $type = 0, $path = '')
    {
        $res = ['ack' => 'fail', 'msg' => 'read file error', 'data' => ''];
        try {
            //生成表格
            $spreadsheet = new Spreadsheet();
            $sheet       = $spreadsheet->getActiveSheet();

            //获取fields
            $fields = array_keys(reset($data));

            //设置标题头部
            foreach ($fields as $k => $field) {
                $alphaNum = $k + 97;
                $colName  = $this->getAlphaValue($alphaNum).$this->titleLine;
                $sheet->setCellValue($colName, $field);
            }

            //设置内容部分
            foreach ($data as $k => $v) {
                $rowLine = $k + $this->titleLine + 1;//相对于标题行偏移一个单位
                foreach ($fields as $k => $field) {
                    $alphaNum = $k + 97;
                    $colName  = $this->getAlphaValue($alphaNum).$rowLine;
                    isset($v[$field]) && $sheet->setCellValue($colName, $v[$field]);
                }
            }
            //生成文件名
            $filename = 'download'.date('YmdHis').'.xlsx';

            if (!empty($type)) {
                //文件输出到浏览器
                $writer   = IOFactory::createWriter($spreadsheet, "Xlsx");
                $fullFile = $this->setPath($path).$filename;
                $writer->save($fullFile);
                unset($spreadsheet);
                //设置header
                $this->setHeader("Content-type", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                $this->setHeader("Content-Disposition", "attachment;filename=".$filename);
                $this->setHeader("Cache-Control", "max-age=0");

            } else {
                //生成文件
                $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
                $writer->save($this->setPath($path).$filename);
                $res['ack']  = 'success';
                $res['msg']  = '';
                $res['data'] = $filename;
            }
        } catch (ExcelOutPutException $e) {
            $res['msg'] = $e->getMessage();
        }
        return $res;
    }

    /**
     * 列名转换 'A'=>97
     * @param string $str
     * @return int
     */
    public function getAlphaNum($str = 'A')
    {
        return ord($str);
    }

    /**
     * 列名转换 97 => 'A'
     * @param int $num
     * @return string
     */
    public function getAlphaValue($num = 97)
    {
        return chr($num);
    }


    public function setHeader($k,$v) {
        header(sprintf("%s:%s",$k,$v));
    }

}
