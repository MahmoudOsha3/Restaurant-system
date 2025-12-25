<template>
    <div>
        <router-view />
    </div>
</template>

<script>
    import DashboardLayout from './Layouts/DashboardLayout.vue';
export default {
    name: "App",
    components : {DashboardLayout } ,
}
</script>


