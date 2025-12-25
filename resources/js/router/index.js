import { createRouter, createWebHistory } from 'vue-router';


import Dashboard from '@/Pages/Dashboard.vue' ;
import Categories from '@/Pages/Categories.vue';

const routes = [
    {
        path : '/dashboard' ,
        name : 'Dashboard' ,
        component : Dashboard
    },
    {
        path : '/categories' ,
        name : 'Categories' ,
        component : Categories
    },
    // {path : '/cashier' , name : 'CashierView' , component : CashierView ,},
];


const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;


