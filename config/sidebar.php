<?php

return [
    'dashboard' => [
        'route' => '#' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'لوحة التحكم ',
        'active' => ['dashboard' , 'dashboard.edit'] ,
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

    // 'roles' => '' ,
    // 'admins' => '' ,
    // 'orders' => '' ,
    // 'payments' => '' ,

] ;
