<template>
   <div id="good">
     <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="编号">
           <el-input v-model="searchForm.number" @keyup.enter.native="search" placeholder="请输入编号">
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
          <el-button  plain icon="el-icon-plus" @click="add()" >添加商品</el-button>
          <el-button  plain icon="el-icon-plus" @click="upload()" >导入商品</el-button>
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="80">
            </el-table-column>
            <el-table-column label="商品编号" prop="number" width="120">
            </el-table-column>
            <el-table-column label="商品名称" prop="name" width="200">
            </el-table-column>
            <el-table-column label="图片"  width="210">
              <template slot-scope="scope">
                <img :src="scope.row.img" alt="" width="200px" height="150px">
              </template>
            </el-table-column>
            <el-table-column label="采购来源" prop="source" width="200">
            </el-table-column>
            <el-table-column label="种类" prop="cate_name" width="80">
            </el-table-column>
            <el-table-column label="成本" prop="cost" width="100">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="100">
            </el-table-column>
            <el-table-column label="操作" >
               <template slot-scope="scope">
                  <el-tooltip content="编辑" placement="right-end"  effect="light">
                     <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)" v-has="'cates.show'"></el-button>
                  </el-tooltip>
                  <el-tooltip content="删除" placement="right-end"  effect="light">
                    <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row, '此删除操作将同时删除商品所对应的库存、配对、入库和销售信息，是否继续')" v-has="'cates.destory'"></el-button>
                  </el-tooltip>
               </template>
            </el-table-column>
         </el-table>
         <!-- 分页 -->
         <el-row class="page">
            <el-col :span="2" :offset="1">
              <el-button type="danger" plain @click="delAll('此删除操作将同时删除商品所对应的库存、配对、入库和销售信息，是否继续')">删除选择</el-button>
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
      <el-dialog title="商品管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label='编号' prop="number" >
                     <el-input :disabled="isEdit===true" v-model="form.number"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='名称' prop="name">
                     <el-input v-model="form.name"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label="种类" prop="cate_id">
                      <el-select v-model="form.cate_id" placeholder="请选择所属类型">
                        <el-option v-for="(item,index) in cates" :key='index' :value='item.id' :label='item.name'></el-option>
                      </el-select>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='来源' prop="source">
                     <el-input v-model="form.source"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label='颜色' prop="rule_color">
                     <el-input v-model="form.rule_color"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                 <el-form-item label="尺寸" prop="rule_size">
                   <el-input v-model="form.rule_size"></el-input>
                 </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label='成本' prop="cost">
                     <el-input v-model="form.cost"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                 <el-form-item label="备注" prop="remark">
                   <el-input v-model="form.remark" placeholder="请输入商品备注"></el-input>
                 </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="24">
                 <el-form-item label='商品图象' prop="img">
                    <el-input v-if="form.img===''" v-model="form.img"></el-input>
                    <img v-else :src="form.img" alt="商品图象" width="200px" height="150px">
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
            module="good"
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
        deleteAll
    } from "@/api/good";

    import {
        getAll
    } from "@/api/cate";

    import {  Model,
        SearchModel,
        Rules} from '@/model/good'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"
    import UploadXls from "@/views/components/UploadXls";

     export default {
       name: 'Good',
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
                  templateFile: config.site + '/xls/goods.xlsx',
                  cates:[]
         }
       },

       created() {
         this.fetchData()
         getAll().then(res=>{
           this.cates = res.data
         })
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
