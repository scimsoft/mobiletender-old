<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UnicentaModels\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isManager();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->isManager();
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isManager();
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isManager();
    }
}
