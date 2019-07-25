import VueRouter from 'vue-router';
let routes = [
    {
        path:'/shoppingcart',
        component: require('./components/ExampleComponent.vue').default
    },
    {
        path: '/shoppingcart/login',
        component: require('./components/login.vue').default
    },
    {
        path: '/shoppingcart/manage/transaction',
        component: require('./components/manage/transaction.vue').default
    },
];
export default new VueRouter ({
    mode: 'history',
    routes
});
