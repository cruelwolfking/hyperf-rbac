<template>
  <div>
  <el-card class="box-card" style="margin-bottom: 20px;margin-top: 20px">
    <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate" >
      添加
    </el-button>
  </el-card>

  <el-card class="box-card" >
      <el-tree
        :data="data"
        show-checkbox
        default-expand-all
        node-key="id"
        ref="tree"
        draggable
        highlight-current
        :props="defaultProps"
        @node-drop="handleDrop"
      >
       <span class="custom-tree-node" slot-scope="{ node, data }">
        <span>{{ data.title}}</span>
        <span>
          <el-button
            type="text"
            size="mini"
            @click="() => append(data)">
            编辑
          </el-button>
          <el-button
            type="text"
            size="mini"
            @click="() => remove(node, data)">
            删除
          </el-button>
        </span>
      </span>
      </el-tree>
  </el-card>

    <el-dialog title="编辑权限" :visible.sync="dialogEditFormVisible">
      <el-form :model="form">
        <el-form-item label="权限标识" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="权限名称" :label-width="formLabelWidth">
          <el-input v-model="form.title" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="editSubmit">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog title="新增权限" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item label="上级" :label-width="formLabelWidth">
          <!--<el-cascader-->
            <!--:options="data"-->
            <!--:props="props"-->
            <!--clearable></el-cascader>-->
          <el-select v-model="form.pid" placeholder="请选择">
            <el-option value="0" label="一级">一级菜单</el-option>
            <el-option
              v-for="item in list"
              :key="item.id"
              :label="item.title"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="权限名称" :label-width="formLabelWidth">
          <el-input v-model="form.title" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="权限标识(字母)" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>


      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="createSubmit">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import { getData , updateData,changePid,deleteData,createData} from '@/api/permission'

export default {
  name: 'permissions',
  data() {
    return {
      data: [],
      defaultProps: {
        children: 'child',
        label: 'title'
      },
      dialogEditFormVisible:false,
      form: {
        id:0,
        name: '',
        title: '',
        pid:null
      },
      formLabelWidth: '120px',
      dialogFormVisible:false,
      list :[],
      props:{
        checkStrictly : true,
        children: 'child',
        label: 'title'
      }
    }
  },
  created() {
     this.getList()
  },
  methods: {
    async getList() {
      const { data } = await getData()
      this.data = data;
    },
    append(data) {
      this.form.id = data.id;
      this.form.name = data.name;
      this.form.title = data.title ;
      this.dialogEditFormVisible =true;
    },
    editSubmit(){
      updateData(this.form).then(res=>{
          if(res.code ===200){
            this.$message.success(res.msg);
            this.dialogEditFormVisible = false;
            this.getList()
          }else{
            this.$message.error(res.msg);
          }
      }).catch(err=>{
          console.log(err)
      })
    },
    remove(node, data) {
      deleteData(data.id).then(res=>{
        if(res.code === 200){
          const parent = node.parent;
          const children = parent.data.child|| parent.data;

          const index = children.findIndex(d => d.id === data.id);
          children.splice(index, 1);
          this.$message.success(res.msg)
        }else{
          this.$message.error(res.msg)
        }
      }).catch(err=>{
        console.log(err)
      })
    },
    //拖拽事件
    handleDrop(draggingNode, dropNode, dropType, ev) {
      console.log(draggingNode);
      if(dropNode.parent.key ===undefined){
        const pid = 0;
        const id = draggingNode.key;
        this.changeParent(id,pid);
      }else{
        const pid = dropNode.parent.key;
        const id = draggingNode.key;
        this.changeParent(id,pid);
      }
    },
    //执行拖拽排序
    changeParent(id,pid){
      const data={
        id:id,
        pid:pid
      };
      changePid(data).then(res=>{
        if(res.code ===200){
          this.$message.success('调整成功')
          this.getList()
        }else{
          this.$message.error(res.msg);
        }
      }).catch(err=>{
        console.log(err)
      })
    },
    /**
     * 生成上级options 可优化
     */
    handleCreate(){
      this.dialogFormVisible= true;
      let arr =[];
      const data = this.data;
      //一层
      for(var i in data){

        if(data[i].child ){

          arr.push({id:data[i].id,title:'|-'+data[i].title})

          var data2 = data[i].child;

          for(var n in data2){

            arr.push({id:data2[n].id,title:'|----'+data2[n].title})

            if(data2[n].child ){

              //arr.push({id:data2[i].id,title:'|----'+data2[i].title})

              var data3= data2[n].child;

              for(var m in data3){

                arr.push({id:data3[m].id,title:'|--------'+data3[m].title})

              }

            }

          }

        }else{
          arr.push({id:data[i].id,title:'|-'+data[i].title})
        }


      }
      this.list = arr;

    },
    createSubmit(){
      createData(this.form).then(res=>{
        if(res.code ===200){
          this.$message.success(res.msg)
          this.dialogFormVisible = false;
          this.getList()
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
.box-card {
  width: 90%;
  margin: auto;
}
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
}
</style>
