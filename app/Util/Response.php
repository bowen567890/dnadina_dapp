<?php
namespace App\Util;

class Response
{
    public $code;
    public $message;
    public $data;
    public $body;
    public $resonse;

    public function __construct()
    {
        $this->resonse = \response();
    }

    public function success(array $data = [])
    {
        $this->code    = 200;
        $this->message = "success";
        $this->data    = $data;
        $this->body();
        return $this;
    }

    public function fail(int $code,string $message)
    {
        $this->code    = $code;
        $this->message = $message;
       // $this->data    = [];
        $this->body();

        return $this;
    }

    public function json()
    {
        return  $this->resonse->json($this->body,200);
    }

    public function jsonForceObject() {
       return json_encode($this->body,JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT);
    }

    public function v2(){
        $this->body['status_code'] = $this->body['code'];
        unset($this->body['code']);
        return $this;
    }

    private function body()
    {

        $this->body = [
            "code"   => $this->code,
            "message"=> $this->message,
        ];

        if ($this->code == 200) {
            $this->body["data"] = $this->data;
        }
    }
}
