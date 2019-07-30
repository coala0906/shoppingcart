<template>
  <div>
    <div class="content-body">
      <h2>購物車</h2>
      <el-table
        :data="tableData"
        empty-text="購物車內無商品"
      >
        <el-table-column
          prop="photo"
          label="商品圖片"
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
            <div v-if="scope.row.Status !== 0">{{ scope.row.Name }}</div>
            <div v-if="scope.row.Status === 0"><p>此商品已下架,請先移除此商品</p></div>
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
        <el-table-column
          prop="amount"
          label="商品數量"
        >
          <template slot-scope="scope">
            <el-input-number size="mini"
                             :min="1"
                             v-model="scope.row.c_Amount"
                             ref="c_Amount"
                             @change="buttonStatus = false"
            ></el-input-number>
          </template>
        </el-table-column>
        <el-table-column label="移除商品">
          <template slot-scope="scope">
            <el-button
              type="primary"
              icon="el-icon-circle-close"
              title="移除"
              @click="confirmDelete(scope.row.PID,scope.row.Name)"
              size="medium"
            ></el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-button type="success"
                 @click="confirmCart()"
                 v-if="tableData != ''"
                 :disabled="!buttonStatus"
      >確定購買</el-button>
      <el-button
        type="primary"
        title="確認修改"
        @click="putCart(tableData)"
        v-if="tableData != ''"
        :disabled="buttonStatus"
      >確認修改</el-button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AdminAuth',
  data() {
    return {
      pageLoading: true,
      editFormStatus: false,
      buttonStatus: true,
      tableData: [],
      formUser: {},
      num: 1,
    };
  },
  methods: {
    getCartList() {
      /* 取資料 */
      axios.get('/api/cart')
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data;
          /* this.tableData.forEach((element) => {
            const data = element;
          });*/
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
    editCart(row) {
      this.editFormStatus = true;
      this.editForm = Object.assign({}, this.editForm, {
        Amount: row.c_Amount,
        Name: row.Name,
        PID: row.c_PID,
      });
    },
    delCart(pid) {
      this.pageLoading = true;
      axios.post('/api/cart/' + pid)
            .then((resp) => {
              if (resp.data.result) {
                this.$message.success('移除成功');
                this.getCartList();
              } else {
                this.$message.error('移除失敗');
              }
            })
            .catch((error) => {
              this.$message.error(error.message);
            });
      this.pageLoading = false;
    },
    putCart(row) {
      this.pageLoading = true;
      axios.put('/api/cart',row)
        .then((resp) => {
          if (resp.data.result) {
            this.$message.success('修改成功');
            this.editFormStatus = false;
            this.buttonStatus = true;
            this.getCartList();
          } else {
            this.$message.error(resp.data.message);
            this.getCartList();
            this.buttonStatus = true;
          }
        })
        .catch((error) => {
          this.$message.error(error.message);
        });
      this.pageLoading = false;
    },
    createTransaction() {
      this.pageLoading = true;
      axios.post('/api/transaction')
          .then((resp) => {
            if (resp.data.result) {
              this.$message.success('購買成功');
              this.$router.push('/shoppingcart/purchase');
            } else {
              this.$message.error(resp.data.message);
            }
          })
            .catch((error) => {
              this.$message.error(error.message);
            });
      this.pageLoading = false;
    },
    confirmDelete(PID, name) {
      this.$confirm(`確認移除${name}?`, '提示', {
        confirmButtonText: '確定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        this.delCart(PID);
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消移除',
        });
      });
    },
    confirmCart() {
      this.$confirm('是否確定購買?', '提示', {
        confirmButtonText: '確定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        this.createTransaction();
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消購買',
        });
      });
    },
  },
  beforeCreate() {
    this.$emit('check');
  },
  mounted() {
    this.getCartList();
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

