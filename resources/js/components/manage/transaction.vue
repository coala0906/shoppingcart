<template>
  <div>
    <div class="content-body">
      <h2>訂單管理</h2>
      <el-table
        @expand-change="rowExpand"
        :data="tableData"
        style="width: 100%"
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
            <el-select v-model="scope.row.Status" placeholder="請選擇" @change="putOrderStatus($event,scope.row.Order)">
              <el-option
                label="已下單"
                :value="0">
              </el-option>
              <el-option
                label="處理中"
                :value="1">
              </el-option>
              <el-option
                label="已出貨"
                :value="2">
              </el-option>
              <el-option
                label="已取消"
                :value="3">
              </el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column
          label="購買人"
          prop="account">
        </el-table-column>
        <el-table-column
          label="訂購日期"
          prop="created_at">
        </el-table-column>
      </el-table>
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
//import apiShoppingcart from '~/api/shoppingcart';

export default {
  name: 'AdminAuth',
  data() {
    return {
      pageLoading: true,
      loadRow: false,
      tableData: [],
      detailData: [],
      userData: [],
      currentPage: 1,
      total: 0,
    };
  },
  methods: {
    putOrderStatus(num, ordernum) {
      const oData = {
        order: ordernum,
        status: num,
      };
      axios.put('/api/transaction',oData)
    .then((resp) => {
      if (resp.data.result === true) {
        this.$message.success('修改成功');
      } else {
        this.$message.error('修改失敗');
        this.getOrder();
      }
    })
    .catch((error) => {
      this.$message.error(error.message);
      this.getOrder();
    });
    },
    toCurrency(num) {
      const parts = num.toString().split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      return parts.join('.');
    },
    getOrder(page) {
      axios.get('/api/order/' + page)
      .then((resp) => {
        if (resp.data.result === true) {
          this.tableData = resp.data.data;
          this.total = resp.data.total;
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
      axios.get('/api/transaction/detail',oData)
      .then((resp) => {
        if (resp.data.result === true) {
          for (let i = 0; i < resp.data.data.length; i += 1) {
            resp.data.data[i].sum = resp.data.data[i].Price * resp.data.data[i].Amount;
            resp.data.data[i].sum = this.toCurrency(resp.data.data[i].sum);
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
    },
    pageChange(page) {
      this.currentPage = page;
      this.getOrder(this.currentPage);
    },
  },
  mounted() {
    this.getOrder(this.currentPage);
  },
};

</script>
<style>
.page-area {
  text-align: center;
  margin: 5px;
}
</style>

