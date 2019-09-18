<template>
  <el-form>
    <el-form-item label="旧密码">
      <el-input type="password" v-model.trim="oldpwd" />
    </el-form-item>
    <el-form-item label="新密码">
      <el-input type="password" v-model.trim="password" />
    </el-form-item>
    <el-form-item label="确认密码">
      <el-input type="password" v-model.trim="confirm_password" />
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submit">确认</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
  import {resetUserPassword} from '@/api/user'
export default {
  data(){
   return {
     oldpwd: null,
     password: null,
     confirm_password: null,
   }
  },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name:'',
          user_id:''
        }
      }
    }
  },
  methods: {
    submit() {
      if(this.password !==this.confirm_password){
        this.$message.error('两次密码输入不一致')
      }

      if(this.oldpwd === null){
        this.$message.error('旧密码不能为空')
      }
      const data = {
        oldpassword: this.oldpwd,
        password: this.password,
        confirm_password:this.confirm_password,
        name:this.user.name,
        user_id: this.user.user_id
      };

      console.log(data);
      resetUserPassword(data).then(res=>{
        if(res.code ===200){
          this.$message.success(res.msg)
        }else{
          this.$message.error('异常错误')
        }
      }).catch(err=>{
        console.log(err)
      })
    }
  }
}
</script>
