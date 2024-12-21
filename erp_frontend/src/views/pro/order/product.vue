<template>
<div id="config">
  <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
    <el-form-item>
      <el-input v-model="searchForm.product_number" @keyup.enter.native="find" placeholder="请输入产品编号">
      </el-input>
    </el-form-item>
    <el-form-item>
      <el-input type="text" v-model="searchForm.merchant_name" @keyup.enter.native="find" placeholder="请输入产品名称">
      </el-input>
    </el-form-item>
    <el-form-item>
      <el-button @click="find()" plain>查询</el-button>
      <el-button type="info" @click="findReset()" plain>重置</el-button>
    </el-form-item>
  </el-form>
  <div id="datagrid">
    <div class="toolbar">
      <el-row>
        <el-col :span="6">
          <el-button plain icon="el-icon-plus" @click="add()">添加产品信息</el-button>
       </el-col>
        <el-col :span="18" >
          <p class="tips" v-html="summary"></p>
        </el-col>
      </el-row>
    </div>
    <!-- 信息表 -->
    <el-table v-contextmenu:contextmenu size='mini' @row-contextmenu='contextHandle' :data="tableData" :row-class-name="tableRowClassName" :row-style="selectedHighlight" border @row-dblclick="dblHandle" @row-click="rowHandle" @select-all="selectChange"
      @selection-change="selectChange" v-loading="loading">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column label="产品编号" prop="product_number" width="150">
      </el-table-column>
      <el-table-column label="产品名称" prop="product_name" width="150">
      </el-table-column>

      <el-table-column label="产品图片" width="100">
        <template slot-scope="scope">
          <img :src="scope.row.product_img" style="display:block;width:80px;height:80px;">
        </template>
      </el-table-column>
      <el-table-column label="购买数量" prop="product_amount" width="100">
      </el-table-column>
      <el-table-column label="未发货数量" prop="remain_amount" width="100">
      </el-table-column>

      <el-table-column label="购买日期" width="100">
        <template slot-scope="scope">
          <span>{{scope.row.buy_date|filterOrderTime}}</span>
        </template>
      </el-table-column>
      <el-table-column label="备注" prop="remark" width="100">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="right-end" effect="light">
            <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)" v-has="'cates.show'"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end" effect="light">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)" v-has="'cates.destory'"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
        <!-- <el-button type="danger" plain @click="delAll()">删除选择</el-button> -->
      </el-col>
      <el-col :span="20">
        <el-pagination background @current-change="pagination" @size-change="sizeChange" :current-page.sync="current_page" :page-sizes="[10,20,25,50]" layout="total,sizes,prev, pager, next" :page-size.sync="pageSize" :total="total">
        </el-pagination>
      </el-col>
    </el-row>
  </div>

  <el-dialog title="产品管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
     <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
        <el-row>
          <el-col :span="12">
             <el-form-item label="产品编号" prop="product_number">
                <el-input :disabled="this.isEdit === true" v-model="form.product_number" placeholder="请输入产品编号"></el-input>
             </el-form-item>
           </el-col>
           <el-col :span="12">
              <el-form-item label="产品名称" prop="product_name">
                 <el-input v-model="form.product_name" placeholder="请输入产品名称"></el-input>
              </el-form-item>
           </el-col>
        </el-row>
        <el-row v-if="this.isNew === true">
          <el-col :span="12">
            <el-form-item label="购买数量" >
               <el-input-number v-model="form.product_amount" @change="amoutChange" :min="1"  label="请输入购买数量"></el-input-number>
            </el-form-item>
          </el-col>
           <el-col :span="12">
            <el-form-item label="待分割数量" >
               <el-input-number v-model="form.remain_amount" disabled :min="1"></el-input-number>
            </el-form-item>
           </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
             <el-form-item label="购买日期" prop="buy_date">
                    <el-date-picker
                      v-model="form.buy_date"
                      type="datetime"
                      placeholder="购买日期">
                    </el-date-picker>
             </el-form-item>
           </el-col>
           <el-col :span="12">
              <el-form-item label="产品图像" prop="product_img">
                 <el-input v-model="form.product_img" placeholder="请输入产品图像对应的地址"></el-input>
              </el-form-item>
           </el-col>
        </el-row>
        <el-row>
           <el-col :span="12">
              <el-form-item label="备注" prop="remark">
                <el-input v-model="form.remark" placeholder="请输入备注"></el-input>
              </el-form-item>
           </el-col>
           <el-col :span="12">
              <el-form-item >
               <img v-if="form.product_img" :src="form.product_img" style="width:150px;height:150px;">
              </el-form-item>
           </el-col>
        </el-row>
     </el-form>
     <div slot="footer" class="dialog-footer">
        <el-button type="primary"  @click="save()">确 定</el-button>
        <el-button @click="cancel()">取 消</el-button>
     </div>
  </el-dialog>

  <v-contextmenu ref="contextmenu">
    <v-contextmenu-item @click="add">新增产品</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="editProduct">修改产品</v-contextmenu-item>
    <v-contextmenu-item @click="deleteHandle">删除产品</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="showPackage">分割包裹</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="showContent">返回订单</v-contextmenu-item>
  </v-contextmenu>

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
} from "@/api/product";

