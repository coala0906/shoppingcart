<template>
  <div>
    <div class="content-body">
      <h2>商品管理</h2>
      <el-button
        type="primary"
        v-loading="pageLoading"
        :data="addFormStatus"
        @click="addFormStatus = true"
      >新增商品</el-button>
      <el-table
        :data="tableData"
        style="width: 90%"
        ref="table"
      >
        <el-table-column
          prop="photo"
          label=""
        >
          <template slot-scope="scope">
            <img :src="scope.row.Image" style="width: 50%;">
          </template>
        </el-table-column>
        <el-table-column
          prop="Name"
          label="商品名稱"
        >
          <template slot-scope="scope">
            {{ scope.row.Name }}
          </template>
        </el-table-column>
        <el-table-column
          prop="Brand"
          label="商品品牌"
        >
          <template slot-scope="scope">
            {{ scope.row.Brand }}
          </template>
        </el-table-column>
        <el-table-column
          prop="Price"
          label="商品價格"
        >
          <template slot-scope="scope">
            ${{ scope.row.Price }}
          </template>
        </el-table-column>
        <el-table-column label="商品狀態">
          <template slot-scope="scope">
            <el-tag type="success" v-if="scope.row.Status === '1'">上架</el-tag>
            <el-tag type="danger" v-else>下架</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="商品修改">
          <template slot-scope="scope">
            <el-button
              type="primary"
              icon="el-icon-edit"
              title="修改"
              @click="editProduct(scope.row)"
              size="small"
            ></el-button>
          </template>
        </el-table-column>
      </el-table>
      <!-- dialog edit form -->
      <el-dialog title="修改商品資料" :visible.sync="editFormStatus">
        <el-form :model="editForm"
                 label-position="right"
                 :rules="rules"
                 ref="editForm">
          <el-form-item label="ID" label-width="120px">
            <el-input v-model="editForm.PID" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="商品名稱" label-width="120px" prop="Name">
            <el-input v-model="editForm.Name" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="商品品牌" label-width="120px" prop="Brand">
            <el-input v-model="editForm.Brand" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="商品價格" label-width="120px" prop="Price">
            <el-input-number v-model="editForm.Price" :min="1" label="描述文字"></el-input-number>
          </el-form-item>
          <el-form-item label="商品庫存" label-width="120px" prop="Amount">
            <el-input-number v-model="editForm.Amount" :min="1" label="描述文字"></el-input-number>
          </el-form-item>
          <el-form-item label="商品描述" label-width="120px" prop="Information">
            <el-input
              type="textarea"
              autosize
              :rows="2"
              placeholder="請輸入商品描述"
              v-model="editForm.Information">
            </el-input>
          </el-form-item>
          <el-form-item label="商品狀態" label-width="120px">
            <el-switch
              v-model="editForm.Status"
              active-text="上架"
              active-value="1"
              inactive-text="下架"
              inactive-value="0"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
          <el-form-item label="商品圖片" label-width="120px">
            <el-upload
              action=""
              list-type="picture"
              :on-remove="handleRemove"
              :on-success="handleSuccess"
              :on-change="fileChange"
              :auto-upload="false"
              :limit="1"
              ref="upload"
              :before-upload="handleBefore">
              <el-button size="small" type="primary" v-if="!buttonStatus">選擇圖片</el-button>
            </el-upload>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel('editForm')">取消</el-button>
          <el-button type="primary" @click="postProduct">確定</el-button>
        </div>
      </el-dialog>
      <!-- dialog add form -->
      <el-dialog title="新增商品資料" :visible.sync="addFormStatus">
        <el-form :model="form"
                 label-position="right"
                 ref="form"
                 :rules="rules">
          <el-form-item label="商品名稱" label-width="120px" prop="new_Name">
            <el-input v-model="form.new_Name" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="商品品牌" label-width="120px" prop="new_Brand">
            <el-input v-model="form.new_Brand" :disabled="false"></el-input>
          </el-form-item>
          <el-form-item label="商品價格" label-width="120px" prop="new_Price">
            <el-input-number v-model="form.new_Price" :min="1" label="描述文字"></el-input-number>
          </el-form-item>
          <el-form-item label="商品庫存" label-width="120px" prop="new_Amount">
            <el-input-number v-model="form.new_Amount" :min="1" label="描述文字"></el-input-number>
          </el-form-item>
          <el-form-item label="商品描述" label-width="120px" prop="new_Information">
            <el-input
              type="textarea"
              autosize
              placeholder="請輸入商品描述"
              v-model="form.new_Information">
            </el-input>
          </el-form-item>
          <el-form-item label="商品狀態" label-width="120px" prop="new_Category">
            <el-switch
              v-model="form.new_Status"
              active-text="上架"
              active-value="1"
              inactive-text="下架"
              inactive-value="0"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>
          <el-form-item label="商品圖片" label-width="120px">
            <el-upload
              action=""
              list-type="picture"
              :on-remove="handleRemove"
              :on-success="handleSuccess"
              :on-change="fileChange"
              :limit="1"
              :before-upload="handleBefore"
              :auto-upload="false"
              ref="upload">
              <el-button size="small" type="primary" v-if="!buttonStatus">選擇圖片</el-button>
            </el-upload>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel('form')">取消</el-button>
          <el-button type="primary" @click="createProduct">確定</el-button>
        </div>
      </el-dialog>
    </div>
    <div class="page-area">
      <el-pagination
        v-if="total !== 0"
        :total="total"
        @current-change="pageChange"
        :current-page="currentPage"
        layout="prev, pager, next"
        :page-size="10"
      ></el-pagination>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AdminAuth',
  data() {
    return {
      pageLoading: true,
      showDialog: false,
      tableData: [],
      total: 0,
      currentPage: 1,
      formData: new FormData(),
      // 修改表單
      editFormStatus: false,
      // 新增表單
      addFormStatus: false,
      categoryFormStatus: false,
      buttonStatus: false,
      editForm: {
        PID: '',
        Name: '',
        Brand: '',
        Price: '',
        category: [],
        files: [],
        imgFile: '',
      },
      form: {
        new_Name: '',
        new_Price: 1,
        new_Amount: 1,
        new_Brand: '',
        switchStatus: 1,
      },
      categoryform: {
        ca_Name: '',
      },
      rules: {
        new_Name: [
    { required: true, message: '請輸入商品名稱', trigger: 'blur' },
        ],
        new_Brand: [
    { required: true, message: '請輸入商品品牌', trigger: 'blur' },
        ],
        new_Information: [
    { required: true, message: '請輸入商品描述', trigger: 'blur' },
        ],
        new_Amount: [
    { required: true, message: '請輸入商品庫存', trigger: 'blur' },
        ],
        new_Price: [
    { required: true, message: '請輸入商品價格', trigger: 'blur' },
        ],
        Name: [
    { required: true, message: '請輸入商品名稱', trigger: 'blur' },
        ],
        Brand: [
    { required: true, message: '請輸入商品品牌', trigger: 'blur' },
        ],
        Information: [
    { required: true, message: '請輸入商品描述', trigger: 'blur' },
        ],
        Price: [
    { required: true, message: '請輸入商品價格', trigger: 'blur' },
        ],
        Amount: [
    { required: true, message: '請輸入商品庫存', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    cancel(name) {
      this.$refs[name].resetFields();
      this.$refs.upload.clearFiles();
      this.buttonStatus = false;
      this.addFormStatus = false;
      this.editFormStatus = false;
    },
     // 修改表單
    editProduct(row) {
      this.editFormStatus = true;
      // this.editForm.PID = row.PID;
      // this.editForm.Name = row.Name;
      // this.editForm.Brand = row.Brand;
      // this.editForm.Price = oriPrice;
      // this.editForm.Amount = row.Amount;
      // this.editForm.Information = row.Information;
      // this.editForm.Image = row.Image;
      // this.editForm.Category = row.Category;
      this.editForm = Object.assign({}, this.editForm, {
        PID: row.PID,
        Name: row.Name,
        Brand: row.Brand,
        Price: row.Price.replace(/,/g, ''),
        Amount: row.Amount,
        Information: row.Information,
        Category: row.Category,
        Status: row.Status,
        Image: row.Image,
      });
    },
      /* 取資料 */
    getProductList(page) {
      this.pageLoading = true;
      axios.get('/api/product/' + page)
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data;
          this.total = resp.data.total;
          this.pageLoading = false;
        } else {
          this.$message.error(resp.data.message);
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
      this.pageLoading = false;
    },
    fileChange(file) {
      const exp = /.\.jpg|.\.gif|.\.png|.\.jpeg|.\.bmp/i;
      if (exp.test(file.name)) {
        this.formData.append('file', file.raw);
        this.buttonStatus = true;
      } else {
        this.$message.error('只能選擇圖片檔案');
        this.$refs.upload.clearFiles();
      }
    },
    postProduct() {
      this.$refs.editForm.validate((valid) => {
        if (valid) {
          /* const oEditData = {
            pid: this.editForm.PID,
            name: this.editForm.Name,
            brand: this.editForm.Brand,
            price: this.editForm.Price,
            amount: this.editForm.Amount,
            information: this.editForm.Information,
            category: this.editForm.Category,
            status: this.editForm.Status,
            image: this.editForm.imageUrl,
          };*/
          const config = {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          };
          this.formData.append('pid', this.editForm.PID);
          this.formData.append('name', this.editForm.Name);
          this.formData.append('category', this.editForm.Category);
          this.formData.append('brand', this.editForm.Brand);
          this.formData.append('amount', this.editForm.Amount);
          this.formData.append('price', this.editForm.Price);
          //this.formData.append('information', encodeURIComponent(this.editForm.Information));
          this.formData.append('information', this.editForm.Information);
          this.formData.append('status', this.editForm.Status);
          axios.post('/api/product/put/' + this.editForm.PID, this.formData, config)
            .then((resp) => {
              if (resp.data.data > 0) {
                this.$message.success('修改成功');
                this.formData.delete('pid');
                this.formData.delete('name');
                this.formData.delete('category');
                this.formData.delete('brand');
                this.formData.delete('amount');
                this.formData.delete('price');
                this.formData.delete('information');
                this.formData.delete('status');
                this.cancel('editForm');
                this.pageLoading = true;
                this.getProductList();
              } else {
                this.formData.delete('pid');
                this.formData.delete('name');
                this.formData.delete('category');
                this.formData.delete('brand');
                this.formData.delete('amount');
                this.formData.delete('price');
                this.formData.delete('information');
                this.formData.delete('status');
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
    createProduct() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const config = {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          };
          this.formData.append('name', this.form.new_Name);
          this.formData.append('category', this.form.new_Category);
          this.formData.append('brand', this.form.new_Brand);
          this.formData.append('amount', this.form.new_Amount);
          this.formData.append('price', this.form.new_Price);
          this.formData.append('information', encodeURIComponent(this.form.new_Information));
          this.formData.append('status', this.form.new_Status);
          axios.post('/api/product',this.formData,config)
            .then((resp) => {
              if (resp.data.result) {
                this.cancel('form');
                this.formData.delete('name');
                this.formData.delete('category');
                this.formData.delete('brand');
                this.formData.delete('amount');
                this.formData.delete('price');
                this.formData.delete('information');
                this.formData.delete('status');
                this.formData.delete('file');
                this.$message.success('新增成功');
                this.pageLoading = true;
                this.getProductList(this.currentPage);
              } else {
                this.formData.delete('name');
                this.formData.delete('category');
                this.formData.delete('brand');
                this.formData.delete('amount');
                this.formData.delete('price');
                this.formData.delete('information');
                this.formData.delete('status');
                this.formData.delete('file');
                this.$message.error('新增失敗' + resp.data.message);
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
    handleRemove() {
      this.buttonStatus = false;
      this.formData.delete('file');
    },
    pageChange(page) {
      this.currentPage = page;
      this.getProductList(this.currentPage);
    },
  },
  beforeCreate() {
    this.$emit('check');
  },
  mounted() {
    this.getProductList(this.currentPage);
    this.$emit('select','9');
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
.page-area {
  text-align: center;
  margin: 5px;
}
</style>

