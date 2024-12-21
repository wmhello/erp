<template>
<div id="config">
  <div id="datagrid">
    <div class="toolbar">
    </div>
    <!-- 信息表 -->
    <el-table v-contextmenu:contextmenu size='mini' :data="tableData" border v-loading="loading" height="250">
      <el-table-column label="产品编号" prop="product_number" width="150">
      </el-table-column>
      <el-table-column label="产品名称" prop="product_name" width="150">
      </el-table-column>
      <el-table-column label="产品图片" width="100">
        <template slot-scope="scope">
          <img :src="scope.row.product_img" style="display:block;width:80px;height:60px;">
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
      <el-table-column label="备注" prop="remark">
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
      </el-col>
      <el-col :span="20">
        <el-pagination background @current-change="pagination" @size-change="sizeChange" :current-page.sync="current_page" :page-sizes="[10,20,25,50]" layout="total,sizes,prev, pager, next" :page-size.sync="pageSize" :total="total">
        </el-pagination>
      </el-col>
    </el-row>
  </div>
  <!-- 第一个表格的右键菜单 -->
  <v-contextmenu ref="contextmenu">
    <v-contextmenu-item @click="add">分割包裹</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="showProduct">返回产品</v-contextmenu-item>
    <v-contextmenu-item @click="showContent">返回订单</v-contextmenu-item>
  </v-contextmenu>

  <v-contextmenu ref="packageOperation">
    <v-contextmenu-item @click="add">分割包裹</v-contextmenu-item>
    <v-contextmenu-item @click="showMenuHandle">显示包裹</v-contextmenu-item>
    <v-contextmenu-item @click="deleteHandle">删除包裹</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="showProduct">返回产品</v-contextmenu-item>
    <v-contextmenu-item @click="showContent">返回订单</v-contextmenu-item>
  </v-contextmenu>

  <div id="package" style="margin-top:10px;">
    <div class="toolbar">
      <el-row>
        <el-col :span="6">
        </el-col>
        <el-col :span="18">
          <p class="tips" v-html="summary"></p>
          <p class="tips" v-html="packageSummary"></p>
        </el-col>
      </el-row>
    </div>
    <!-- 包裹表 -->
    <el-table v-contextmenu:packageOperation :data="packageData" border height="550" @row-contextmenu='contextHandle' :row-class-name="tableRowClassName" :row-style="selectedHighlight" @row-dblclick="dblHandle" @row-click="rowHandle">
      <el-table-column label="包裹标识" prop="package_id" width="100">
      </el-table-column>
      <el-table-column label="物流编号" prop="logistics_number" width="150">
      </el-table-column>
      <el-table-column label="物流日期" width="150">
        <template slot-scope="scope">
          <span>{{scope.row.logistics_date|filterLogisticsDate}}</span>
        </template>
      </el-table-column>
      <el-table-column label="产品信息" prop="info" width="250">
      </el-table-column>
      <el-table-column label="备注" prop="remark" width="200">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="显示包裹" placement="right-end" effect="light">
            <el-button plain icon="el-icon-view" type="primary" size="small" @click="show(scope.row.package_id)"></el-button>
          </el-tooltip>
          <el-tooltip content="增加备注" placement="right-end" effect="light">
            <el-button plain icon="el-icon-document"  type="primary" size="small" @click=" addRemark(scope.row.package_id)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除包裹" placement="right-end" effect="light">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row.package_id)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>

    </el-table>
    <!-- 分页 -->
  </div>

 <!-- 添加备注 -->
<el-dialog title="备注信息" :visible.sync="isRemark"  width="30%">
    <el-input v-model="packageRemark" placeholder="请输入包裹的备注信息"></el-input>
  <div slot="footer" class="dialog-footer">
    <el-button @click="isRemark = false">取 消</el-button>
    <el-button type="primary" @click="saveRemark">确 定</el-button>
  </div>