import {
  getInfoById as getOrderInfo,

} from "@/api/order";

import {
  Model,
  SearchModel,
  Rules
} from '@/model/product'
import {
  config
} from "@/config/index"
import {
  Tools
} from "@/views/utils/Tools"


export default {
  name: 'order_products',
  data() {
    return {
      searchForm: new SearchModel(),
      form: new Model(),
      rules: Rules,
      searchModel: SearchModel,
      model: Model,
      tools: Tools,
      showMenu: false,
      searchSelect: 'order_number',
      searchContent: '',
      types: ['无'],
      templateFile: config.site + '/xls/config.xlsx',
      goods: [],
      getIndex: null,
      currentRecord: null,
      routeId: null,
      tableData: [],
      editDialogFormVisible: false,
      uploadId: "",
      teachers: [],
      loading: false,
      isNew: false,
      isEdit: false,
      current_page: 1,
      total: 0,
      pageSize: 10,
      order_number: null,
      merchant_name: '',
      merchant_number: ''
    }
  },

  filters: {
    filterOrderTime(value) {
      let tempDate = new Date(value);
      return tempDate.getFullYear() + '-' + (tempDate.getMonth() + 1) + '-' + tempDate.getDate();
    },
    filterStatus(value) {
      let status = ['', '未发', '已发未完', '已发完']
      return status[value]
    }
  },
  computed: {
    summary(){
      if (this.order_number){
        return `订单编号： <span>${this.order_number} </span>,  商家编号: <span>${this.merchant_number} </span>,  商家名称： <span>${this.merchant_name} </span>`
      }  else {
        return ''
      }
    }
  },
  // beforeRouteEnter(to, from, next) {
  //   next(vm => {
  //     // 通过 `vm` 访问组件实例
  //     vm.currentRecord =  parseInt(to.params.id)
  //     getOrderInfo(vm.currentRecord).then(response => {
  //       let {order_number,merchant_name, merchant_number} = response.data
  //       vm.order_number = order_number
  //       vm.merchant_name = merchant_name
  //       vm.merchant_number = merchant_number
  //     })
  //     vm.fetchData()
  //   })
  // },
  created() {
    this.routeId=  parseInt(this.$route.params.id)
    getOrderInfo(this.routeId).then(response => {
      let {order_number,merchant_name, merchant_number} = response.data
      this.order_number = order_number
      this.merchant_name = merchant_name
      this.merchant_number = merchant_number
    })
    this.fetchData()
  },
  methods: {
    editProduct(){
      this.edit({id:this.currentRecord})
    },
    amoutChange(value){
      this.form.remain_amount = value
    },
    showPackage(){
      this.currentRecord = this.$route.params.id
     this.$router.push({path: '/order/' + this.currentRecord + '/package'})
    },
    showContent() {
     this.$router.push({path: '/order/index'})
    },
    deleteHandle() {
      this.del({
        id: this.currentRecord
      },'删除该产品，将同时删除该产品对应的包裹信息，是否继续')
    },
    find(event) {
      this.$nextTick()
        .then(() => {
          this.fetchData();
        })
    },

    dblHandle(row, column, event) {
      this.edit({id:row.id})
    },
    rowHandle(row, column, event) {
      this.getIndex = row.index // index
    },
    contextHandle(row, column, event) {
      this.getIndex = row.index
      this.currentRecord = row.id;
    },
    tableRowClassName({
      row,
      rowIndex
    }) {
      //把每一行的索引放进row
      row.index = rowIndex // row.id rowIndex;
    },
    selectedHighlight({
      row,
      rowIndex
    }) {

      if ((this.getIndex) === (rowIndex)) {
        return {
          "background-color": "#CAE1ff"
        };
      }
    },
    // 搜索相关
    find() {
      this.fetchData();
    },
    findReset() {
      let obj = this.searchModel;
      this.searchForm = new obj();
      this.fetchData();
    },
    fetchData(
      searchObj = this.searchForm,
      page = this.current_page,
      pageSize = this.pageSize,
      id = this.currentRecord
    ) {
      this.loading = true;
      getInfo(searchObj, page, pageSize,this.routeId)
        .then(response => {
          //成功执行内容
          if(typeof this.listHandle === "function") { //是函数    其中 FunName 为函数名称
               this.listHandle(response)
           } else { //不是函数
             let result = response.data;
             this.tableData = result;
         }
          this.total = response.meta.total;
          this.loading = false;
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
          this.loading = false;
        });
    },
    // 查询数据 获取信息列表
    add() {
      let obj = this.model
      this.form = new obj();
      this.editDialogFormVisible = true;
      this.isNew = true;
    },
    save() {
      this.$refs['ruleForm'].validate((valid) => {
          if (valid) {
            this.editDialogFormVisible = false;
            if (this.isNew) {
              this.isNew = false;
              this.newData();
            }
            if (this.isEdit) {
              this.isEdit = false;
              this.updateData();
            }
          } else {
              return false;
            }
          });
    },
    cancel() {
      this.editDialogFormVisible = false;
      this.isNew = false;
      this.isEdit = false;
    },
    edit(row) {  // 获取某个信息
      let id = row.id;
      this.uploadId = id;
      getInfoById(id).then(response => {
          // 构建前端使用的数据 如果存在数据处理的函数，则调用函数
          if(typeof this.editHandle === "function") { //是函数    其中 FunName 为函数名称
               this.editHandle(response)
           } else { //不是函数
             let result = response.data
             this.form = result
         }
        this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    updateData() {
      updateInfo(this.uploadId, this.form)
        .then(response => {
          //成功执行内容
          let result = response.status_code;
          if (result == 200) {
            this.fetchData();
            this.tools.success(this, "信息更新成功");
          }
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
        });
    },
    newData() {
      this.form.order_id = this.routeId
      addInfo(this.form)
        .then(response => {
          this.tools.success(this, "功能信息添加成功");
          this.fetchData();
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
        });
    },
    // 删除  删除单个 批量删除
    del(row,tips = "删除该产品，将同时删除该产品对应的包裹信息，是否继续") {
      this.$confirm(tips, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        let id = row.id;
        let record = null;
        deleteInfoById(id)
          .then(response => {
            let result = response.status_code;
            if (result == 200) {
              this.$message({
                type: "success",
                message: "删除成功!"
              });
              this.fetchData();
            }
          })
          .catch(err => {
            //失败执行内容
            this.tools.error(this, err.response.data);
          });
      });
    },
    selectChange(selection) {
      this.multiSelect = selection;
    },
    delAll() {
      // 删除所有的数据
      this.$confirm("此操作将永久删除该功能, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let arr = [];
          this.multiSelect.forEach(item => {
            arr.push(item.id);
          });
          deleteAll(arr)
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
    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData();
    },
    sizeChange(val) {
      this.pageSize = val;
      this.fetchData();
    }
  }
}
</script>

<style lang="scss">
@import "./../../../styles/app-main.scss";
#config .el-input {
    width: 100%;
    margin-left: 10px;
}
#config .el-input-number{
      width: 90%;
    margin-left: 10px;
}
#datagrid {
    .toolbar {
        margin-bottom: 10px;
        .tips{
           line-height: 40px;
           height: 40px;
           margin:0px;
           padding:0px 10px;
           span{
             color: #f44;
           }
        }
    }
    .page {
        margin-top: 10px;
    }
}
.el-button--mini.is-round,
.toolbar-config .el-button--mini {
    padding: 10px;
}
.toolbar-config .el-button+.el-button {
    margin-left: 5px;
}
</style>
