<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function view(User $user, User $subject): bool
    {
        return $user->is($subject) || $user->can('manage_users');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_users');
    }

    public function update(User $user, User $subject): bool
    {
        return $user->is($subject) || ($user->can('manage_users') && (! $subject->hasRole('Super Admin') || $user->hasRole('Super Admin')));
    }

    public function delete(User $user, User $subject): bool
    {
        return ! $user->is($subject) && $user->hasRole('Super Admin') && ! $subject->hasRole('Super Admin');
    }

    public function assignRoles(User $user): bool
    {
        return $user->can('manage_roles');
    }
}