</el-dialog>


  <!-- 显示包裹信息对话框 -->
  <el-dialog title="包裹信息" :visible.sync="detailsTableVisible">
    <el-table :data="detailsInfo">
      <el-table-column property="product_number" label="产品编号" width="150"></el-table-column>
      <el-table-column property="product_name" label="产品名称" width="100"></el-table-column>
      <el-table-column label="产品图象" width="200">
        <template slot-scope="scope">
          <img :src="scope.row.product_img" style="width: 80px;height: 80px;"></span>
        </template>
      </el-table-column>
      <el-table-column property="product_amount" label="数量" width="100"></el-table-column>
    </el-table>
  </el-dialog>

  <!-- 添加包裹对话框 -->
  <el-dialog title="添加包裹信息" width="60%" :visible.sync="editDialogFormVisible">
    <el-table :data="enableProducts" size="mini" border heigth="250" @selection-change="handleSelectionChange">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column label="选择数量" width="200">
        <template slot-scope="scope">
          <el-input-number v-model="scope.row.value"  :min="1" :max="scope.row.remain_amount" label="描述文字"></el-input-number>
          <!-- <el-select v-model="scope.row.value">
            <el-option v-for="item in scope.row.remain_amount" :key="item" :value="item" :label="item"></el-option>
          </el-select> -->
        </template>
      </el-table-column>
      <el-table-column label="可分配数量" width="100" prop="remain_amount">
      </el-table-column>
      <el-table-column property="product_number" label="产品编号" width="150"></el-table-column>
      <el-table-column property="product_name" label="产品名称" width="100"></el-table-column>
      <el-table-column label="产品图象">
        <template slot-scope="scope">
          <img :src="scope.row.product_img" style="width: 50px;height: 40px;"></span>
        </template>
      </el-table-column>
    </el-table>
    <el-row style="margin-top: 10px;">
      <el-col :span="22" :offset="2">
        <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
          <el-form-item label="" prop="logistics_number">
            <el-input v-model="form.logistics_number" placeholder="物流编号"></el-input>
          </el-form-item>
          <el-form-item label="" prop="logistics_date">
            <el-date-picker v-model="form.logistics_date"
            type="datetime"
            placeholder="日期">
            </el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="success" @click="save">添加包裹</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </el-dialog>
</div>
</template>

<script>
import {
  getInfo
} from "@/api/product";

import {
  getInfoById as getOrderInfo,
  getSummaryById,
  getEnableProductsById
} from "@/api/order";

import {
  getPackagesByOrderId,
  getPackageInfoById,
  deleteInfoById,
  addInfo,
  addRemark
} from "@/api/package";



import {
  Model,
  SearchModel,
  Rules
} from '@/model/package'
import {
  config
} from "@/config/index"
import {
  Tools
} from "@/views/utils/Tools"


