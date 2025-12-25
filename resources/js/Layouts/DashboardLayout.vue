<template>
    <div class="dashboard">
        <Sidebar />
        <div class="main-container">
            <Navbar />
            <slot />
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue'
import Navbar from '@/components/Navbar.vue'

export default {
    components: { Sidebar, Navbar }
}
</script>
