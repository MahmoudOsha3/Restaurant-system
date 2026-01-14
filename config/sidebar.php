<?php

return [
    'dashboard' => [
        'route' => 'dashboard' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'لوحة التحكم ',
        'active' => ['dashboard'] ,
        'permission' => 'dashboard.view',
    ] ,
    'category' => [
        'route' => '/categories' ,
        'icon' => 'fas fa-list' ,
        'label' => 'اقسام الطعام',
        'active' => ['categories'] ,
        'permission' => 'category.view'  ,
    ],
    'meals' => [
        'route' => '/meals' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'الواجبات',
        'active' => ['meals']  ,
        'permission' => 'meal.view' ,
    ] ,

    'orders' => [
        'route' => '/orders' ,
        'icon' => 'fas fa-desktop' ,
        'label' => 'مراقبة الطلبات ',
        'active' => ['orders']  ,
        'permission' => 'order.view' ,
    ] ,

    'cashier' => [
        'route' => '/cashier' ,
        'icon' => 'fas fa-plus-circle' ,
        'label' => 'الكاشير',
        'active' => ['cashier']  ,
        'permission' => 'cashier.view' ,
    ] ,


    'history' => [
        'route' => '/cashier/history' ,
        'icon' => 'fas fa-history' ,
        'label' => 'سجل طلبات',
        'active' => ['cashier/history']  ,
        'permission' => 'cashier.show' ,
    ],

    'admins' => [
        'route' => '/admins' ,
        'icon' => 'fas fa-users' ,
        'label' => 'المواظفين',
        'active' => ['admins']  ,
        'permission' => 'admin.view' ,
    ] ,

    'users' => [
        'route' => '/users' ,
        'icon' => 'fas fa-user-shield' ,
        'label' => 'المستخدمين',
        'active' => ['users']  ,
        'permission' => 'user.view',
    ] ,

    'roles' => [
        'route' => '/roles' ,
        'icon' => 'fas fa-user-shield' ,
        'label' => 'الادوار والصلاحيات',
        'active' => ['roles']  ,
        'permission' => 'role.view' ,
    ] ,
    'invoice' => [
        'route' => '/invoices' ,
        'icon' => 'fas fa-receipt' ,
        'label' => 'إدارة المصاريف والفواتير',
        'active' => ['invoices']  ,
        'permission' => 'invoice.view' ,
    ] ,

    'reports' => [
        'route' => '/reports' ,
        'icon' => 'fas fa-chart-line' ,
        'label' => 'السجل والتقرير',
        'active' => ['reports']  ,
        'permission' => 'report.view',
    ] ,
] ;
