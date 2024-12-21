import Vue from 'vue'
import Router from 'vue-router'
const _import = require('./_import_' + process.env.NODE_ENV)
// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
import Layout from '../views/layout/Layout'

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
**/
export const constantRouterMap = [
  {
    path: '/login',
    component: _import('login/index'),
    hidden: true
  },
  {
    path: '/bind',
    component: _import('login/bind'),
    hidden: true
  },
  {
    path: '/404',
    component: _import('404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    name: '首页',
    hidden: true,
    children: [{
      path: 'dashboard',
      component: _import('dashboard/index'),
    }]
  },
  // {
  //   path: '*',
  //   redirect: '/404',
  //   hidden: true
  // }
]

export default new Router({
  mode: 'history', //后端支持可开
  base: 'admin',
  scrollBehavior: () => ({
    y: 0
  }),
  routes: constantRouterMap
})


export const asyncRouterMap = [
  {
    path: '/admin',
    component: Layout,
    redirect: '/admin/index',
    name: 'admin',
    alwaysShow: true,
    meta: {
      role: ['admin'],
      icon: 'user',
      title: '用户管理'
    },
    children: [
      {
        path: 'index',
        name: 'admin/index',
        component: _import('admin/Index'),
        meta: {
          role: ['admin'],
          title: '用户列表',
          icon: 'table'
        }
      }
    ]
  },
  {
    path: '/set',
    component: Layout,
    redirect: '/set/cate',
    name: 'set',
    alwaysShow: true,
    meta: {
      role: ['admin'],
      icon: 'tubiao',
      title: '基础设置'
    },
    children: [
      {
        path: 'cate',
        name: 'set/cate',
        component: _import('pro/cate/index'),
        meta: {
          role: ['admin'],
          title: '商品种类设置',
          icon: 'tab'
        }
      },
      {
        path: 'config',
        name: 'set/config',
        component: _import('pro/config/index'),
        meta: {
          role: ['admin'],
          title: '商品配对设置',
          icon: 'tab'
        }
      },
      {
        path: 'param',
        name: 'set/param',
        component: _import('pro/param/index'),
        meta: {
          role: ['admin'],
          title: '字典设置',
          icon: 'tab'
        }
      },
    ]
   },
  {
    path: '/pro',
    component: Layout,
    redirect: '/pro/good',
    name: 'pro',
    alwaysShow: true,
    meta: {
      role: ['admin'],
      icon: 'EXCEL',
      title: '业务管理'
    },
    children: [
      {
        path: 'good',
        name: 'pro/good',
        component: _import('pro/good/index'),
        meta: {
          role: ['admin'],
          title: '商品信息管理',
          icon: 'table'
        }
      },
      {
        path: 'storage',
        name: 'pro/storage',
        component: _import('pro/storage/index'),
        meta: {
          role: ['admin'],
          title: '商品入库',
          icon: 'table'
        }
      },
      {
        path: 'sell',
        name: 'pro/sell',
        component: _import('pro/sell/index'),
        meta: {
          role: ['admin'],
          title: '商品销售',
          icon: 'table'
        }
      },
      {
        path: 'stock',
        name: 'pro/stock',
        component: _import('pro/stock/index'),
        meta: {
          role: ['admin'],
          title: '库存管理',
          icon: 'table'
        }
      },
    ]
  },
  {
    path: '/ai',
    component: Layout,
    redirect: '/ai/point',
    name: 'ai',
    alwaysShow: true,
    meta: {
      role: ['admin'],
      icon: 'tubiao',
      title: '智能建议'
    },
    children: [
      {
        path: 'point',
        name: 'ai/point',
        component: _import('pro/cacl/index'),
        meta: {
          role: ['admin'],
          title: '商品购买提示',
          icon: 'tab'
        }
      },
      {
        path: 'purchase',
        name: 'ai/purchase',
        component: _import('pro/purchase/index'),
        meta: {
          role: ['admin'],
          title: '采购与入库',
          icon: 'tab'
        }
      },
    ]
   },
  {
    path: '/order',
    component: Layout,
    redirect: '/order/index',
    name: 'order',
    alwaysShow: true,
    meta: {
      role: ['admin'],
      icon: 'tubiao',
      title: '订单与包裹'
    },
    children: [
      {
        path: 'index',
        name: 'order_index',
        component: _import('pro/order/index'),
        meta: {
          role: ['admin'],
          title: '订单列表',
          icon: 'tab'
        }
      },
      {
        path: ':id/products',
        name: 'order_products',
        hidden: true,
        component: _import('pro/order/product'),
        meta: {
          role: ['admin'],
          title: '产品管理',
          icon: 'tab'
        }
      },
      {
        path: ':id/package',
        name: 'order_package',
        hidden: true,
        component: _import('pro/order/package'),
        meta: {
          role: ['admin'],
          title: '包裹管理',
          icon: 'tab'
        }
      },
    ]
   },
  {
    path: '*',
    redirect: '/404',
    hidden: true
  }
]