export default {
  name: 'order_package',
  data() {
    return {
      searchForm: new SearchModel(),
      form: new Model(),
      rules: Rules,
      searchModel: SearchModel,
      model: Model,
      tools: Tools,
      getIndex: null,
      currentRecord: null,
      routeId:null,
      packageId: null,
      tableData: [],
      packageData: [],
      editDialogFormVisible: false,
      detailsTableVisible: false, // 显示包裹的详细信息
      uploadId: "",
      loading: false,
      isNew: false,
      isEdit: false,
      current_page: 1,
      total: 0,
      pageSize: 10,
      // 信息提示
      order_number: null,
      merchant_name: '',
      merchant_number: '',
      product_count: 0,
      sum_amount: 0,
      sum_remain: 0,
      package: {
        current_page: 1,
        total: null,
        pageSize: 10
      },
      detailsInfo: [], // 包裹对应的详细信息，用于显示内容
      enableProducts: [],
      multipleSelection: [],
      isRemark: false,
      packageRemark: null,
      packageId: null

    }
  },

  filters: {
    filterOrderTime(value) {
      let tempDate = new Date(value);
      return tempDate.getFullYear() + '-' + (tempDate.getMonth() + 1) + '-' + tempDate.getDate();
    },
    filterLogisticsDate(value) {
      let tempDate = new Date(value);
      return tempDate.getFullYear() + '-' + (tempDate.getMonth() + 1) + '-' + tempDate.getDate();
    }
  },
  computed: {
    summary() {
      if (this.order_number) {
        return `订单编号： <span>${this.order_number} </span>,  商家编号: <span>${this.merchant_number} </span>,  商家名称： <span>${this.merchant_name} </span>`
      } else {
        return ''
      }
    },
    packageSummary() {
      if (this.product_count) {
        return `当前订单有产品 <span>${this.product_count} </span>类,  合计 <span>${this.sum_amount} </span>件商品,  已经发送 <span>${this.sum_amount-this.sum_remain} </span>件,  还剩<span>${this.sum_remain}</span>件未发送`
      } else {
        return ''
      }
    }
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      // 通过 `vm` 访问组件实例
    })
  },
  created(){
      this.routeId = this.$route.params.id
  },
  mounted() {
    this.getTipsInfo();
    this.fetchData();
  },
  methods: {
    async saveRemark(){
        this.isRemark = false
        let result = addRemark(this.packageId, this.packageRemark)
        this.tools.success(this, "备注信息添加成功");
        this.fetchData();
        this.getPackagesByOrderId();
    },
    addRemark(id){
      this.packageId = id
      this.packageRemark = ''
      this.isRemark = true
    },
    handleSelectionChange(val){
       this.multipleSelection = val;
    },
    save() {
      if (this.multipleSelection.length === 0) {
        this.$message.error('添加包裹失败,请选择对应的产品数量后再添加');
        return false;
      }
      this.$refs['ruleForm'].validate((valid) => {
        if (valid) {
          this.editDialogFormVisible = false;
           // 处理数据
          let selectData = this.multipleSelection
          selectData.forEach(item=>{
            let obj = {
              order_id: item.order_id,
              product_amount: item.value,
              product_id: item.id,
              package_id: null
            }
            this.form.items.push(obj)
          })
           // 添加数据
          addInfo(this.form)
            .then(response => {
              this.tools.success(this, "功能信息添加成功");
              this.fetchData();
            })
            .catch(err => {
              this.tools.error(this, err.response.data);
            });
        } else {
          return false;
        }
      });
    },
    add() {
      getEnableProductsById(this.routeId).then(res => {
        let result = res.data;
        result = result.map((item) => {
          item.value = 1
          item.remain_amount = parseInt(item.remain_amount)
          return item
        })
        this.form = new Model()
        this.enableProducts = result
        this.editDialogFormVisible = true
      })
    },
    // 右键菜单的处理
    showMenuHandle() {
      this.show()
    },
    deleteHandle() {
      let id = this.packageId
      this.del(id)
    },
    del(id) {
      this.$confirm('此操作将永久删除该包裹, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteInfoById(id).then(res => {
          this.fetchData();
        })
        this.$message({
          type: 'success',
          message: '删除成功!'
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });
      });

    },
    show(id = 0) {
      id = id || this.packageId
      getPackageInfoById(id).then(res => {
        this.detailsInfo = res.data;
        this.detailsTableVisible = true;
      }).catch(err => {

      })
    },
    dblHandle(row, column, event) { // 双击编辑
      getPackageInfoById(row.package_id).then(res => {
        this.detailsInfo = res.data;
        this.detailsTableVisible = true;
      }).catch(err => {})
    },
    rowHandle(row, column, event) { // 单击行
      this.getIndex = row.index // index
    },
    contextHandle(row, column, event) { // 右键单击
      this.getIndex = row.index
      this.packageId = row.package_id;
    },
    tableRowClassName({
      row,
      rowIndex
    }) {
      row.index = rowIndex
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
    getTipsInfo() {
      getOrderInfo(this.routeId).then(response => {
        let {
          order_number,
          merchant_name,
          merchant_number
        } = response.data
        this.order_number = order_number
        this.merchant_name = merchant_name
        this.merchant_number = merchant_number
      })
      getSummaryById(this.routeId).then(response => {
        let {
          product_count,
          sum_amount,
          sum_remain
        } = response.data
        this.product_count = product_count
        this.sum_amount = sum_amount
        this.sum_remain = sum_remain
      })
    },
    showProduct() {
      this.$router.push({
        path: '/order/' + this.routeId + '/products'
      })
    },
    showContent() {
      this.$router.push({
        path: '/order/index'
      })
    },

    // 搜索相关
    fetchData(
      searchObj = this.searchForm,
      page = this.current_page,
      pageSize = this.pageSize,
    ) {
      this.loading = true;
      getInfo(searchObj, page, pageSize, this.routeId)
        .then(response => {
          //成功执行内容
          if (typeof this.listHandle === "function") { //是函数    其中 FunName 为函数名称
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
      // 获取当前订单的表单
      getPackagesByOrderId(this.routeId).then(res => {
        this.packageData = res.data;
      }).catch(err => {})
      this.getTipsInfo()
    },

    pagination(val) {
      this.current_page = val;
      this.fetchData();
    },
    sizeChange(val) {
      this.pageSize = val;
      this.fetchData();
    },
    // 包裹信息的分页
    packagePagination(val) {
      this.package.current_page = val;
    },
    packageSizeChange(val) {
      this.package.pageSize = val;
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
#config .el-input-number {
    width: 90%;
    margin-left: 10px;
}
#datagrid {
    .toolbar {
        margin-bottom: 10px;
        .tips {
            line-height: 40px;
            height: 40px;
            margin: 0;
            padding: 0 10px;
            span {
                color: #f44;
            }
        }
    }
    .page {
        margin-top: 10px;
    }
}

#package {
    .toolbar {
        margin-bottom: 10px;
        .tips {
            line-height: 40px;
            height: 40px;
            margin: 0;
            padding: 0 10px;
            span {
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
