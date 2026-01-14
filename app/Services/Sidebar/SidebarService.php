<?php

namespace App\Services\Sidebar;

use Illuminate\Support\Facades\Auth;

class SidebarService
{
    public static function items(): array
    {
        $items = config('sidebar');
        $admin = Auth::user();

        foreach ($items as $key => $item) {

            if (empty($item['permission'])) {
                unset($items[$key]);
            }

            if (! $admin) {
                unset($items[$key]);
                continue;
            }

            if (! $admin->hasPermission($item['permission'])){
                unset($items[$key]);
            }
        }
        return $items;
    }
}
