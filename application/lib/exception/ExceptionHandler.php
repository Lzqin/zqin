<?php

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle 
{   
    private $code;
    private $msg;
    private $errorCode;
    //返回请求URL路径

    public function render(\Exception $e)
    {
        if($e instanceof BaseException ){
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            if (config('app_debug')){
                return parent::render($e);
            }
            else{
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorlog($e);
            }
        }
        $request = Request::instance();

        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }

    private function recordErrorlog(\Exception $e){
        log::init([
           'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        log::record($e->getMessage(),'error');
    }
}
