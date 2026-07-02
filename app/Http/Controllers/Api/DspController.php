<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DspClientResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DspController extends BaseController
{
    /**
     * 5. DSP's Clients List
     *
     * Returns all family admins (clients) that are currently involved
     * in conversations with the authenticated DSP, along with their
     * individual profile details, status, and timing info.
     *
     * GET /api/dsp/clients
     */
    public function clientsList(Request $request)
    {
        try {
            /** @var User $dsp */
            $dsp = Auth::user();

            if (! $dsp->hasRole('dsp')) {
                return $this->sendError('Unauthorized. Only DSPs can access this endpoint.', [], 403);
            }

            $conversationIds = Conversation::forUser($dsp->id)->pluck('id');

            $clientIds = User::whereHas('roles', function ($q) {
                $q->where('name', 'family_admin');
            })
                ->whereHas('conversations', function ($q) use ($conversationIds) {
                    $q->whereIn('conversations.id', $conversationIds);
                })
                ->where('id', '!=', $dsp->id)
                ->pluck('id');

            $clients = User::with([
                'individualProfiles.moodChecks' => function ($q) {
                    $q->orderBy('check_date', 'desc')->limit(5);
                },
                'individualProfiles.careNotes' => function ($q) {
                    $q->orderBy('created_at', 'desc')->limit(1);
                },
                'conversations' => function ($q) use ($conversationIds) {
                    $q->whereIn('conversations.id', $conversationIds)
                        ->with(['messages' => function ($mq) {
                            $mq->orderBy('created_at', 'desc')->limit(1);
                        }]);
                },
            ])
                ->whereIn('id', $clientIds)
                ->get();

            // Apply pagination
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $total = $clients->count();
            $paginated = $clients->forPage($page, $perPage)->values();

            return $this->sendResponse([
                'clients' => DspClientResource::collection($paginated),
                'pagination' => [
                    'total' => $total,
                    'per_page' => (int) $perPage,
                    'current_page' => (int) $page,
                    'last_page' => (int) ceil($total / $perPage),
                ],
            ], 'DSP clients list retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 6. Search Clients
     *
     * Searches across family admins who are in active conversations
     * with the authenticated DSP by name, email, or individual name.
     *
     * GET /api/dsp/clients/search?q=...
     */
    public function searchClients(Request $request)
    {
        try {
            $dsp = Auth::user();

            if (! $dsp->hasRole('dsp')) {
                return $this->sendError('Unauthorized. Only DSPs can access this endpoint.', [], 403);
            }

            $validator = Validator::make($request->all(), [
                'q' => 'nullable|string|min:1|max:100',
                'status' => 'nullable|string|in:active,inactive,pending',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $search = $request->get('q', '');
            $status = $request->get('status');
            $perPage = $request->get('per_page', 15);

            $conversationIds = Conversation::forUser($dsp->id)->pluck('id');

            $query = User::with([
                'individualProfiles',
                'conversations' => function ($q) use ($conversationIds) {
                    $q->whereIn('conversations.id', $conversationIds)
                        ->with(['messages' => function ($mq) {
                            $mq->orderBy('created_at', 'desc')->limit(1);
                        }]);
                },
            ])
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'family_admin');
                })
                ->whereHas('conversations', function ($q) use ($conversationIds) {
                    $q->whereIn('conversations.id', $conversationIds);
                })
                ->where('id', '!=', $dsp->id);

            // Search filter
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('individualProfiles', function ($iq) use ($search) {
                            $iq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                });
            }

            // Status filter
            if ($status) {
                $query->where('status', $status);
            }

            $clients = $query->paginate($perPage);

            return $this->sendResponse([
                'clients' => DspClientResource::collection($clients->items()),
                'pagination' => [
                    'total' => $clients->total(),
                    'per_page' => $clients->perPage(),
                    'current_page' => $clients->currentPage(),
                    'last_page' => $clients->lastPage(),
                ],
                'search_query' => $search,
            ], 'Search results retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
