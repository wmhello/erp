<template>
  <div class="dashboard-container" id="dashboard-container">

    <el-row :gutter='10'>
      <el-col :span="12">
        <el-card class="box-card" id="introduction">
          <div slot="header" class="clearfix">
            <span>系统介绍</span>
          </div>
          <p class="desc">
              本ERP系统主要完成以下功能：
           <ol>
             <li>商品及种类管理</li>
             <li>进货管理</li>
             <li>销售管理</li>
             <li>打印统计报表</li>
             <li>根据库存进行智能提醒</li>
           </ol>
          </p>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card class="box-card" >
          <div slot="header" class="clearfix">
            <span>数据统计</span>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <div style="height: 10px"></div>

    <el-row :gutter='10'>
      <el-col :span="12">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>智能提醒</span>
          </div>

        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card class="box-card" ref="log">
          <div slot="header" class="clearfix">
            <span>系统日志</span>
          </div>
          <table class="dataintable log-info">
            <tbody>
              <tr>
                <th>标识</th>
                <th>用户</th>
                <th>操作</th>
                <th>描述</th>
            </tr>
            <tr v-for="item in logs" :key="item.id">
                <td>{{item.id}}</td>
                <td>{{item.user_name}}</td>
                <td>{{item.type}}</td>
                <td>{{item.desc}}</td>
            </tr>
            </tbody>
          </table>
          <div class="page">
            <el-pagination
                background
                @current-change="pagination"
                @size-change="sizeChange"
                :current-page.sync="current_page"
                layout="total,prev, pager, next"
                :page-size.sync="pageSize"
                :total="total">
              </el-pagination>
          </div>
        </el-card>
      </el-col>
    </el-row>


  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getInfo as getInfoBySession } from "@/api/session";
import { getLogInfo } from "@/api/dashboard";
import {Tools} from "@/views/utils/Tools";
import DataTable from '@/views/components/DataTable'

export default {
  name: 'dashboard',
  components: {
   DataTable
  },
  data() {
    return {
      sessionInfo: [],
      leaderInfo: [],
      activeName2:'first',
      task:'task-completed',
      tableData: [
       {
          name: '基本构架',
          desc: '完成SPA前端页面搭建',
          date: '2018.2',
          isSuccess:true
       },
       {
          name: '权限控制集成',
          desc: '控制权限到按钮级别',
          date: '2018.3',
          isSuccess: true
       },
       {
          name: '第三方用户登录',
          desc: '集成第三方用户登录功能',
          date: '2018.6',
          isSuccess: true
       },
       {
          name: '日志',
          desc: '集成登录和业务日志业务',
          date: '2018.6',
          isSuccess: true
       },
       {
          name: '首页优化',
          desc: '首页面板的优化',
          date: '2018.6',
          isSuccess: false
       }
      ],
      logs: [],
      current_page: 1,
      total: 0,
      pageSize: 5,
      todoList:{
        completed:[
          {
             id:1,
             name: '基础',
             desc: '后端API，前端SPA结构成型',
             date: '2018.2'
          },
          {
            id:2,
            name: '权限控制',
            desc: '权限集成，权限控制到按钮',
            date: '2018.2'
          },
          {
            id:3,
            name: '短信',
            desc: '系统集成短信通知API',
            date:'2018.6'
          },
          {
            id: 4,
            name: '第三方登录',
            desc: '第三方登录集成到项目',
            date: '2018.6'
          },
          {
            id: 5,
            name: '日志',
            desc: '集成日志功能，能显示日志',
            date: '2018.6'
          }
        ],
        doing:[
          {
            id: 6,
            name: '首页',
            desc: '优化首页面板',
            date: '进行中'
          }
        ],
        plan: [
          {
            id: 7,
            name: 'QQ登录',
            desc: '集成QQ登录到项目',
            date: '计划中'
          },
          {
            id: 8,
            name: '客服',
            desc: '集成客服功能，实现IM通讯',
            date: '计划中'
          }
        ]
      },
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'roles'
    ])
  },
  methods: {
    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData()
    },
    sizeChange(val) {
       this.pageSize = val;
       this.fetchData()
    },
    fetchData() {
      getLogInfo(this.current_page, this.pageSize).then(res => {
           this.logs = res.data;
           this.total = res.total;
       }).catch(err => {
           console.log(error)
       })
    },
    adjustDom() {
      let doms = document.documentElement.querySelectorAll('.el-card__body');
      for(let i =0, len = doms.length; i<len; i++){
         doms[i].style.cssText="padding-top: 0;"
      }
      let other = document.documentElement.querySelectorAll('.el-card__header');
      for(var t =0, len = other.length; t<len; t++){
         other[t].style.cssText="border-bottom: 1px solid rgba(20, 132, 210, 0.71);"
      }
      var tags = document.documentElement.querySelectorAll('#sponsor .el-tag');
      for(t =0, len = tags.length; t<len; t++){
         tags[t].style.cssText="display: inline-block; margin-right: 5px; margin-bottom: 5px;"
      }
    },
     getSession() {
       getInfoBySession().then(res => {
           this.sessionInfo = res.data;
       }).catch(err => {
           console.log(error)
       })

     },
     getLeader() {
       getInfoByLeader().then(res => {
           this.leaderInfo = res.data;
       }).catch(err => {
            this.$message({
              type: 'error',
              message: err.response.data.message
            })
       })
     }
  },
  created() {
    let n = this.tableData.length
    if (n>4) {
       for (let i=0;i<n-4;i++){
        this.tableData.shift();
       }
    }
    this.fetchData();
  },
  mounted() {
    this.adjustDom();
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard {
  &-container {
    margin: 30px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}


  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .el-card{
    height: 350px;
    border: 1px solid #1e4db95e;
  }
  #dashboard-container .el-card .el-card__header {
     padding-top:10px;
     padding-bottom: 10px;
     border-bottom: 1px solid #0c172fb0 !important;
  }

  .el-table_body-wrapper{
    overflow-y:scroll;
  }
  #introduction .el-card__body{
    padding-top: 10px;
  }

  [data-v-0bfbb486].el-card__body {
    padding: 10px !important;
}
  .desc{
    text-indent: 2em;
    line-height: 1.5;

  }

  table.log-info{
    margin-top: 15px;
    border-collapse: collapse;
  }


  table.browsersupport {
    margin-top: 15px;
    border-collapse: collapse;
  }

  table.dataintable {
    border: 1px solid #aaa;
    width: 100%;
  }

   table.browsersupport th, table.log-info th {
    padding: 0;
    height: 36px;
    vertical-align: middle;
    text-align: center;
    background-color: #F5F5F5;
    border: 1px solid #ddd;
}
   table.dataintable tr:nth-child(even) {
      background-color: #fff;
   }

  table.browsersupport td {
      padding: 0;
      height: 86px;
      width: 86px;
      vertical-align: middle;
      background: #fdfcf8 no-repeat center;
      border: 1px solid #ddd;
  }

  table.log-info td{
      padding: 0;
      height: 36px;
      text-align: center;
      vertical-align: middle;
      background: #fdfcf8 no-repeat center;
      border: 1px solid #ddd;
  }

  table.browsersupport td a:hover{
    color:#00f;
    text-decoration: underline;
  }

  table.browsersupport td {
      text-align: center ;
  }

  .el-tabs__header{
    margin: 0 0 5px;
  }

  .page{
     margin-top: 5px;
     text-align: center;
  }

  .desc .el-tag{
   display: inline-block;
   margin-right: 5px;
   margin-bottom: 5px;
  }
  .el-table .cell{
    line-height: 20px;
  }

</style>
