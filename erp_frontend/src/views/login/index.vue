<template>
  <div class="login-container">
   <el-form autoComplete="on" :model="loginForm" :rules="loginRules" ref="loginForm" label-position="left" label-width="0px"  class="card-box login-form">
       <h3 class="title">ERP管理系统</h3>
         <el-form-item prop="username">
             <el-input prefix-icon="el-icon-search" type="text" name="username" v-model="loginForm.username"  placeholder="登陆名">  </el-input>
         </el-form-item>
        <el-form-item prop="password">
        <el-input prefix-icon="el-icon-search" name="password" type="password" @keyup.enter.native="handleLogin" v-model="loginForm.password" autoComplete="on"
          placeholder="密码"></el-input>
      </el-form-item>
      <el-form-item style="border-width: 0px;">
        <el-button type="primary" style="width: 100%; border-radius: 0px;" :loading="loading" @click.native.prevent="handleLogin">
          登录
        </el-button>
        <!-- <div class="github" title="使用github登录" @click.prevent="openGithub">
          <a @click.prevent="openGithub" title="使用github登录">
            <svg-icon icon-class="social-github" style="color: #f80"/>
          </a>
        </div> -->
      </el-form-item>

    </el-form>
  </div>
</template>

<script>


import { isvalidUsername } from "@/utils/validate";
import {config} from "@/config/index";
import { getToken, setToken, removeToken } from '@/utils/auth'
// let UUID = require("uuidjs");

export default {
  name: "login",
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!isvalidUsername(value)) {
        callback(new Error("请输入正确的用户名"));
      } else {
        callback();
      }
    };
    const validatePass = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error("密码不能小于6位"));
      } else {
        callback();
      }
    };
    return {
      loginForm: {
        username: "",
        password: ""
      },
      loginRules: {
        username: [{ required: true, trigger: "blur" }],
        password: [{ required: true, trigger: "blur", validator: validatePass }]
      },
      loading: false,
      githubAddress: config.github,
    };
  },
  methods: {
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store
            .dispatch("Login", this.loginForm)
            .then(() => {
              this.loading = false;
              this.$router.push({ path: "/" });
            })
            .catch(error => {
              let result = error.response;
              if (result.status == 421) {
                this.$message.error("登录名或者登录密码不正确，无法登录");
                this.loading = false;
              }
            });
        } else return false;
      });
    },
    
    // openGithub() {
    //   sessionStorage.setItem('uuid', UUID.generate());
    //   var url = this.githubAddress + '?time='+sessionStorage.getItem('uuid');
    //   window.window1 = window.open(url, 'githubLogin', "height=400, width=400, top=10,left=10, resizeable='yes', location='no'")
    // }
  },
  created: function() {
    // 第三方登录监听  暂时不需要
    // window.Echo.channel('github').listen('GithubLogin', e => {
    //   // 后端使用github第三方登录，第一次必须绑定指定的用户
    //   var uuid = e.user.time
    //   if (uuid == sessionStorage.getItem('uuid')){
    //     sessionStorage.setItem('platformId', e.user.id);
    //     sessionStorage.setItem('provider', e.user.provider);
    //     if (window1 && window1.close) {
    //       window1.close();
    //     }
    //     this.$router.push({ path: "/bind" })
    //   }
    // })
    // window.Echo.channel('githubSuccess').listen('GithubLoginSuccess', e => {
    //   // 后端使用github登录后，前端通过token登录
    //   var uuid = e.data.time
    //   if (uuid == sessionStorage.getItem('uuid')){
    //     setToken(e.data.token)
    //     if (window1 && window1.close) {
    //       window1.close();
    //     }
    //     this.$store.commit('SET_TOKEN', e.data.token)
    //     this.$router.push({ path: "/" })
    //   }
    // })
  }
};
</script>

<style rel="stylesheet/scss" lang="scss">
$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: #eee;

.github {
  margin-left: 5px;
  width: 18%;
  text-align: center;
  float:right;
  border: 1px solid #999;
  background: #999
}
div.github:hover{
  background: #ccc;
  border: 1px solid #ccc;
  cursor: pointer;
}
.login-container {
  height: 100vh;
  background-color: $bg;
  input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0px 1000px #293444 inset !important;
    -webkit-text-fill-color: #fff !important;
  }
  input {
    background: transparent;
    border: 0px;
    -webkit-appearance: none;
    border-radius: 0px;
    padding: 12px 5px 12px 15px;
    color: $light_gray;
    height: 47px;
  }
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;
  }
  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;
  }
  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    &_login {
      font-size: 20px;
    }
  }
  .title {
    font-size: 26px;
    font-weight: 400;
    color: $light_gray;
    margin: 0px auto 40px auto;
    text-align: center;
    font-weight: bold;
  }
  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 400px;
    padding: 35px 35px 15px 35px;
    margin: 120px auto;
  }
  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
  }
  .thirdparty-button {
    position: absolute;
    right: 35px;
    bottom: 28px;
  }
}
</style>
