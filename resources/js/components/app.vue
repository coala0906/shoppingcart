<template>
    <div>
        
        <navbar></navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import navbar from './nav.vue';
    export default {
        components: {
            navbar: navbar,
        }
    }
</script>
