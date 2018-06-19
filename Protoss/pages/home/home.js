// pages/home/home.js

import {Home} from 'home-model.js';

var home = new Home();

Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  onLoad:function(){
    this._loadData();
  },

  _loadData:function(){
    var id = 1;
    var data = home.getBannerData(id,(res)=>{console.log(res)});
  },

  // callBake:function(res){
  //   console.log(res);
  // }
 
})