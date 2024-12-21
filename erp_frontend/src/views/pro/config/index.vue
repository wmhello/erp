<template>
   <div id="config">
     <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="配对编号">
           <el-input v-model="searchForm.sub_number" @keyup.enter.native="find" placeholder="请输入编号">
           </el-input>
           <!-- <input type="text"   v-model="searchForm.sub_number" class="el-input__inner"/> -->
        </el-form-item>
        <el-form-item label="商品编号" style="display:none">
           <!-- <el-input v-model="searchForm.sub_number" placeholder="请输入编号">
           </el-input> -->
           <input type="text"  v-model="searchForm.good_number" class="el-input__inner"/>
        </el-form-item>
        <el-form-item>
           <el-button  @click="find()" plain>查询</el-button>
           <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
     </el-form>
      <div id="datagrid">
        <div class="toolbar">
          <el-button  plain icon="el-icon-plus" @click="add()" >添加配对信息</el-button>
          <el-button  plain icon="el-icon-plus" @click="upload()" >导入配对表</el-button>
        </div>
         <!-- 信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="配对号" prop="sub_number" width="200">
            </el-table-column>
            <el-table-column label="配对详情"  width="450">
              <template slot-scope="scope">
                <div>
                  <p v-for="(item,index) in scope.row.note" :key='index'>{{item}}</p>
                </div>
              </template>
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
      <el-dialog title="配对管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
         <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
            <el-row>
               <el-col :span="12">
                  <el-form-item label='配对编号' prop="sub_number">
                     <el-input :disabled="isEdit===true" v-model="form.sub_number"></el-input>
                  </el-form-item>
               </el-col>
               <el-col :span="12">
                  <el-form-item label='备注' prop="remark">
                     <el-input v-model="form.remark"></el-input>
                  </el-form-item>
               </el-col>
            </el-row>
            <fieldset>
               <legend>配对操作</legend>
               <el-row>
                 <el-col :span="12">
                   <!-- <el-select  style="width: 100%" v-model="item.number" filterable clearable placeholder="请选择或者搜索选择">
                    <el-option
                      v-for="good in goods"
                      :key="good.id"
                      :label="good.tips"
                      :value="good.number">
                    </el-option>
                  </el-select> -->
                  <el-input v-model="item.number"></el-input>
                  </el-col>
                  <el-col :span="12">
                     <el-col :span="12" style="text-align:center">
                         <el-input-number v-model="item.count" :min="0"></el-input-number>
                     </el-col>
                     <el-col :span="10" style="text-align:right" class="toolbar-config">
                        <el-button type="primary" icon="el-icon-plus" round size="mini" @click="addConfig"></el-button>
                     </el-col>
                 </el-col>
               </el-row>
            </fieldset>
            <fieldset>
               <legend>配对表</legend>
               <el-table
                :data="form.result"
                border
                style="width: 100%">
                <el-table-column
                  type="index"
                  width="50">
                </el-table-column>
                <el-table-column
                  prop="number"
                  label="商品编号"
                  width="200">
                </el-table-column>
                <el-table-column
                  prop="name"
                  label="商品名称"
                  width="200">
                </el-table-column>
                <el-table-column
                  prop="count"
                  label="数量"
                  width="80">
                </el-table-column>
                <el-table-column label="操作" >
                   <template slot-scope="scope">
                      <el-tooltip content="删除" placement="right-end"  effect="light">
                        <el-button plain icon="el-icon-delete" type="danger" size="small" @click="delConfig(scope.row)" v-has="'cates.destory'"></el-button>
                      </el-tooltip>
                   </template>
                </el-table-column>
              </el-table>
            </fieldset>
         </el-form>
         <div slot="footer" class="dialog-footer">
            <el-button type="primary"  @click="save()">确 定</el-button>
            <el-button @click="cancel()">取 消</el-button>
         </div>
      </el-dialog>
      <upload-xls :show="isShowUpload"
            :template-file="templateFile"
            module="config"
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
    } from "@/api/config";

    import {
        getGoodsInfo
    } from "@/api/good";

    import {  Model,
        SearchModel,
        Rules,
      Single} from '@/model/config'
    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"
    import UploadXls from "@/views/components/UploadXls";

     export default {
       name: 'Config',
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
                  templateFile: config.site + '/xls/config.xlsx',
                  goods:[],
                  editDialogFormVisible: false,
                  item: new Single()
         }
       },

       created() {
         this.fetchData()
         getGoodsInfo().then(res=>{
           this.goods = res.data
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
       },
       methods: {
         find(event) {
           let node = document.querySelector('.el-input__inner');
           this.searchForm.sub_number = node.value
           this.$nextTick()
            .then(()=>{
              this.fetchData();
            })
         },
         editHandle(response){
           let obj = response.data;
            this.form = {}
            this.form.sub_number = obj.sub_number
            this.form.id = obj.id
            this.form.remark = obj.remark
            let str = obj.tips
            let arrStr = str.split(',')
            let ids = obj.good_ids
            let arrIds = ids.split(',')
            let arrTemp = []
            arrStr.forEach((res1,index)=>{
               // res1 = 'P930-heimoshui(黑墨水)×5'

              let arr = res1.split(/[\(\)×]/) // 解析出number name count
              arr.splice(2,1)
              let [number,name,count] = arr
              count = +count  // 转成数字
              let obj = {
                 id: +arrIds[index],
                 number,
                 name,
                 count
              }
              arrTemp.push(obj)
            })
            this.form.result = arrTemp;
         },
         addConfig(){
           // 根据选择的编号查找出相应的商品，构建表格内容
           if (!this.item.number) {
             this.$message.error('请选择商品后再添加配对');
             return false
           }
           if (this.item.count){
             let number = this.item.number
             number = number.trim()
             let content = this.goods.find(res=>res.number===number)
             if (content===undefined){ // 没有指定编号的商品
              this.$message.error('指定的商品信息不存在，请重新填写');
              this.item.number = null
              this.item.count = null
              return false;
             }
             this.item.id = content.id
             this.item.name = content.name
             // 检查是否有相关的商品存在， 保证一个商品一条记录
             let checkResult = this.form.result
             let index = checkResult.findIndex(res => res.number === number)
             if (index>=0){
                // 表示有相关的内容已经存在
                this.$message.error('商品信息已经存在，请勿重复添加');
                return false
             } else {
               this.form.result.push(this.item)
             }

             // 新建项目结束后配对内容为空
             this.item = new Single()
           } else {
              this.$message.error('请输入数量后再添加配对');
           }
         },
         delConfig(row) {
            // 获取所在的位置后删除
           let index = this.form.result.findIndex(res=>res.number === row.number)
           this.form.result.splice(index,1)
         },
         fetchData(
           searchObj = this.searchForm,
           page = this.current_page,
           pageSize = this.pageSize
         ) {
           this.loading = true;
           this.curd.getInfo(searchObj, page, pageSize)
             .then(response => {
               //成功执行内容
               let result = response.data;
               let arrTemp = []
              result.forEach(res=>{
                 arrTemp = []
                 let str = res.tips
                 let arrStr = str.split(',')
                 arrStr.forEach((res1,index)=>{
                    // res1 = 'P930-heimoshui(黑墨水)×5'
                   let arr = res1.split(/[\(\)×]/) // 解析出number name count
                   arr.splice(2,1)
                   let [number,name,count] = arr
                   count = +count  // 转成数字
                   let obj = {
                      number,
                      name,
                      count
                   }
                   arrTemp.push(obj)
                 })
                 res.note = arrStr
                 res.result = arrTemp
                 return res;
               })
               this.tableData = result;
               this.total = response.meta.total;
               this.loading = false;
             })
             .catch(err => {
               this.tools.error(this, err.response.data);
               this.loading = false;
             });
         },
       }
     }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #config .el-input {
      // width: 200px;
      // margin-left: 10px;
   }
   #datagrid {
      .toolbar {
         margin-bottom: 10px;
      }
      .page {
         margin-top: 10px;
      }
   }
   .toolbar-config .el-button--mini, .el-button--mini.is-round {
    padding: 10px 10px;
   }
   .toolbar-config .el-button+.el-button {
    margin-left: 5px;
   }
</style>
