<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_posts');
    }

    public function view(User $user, Post $post): bool
    {
        return $user->can('view_all_posts') || ($user->can('view_posts') && $post->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->can('create_posts');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can('edit_all_posts') || ($user->can('edit_own_posts') && $post->user_id === $user->id);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('delete_all_posts') || ($user->can('delete_own_posts') && $post->user_id === $user->id);
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->can('publish_posts') && $this->update($user, $post);
    }
}
