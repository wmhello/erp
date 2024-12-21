<template>
   <div id="cacl">
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
          <el-button  plain icon="el-icon-plus" @click="download()" >生成采购建议</el-button>
        </div>
         <!-- 教师基本信息表 -->
         <el-table :data="tableData" border  @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="序号" prop="id" width="50">
            </el-table-column>
            <el-table-column label="商品编号" prop="good_number" width="200">
            </el-table-column>
            <el-table-column label="商品名称" prop="name" width="200">
            </el-table-column>
            <el-table-column label="库存数量" prop="count" width="100">
            </el-table-column>
            <el-table-column label="近30天售量" prop="good_count" width="100">
            </el-table-column>
            <el-table-column label="剩余天数" prop="yj_day" width="80">
            </el-table-column>
            <el-table-column label="购买建议">
              <template slot-scope="scope">
                <el-tag  type="success">{{scope.row|filterPurchase(purchases)}}</el-tag>
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
        deleteAll,
        exportFile
    } from "@/api/cacl";
    import {getBaseInfo} from '@/api/purchase'
    import {  Model,
        SearchModel,
        Rules} from '@/model/cacl'
    import {
            getAll
        } from "@/api/cate"

    import { config } from "@/config/index"
    import { Tools } from "@/views/utils/Tools"

     export default {
       name: 'Cacl',
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
                  cates:[],
                  downloadFile:config.site+'/xls/采购表.xlsx',
                  purchases: []  // 采购单
         }
       },
       created() {
         this.fetchData()
         getBaseInfo().then(res=>{
           this.purchases = res.data
         })
         getAll().then(res=>{
           this.cates = res.data
         })
       },
       methods: {
         download() {
           let _this = this

           exportFile().then(res => {
               location.href = this.downloadFile;
           }).catch(err=>{
             Tools.error(this, err.response.data);
           })
         },

       },
       filters:{
         filterPurchase(val,purchases) {



           // 正常库存计算
           let goodId = val.good_id
           let index = purchases.findIndex(res=>{
             return res.good_id === goodId
           })
           if (index>=0) {
             // 针对库存为零的情况下 特殊处理
             let purchaseCount = purchases[index].good_count
             if (val.count ==0 && val.good_count <=3 && purchaseCount===0) {
               return '需采购'
             }

             if (val.count+purchaseCount>=val.jj_count) {
               return '无'
             } else {
               return '需采购'
             }
           } else {
             if (val.count ==0 && val.good_count <=3) {
               return '需采购'
             }

             if (val.count>=val.jj_count) {
               return '无'
             } else {
               return '需采购'
             }
           }

         }
       }
     }
</script>

<style lang="scss">
   @import "./../../../styles/app-main.scss";
   #cacl .el-input {
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
