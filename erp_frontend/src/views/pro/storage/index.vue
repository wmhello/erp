<template>
   <div id="storage">
     <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="商品编号">
         <el-input v-model="searchForm.good_number" @keyup.enter.native="search"></el-input>
        </el-form-item>
        <el-form-item label="日期">
            <el-date-picker type="date" value-format="yyyy-MM-dd" placeholder="选择日期" v-model="searchForm.date" style="width: 100%;"></el-date-picker>
        </el-form-item>
        <el-form-item>
           <el-button  @click="find()" plain>查询</el-button>
           <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
     </el-form>
      <div id="datagrid">
        <div class="toolbar">
          <el-button  plain icon="el-icon-plus" @click="add()" >添加入库商品</el-button>
          <el-button  plain icon="el-icon-plus" @click="upload()" >入库商品导入</el-button>
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
            <el-table-column label="入库数量" prop="good_count" width="100">
            </el-table-column>
            <el-table-column label="时间" prop="date" width="100">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="100">
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
      <el-dialog title="商品入库管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label='商品编号' prop="good_number">
                    <!-- <el-select  style="width: 100%" v-model="form.good_number" filterable clearable placeholder="请选择或者搜索选择">
                     <el-option
                       v-for="good in goods"
                       :key="good.id"
                       :label="good.tips"
                       :value="good.number">
                     </el-option>
                   </el-select> -->
                   <el-input v-model="form.good_number" :disabled="isEdit===true"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='入库时间' prop="date">
                    <el-date-picker
                      v-model="form.date"
                      type="date"
                      placeholder="选择日期"
                      value-format="yyyy-MM-dd"
                      >
                    </el-date-picker>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label="入库数量" prop="good_count">
                     <el-input-number v-model="form.good_count" :min="1" label="入库数量"></el-input-number>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                 <el-form-item label="备注" prop="remark">
                   <el-input v-model="form.remark" placeholder="请输入商品备注"></el-input>
                 </el-form-item>
               </el-col>
            </el-row>
         </el-form>
         <div slot="footer" class="dialog-footer">
            <el-button type="primary"  @click="handleSave()">确 定</el-button>
            <el-button @click="cancel()">取 消</el-button>
         </div>
      </el-dialog>

      <el-dialog title="入库商品导入" @open="initUpload" width="40%" center :visible="isShowUpload" :close-on-click-modal="false" @close="closeUpload()">
        <el-form label-width="100px">
         <el-form-item label="日期">
           <el-date-picker
             v-model="uploadDate"
             type="date"
             value-format='yyyy-MM-dd'
             placeholder="请选择日期">
           </el-date-picker>
         </el-form-item>
         <el-form-item label="模板">
          <el-button size="small" type="text" @click="downloadTemplate()">下载模板</el-button>
         </el-form-item>
         <el-form-item label="选择与上传" prop="file">
           <el-upload
               class="upload-demo"
               action="123"
               accept=".xlsx"
               ref="upload"
              :auto-upload="false"
              :before-upload="beforeUpload">
           <el-button slot="trigger" size="small" type="primary">选择文件</el-button>
           <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button>
           <div slot="tip" class="el-upload__tip">只能上传xlsx文件。</div>
         </el-upload>
         </el-form-item>
      </el-form>
      </el-dialog>

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
        uploadFile
    } from "@/api/storage";

    import {
        getGoodsInfo
    } from "@/api/good";

    import {  Model,
        SearchModel,
        Rules} from '@/model/storage'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"

     export default {
       name: 'Storage',
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
                  templateFile: config.site + '/xls/storages.xlsx',
                  goods:[],
                  uploadDate: null,
                  errorFile: config.site+ '/xls/error.xlsx'
         }
       },

       created() {
         this.fetchData()
         getGoodsInfo().then(res=>{
           this.goods = res.data
         })
       },
       filters: {
       },
       methods: {
         search(event){
          let node = document.querySelector('.el-input__inner')
          this.searchForm.good_number = node.value
          this.fetchData()
         },
         downloadTemplate() {
           location.href = this.templateFile;
         },
         initUpload() {
              this.uploadDate = null
         },
         submitUpload() {
           // 没有选择月份，无法上传
           if (!this.uploadDate){
             this.$message.error('请选择要上传的日期');
             return false;
           }
            this.$refs.upload.submit()
         },
         beforeUpload(file) {
           let fd = new FormData();
           fd.append("file", file);  // 传文件
           fd.append('uploadDate', this.uploadDate); // 传月份
           uploadFile(fd).then(res => {
             // 200、201、206
             if (res.info){
                Tools.success(this, res.info);
             } else {
               Tools.success(this, '文件信息上传成功');
             }

             try
             {
              if(typeof(eval(this.fetchData))=="function")
              {
                // 文件传成功之后，获取最近的内容，并刷新月份列表
                this.fetchData();
                // 关闭对话框
                this.closeUpload();
              }
               }catch(e)
             {
                console.log('没有相关函数')
             }

           }).catch(err=> {
             // 404
             Tools.errorTips(this, err.response.data.message);
             location.href = this.errorFile;
             try
             {
              if(typeof(eval(this.fetchData))=="function")
              {
                // 文件传成功之后，获取最近的内容，并刷新月份列表
                this.fetchData();
                // 关闭对话框
                this.closeUpload();
              }
               }catch(e)
             {
                console.log('没有相关函数')
             }
           })
         },
         handleSave(){
           let index = this.goods.findIndex(res=>res.number === this.form.good_number)
           if (index === -1){
             this.$message.error('输入的商品编号不存在，请重新输入')
             return false
           }
           this.form.good_id = this.goods[index].id
           this.save();
         }
         }
       }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #storage.el-input {
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
