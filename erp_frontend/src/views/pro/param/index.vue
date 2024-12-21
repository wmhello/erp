<template>
   <div id="param">
      <div id="datagrid">
        <div class="toolbar">
          <el-button  plain icon="el-icon-plus" @click="add()" >添加字典</el-button>
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="50">
            </el-table-column>
            <el-table-column label="字典名称" prop="name" width="200">
            </el-table-column>
            <el-table-column label="字典描述" prop="desc" width="200">
            </el-table-column>
            <el-table-column label="字典值" prop="value" width="200">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="200">
            </el-table-column>
            <el-table-column label="操作" >
               <template slot-scope="scope">
                  <el-tooltip content="编辑" placement="right-end"  effect="light">
                     <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)" v-has="'cates.show'"></el-button>
                  </el-tooltip>
                  <el-tooltip content="删除" placement="right-end"  effect="light">
                    <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)" v-has="'cates.destory'"></el-button>
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
      <el-dialog title="字典管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label="参数名称" prop="name">
                     <el-input v-model="form.name" placeholder="请输入参数名称(英文)"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                 <el-form-item label="参数说明" prop="desc">
                    <el-input v-model="form.desc" placeholder="请输入参数说明(中文)"></el-input>
                 </el-form-item>
               </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-form-item label="参数值" prop="value">
                   <el-input v-model="form.value"></el-input>
                </el-form-item>
              </el-col>
               <el-col :span="12">
                  <el-form-item label="备注" prop="remark">
                    <el-input v-model="form.remark" placeholder="请输入参数备注"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
         </el-form>
         <div slot="footer" class="dialog-footer">
            <el-button type="primary"  @click="save()">确 定</el-button>
            <el-button @click="cancel()">取 消</el-button>
         </div>
      </el-dialog>

   </div>
</template>

<script>
    import CURD from '@/minix/curd'
    import {
        getInfo,
        getInfoById,
        updateInfo,
        addInfo,
        deleteInfoById,
        deleteAll
    } from "@/api/param";

    import {  Model,
        SearchModel,
        Rules} from '@/model/param'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"

     export default {
       name: 'Cate',
       mixins: [CURD],
       data() {
         return {
            searchForm: new SearchModel(),
                  form: new Model(),
                  rules: Rules,
                  searchModel: SearchModel,
                  model: Model,
                  tools:Tools,
                  curd: {
                    getInfo: getInfo || function(){},
                    getInfoById: getInfoById || function(){},
                    updateInfo: updateInfo || function(){},
                    addInfo: addInfo || function(){},
                    deleteInfoById: deleteInfoById || function(){},
                    deleteAll: deleteAll || function(){},
                  },
                  types:['无'],
         }
       },
       created() {
         this.fetchData()
       }
     }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #param .el-input {
      width: 200px;
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
