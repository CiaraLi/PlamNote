//app.js
var qcloud = require('./vendor/wafer2-client-sdk/index')
var config = require('./config')
var util = require('./utils/util.js')

App({
  globalData: {
    userInfo: {},
    auth: false,
    logged: false,
  },
  onLaunch: function() {},

  //登录请求
  LoginUser: function(obj) {

    qcloud.setLoginUrl(config.service.loginUrl)
    util.showBusy('正在登录')
    const session = qcloud.Session.get()
    //登录
    if (session) {
      qcloud.loginWithCode({
        success: res => {
          console.log(res);
          this.globalData.logged = true;
          this.globalData.userInfo = res;
          //登录回调
          if (obj.UserCallBack) {
            obj.UserCallBack(res);
          }
          util.showSuccess('登录成功');
        },
        fail: err => {
          console.error(err)
          util.showModel('登录错误', err.message)
          //登录回调
          if (obj.UserCallBack) {
            obj.UserCallBack(res);
          }
        }
      })
    } else {

      var that = this;
      wx.getSetting({
        success: function(res) {
          var data = res.authSetting;
          if (data['scope.userInfo']) {
            qcloud.login({
              success: res => {
                that.globalData.logged = true;
                that.globalData.userInfo = res;
                util.showSuccess('登录成功');
                //登录回调
                if (obj.UserCallBack) {
                  obj.UserCallBack(res);
                }
              },
              fail: err => {
                console.error(err)
                util.showModel('登录错误', err.message);
                //登录回调
                if (obj.UserCallBack) {
                  obj.UserCallBack(res);
                }
              }
            })
          } else {
            util.showModel('还没有授权', '请点击登录进行微信授权');
          }
        }
      });
    }
  },

  //请求其他
  doRequest: function(url, data, callback) {
    var that = this;
    util.showBusy('请求中...');
    var options = {
      url: url,
      login: false,
      method: data ? 'GET' : 'POST',
      success(result) {
        util.showSuccess('请求成功完成')
        console.log('request success', result.statusCode)
        if (result.statusCode == 200) {
          if (callback) {
            callback(result.data);
          }
        } else if (result.statusCode == 403) {
          that.globalData.logged = false;
          wx.switchTab({
            url: '/pages/index/index',
          })
        } else {
          util.showModel('请求失败', error);
        }
      },
      fail(error) {
        util.showModel('请求失败', error);
        console.log('request fail', error);
        return null;
      }
    }
    qcloud.request(options)

  },
})