import Vue from 'vue'

import 'normalize.css/normalize.css'// A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN'

import '@/styles/index.scss' // global css

import App from './App'
import router from './router'  //进入路由插件
import store from './store'   //引入状态管理器

import '@/icons' // icon   引入了图标文件
import '@/permission' // permission control  认证限制

Vue.use(ElementUI, { locale })

Vue.config.productionTip = false

// 按钮级别的权限判断  'session.index'
Vue.prototype.$_has = function (feature) {
  let resources = [];
  let permission = true;
  let outeNamer = store.state.user.routeName
  let roles = store.state.user.roles // 当前用户角色
  // 对admin角色自动显示所有按钮
  if (Array.indexOf(roles, 'admin') !== -1) {
    return true
  }
  //提取权限数组
  if (Array.isArray(feature)) {
  } else {
    // 判断是否有指定的功能权限
    // permission = Array.indexOf(routeName,feature) === -1 ? false : true
    permission = routeName.findIndex(item => item === feature)>=0 ? true : false
  }
  return permission;
}

Vue.directive('has', {
  bind: function (el, binding) {
    if (!Vue.prototype.$_has(binding.value)) {
      el.parentNode.removeChild(el);
    }
  }
});

import contentmenu from 'v-contextmenu'
import 'v-contextmenu/dist/index.css'
Vue.use(contentmenu)

// 消息推送 暂时不需要
// import Echo from 'laravel-echo'
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
