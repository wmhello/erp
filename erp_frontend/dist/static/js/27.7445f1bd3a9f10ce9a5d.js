webpackJsonp([27,6],{Fdr0:function(e,t,o){var a=o("x03R");"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);o("rjj0")("6580dbd2",a,!0)},KA92:function(e,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.getInfo=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1,o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:10;return Object(a.a)({url:"/api/storages",method:"get",params:{page:t,pageSize:o,date:e.date,number:e.good_number}})},t.getInfoById=function(e){return Object(a.a)({url:"/api/storages/"+e,method:"get"})},t.updateInfo=function(e,t){return Object(a.a)({url:"/api/storages/"+e,method:"PATCH",data:t})},t.deleteInfoById=function(e){return Object(a.a)({url:"/api/storages/"+e,method:"delete"})},t.addInfo=function(e){return Object(a.a)({url:"/api/storages",method:"post",data:e})},t.deleteAll=function(e){return Object(a.a)({url:"/api/storages/deleteAll",method:"post",data:{ids:e}})},t.uploadFile=function(e){return Object(a.a)({url:"/api/storages/upload",method:"post",data:e,headers:{"Content-Type":"multipart/form-data"}})};var a=o("Vo7i")},LTf8:function(e,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=o("MGnS"),n={render:function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{attrs:{id:"storage"}},[o("el-form",{staticClass:"demo-form-inline",attrs:{id:"toolbar",inline:!0,model:e.searchForm}},[o("el-form-item",{attrs:{label:"商品编号"}},[o("el-input",{nativeOn:{keyup:function(t){if(!("button"in t)&&e._k(t.keyCode,"enter",13,t.key))return null;e.search(t)}},model:{value:e.searchForm.good_number,callback:function(t){e.$set(e.searchForm,"good_number",t)},expression:"searchForm.good_number"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"日期"}},[o("el-date-picker",{staticStyle:{width:"100%"},attrs:{type:"date","value-format":"yyyy-MM-dd",placeholder:"选择日期"},model:{value:e.searchForm.date,callback:function(t){e.$set(e.searchForm,"date",t)},expression:"searchForm.date"}})],1),e._v(" "),o("el-form-item",[o("el-button",{attrs:{plain:""},on:{click:function(t){e.find()}}},[e._v("查询")]),e._v(" "),o("el-button",{attrs:{type:"info",plain:""},on:{click:function(t){e.findReset()}}},[e._v("重置")])],1)],1),e._v(" "),o("div",{attrs:{id:"datagrid"}},[o("div",{staticClass:"toolbar"},[o("el-button",{attrs:{plain:"",icon:"el-icon-plus"},on:{click:function(t){e.add()}}},[e._v("添加入库商品")]),e._v(" "),o("el-button",{attrs:{plain:"",icon:"el-icon-plus"},on:{click:function(t){e.upload()}}},[e._v("入库商品导入")])],1),e._v(" "),o("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],attrs:{data:e.tableData,border:""},on:{"select-all":e.selectChange,"selection-change":e.selectChange}},[o("el-table-column",{attrs:{type:"selection",width:"55"}}),e._v(" "),o("el-table-column",{attrs:{label:"序号",prop:"id",width:"50"}}),e._v(" "),o("el-table-column",{attrs:{label:"商品编号",prop:"good_number",width:"200"}}),e._v(" "),o("el-table-column",{attrs:{label:"商品名称",prop:"good_name",width:"200"}}),e._v(" "),o("el-table-column",{attrs:{label:"入库数量",prop:"good_count",width:"100"}}),e._v(" "),o("el-table-column",{attrs:{label:"时间",prop:"date",width:"100"}}),e._v(" "),o("el-table-column",{attrs:{label:"备注",prop:"remark",width:"100"}}),e._v(" "),o("el-table-column",{attrs:{label:"操作"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-tooltip",{attrs:{content:"编辑",placement:"right-end",effect:"light"}},[o("el-button",{directives:[{name:"has",rawName:"v-has",value:"cates.show",expression:"'cates.show'"}],attrs:{plain:"",icon:"el-icon-edit",type:"primary",size:"small"},on:{click:function(o){e.edit(t.row)}}})],1),e._v(" "),o("el-tooltip",{attrs:{content:"删除",placement:"right-end",effect:"light"}},[o("el-button",{directives:[{name:"has",rawName:"v-has",value:"cates.destory",expression:"'cates.destory'"}],attrs:{plain:"",icon:"el-icon-delete",type:"danger",size:"small"},on:{click:function(o){e.del(t.row)}}})],1)]}}])})],1),e._v(" "),o("el-row",{staticClass:"page"},[o("el-col",{attrs:{span:2,offset:1}},[o("el-button",{attrs:{type:"danger",plain:""},on:{click:function(t){e.delAll()}}},[e._v("删除选择")])],1),e._v(" "),o("el-col",{attrs:{span:20}},[o("el-pagination",{attrs:{background:"","current-page":e.current_page,"page-sizes":[10,20,25,50],layout:"total,sizes,prev, pager, next","page-size":e.pageSize,total:e.total},on:{"current-change":e.pagination,"size-change":e.sizeChange,"update:currentPage":function(t){e.current_page=t},"update:pageSize":function(t){e.pageSize=t}}})],1)],1)],1),e._v(" "),o("el-dialog",{attrs:{title:"商品入库管理",center:"",visible:e.editDialogFormVisible,"close-on-click-modal":!1},on:{"update:visible":function(t){e.editDialogFormVisible=t}}},[o("el-form",{ref:"ruleForm",attrs:{model:e.form,rules:e.rules,"label-width":"100px"}},[o("el-row",[o("el-col",{attrs:{span:12}},[o("el-form-item",{attrs:{label:"商品编号",prop:"good_number"}},[o("el-input",{attrs:{disabled:!0===e.isEdit},model:{value:e.form.good_number,callback:function(t){e.$set(e.form,"good_number",t)},expression:"form.good_number"}})],1)],1),e._v(" "),o("el-col",{attrs:{span:12}},[o("el-form-item",{attrs:{label:"入库时间",prop:"date"}},[o("el-date-picker",{attrs:{type:"date",placeholder:"选择日期","value-format":"yyyy-MM-dd"},model:{value:e.form.date,callback:function(t){e.$set(e.form,"date",t)},expression:"form.date"}})],1)],1)],1),e._v(" "),o("el-row",[o("el-col",{attrs:{span:12}},[o("el-form-item",{attrs:{label:"入库数量",prop:"good_count"}},[o("el-input-number",{attrs:{min:1,label:"入库数量"},model:{value:e.form.good_count,callback:function(t){e.$set(e.form,"good_count",t)},expression:"form.good_count"}})],1)],1),e._v(" "),o("el-col",{attrs:{span:12}},[o("el-form-item",{attrs:{label:"备注",prop:"remark"}},[o("el-input",{attrs:{placeholder:"请输入商品备注"},model:{value:e.form.remark,callback:function(t){e.$set(e.form,"remark",t)},expression:"form.remark"}})],1)],1)],1)],1),e._v(" "),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){e.handleSave()}}},[e._v("确 定")]),e._v(" "),o("el-button",{on:{click:function(t){e.cancel()}}},[e._v("取 消")])],1)],1),e._v(" "),o("el-dialog",{attrs:{title:"入库商品导入",width:"40%",center:"",visible:e.isShowUpload,"close-on-click-modal":!1},on:{open:e.initUpload,close:function(t){e.closeUpload()}}},[o("el-form",{attrs:{"label-width":"100px"}},[o("el-form-item",{attrs:{label:"日期"}},[o("el-date-picker",{attrs:{type:"date","value-format":"yyyy-MM-dd",placeholder:"请选择日期"},model:{value:e.uploadDate,callback:function(t){e.uploadDate=t},expression:"uploadDate"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"模板"}},[o("el-button",{attrs:{size:"small",type:"text"},on:{click:function(t){e.downloadTemplate()}}},[e._v("下载模板")])],1),e._v(" "),o("el-form-item",{attrs:{label:"选择与上传",prop:"file"}},[o("el-upload",{ref:"upload",staticClass:"upload-demo",attrs:{action:"123",accept:".xlsx","auto-upload":!1,"before-upload":e.beforeUpload}},[o("el-button",{attrs:{slot:"trigger",size:"small",type:"primary"},slot:"trigger"},[e._v("选择文件")]),e._v(" "),o("el-button",{staticStyle:{"margin-left":"10px"},attrs:{size:"small",type:"success"},on:{click:e.submitUpload}},[e._v("上传到服务器")]),e._v(" "),o("div",{staticClass:"el-upload__tip",attrs:{slot:"tip"},slot:"tip"},[e._v("只能上传xlsx文件。")])],1)],1)],1)],1)],1)},staticRenderFns:[]};var l=function(e){o("Fdr0")},r=o("VU/8")(a.a,n,!1,l,null,null);t.default=r.exports},MGnS:function(module,__webpack_exports__,__webpack_require__){"use strict";var __WEBPACK_IMPORTED_MODULE_0__minix_curd__=__webpack_require__("wtDQ"),__WEBPACK_IMPORTED_MODULE_1__minix_upload__=__webpack_require__("UM7R"),__WEBPACK_IMPORTED_MODULE_2__api_storage__=__webpack_require__("KA92"),__WEBPACK_IMPORTED_MODULE_3__api_good__=__webpack_require__("1x0Q"),__WEBPACK_IMPORTED_MODULE_4__model_storage__=__webpack_require__("nh80"),__WEBPACK_IMPORTED_MODULE_5__config_index__=__webpack_require__("2uFj"),__WEBPACK_IMPORTED_MODULE_6__views_utils_Tools__=__webpack_require__("TvqX");__webpack_exports__.a={name:"Storage",mixins:[__WEBPACK_IMPORTED_MODULE_0__minix_curd__.a,__WEBPACK_IMPORTED_MODULE_1__minix_upload__.a],data:function(){return{searchForm:new __WEBPACK_IMPORTED_MODULE_4__model_storage__.c,form:new __WEBPACK_IMPORTED_MODULE_4__model_storage__.a,rules:__WEBPACK_IMPORTED_MODULE_4__model_storage__.b,searchModel:__WEBPACK_IMPORTED_MODULE_4__model_storage__.c,model:__WEBPACK_IMPORTED_MODULE_4__model_storage__.a,tools:__WEBPACK_IMPORTED_MODULE_6__views_utils_Tools__.a,curd:{getInfo:__WEBPACK_IMPORTED_MODULE_2__api_storage__.getInfo||function(){},getInfoById:__WEBPACK_IMPORTED_MODULE_2__api_storage__.getInfoById||function(){},updateInfo:__WEBPACK_IMPORTED_MODULE_2__api_storage__.updateInfo||function(){},addInfo:__WEBPACK_IMPORTED_MODULE_2__api_storage__.addInfo||function(){},deleteInfoById:__WEBPACK_IMPORTED_MODULE_2__api_storage__.deleteInfoById||function(){},deleteAll:__WEBPACK_IMPORTED_MODULE_2__api_storage__.deleteAll||function(){}},types:["无"],templateFile:__WEBPACK_IMPORTED_MODULE_5__config_index__.a.site+"/xls/storages.xlsx",goods:[],uploadDate:null,errorFile:__WEBPACK_IMPORTED_MODULE_5__config_index__.a.site+"/xls/error.xlsx"}},created:function(){var e=this;this.fetchData(),Object(__WEBPACK_IMPORTED_MODULE_3__api_good__.getGoodsInfo)().then(function(t){e.goods=t.data})},filters:{},methods:{search:function(e){var t=document.querySelector(".el-input__inner");this.searchForm.good_number=t.value,this.fetchData()},downloadTemplate:function(){location.href=this.templateFile},initUpload:function(){this.uploadDate=null},submitUpload:function(){if(!this.uploadDate)return this.$message.error("请选择要上传的日期"),!1;this.$refs.upload.submit()},beforeUpload:function beforeUpload(file){var _this2=this,fd=new FormData;fd.append("file",file),fd.append("uploadDate",this.uploadDate),Object(__WEBPACK_IMPORTED_MODULE_2__api_storage__.uploadFile)(fd).then(function(res){res.info?__WEBPACK_IMPORTED_MODULE_6__views_utils_Tools__.a.success(_this2,res.info):__WEBPACK_IMPORTED_MODULE_6__views_utils_Tools__.a.success(_this2,"文件信息上传成功");try{"function"==typeof eval(_this2.fetchData)&&(_this2.fetchData(),_this2.closeUpload())}catch(e){console.log("没有相关函数")}}).catch(function(err){__WEBPACK_IMPORTED_MODULE_6__views_utils_Tools__.a.errorTips(_this2,err.response.data.message),location.href=_this2.errorFile;try{"function"==typeof eval(_this2.fetchData)&&(_this2.fetchData(),_this2.closeUpload())}catch(e){console.log("没有相关函数")}})},handleSave:function(){var e=this,t=this.goods.findIndex(function(t){return t.number===e.form.good_number});if(-1===t)return this.$message.error("输入的商品编号不存在，请重新输入"),!1;this.form.good_id=this.goods[t].id,this.save()}}}},nh80:function(e,t,o){"use strict";t.a=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null,n=arguments.length>4&&void 0!==arguments[4]?arguments[4]:"";this.good_id=e,this.good_number=t,this.good_count=o,this.date=a,this.remark=n},t.c=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;this.good_number=e,this.date=t},o.d(t,"b",function(){return a});var a={good_number:[{required:!0,message:"请选择入库商品",trigger:"blur"}],good_count:[{required:!0,message:"请设置入库数量",trigger:"blur"}],date:[{type:"string",required:!0,message:"请选择日期",trigger:"change"}]}},x03R:function(e,t,o){(e.exports=o("FZ+f")(!1)).push([e.i,"\n#toolbar {\n  background-color: #e8e8e8 !important;\n  margin-bottom: 5px;\n}\n#toolbar .el-form-item {\n    margin-bottom: 5px;\n    margin-top: 5px;\n    margin-left: 5px;\n}\n#datagrid {\n  border: 1px solid #ddd;\n  padding-bottom: 5px;\n}\n#datagrid .toolbar {\n    padding-left: 5px;\n    margin-bottom: 5px;\n    margin-top: 5px;\n}\n#datagrid .page {\n    margin-top: 5px;\n    height: 40px;\n}\n#datagrid .page .el-col-2 {\n      text-align: center;\n      min-width: 120px;\n      height: 100%;\n}\n#datagrid .page .el-col-20 {\n      line-height: 40px;\n      height: 100%;\n}\n#datagrid .page .el-col-20 .el-pagination {\n        padding: 0;\n        margin-top: 5px;\n        text-align: center;\n}\n#storage.el-input {\n  width: 200px;\n  margin-left: 10px;\n}\n#datagrid .toolbar {\n  margin-bottom: 10px;\n}\n#datagrid .page {\n  margin-top: 10px;\n}\n",""])}});