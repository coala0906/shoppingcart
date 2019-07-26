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
    {
        path: '/shoppingcart/manage/member',
        component: require('./components/manage/member.vue').default
    },
    {
        path: '/shoppingcart/manage/item',
        component: require('./components/manage/itme.vue').default
    },
];
export default new VueRouter ({
    mode: 'history',
    routes
});
