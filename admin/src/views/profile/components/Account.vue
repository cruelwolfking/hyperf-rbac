<template>
  <el-form>
    <el-form-item label="用户名">
      <el-input v-model.trim="user.name" />
    </el-form-item>
    <el-form-item label="昵称">
      <el-input v-model.trim="user.nickname" />
    </el-form-item>

    <el-form-item>
      <el-button type="primary" @click="submit">确认</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import {update} from '@/api/user'
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          nickname: '',
        }
      }
    }
  },
  methods: {
    submit() {

      const data = {
        name :this.user.name,
        nickname :this.user.nickname
      };
      console.log(data);
      update(data,this.$store.state.user.user_id).then(res=>{
        if(res.code ===200){
          this.$message.success(res.msg)
        }else{
          this.$message.error('异常错误')
        }
      }).catch(err=>{
        console.log()
      })
    }
  }
}
</script>
