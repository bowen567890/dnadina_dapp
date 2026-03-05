<?php

namespace App\Util;

class AES
{
    private static $_instance = null;
    public  $key;
    public  $result;


    /**
     *
     *  获取实例
     *
     * @return \App\Util\App
     *
     * @example
     *
     * 说明：
     *
     */
    public  static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;


    }

    /**
     *
     *  设置对称Key
     *
     * @return \App\Util\App
     *
     * @example
     *
     * 说明：
     *
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }


    /**
     *
     *  对称加密
     *
     * @param  $str  string
     *  -待加密数据
     * @return \App\Util\App
     *
     * @example
     *
     * 说明：
     *
     */
    public  function encrypt(string $str)
    {
        $this->result = base64_encode(openssl_encrypt($str, 'AES-128-ECB', $this->key, OPENSSL_RAW_DATA));
        return $this;
    }

    /**
     *
     *  对称解密
     *
     * @param  $str  string
     *  -待解密数据
     *
     * @return \App\Util\App
     *
     * @example
     *
     * 说明：
     *
     */
    public function decrypt(string $str)
    {
        $str = base64_decode($str);
        $this->result = openssl_decrypt($str, 'AES-128-ECB', $this->key, OPENSSL_RAW_DATA);
        return $this;
    }


    public function get()
    {
        return $this->result;
    }



    public function urlsafeB64decode($string)
    {
        // 1. - 替换成 +   _ 替换 /
        // 2. 计算base64字符长度  1212312312% 4   0 1 2 3 4
       $data = str_replace(array('-','_'),array('+','/'),$string);

       $mod4 = strlen($data) % 4;
       if ($mod4) {
           $data .= substr('====', $mod4);
       }
       return base64_decode($data);
    }

    public function urlsafeB64encode($string) {
       $data = base64_encode($string);
       $data = str_replace(array('+','/','='),array('-','_',''),$data);
       return $data;
    }

    public  function __construct() {}
}
