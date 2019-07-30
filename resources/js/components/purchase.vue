<template>
  <div>
    <div class="content-body">
      <h2>購買紀錄</h2>
      <el-table
        @expand-change="rowExpand"
        :data="tableData"
        style="width: 90%"
        ref="table">
        <el-table-column type="expand">
          <template scope="scope">
            <el-table
              v-loading="loadRow"
              :data="scope.row.children">
              <el-table-column>
                <template scope="scope">
                  <img :src="scope.row.Image" width="60" height="60"/>
                </template>
              </el-table-column>
              <el-table-column
                label="商品名稱"
                prop="Name">
              </el-table-column>
              <el-table-column
                label="商品數量"
                prop="Amount">
              </el-table-column>
              <el-table-column
                label="商品總價"
                prop="sum">
              </el-table-column>
            </el-table>
          </template>
        </el-table-column>
        <el-table-column
          label="訂單編號"
          prop="Order">
        </el-table-column>
        <el-table-column
          label="訂單狀態"
          prop="Status">
          <template scope="scope">
            <p v-if="scope.row.Status === '0'">已下單</p>
            <p v-if="scope.row.Status === '1'">處理中</p>
            <p v-if="scope.row.Status === '2'">已出貨</p>
            <p v-if="scope.row.Status === '3'">已取消</p>
          </template>
        </el-table-column>
        <el-table-column
          label="訂購日期"
          prop="created_at">
        </el-table-column>
      </el-table>
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
      detailData: [],
      editForm: {
        Account: '',
        Password: '',
        Name: '',
        Permission: '',
        Email: '',
        Phone: '',
        Locked: '',
      },
    };
  },
  methods: {
    getTransaction() {
      const oData = {
        page: this.currentPage,
      };
      /* 取資料 */
      axios.get('/api/transaction',oData)
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
    rowExpand(row, expandedRows) {
      if (expandedRows.length > 1) {
        expandedRows.shift();
      }
      this.loadRow = true;
      const oData = {
        order: row.Order,
      };
      this.detailData = [];
      axios.get('/api/transaction/detail/' + row.Order)
      .then((resp) => {
        if (resp.data.result === true) {
          for (let i = 0; i < resp.data.data.length; i += 1) {
            resp.data.data[i].sum = resp.data.data[i].Price * resp.data.data[i].Amount;
            resp.data.data[i].sum = `$${resp.data.data[i].sum}`;
          }
          this.loadRow = false;
          row.children = resp.data.data;
        } else {
           this.$message.error('取得資料失敗');
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
      this.loadRow = false;
    },
    pageChange(page) {
      this.currentPage = page;
    },
    cancel(name) {
      this.$refs[name].resetFields();
      this.addFormStatus = false;
      this.editFormStatus = false;
    },
  },
  beforeCreate() {
    this.$emit('check');
  },
  mounted() {
    this.getTransaction();
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

