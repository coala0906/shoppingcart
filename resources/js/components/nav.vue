<template>
  <el-menu
    :default-active="activeIndex2"
    class="el-menu-demo"
    mode="horizontal"
    @select="handleSelect"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b"
    ref="navbar">
    <el-menu-item index="1" @click="goIndex()">首頁</el-menu-item>
    <el-menu-item index="3" @click="goCart()" v-if="userData.permission === '0'">購物車</el-menu-item>
    <el-menu-item index="4" v-if="userData.permission === '0'" @click="goTransactionList()">購買紀錄</el-menu-item>
    <el-menu-item index="8" v-if="userData.permission === '1'" @click="goMember()">帳號管理</el-menu-item>
    <el-menu-item index="9" v-if="userData.permission === '1'" @click="goItem()">商品管理</el-menu-item>
    <el-menu-item index="10" v-if="userData.permission === '1'" @click="goTransaction()">訂單管理</el-menu-item>
    <el-menu-item @click="logout()"
                  index="5"
                  v-if="userData != ''"
                  style="float: right;">登出</el-menu-item>
    <el-menu-item index="6"
                  v-if="userData != ''"
                  style="float: right;"
                  @click="goMeminfo()">{{ userData.name }}</el-menu-item>
    <el-menu-item @click="gologin()"
                  index="7"
                  v-if="userData == ''"
                  style="float: right;">登入</el-menu-item>
  </el-menu>
</template>
<script>
  //import apiShoppingcart from '~/api/shoppingcart';

  export default {
    data() {
      return {
        activeIndex: '',
        activeIndex2: '',
        userData: [],
      };
    },
    methods: {
      handleSelect(key, keyPath) {
        this.activeIndex2 = key;
        this.activeIndex = keyPath;
      },
      getUser() {
        axios.get('/api/session')
      .then((resp) => {
        if (resp.data.result === true) {
          this.userData = resp.data.data;
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
      },
      goIndex() {
        this.$router.push('/shoppingcart/');
      },
      goCart() {
        this.$router.push('/shoppingcart/cart');
      },
      gologin() {
        this.$router.push('/shoppingcart/login');
      },
      goMeminfo() {
        this.$router.push('/shoppingcart/meminfo');
      },
      goMember() {
        this.$router.push('/shoppingcart/manage/member');
      },
      goItem() {
        this.$router.push('/shoppingcart/manage/item');
      },
      goTransactionList() {
        this.$router.push('/shoppingcart/purchase');
      },
      goTransaction() {
        this.$router.push('/shoppingcart/manage/transaction');
      },
      logout() {
        axios.post('/api/session/logout')
      .then((resp) => {
        if (resp.data.result === true) {
          this.$message.success('登出成功');
          this.userData = [];
          this.$router.push('/shoppingcart/login');
        } else {
          this.$message.error('請重新登出');
          this.$router.push('/shoppingcart/');
        }
      })
      .catch((error) => {
        this.$message.error(error.message);
      });
      },
    },
    mounted() {
      this.getUser();
    },
  };
</script>

