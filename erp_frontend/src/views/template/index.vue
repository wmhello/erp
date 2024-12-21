<template>
   <div id="info">
      <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
         <el-form-item label="姓名">
            <el-input v-model="searchForm.title" placeholder="请输入标题">
            </el-input>
         </el-form-item>
         <el-form-item>
            <el-button  @click="find()" plain>查询</el-button>
            <el-button type="info" @click="findReset()" plain>重置</el-button>
         </el-form-item>
      </el-form>
      <div id="datagrid">
        <div class="toolbar">
          <el-button  plain icon="el-icon-plus" @click="add()"  >添加学校资讯</el-button>
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="50">
            </el-table-column>
            <el-table-column label="标题" prop="title" width="300">
            </el-table-column>
            <el-table-column label="网址" prop="url" width="500">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="100">
              <template slot-scope="scope">
              <span>{{scope.row.remark}}</span>
              </template>
            </el-table-column>
            <el-table-column label="操作" >
               <template slot-scope="scope">
                  <el-tooltip content="编辑" placement="right-end"  effect="light">
                     <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)" v-has="'info.show'"></el-button>
                  </el-tooltip>
                  <el-tooltip content="删除" placement="right-end"  effect="light">
                    <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)" v-has="'info.destory'"></el-button>
                  </el-tooltip>
               </template>
            </el-table-column>
         </el-table>
         <!-- 分页 -->
         <el-row class="page">
            <el-col :span="2" :offset="1">
              <el-button type="danger" plain @click="delAll()">删除选择</el-button>
            </el-col>
            <el-col :span="20">
               <el-pagination
                       background
                       @current-change="pagination"
                       @size-change="sizeChange"
                       :current-page.sync="current_page"
                       :page-sizes="[10,20,25,50]"
                       layout="total,sizes,prev, pager, next"
                       :page-size.sync="pageSize"
                       :total="total">
               </el-pagination>
            </el-col>
         </el-row>
      </div>

      <!-- 编辑列表 -->
      <el-dialog title="微信端校园信息管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="120px">
            <el-row>
               <el-col :span="24">
                  <el-form-item label="标题" prop="title">
                     <el-input v-model="form.title"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="24">
                  <el-form-item label="网址" prop="url">
                     <el-input v-model="form.url"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <el-form-item label="备注"  prop="remark">
                     <el-input v-model="form.remark"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
         </el-form>
         <div slot="footer" class="dialog-footer">
            <el-button type="primary" v-has="'pro.update'" @click="save()">确 定</el-button>
            <el-button @click="cancel()">取 消</el-button>
         </div>
      </el-dialog>

   </div>
</template>

<script>
    // import CURD from '@/utils/curd'
    // import {
    //     getInfo,
    //     getInfoById,
    //     updateInfo,
    //     deleteInfoById,
    //     addInfo,
    //     deleteAll
    // } from "@/api/wxInfo";
    // import {  Model,
    //     SearchModel,
    //     Rules} from '@/model/info'
    // import { config } from "@/config/index"
    // import { Tools } from "@/views/utils/Tools"
    //
    //  export default {
    //    name: 'Info',
    //    mixins: [CURD],
    //    data() {
    //      return {
    //         searchForm: new SearchModel(),
    //               form: new Model(),
    //               rules: Rules,
    //               searchModel: SearchModel,
    //               model: Model,
    //               tools:Tools,
    //               curd: {
    //                 getInfo: getInfo || function(){},
    //                 getInfoById: getInfoById || function(){},
    //                 updateInfo: updateInfo || function(){},
    //                 addInfo: addInfo || function() {},
    //                 deleteAll: deleteAll || function() {},
    //                 deleteInfoById: deleteInfoById || function() {}
    //               }
    //      }
    //    },
    //    created() {
    //      this.fetchData()
    //    }
    //  }
</script>

<style lang="scss">
   @import "./../../styles/app-main.scss";
   #info .el-input {
      width: 100%;
      margin-left: 10px;
   }
   #datagrid {
      .toolbar {
         margin-bottom: 10px;
      }
      .page {
         margin-top: 10px;
      }
   }
</style>
