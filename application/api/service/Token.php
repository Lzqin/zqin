<?php

namespace app\api\service;


use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use think\Controller;

class Token
{
    public static function generateToken()
    {
        //32个字符串组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串，进行MD5加密
        $timestamp =$_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrenTokenVar($key)
    {
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }
        else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrenTokenVar('uid');
        return $uid;
    }

    //需要管理员和用户才能访问的接口权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrenTokenVar('scope');
        if($scope){
            if($scope >= ScopeEnum::User){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //只有用户才能访问的接口权限
    public static function needExclusiveScope()
    {
        $scope = self::getCurrenTokenVar('scope');
        if($scope){
            if($scope == ScopeEnum::User){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //检查当前操作的uid
    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID) {
            throw new Exception('检查UID时必须传入一个被检测的UID');
        }
        $currenOperateUID = self::getCurrentUid();
        if ($currenOperateUID == $checkedUID){
            return true;
        }
        return false;
    }
}