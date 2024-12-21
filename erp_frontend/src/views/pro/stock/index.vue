<template>
   <div id="good">
     <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="编号">
           <el-input v-model="searchForm.good_number" @keyup.enter.native="search" placeholder="请输入编号">
           </el-input>
        </el-form-item>
        <el-form-item label="类型">
          <el-select v-model="searchForm.cate_id" placeholder="请选择种类">
            <el-option v-for="(item,index) in cates" :key='index' :value='item.id' :label='item.name'></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
           <el-button  @click="find()" plain>查询</el-button>
           <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
     </el-form>
      <div id="datagrid">
        <div class="toolbar">
          <el-button  plain type="success" icon="el-icon-plus" @click="upload()" >导入盘点库存</el-button>
          <el-button  plain type="success" icon="el-icon-plus" @click="exportStocks()" >导出库存</el-button>
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="50">
            </el-table-column>
            <el-table-column label="商品编号" prop="good_number" width="200">
            </el-table-column>
            <el-table-column label="商品名称" prop="good_name" width="200">
            </el-table-column>
            <el-table-column label="图象" width="200">
              <template slot-scope="scope">
                 <img :src="scope.row.good_img" alt="" width="200px" height="150px">
              </template>
            </el-table-column>
            <el-table-column label="种类" width="100">
              <template slot-scope="scope">
                <el-tag>{{scope.row.cate_id|filterCate(cates)}}</el-tag>
              </template>
            </el-table-column>
            <el-table-column label="数量" prop="count" width="100">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="100">
            </el-table-column>
            <el-table-column label="操作" >
               <template slot-scope="scope">
                  <el-tooltip content="编辑" placement="right-end"  effect="light">
                     <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)" v-has="'cates.show'"></el-button>
                  </el-tooltip>
               </template>
            </el-table-column>
         </el-table>
         <!-- 分页 -->
         <el-row class="page">
            <el-col :span="2" :offset="1">
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
      <el-dialog title="库存管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label='编号' prop="good_number">
                     <el-input v-model="form.good_number" disabled></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='名称' prop="good_name">
                     <el-input v-model="form.good_name" disabled></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label="数量" prop="count">
                    <el-input-number size="medium" v-model="form.count"></el-input-number>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='备注' prop="remark">
                     <el-input v-model="form.remark"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
         </el-form>
         <div slot="footer" class="dialog-footer">
            <el-button type="primary"  @click="save()">确 定</el-button>
            <el-button @click="cancel()">取 消</el-button>
         </div>
      </el-dialog>
      <upload-xls :show="isShowUpload"
            :template-file="templateFile"
            module="stock"
           @close-upload="closeUpload">
     </upload-xls>

   </div>
</template>

<script>
    import CURD from '@/minix/curd'
    import UPLOAD from '@/minix/upload'
    import {
        getInfo,
        getInfoById,
        updateInfo,
        addInfo,
        deleteInfoById,
        deleteAll,
        exportFile
    } from "@/api/stock";

    import {
        getAll
    } from "@/api/cate";

    import {  Model,
        SearchModel,
        Rules} from '@/model/stock'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"
    import UploadXls from "@/views/components/UploadXls";

     export default {
       name: 'Stock',
       components: {
         UploadXls,
       },
       mixins: [CURD, UPLOAD],
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
                  templateFile: config.site + '/xls/stocks.xlsx',
                  successFile: config.site + '/xls/export.xlsx',
                  cates:[]
         }
       },

       created() {
         this.fetchData()
         getAll().then(res=>{
           this.cates = res.data
         })

       },
       methods: {
         exportStocks(){
            exportFile().then(res=> {
              Tools.success(this, res.info)
              location.href = this.successFile
            })

         }
       },
       filters: {
         filterCate(v,cates) {
          let item = cates.find(res=>res.id===v)
          if (item) {
                      return item.name
          } else {
            return '无'
          }

         }
       }
     }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #good .el-input {
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
