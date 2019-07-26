<template>
  <div>
    <div class="content-body">
      <h2>帳號管理</h2>
      <el-button
        type="primary"
        :data="addFormStatus"
        @click="addFormStatus = true"
      >新增帳號</el-button>
      <el-table
        :data="tableData"
        style="width: 90%"
        ref="table">
        <el-table-column
          prop="id"
          label="ID"
        >
        </el-table-column>
        <el-table-column
          prop="permission"
          label="權限"
        >
          <template slot-scope="scope">
            <el-tag type="success" v-if="scope.row.permission ==='1'">管理員</el-tag>
            <el-tag type="success" v-else>會員</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="account"
          label="帳號"
        >
        </el-table-column>
        <el-table-column
          prop="name"
          label="暱稱"
        >
        </el-table-column>
        <el-table-column
          label="狀態" 
        >
          <template slot-scope="scope">
            <el-tag type="success" v-if="scope.row.locked ==='0'">正常</el-tag>
            <el-tag type="danger" v-else>停權</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="修改">
          <template slot-scope="scope">
            <el-button
              type="primary"
              icon="el-icon-edit"
              title="修改"
              @click="editMember(scope.row)"
              size="small"
            ></el-button>
          </template>
        </el-table-column>
      </el-table>

      <!-- dialog add form -->
      <el-dialog title="新增資料" :visible.sync="addFormStatus">
        <el-form :model="addForm"
                 label-position="right"
                 :rules="rules"
                 ref="addForm">
          <el-form-item label="帳號" label-width="120px" prop="Account">
            <el-input v-model="addForm.Account" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="密碼" label-width="120px" prop="Password">
            <el-input v-model="addForm.Password" :disabled="false" type="password"></el-input>
          </el-form-item>
          <el-form-item label="暱稱" label-width="120px" prop="Name">
            <el-input v-model="addForm.Name" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="權限" label-width="120px" prop="Permission">
            <el-select v-model="addForm.Permission" placeholder="請選擇" prop="Permission">
              <el-option
                label="管理員"
                value="1"
                key="1"
              ></el-option>
              <el-option
                label="會員"
                value="0"
                key="0"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="email" label-width="120px" prop="Email">
            <el-input v-model="addForm.Email" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="電話" label-width="120px" prop="Phone">
            <el-input v-model="addForm.Phone" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="狀態" label-width="120px">
            <el-switch
              v-model="addForm.Locked"
              active-text="正常"
              active-value="0"
              inactive-text="停權"
              inactive-value="1"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel('addForm')">取消</el-button>
          <el-button type="primary" @click="createMember">確定</el-button>
        </div>
      </el-dialog>

      <!-- dialog edit form -->
      <el-dialog title="修改資料" :visible.sync="editFormStatus">
        <el-form :model="editForm"
                 label-position="right"
                 :rules="rules"
                 ref="editForm">
          <el-form-item label="ID" label-width="120px" prop="Id">
            <el-input v-model="editForm.Id" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="帳號" label-width="120px" prop="Account">
            <el-input v-model="editForm.Account" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="密碼" label-width="120px">
            <el-input v-model="editForm.Password" :disabled="false" type="password"></el-input>
          </el-form-item>
          <el-form-item label="暱稱" label-width="120px" prop="Name">
            <el-input v-model="editForm.Name" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="權限" label-width="120px" prop="Permission">
            <el-select v-model="editForm.Permission" placeholder="請選擇" prop="Permission">
              <el-option
                label="管理員"
                value="1"
                key="1"
              ></el-option>
              <el-option
                label="會員"
                value="0"
                key="0"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="email" label-width="120px" prop="Email">
            <el-input v-model="editForm.Email" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="電話" label-width="120px" prop="Phone">
            <el-input v-model="editForm.Phone" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="狀態" label-width="120px">
            <el-switch
              v-model="editForm.Locked"
              active-text="正常"
              active-value="0"
              inactive-text="停權"
              inactive-value="1"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel('editForm')">取消</el-button>
          <el-button type="primary" @click="putMember">確定</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AdminAuth',
  data() {
    return {
      pageLoading: true,
      addFormStatus: false,
      editFormStatus: false,
      tableData: [],
      formUser: {},
      adminId: 0,
      addForm: {
        Account: '',
        Password: '',
        Name: '',
        Permission: '0',
        Email: '',
        Phone: '',
        Locked: 0,
      },
      editForm: {
        Account: '',
        Password: '',
        Name: '',
        Permission: '',
        Email: '',
        Phone: '',
        Locked: '',
      },
      rules: {
        Account: [
    { required: true, message: '請輸入會員帳號', trigger: 'blur' },
        ],
        Password: [
    { required: true, message: '請輸入會員密碼', trigger: 'blur' },
        ],
        Name: [
    { required: true, message: '請輸入會員姓名', trigger: 'blur' },
        ],
        Email: [
    { required: true, message: '請輸入會員email', trigger: 'blur' },
        ],
        Phone: [
    { required: true, message: '請輸入會員電話', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    createMember() {
      this.$refs.addForm.validate((valid) => {
        if (valid) {
          const oEditData = {
            account: this.addForm.Account,
            password: this.addForm.Password,
            name: this.addForm.Name,
            permission: this.addForm.Permission,
            email: this.addForm.Email,
            locked: this.addForm.Locked,
            phone: this.addForm.Phone,
          };
          axios.post('/api/member',oEditData)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('新增成功');
                this.addFormStatus = false;
                this.pageLoading = true;
                this.cancel('addForm');
                this.getMemberList();
              } else {
                this.$message.error(`新增失敗 ${resp.data.message}`);
              }
            })
            .catch((error) => {
              this.$message.error(error.message);
            });
        } else {
          this.$message.error('請修正錯誤欄位');
        }
      });
    },
    getMemberList() {
      /* 取資料 */
      axios.get('/api/member')
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data;
          this.pageLoading = false;
        } else {
          this.$message.error('取得資料失敗');
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
    },
     // 修改表單
    editMember(row) {
      this.editFormStatus = true;
      this.editForm = Object.assign({}, this.editForm, {
        Id: row.id,
        Name: row.name,
        Account: row.account,
        Permission: row.permission,
        Email: row.email,
        Locked: row.locked,
        Phone: row.phone,
      });
    },
    putMember() {
      this.$refs.editForm.validate((valid) => {
        if (valid) {
          const oEditData = {
            id: this.editForm.Id,
            account: this.editForm.Account,
            password: this.editForm.Password,
            name: this.editForm.Name,
            permission: this.editForm.Permission,
            email: this.editForm.Email,
            locked: this.editForm.Locked,
            phone: this.editForm.Phone,
          };
          axios.put('/api/member/' + oEditData.id, oEditData)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('修改成功');
                this.editFormStatus = false;
                this.pageLoading = true;
                this.getMemberList();
              } else {
                this.$message.error(`修改失敗 ${resp.data.message}`);
              }
            })
            .catch((error) => {
              this.$message.error(error.message);
            });
        } else {
          this.$message.error('請修正錯誤欄位');
        }
      });
    },
    cancel(name) {
      this.$refs[name].resetFields();
      this.addFormStatus = false;
      this.editFormStatus = false;
    },
  },
  mounted() {
    this.getMemberList();
  },
};

</script>
<style scoped>
.content-body {
  padding: 10px;
  width: auto;
}
table {
  border-collapse: collapse;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s
}
.fade-enter,
.fade-leave {
  opacity: 0
}
.paper-btn {
  width: 100%;
}
.btn-body {
  padding: 15px;
  width: auto;
}
.function-title {
  padding-left: 10px;
}
.green-btn {
  background-color: green;
  border-color: green;
  color: #FFFFFF;
}
.red-btn {
  background-color: red;
  border-color: red;
  color: #FFFFFF;
}
.normal-btn {
  background-color: #4890f7;
  border-color: #4890f7;
  color: #FFFFFF;
}
.quit-btn {
  background-color: #909399;
  border-color: #909399;
  color: #FFFFFF;
}
</style>

