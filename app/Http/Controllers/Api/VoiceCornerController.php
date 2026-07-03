<?php

namespace App\Http\Controllers\Api;

use App\Events\VoiceCornerPostCreated;
use App\Events\VoiceCornerReactionUpdated;
use App\Models\User;
use App\Models\VoiceCornerPost;
use App\Models\VoiceCornerReaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoiceCornerController extends BaseController
{
    public function __construct()
    {
        // Voice Corner is strictly DSP-only — coordinators/program staff and
        // families must never see or post here. Registered as deferred
        // controller middleware (rather than checked directly here) so it
        // only runs against real HTTP requests — Artisan commands like
        // `route:list` instantiate controllers with no authenticated user.
        $this->middleware(function ($request, $next) {
            if (! Auth::user()?->hasRole('dsp')) {
                abort(403, 'Voice Corner is only available to DSPs.');
            }

            return $next($request);
        });
    }

    /**
     * List the community feed, most recent first.
     */
    public function index(Request $request)
    {
        try {
            $request->validate([
                'tag' => ['nullable', Rule::in(VoiceCornerPost::TAGS)],
            ]);

            $posts = VoiceCornerPost::query()
                ->with(['user', 'reactions'])
                ->when($request->filled('tag'), fn ($q) => $q->where('tag', $request->query('tag')))
                ->latest()
                ->paginate(20);

            $posts->getCollection()->transform(fn ($post) => $this->formatPost($post, Auth::id()));

            return $this->sendResponse($posts, 'Voice Corner posts retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Voice Corner posts could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Share a new post to the feed.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tag' => ['required', Rule::in(VoiceCornerPost::TAGS)],
                'content' => ['required', 'string', 'max:2000'],
                'is_anonymous' => ['boolean'],
            ]);

            $post = VoiceCornerPost::create([
                'user_id' => Auth::id(),
                'tag' => $validated['tag'],
                'content' => $validated['content'],
                'is_anonymous' => $validated['is_anonymous'] ?? false,
            ])->load('user');

            // Broadcast the public view (no identity leaked for anonymous posts,
            // and no viewer-specific "is_own"/"reacted_by_me" state).
            event(new VoiceCornerPostCreated($this->formatPost($post)));

            return $this->sendResponse($this->formatPost($post, Auth::id()), 'Post shared successfully.');
        } catch (Exception $e) {
            return $this->sendError('Post could not be shared.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle a reaction (heart/fire/clap) on a post for the current user.
     */
    public function react(Request $request, VoiceCornerPost $post)
    {
        try {
            $validated = $request->validate([
                'type' => ['required', Rule::in(VoiceCornerReaction::TYPES)],
            ]);

            $reaction = VoiceCornerReaction::where([
                'voice_corner_post_id' => $post->id,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
            ])->first();

            if ($reaction) {
                $reaction->delete();
            } else {
                VoiceCornerReaction::create([
                    'voice_corner_post_id' => $post->id,
                    'user_id' => Auth::id(),
                    'type' => $validated['type'],
                ]);
            }

            $post->load('reactions');
            $counts = $this->reactionCounts($post);

            event(new VoiceCornerReactionUpdated($post->id, $counts));

            return $this->sendResponse([
                'post_id' => $post->id,
                'reactions' => $counts,
                'reacted_by_me' => $this->reactedByMe($post, Auth::id()),
            ], 'Reaction updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('Reaction could not be updated.', ['error' => $e->getMessage()], 500);
        }
    }

    private function formatPost(VoiceCornerPost $post, ?int $viewerId = null): array
    {
        $isOwn = $viewerId !== null && $post->user_id === $viewerId;

        return [
            'id' => $post->id,
            'tag' => $post->tag,
            'content' => $post->content,
            'is_anonymous' => $post->is_anonymous,
            'author' => $post->is_anonymous ? null : $this->formatAuthor($post->user),
            'is_own' => $isOwn,
            'reactions' => $this->reactionCounts($post),
            'reacted_by_me' => $viewerId !== null ? $this->reactedByMe($post, $viewerId) : null,
            'created_at' => $post->created_at->toIso8601String(),
            'created_at_human' => $this->humanTime($post->created_at),
        ];
    }

    private function formatAuthor(User $user): array
    {
        $lastInitial = $user->last_name ? strtoupper($user->last_name[0]).'.' : '';

        return [
            'id' => $user->id,
            'name' => trim($user->first_name.' '.$lastInitial),
            'initials' => strtoupper(($user->first_name[0] ?? '').($user->last_name[0] ?? '')),
        ];
    }

    private function reactionCounts(VoiceCornerPost $post): array
    {
        $counts = $post->reactions->countBy('type');

        return collect(VoiceCornerReaction::TYPES)
            ->mapWithKeys(fn ($type) => [$type => $counts->get($type, 0)])
            ->all();
    }

    private function reactedByMe(VoiceCornerPost $post, int $userId): array
    {
        $mine = $post->reactions->where('user_id', $userId)->pluck('type');

        return collect(VoiceCornerReaction::TYPES)
            ->mapWithKeys(fn ($type) => [$type => $mine->contains($type)])
            ->all();
    }

    private function humanTime(\Illuminate\Support\Carbon $date): string
    {
        if ($date->isToday()) {
            return 'Today';
        }

        if ($date->isYesterday()) {
            return 'Yesterday';
        }

        return $date->diffForHumans();
    }
}
