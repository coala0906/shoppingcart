<template>
  <div>
    <navbar></navbar>
    <div class="content-body">
      <h2>購買紀錄</h2>
      <v-table
        :data="tableData"
        striped
        fix
        v-loading="pageLoading"
      >
        <v-column
          header-class="column"
          prop="image"
          label="商品圖片"
        >
          <template slot-scope="scope">
            <img :src="scope.row.Image" style="width: 50%;">
          </template>
        </v-column>
        <v-column
          header-class="column"
          prop="Name"
          label="商品名稱"
          filterable
        >
          <template slot-scope="scope">
            {{ scope.row.Name }}
          </template>
        </v-column>
        <v-column
          header-class="column"
          prop="Price"
          label="商品價格"
          filterable
        >
          <template slot-scope="scope">
            ${{ scope.row.Price }}
          </template>
        </v-column>
        <v-column
          header-class="column"
          prop="Amount"
          label="商品數量"
          filterable
        >
          <template slot-scope="scope">
            {{ scope.row.Amount }}
          </template>
        </v-column>
        <v-column
          header-class="column"
          prop="date"
          label="購買日期"
        >
          <template slot-scope="scope">
            {{ scope.row.created_at }}
          </template>
        </v-column>
      </v-table>
    </div>
  </div>
</template>
<script>
import { vTable, vColumn } from 'components/v-table';
import apiShoppingcart from '~/api/shoppingcart';
import navbar1 from '~/components/shoppingcart/nav';

export default {
  name: 'AdminAuth',
  components: {
    vTable,
    vColumn,
    navbar: navbar1,
  },
  data() {
    return {
      pageLoading: true,
      addFormStatus: false,
      editFormStatus: false,
      tableData: [],
      formUser: {},
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
      apiShoppingcart.getTransaction(oData)
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
    pageChange(page) {
      this.currentPage = page;
    },
    cancel(name) {
      this.$refs[name].resetFields();
      this.addFormStatus = false;
      this.editFormStatus = false;
    },
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

