<template>
    <div> 
        <navbar ref='navbar'></navbar>
        <router-view @login='reload' @check='check' @select='select'></router-view>
    </div>
</template>

<script>
    import navbar from './nav.vue';
    export default {
        data() {
        return{
        count: 0,         
        }
        },
        components: {
            navbar: navbar,
        },
        methods:{
        reload() {
        this.$refs.navbar.getUser();
        },
        select(i) {
        this.$refs.navbar.changeSelect(i);
        },
        check() {
        axios.get('/api/session')
        .then((resp) => {
        if (!resp.data.result) {
          this.$router.push('/shoppingcart/login');
          this.$message.error('尚未登入');
        }
        })
        .catch((error) => {
        this.$message.error(error.message);
        });
        },
        },
    }
</script>
