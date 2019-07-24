<template>
  <div>
    <navbar></navbar>
    <div>
      <div class="w3-white w3-xlarge w3-padding-xlarge" style="max-width:1200px;margin:auto">
        <div class="w3-center">Shop</div>
      </div>
      <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-row-padding w3-padding-16 w3-center" id="food">
          <div
            v-for="item in tableData"
            v-if="item.Status===1"
            :key='item.PID'
            class="w3-quarter"
          >
            <img :src="item.Image" style="width: 200px; height:200px;">
            <h3>{{ item.Name }}</h3>
            <h4>${{ item.Price }}</h4>
            <button
              class="w3-btn w3-round-large w3-large w3-padding-large"
              :class="{
                'w3-dark-grey': item.Amount >-1
              }"
              @click="getProduct(item)">
              <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
              <span>查看商品</span>
            </button>
            <button
              v-if="item.Amount>0 && userData.permission == 0"
              class="w3-btn w3-round-large w3-large w3-padding-large"
              :class="{
                'w3-dark-grey': item.Amount >-1
              }"
              @click="createCart(item.PID,1)">
              <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
              <span>加入購物車</span>
            </button>
            <button
              v-if="item.Amount<1 && userData.permission == 0"
              class="w3-btn w3-round-large w3-large w3-padding-large"
              :class="{
                'w3-dark-grey': item.Amount >-1
              }"
              disabled="true">
              <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
              <span>缺貨中</span>
            </button>
            <div style="margin-top:100px"></div>
          </div>
        </div>
      </div>
      <!-- dialog add form -->
      <el-dialog title="商品資料" :visible.sync="productFormStatus" top="50px">
        <el-form
          :model="productForm"
          label-position="right"
          ref="productForm">
          <el-form-item label-width="300px">
            <img :src="productForm.Image"
                 style="width: 50%; height: 50%;">
          </el-form-item>
          <el-form-item label-width="200px" label="商品名稱">
            {{ productForm.Name }}
          </el-form-item>
          <el-form-item label-width="200px" label="商品品牌">
            {{ productForm.Brand }}
          </el-form-item>
          <el-form-item label-width="200px" label="商品描述">
            {{ productForm.Information }}
          </el-form-item>
          <el-form-item label-width="200px" label="商品價格">
            ${{ productForm.Price }}
          </el-form-item>
          <el-form-item label-width="200px" label="購買數量">
            <el-input v-model="productForm.Amount"
                      :disabled="false"
                      type="number"
                      style="width: 20%;"
                      :max="99"
                      :maxlength="2"
                      :min="1"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer" v-if="userData.permission ==='0'">
          <el-button @click="cancel('productForm')">取消</el-button>
          <el-button type="primary" v-if="productForm.Amount_safe>0" @click="createCart(productForm.PID,productForm.Amount)">加入購物車</el-button>
          <el-button type="primary" v-if="productForm.Amount_safe<1" :disabled="true">缺貨中</el-button>
        </div>
      </el-dialog>
    </div>
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
      tableData: [],
      userData: [],
      pageLoading: true,
      productFormStatus: false,
      productForm: {
        Amount: 1,
        userid: '',
      },
    };
  },
  methods: {
    getUser() {
      /* 取資料 */
      apiShoppingcart.getUser()
      .then((resp) => {
        if (resp.data.result === true) {
          this.userData = resp.data.data;
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
    },
    /* 取資料 */
    getProductList() {
      apiShoppingcart.getProductList()
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data;
          this.pageLoading = false;
        } else {
          this.$message.error(resp.data.message);
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
    },
     // 查看商品
    getProduct(row) {
      this.productFormStatus = true;
      this.productForm = Object.assign({}, this.productForm, {
        PID: row.PID,
        Name: row.Name,
        Brand: row.Brand,
        Information: row.Information,
        Image: row.Image,
        Price: row.Price,
        Amount_safe: row.Amount,
      });
    },
    cancel(name) {
      this.$refs[name].resetFields();
      this.productFormStatus = false;
    },
    createCart(ipid, iamount) {
      if (!this.userData.name) {
        this.$message.error('請登入會員');
      } else {
        const oEditData = {
          pid: ipid,
          amount: iamount,
        };
        apiShoppingcart.createCart(oEditData)
      .then((resp) => {
        if (resp.data.result === true) {
          this.$message.success('加入購物車成功');
          this.productFormStatus = false;
        } else {
          this.$message.error(resp.data.message);
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
      }
    },
  },
  mounted() {
    this.getProductList();
    this.getUser();
  },
};
</script>
<style scoped>
  @import '/static/css/w3.css';
</style>

