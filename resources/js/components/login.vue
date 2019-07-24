<template>
  <div>
    <div class="login-box">
      <div class="title-space">
        <span>使用者登入</span>
      </div>
      <el-form ref="Form" :model="Form" :rules="rules">
        <el-form-item prop="username">
          <el-input
            v-model="Form.username"
            type="text"
            name="username"
            placeholder="帳號"
            :autofocus="true"/>
        </el-form-item>
        <el-form-item prop="password">
          <el-input type="password"
                    v-model="Form.password"
                    name="password"
                    placeholder="密碼"/>
        </el-form-item>
        <el-form-item>
          <el-button
            :disabled="now_login"
            @click="login"
            size="large"
            type="primary"
          >
            登入
            <template v-if="now_login">
              中...
            </template>
          </el-button>
        </el-form-item>
        <el-form-item>
          <el-button
            @click="addFormStatus = true"
            size="large"
            type="primary"
          >
            註冊新會員
          </el-button>
        </el-form-item>
      </el-form>

      <!-- dialog add form -->
      <el-dialog title="新增會員資料" :visible.sync="addFormStatus">
        <el-form :model="addForm"
                 label-position="right"
                 :rules="rules"
                 ref="addForm">
          <el-form-item label="會員帳號" label-width="120px" prop="Account">
            <el-input v-model="addForm.Account" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="會員密碼" label-width="120px" prop="Password">
            <el-input v-model="addForm.Password" :disabled="false" type="password"></el-input>
          </el-form-item>
          <el-form-item label="會員暱稱" label-width="120px" prop="Name">
            <el-input v-model="addForm.Name" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="會員email" label-width="120px" prop="Email">
            <el-input v-model="addForm.Email" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="會員電話" label-width="120px" prop="Phone">
            <el-input v-model="addForm.Phone" :disabled="false"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel('addForm')">取消</el-button>
        </div>
        <div slot="footer" class="dialog-footer">
          <el-button type="primary" @click="createMember">確定</el-button>
        </div>
      </el-dialog>

    </div>
  </div>
</template>

<script>
//import apiShoppingcart from '~/api/shoppingcart';

export default {
  name: 'login',
  data() {
    return {
      env: '',
      addFormStatus: false,
      Form: {
        username: '',
        password: '',
      },
      addForm: {
        Account: '',
        Password: '',
        Name: '',
        Email: '',
        Phone: '',
      },
      now_login: false,
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
        password: [
          { required: true, message: '請輸入會員密碼', trigger: 'blur' },
        ],
        username: [
          { required: true, message: '請輸入會員帳號', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    // 取得Env資訊
    /*getEnv() {
      this.$store.dispatch('getEnv').then((resp) => {
        this.env = resp.data.data;
      })
      .catch((resp) => {
        this.$message.error(resp);
      });
    },
    login() {
      this.now_login = true;
      this.$refs.Form.validate((valid) => {
        if (valid) {
          const oEditData = {
            account: this.Form.username,
            password: this.Form.password,
          };
          apiShoppingcart.login(oEditData)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('登入成功');
                this.$router.push('/shoppingcart/main');
              } else {
                this.$message.error(resp.data.message);
                this.now_login = false;
              }
            })
            .catch((error) => {
              this.$message.error(error.message);
            });
        } else {
          this.$message.error('請修正錯誤欄位');
          this.now_login = false;
        }
      });
    },
    cancel(name) {
      this.$refs[name].resetFields();
      this.addFormStatus = false;
    },*/
    createMember() {
      this.$refs.addForm.validate((valid) => {
        if (valid) {
          const oEditData = {
            account: this.addForm.Account,
            password: this.addForm.Password,
            name: this.addForm.Name,
            email: this.addForm.Email,
            phone: this.addForm.Phone,
          };
          axios.post('/api/member',oEditData)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('註冊成功');
                this.cancel('addForm');
              } else {
                this.$message.error(resp.data.message);
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
  },
};
</script>

<style scoped>
.title-space {
  width: 100%;
  height: auto;
  padding: 6px 0;
  color: block;
  border-radius: 4px;
  font-size: 22px;
  font-weight: bold;
  margin-bottom: 18px;
}
.title-space span {
  margin-left: 16px;
}
.login-box {
  width: 300px;
  margin: 100px auto 0;
  padding: 36px;
  background-color: #eef1f6;
  border-radius: 4px;
  box-shadow: 0 6px 6px rgba(0,0,0,.117647), 0 4px 4px rgba(0,0,0,.117647);
}
.el-button {
  font-weight: bold;
  width: 100%;
}
.dev {
  background-color: #00A0E9;
}
.qa {
  background-color: #F0BC2B;
}
.production {
  background-color: #800000;
}
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

