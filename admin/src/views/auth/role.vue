<template>
  <div class="app-container">
    <el-card>
      <el-button class="filter-item" style="margin-left: 10px;margin-bottom: 20px" type="primary" icon="el-icon-edit" @click="dialogFormVisible = true">
        添加
      </el-button>

      <el-table  :data="list" border fit highlight-current-row style="width: 100%">
        <el-table-column align="center" label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>

        <el-table-column width="120px" align="center" label="角色名称">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column width="120px" align="center" label="角色">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>

        <el-table-column min-width="300px" label="权限列表">
          <template slot-scope="scope">
            <template >
              <el-row>
                <el-button  v-for="(item,index) in scope.row.permissions" :key="index" type="primary"  style="margin: 5px"> {{ item.title }}</el-button >
              </el-row>

            </template>

          </template>
        </el-table-column>

        <el-table-column align="center" label="编辑" width="240">
          <template slot-scope="{row}">

            <el-button
              type="primary"
              size="small"
              icon="el-icon-edit"
              @click="handleEdit(row)"
            >
              编辑
            </el-button>

            <el-button
              type="danger"
              size="small"
              icon="el-icon-delete"
              @click="handleDelete(row)"
            >
              删除
            </el-button>

          </template>
        </el-table-column>
      </el-table>
    </el-card>

  <el-dialog title="新增角色" :visible.sync="dialogFormVisible">
    <el-form :model="form">
      <el-form-item label="角色名称" :label-width="formLabelWidth">
        <el-input v-model="form.title" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="权限标识(字母)" :label-width="formLabelWidth">
        <el-input v-model="form.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="分配权限" :label-width="formLabelWidth">
        <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
        <div style="margin: 15px 0;"></div>
        <el-checkbox-group v-model="form.permissions" @change="handleCheckedChange">
          <el-checkbox v-for="(item,index) in permissions" :label="item.id"   >{{item.title}}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取 消</el-button>
      <el-button type="primary" @click="createSubmit">确 定</el-button>
    </div>
  </el-dialog>

    <el-dialog title="编辑角色" :visible.sync="dialogEditFormVisible">
      <el-form :model="form">
        <el-form-item label="角色名称" :label-width="formLabelWidth">
          <el-input v-model="form.title" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="权限标识(字母)" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="分配权限" :label-width="formLabelWidth">
          <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
          <div style="margin: 15px 0;"></div>
          <el-checkbox-group v-model="form.permissions" @change="handleCheckedChange">
            <el-checkbox v-for="(item,index) in permissions" :label="item.id"   >{{item.title}}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="editSubmit">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import { getRoles,addRole,updateRole,deleteRole } from '@/api/role'
import { getOptions } from "../../api/permission";

export default {
  name: 'InlineEditTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    }
  },
  data() {

    return {
      list: [],
      listLoading: false,
      listQuery: {
        page: 1,
        limit: 10
      },
      form:{
        name:'',
        title:'',
        permissions:null
      },
      checkAll: false,
      dialogFormVisible: false,
      formLabelWidth: '120px',
      permissions:[],
      isIndeterminate: true,
      dialogEditFormVisible: false,
    }
  },
  created() {
      this.getList()
      getOptions().then(res=>{
        if(res.code === 200){
          this.permissions = res.data
        }
      }).catch()
  },
  methods: {
    async getList() {
      this.listLoading = true
      const { data } = await getRoles(this.listQuery)

      this.list = data;
      this.listLoading = false
    },

    handleCheckAllChange(bool) {
      if(bool){
        let arr =[];
        for(var i in this.permissions){
          arr.push(this.permissions[i].id)
        }
        this.form.permissions = arr;
      }else {
        this.form.permissions =[];
      }
      this.isIndeterminate = false;
    },
    handleCheckedChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.permissions.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.permissions.length;
    },
    createSubmit(){
      addRole(this.form).then(res=>{
        if(res.code === 200){
          this.$message.success(res.msg)
          this.dialogFormVisible = false;
          this.getList();
        }else{
          this.$message.error(res.msg)
        }
      }).catch(err=>{
        console.log(err)
      })
    },
    handleDelete(row){
      deleteRole(row.id).then(res=>{
        if(res.code === 200){
          this.$message.success(res.msg)
          this.dialogFormVisible = false;
          this.getList();
        }else{
          this.$message.error(res.msg)
        }
      }).catch(err=>{
        console.log(err)
      })
    },
    handleEdit(scope) {
      this.form.id = scope.id;
      this.form.name = scope.name;
      this.form.title = scope.title;
      let  arr = [];
      for (var i in scope.permissions){
        arr.push(scope.permissions[i].id)
      }
      this.form.permissions =arr ;

      this.dialogEditFormVisible = true;
    },
    editSubmit() {
      updateRole(this.form,this.form.id).then(res=>{
        if(res.code === 200){
          this.$message.success(res.msg)
          this.dialogEditFormVisible  = false;
          this.getList();
        }else{
          this.$message.error(res.msg)
        }
      }).catch(err=>{
        console.log(err)
      })
    }

  }
}
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
