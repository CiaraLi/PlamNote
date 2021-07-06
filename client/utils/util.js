const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}


// 显示繁忙提示
var showBusy = text => wx.showToast({
  title: text,
  icon: 'loading',
  duration: 10000
})

// 显示成功提示
var showSuccess = text => wx.showToast({
  title: text,
  icon: 'success'
})

// 显示失败提示
var showModel = (title, content) => {
  wx.hideToast();

  wx.showModal({
    title,
    content: JSON.stringify(content),
    showCancel: false
  })
}

// 显示提示
var showTip = text => wx.showToast({
  title: text,
  icon: 'none',
  duration: 1500
})
// 显示选择提示
var showConform = (title, content, conform, cancel) => {
  wx.hideToast();

  wx.showModal({
    title,
    content: content,
    showCancel: true,
    success: function(res) {
      if (res.confirm && conform) {
        conform();
      } else if (res.cancel && cancel) {
        cancel();
      }
    },

  })
}
module.exports = {
  formatTime,
  showBusy,
  showSuccess,
  showModel,
  showTip,
  showConform
}