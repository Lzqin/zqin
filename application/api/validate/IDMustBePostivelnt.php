<?php

namespace app\api\validate;

use think\Validate;

class IDMustBePostivelnt extends BaseValidate
{
    protected $message = [
        'id' => 'id必须是正整数'
    ];


    protected  $rule = [
        'id' => 'require|isPositiveInteger'
    ];
    
    
}