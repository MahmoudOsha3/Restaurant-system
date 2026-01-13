<?php

return [
    'dashboard' => [
        'route' => 'dashboard' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'لوحة التحكم ',
        'active' => ['/'] ,
        'permission' => '' ,
    ] ,
    'category' => [
        'route' => '/categories' ,
        'icon' => 'fas fa-list' ,
        'label' => 'اقسام الطعام',
        'active' => ['categories'] ,
        'permission' => ''  ,
    ] ,
    'meals' => [
        'route' => '/meals' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'الواجبات',
        'active' => ['meals']  ,
        'permission' => '' ,
    ] ,

    'orders' => [
        'route' => '/orders' ,
        'icon' => 'fas fa-desktop' ,
        'label' => 'مراقبة الطلبات ',
        'active' => ['orders']  ,
        'permission' => '' ,
    ] ,

    'cashier' => [
        'route' => '/cashier' ,
        'icon' => 'fas fa-plus-circle' ,
        'label' => 'الكاشير',
        'active' => ['cashier']  ,
        'permission' => '' ,
    ] ,

    'reports' => [
        'route' => '/cashier/reports' ,
        'icon' => 'fas fa-history' ,
        'label' => 'السجل والتقارير',
        'active' => ['cashier/reports']  ,
        'permission' => '' ,
    ],

    'admins' => [
        'route' => '/admins' ,
        'icon' => 'fas fa-users' ,
        'label' => 'المواظفين',
        'active' => ['admins']  ,
        'permission' => '' ,
    ] ,

    'roles' => [
        'route' => '/roles' ,
        'icon' => 'fas fa-user-shield' ,
        'label' => 'الادوار والصلاحيات',
        'active' => ['roles']  ,
        'permission' => '' ,
    ] ,
    'invoice' => [
        'route' => '/invoices' ,
        'icon' => 'fas fa-receipt' ,
        'label' => 'إدارة المصاريف والفواتير',
        'active' => ['invoices']  ,
        'permission' => '' ,
    ] ,


] ;
