<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Comment;
use Illuminate\View\View;

class DashboardCommentController extends Controller
{
    public function __invoke(): View
    {
        $user = request()->user();
        $canModerate = $user->hasAnyRole(['Super Admin', 'Admin']);

        $comments = Comment::query()
            ->with(['user', 'post'])
            ->whereHas('post', fn ($query) => $query
                ->where('status', PostStatus::PUBLISHED)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now()))
            ->when(! $canModerate, fn ($query) => $query->whereBelongsTo($user))
            ->latest()
            ->paginate(15);

        return view('admin.comments.index', compact('comments', 'canModerate'));
    }
}
