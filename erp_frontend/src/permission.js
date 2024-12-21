import router from './router'
import store from './store'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css'// Progress 进度条样式
import { getToken } from '@/utils/auth' // 验权

const whiteList = ['/login', '/bind']
router.beforeEach((to, from, next) => {
  // to=>表示我们即将要达到的地方的路由
  // from =>表示我们现在在着的路由
  // next => 执行下一个中间件或者说为守卫

  NProgress.start()
  if (getToken()) {
    if (to.path === '/login') {
      next({ path: '/' })  // 如果已经登录了 就无需再输入密码
    } else {
      // 登录后，如果不是直接执行到login页面，则我们要根据达到的页面显示不同的内容
      if (store.getters.roles.length === 0) {
        // 登录后，还没有获取用户的信息，这个时候，我们要获取信息
        store.dispatch('GetInfo').then(res => {
          const roles = res.data.role
          // 根据角色信息，去过滤路由表上的内容，得到该用户拥有的权限，得到对应的路由
          store.dispatch('GenerateRoutes', { roles }).then(() => {
            // 就把路由添加到程序内
            router.addRoutes(store.getters.addRouters)
            next({ ...to })
          })
        })
      } else {
        next()
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next('/login')
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done() // 结束Progress
})
