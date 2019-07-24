te>
  <div>
    <navbar></navbar>
    <div style="position: absolute; top: 30%; left: 50%;">
      <h2>會員資料</h2>
      <div style="margin: 20px;"></div>
      <el-form label-position="left" label-width="80px">
        <el-form-item label="帳號">
          {{ tableData.account }}
        </el-form-item>
        <el-form-item label="姓名">
          {{ tableData.name }}
        </el-form-item>
        <el-form-item label="email">
          {{ tableData.email }}
        </el-form-item>
        <el-form-item label="電話">
          {{ tableData.phone }}
        </el-form-item>
        <el-form-item label="建立時間">
          {{ tableData.created_at }}
        </el-form-item>
        <el-form-item style="text-align:left;">
          <el-button @click="editMember(tableData)">修改</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!-- dialog edit form -->
    <el-dialog title="修改資料" :visible.sync="editFormStatus">
      <el-form :model="editForm"
               label-position="right"
               :rules="rules"
               ref="editForm">
        <el-form-item label="帳號" label-width="120px" prop="Account">
          <el-input v-model="editForm.Account" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="密碼" label-width="120px">
          <el-input v-model="editForm.Password" :disabled="false" type="password"></el-input>
        </el-form-item>
        <el-form-item label="暱稱" label-width="120px" prop="Name">
          <el-input v-model="editForm.Name" :disabled="false"></el-input>
        </el-form-item>
        <el-form-item label="email" label-width="120px" prop="Email">
          <el-input v-model="editForm.Email" :disabled="false"></el-input>
        </el-form-item>
        <el-form-item label="電話" label-width="120px" prop="Phone">
          <el-input v-model="editForm.Phone" :disabled="false"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel('editForm')">取消</el-button>
        <el-button type="primary" @click="putMember">確定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import apiShoppingcart from '~/api/shoppingcart';
import navbar1 from '~/components/shoppingcart/nav';

export default {
  name: 'AdminAuth',
  components: {
    navbar: navbar1,
  },
  data() {
    return {
      pageLoading: true,
      editFormStatus: false,
      tableData: [],
      editForm: {
        Account: '',
        Password: '',
        Name: '',
        Email: '',
        Phone: '',
      },
      rules: {
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
    getUser() {
      /* 取資料 */
      apiShoppingcart.getUser()
      .then((resp) => {
        if (resp.data.result === true) {
          this.getUserInfo(resp.data.data.id);
        } else {
          this.$message.error('取得資料失敗');
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
    },
    getUserInfo(id) {
      apiShoppingcart.getMember(id)
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data[0];
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
        Email: row.email,
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
            email: this.editForm.Email,
            phone: this.editForm.Phone,
          };
          apiShoppingcart.putMember(oEditData.id, oEditData)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('修改成功');
                this.editFormStatus = false;
                this.pageLoading = true;
                this.getUser();
              } else {
                this.$message.error('修改失敗');
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
    this.getUser();
  },
};

</script>

