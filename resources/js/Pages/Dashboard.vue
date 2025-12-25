<template>
    <div>
        <DashboardLayout >
            <h1>Iam Dashboard</h1>
            <router-link to="/categories">Categories</router-link>
        </DashboardLayout>
        <!-- <router-view /> -->
    </div>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

    export default {
        name : "Dashboard" ,
        components:{
            DashboardLayout ,
        }
    }
</script>



