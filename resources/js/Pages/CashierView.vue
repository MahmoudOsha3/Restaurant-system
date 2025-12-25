<template>
    <div>
        <DashboardLayout>
            i am cashier
    </DashboardLayout>
    </div>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useCashier } from '@/components/useCashier';

export default {
    name : "CashierView" ,
    components:{
        DashboardLayout ,
    },
    setup() {
        return useCashier ;
    }
}
</script>



