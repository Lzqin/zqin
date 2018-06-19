class Home{

  constructor(){

  }

  getBannerData(id,callBake){
    wx.request({
      url: 'http://www.zqin.com/api/v1/banner/'+id,
      method:'GET',
      success:function(res){
        // console.log(res);
        // return res;
        callBake(res);
      }
    })
  }
}

export {Home};