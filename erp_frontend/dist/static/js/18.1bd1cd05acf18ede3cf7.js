webpackJsonp([18],{"76vU":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.getInfo=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1,a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:10;return Object(o.a)({url:"/api/cacls",method:"get",params:{page:t,pageSize:a,good_number:e.good_number,cate_id:e.cate_id}})},t.getInfoById=function(e){return Object(o.a)({url:"/api/cacls/"+e,method:"get"})},t.updateInfo=function(e,t){return Object(o.a)({url:"/api/cacls/"+e,method:"PATCH",data:t})},t.deleteInfoById=function(e){return Object(o.a)({url:"/api/cacls/"+e,method:"delete"})},t.addInfo=function(e){return Object(o.a)({url:"/api/cacls",method:"post",data:e})},t.deleteAll=function(e){return Object(o.a)({url:"/api/cacls/deleteAll",method:"post",data:{ids:e}})},t.exportFile=function(){return Object(o.a)({url:"/api/cacls/export",method:"post"})};var o=a("Vo7i")}});