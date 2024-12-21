<template>
   <div id="purchase">
    <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="商品编号">
             <el-input v-model="searchForm.good_number" placeholder="请输入编号" @keyup.enter.native="search"></el-input>
        </el-form-item>

        <el-form-item label="日期">
            <el-date-picker type="date" value-format="yyyy-MM-dd" placeholder="选择日期" v-model="searchForm.date" style="width: 100%;"></el-date-picker>
        </el-form-item>

        <el-form-item label="类型">
          <el-select v-model="searchForm.cate_id" clearable placeholder="请选择种类">
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
          <el-button  plain icon="el-icon-plus" @click="upload()" >导入采购信息</el-button>
          <el-button  plain icon="el-icon-plus" type="success" @click="importStorage">采购信息入库</el-button>
          <!-- <el-button  plain icon="el-icon-plus" type="danger" @click="deleteTable">删除采购信息</el-button> -->
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="50">
            </el-table-column>
            <el-table-column label="商品编号" prop="good_number" width="200">
            </el-table-column>
            <el-table-column label="商品名称" prop="good_name" width="150">
            </el-table-column>
            <el-table-column label="商品图象" width="200">
              <template slot-scope="scope">
                <img :src="scope.row.good_img" alt="" width="200px" height="150px">
              </template>
            </el-table-column>
            <el-table-column label="采购数量" prop="good_count" width="80">
            </el-table-column>
            <el-table-column label="时间" prop="date" width="100">
            </el-table-column>
            <el-table-column label="种类" prop="cate_name" width="100">
            </el-table-column>
            <el-table-column label="备注" prop="remark" width="100">
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-tooltip content="编辑" placement="right-end"  effect="light">
                   <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
                </el-tooltip>
                <el-tooltip content="删除" placement="right-end"  effect="light">
                  <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)" ></el-button>
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

      <el-dialog title="采购商品导入" @open="initUpload" width="40%" center :visible="isShowUpload" :close-on-click-modal="false" @close="closeUpload()">
        <el-form label-width="100px">
         <el-form-item label="日期">
           <el-date-picker
             v-model="uploadDate"
             type="date"
             value-format='yyyy-MM-dd'
             placeholder="请选择日期">
           </el-date-picker>
         </el-form-item>
         <!-- <el-form-item label="模板">
          <el-button size="small" type="text" @click="downloadTemplate()">下载模板</el-button>
         </el-form-item> -->
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

      <el-dialog title="采购管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label='编号' prop="good_number">
                     <el-input :disabled="isEdit===true" v-model="form.good_number"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='名称' prop="good_name">
                     <el-input :disabled="isEdit===true" v-model="form.good_name"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <el-row>
               <el-col :span="12">
                  <el-form-item label='数量' prop="good_count">
                     <el-input-number v-model="form.good_count" label="描述文字"></el-input-number>
                     <!-- <el-input v-model="form.good_count"></el-input> -->
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
                    <el-input v-if="form.good_img===''" v-model="form.img"></el-input>
                    <img v-else :src="form.good_img" alt="商品图象" width="200px" height="150px">
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
    import UPLOAD from '@/minix/upload'
    import {
        getInfo,
        uploadFile,
        importStore,
        getInfoById,
        updateInfo,
        deleteInfoById
    } from "@/api/purchase";

    import {
        getAll
    } from "@/api/cate";

    import {  Model,
        SearchModel,
        Rules} from '@/model/purchase'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"

     export default {
       name: 'Purchase',
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
                    // addInfo: addInfo || function(){},
                    deleteInfoById: deleteInfoById || function(){},
                    // deleteAll: deleteAll || function(){},
                  },
                  types:['无'],
                  templateFile: config.site + '/xls/storages.xlsx',
                  goods:[],
                  uploadDate: null,
                  cates:[],
                  errorFile: config.site+'/xls/error.xlsx'
         }
       },

       created() {
         this.fetchData()
         getAll().then(res=>{
           this.cates = res.data
         })
       },
       mounted(){
         this.$nextTick()
          .then( ()=> {
            // DOM 更新了
            document.addEventListener('scroll',this.adjuestToolbar,false)
          })
       },
       filters: {
       },
       methods: {
         adjuestToolbar() {
           let datagrid = document.querySelector('#datagrid')
           let x = datagrid.getBoundingClientRect().left;
           let y = datagrid.getBoundingClientRect().top;
           if (y<0){
             let toolbar = document.querySelector('.toolbar')
             toolbar.className="toolbar fixed"
           }
          if (y>75) {
            let toolbar = document.querySelector('.toolbar')
            toolbar.className="toolbar"
          }
        },
         search(event){
           let node = document.querySelector('.el-input__inner');
           this.searchForm.good_number = node.value
           this.fetchData()
         },
         downloadTemplate() {
           location.href = this.templateFile;
         },
         initUpload() {
              this.uploadDate = null
         },
         // deleteTable() {
         //   this.$confirm('此操作将永久删除相关采购信息, 是否继续?', '提示', {
         //       confirmButtonText: '确定',
         //       cancelButtonText: '取消',
         //       type: 'warning'
         //     }).then(() => {
         //        deleteAll().then(res => {
         //          this.fetchData()
         //        })
         //     }).catch(() => {
         //
         //     });
         // },
         importStorage(){
           this.$confirm("此操作将对采购信息进行入库操作, 是否继续?", "提示", {
             confirmButtonText: "确定",
             cancelButtonText: "取消",
             type: "warning"
           })
             .then(() => {
               let arr = [];
               this.multiSelect.forEach(item => {
                 arr.push(item.id);
               });
               importStore(arr)
                 .then(response => {
                   this.fetchData();
                 })
                 .catch(err => {
                   this.tools.error(err.response.data);
                 });
             })
             .catch(() => {
               this.tools.errorTips(this, "删除操作已经取消");
             });
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
             Tools.success(this, '文件信息上传成功');
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
           }).catch(error => {
             Tools.errorTips(this, error.response.data.message);
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
           this.form.good_id = this.goods[index].id
           this.save();
         }
         }
       }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #purchase.el-input {
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
   .fixed{
     position:fixed;
     left: 200px;
     top: 0px;
     z-index: 1000
   }
</style>
