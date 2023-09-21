<?php
/**
 * 通用 , 适用于全局
 *
 */

namespace App\Response;

/**
 * Trait BaseTrait
 * @package App\Traits
 */
class Response
{


    public $name ="Response for App Controller";

    public $version = "v1";

    /**
     * 成功
     *
     * @param array  $response
     *
     * @return string
     */
    public static function output($response) {
        return   (is_array($response) ?  json_encode($response,JSON_UNESCAPED_UNICODE) : strval($response))??"";
    }



    /**
     * 成功
     *
     * @param string $msg
     * @param array  $data
     *
     * @return string
     */
    public  function success($msg = '', $data = [])
    {
        $response = [
            'code'    => 200,
            'message' => $msg,
            'data'    => $data
        ];

       return self::output($response);
    }

    /**
     * 异常
     *
     * @param int    $code
     * @param string $msg
     *
     * @return string
     */
    public  function fail($code = 0, $msg = '')
    {
        $code = ($code != 200) ? $code : 0;

        $response = ['code' => $code, 'message' => $msg];

        return self::output($response);
    }

    /**
     * 错误返回
     *
     * @param int    $code
     * @param string $msg
     * @param array  $error
     *
     * @return string
     */
    public  function error($code = 0, $msg = '', $error = [])
    {
        $code = ($code != 200) ? $code : 0;

        $response = ['code' => $code, 'message' => $msg, 'error' => $error];

        return self::output($response);
    }
}
