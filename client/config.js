/**
 * 小程序配置文件
 */

// 此处主机域名修改成腾讯云解决方案分配的域名
var host = 'https://ix5umjlv.qcloud.la';

var config = {

  // 下面的地址配合云端 Demo 工作
  service: {
    host,

    // 登录地址，用于建立会话
    loginUrl: `${host}/weapp/login`,

    // 测试的请求地址，用于测试会话
    requestUrl: `${host}/weapp/user`,

    // 测试的信道服务地址
    tunnelUrl: `${host}/weapp/tunnel`,

    // 上传图片接口
    uploadUrl: `${host}/weapp/upload`,

    // note分组接口
    noteGroup: `${host}/weapp/note/group`,
    // note列表接口
    noteList: `${host}/weapp/note`,
    // note内容接口
    noteList: `${host}/weapp/note/list`,

    // saveNote接口
    saveList: `${host}/weapp/note/saveList`,
    // saveNote接口
    saveNote: `${host}/weapp/note/saveNote`,
    // saveGroup
    saveGroup: `${host}/weapp/note/saveGroup` ,

    //删除分组
    delGroup: `${host}/note/deleteGroup`,
    //删除note
    delNote: `${host}/note/deleteNote`,

    delList: `${host}/note/deleteList`,
  }
};

module.exports = config;