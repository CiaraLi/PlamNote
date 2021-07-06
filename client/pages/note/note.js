//note.js 
var config = require('../../config')
var util = require('../../utils/util.js')

const app = getApp();

Page({
  data: { 
    groups: [],
    groupName: ''
  },

  /**
   * 生命周期函数--监听页面显示
   */

  onLoad: function() {
    this.loadGroup();
  },

  onPullDownRefresh: function() {
    this.loadGroup();
  },

  /*加载分组 */
  loadGroup: function() {
    var that = this;
    app.doRequest(config.service.noteGroup, [], function(data) {
      wx.stopPullDownRefresh({});
      if (data.code == 1 && data.data) {
        that.setData({
          groups: data.data
        })
      }
    });
  },
  /*添加新分组 */
  addGroup: function(title) {
    var that = this;
    app.doRequest(config.service.saveGroup + '?group_name=' + title, [], function(data) {
      console.log(2);
      that.setData({
        groupName: ''
      })
      that.loadGroup();
      if (data.code == 1 && data.data) {}
    });
  },
  /*分组名称 */
  bindgroupName: function(e) {
    this.setData({
      groupName: e.detail.value
    })
  },
  /**添加分组按钮 */
  AddGroup: function() {
    console.log(this.data.groupName);
    if (this.data.groupName != '') {
      this.addGroup(this.data.groupName);
    } else {
      util.showTip('请填写分组名称');
    }
  },
  /**进入分组 */
  openGroup: function(e) { 
    console.log(e);
    console.log(e.currentTarget.id);
  },
  /**删除分组确认 */
  showDeleteGroup: function(e) {
    var that = this;
    var index = e.currentTarget.id.substring(2);
    var id = this.data.groups[index].group_id;
    var name = this.data.groups[index].group_name;
  
    util.showConform('', '删除分组' + name+'?', function() {
      that.deleteGroup(id)
    });
  },
  /**删除分组请求 */
  deleteGroup: function (id) {
    var that = this;
    app.doRequest(config.service.delGroup + '?group_id=' + id, [], function(data) {
      console.log('deleteGroup'); 
      that.loadGroup(); 
    });
  }
})