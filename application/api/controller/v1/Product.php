<?php

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;

use app\api\validate\Count;
use app\api\validate\IDMustBePostivelnt;
use app\lib\exception\CategoryException;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);

        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getAllInCategory($id)
    {
        (new IDMustBePostivelnt())->goCheck();

        $products = ProductModel::getProductsByCategoryID($id);

        if ($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getOne($id){
        (new IDMustBePostivelnt())->goCheck();

        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

}