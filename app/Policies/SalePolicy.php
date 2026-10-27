<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sale;
use App\Models\Store;

class SalePolicy
{
    public function create(User $user, Store $store): bool
    {
        return $user->can('sales.create') && 
               $user->stores()->where('stores.id', $store->id)->exists();
    }

    public function void(User $user, Sale $sale): bool
    {
        return $user->can('sales.void') && 
               $user->stores()->where('stores.id', $sale->store_id)->exists();
    }
}