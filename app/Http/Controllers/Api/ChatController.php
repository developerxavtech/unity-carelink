<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends BaseController
{
    /**
     * List conversations for the authenticated user.
     */
    public function index()
    {
        try {
            $user = Auth::user();

            $query = $user->hasRole('program_staff')
                ? Conversation::query()
                : Conversation::forUser($user->id);

            $conversations = $query
                ->with([
                    'participants',
                    'messages' => fn ($q) => $q->latest()->limit(1),
                ])
                ->latest()
                ->paginate(20);

            $conversations->getCollection()->transform(fn ($conversation) => $this->formatConversation($conversation, $user));

            return $this->sendResponse($conversations, 'Conversations retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Conversations could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Start a new conversation (or reuse the existing one with the target user) and send the first message.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string',
            ]);

            $authId = Auth::id();
            $targetUserId = $validated['user_id'];

            $conversation = Conversation::has('participants', '=', 2)
                ->whereHas('participants', fn ($q) => $q->where('user_id', $authId))
                ->whereHas('participants', fn ($q) => $q->where('user_id', $targetUserId))
                ->first();

            if (! $conversation) {
                $conversation = Conversation::create([
                    'subject' => $validated['subject'] ?? null,
                    'type' => 'general',
                ]);

                $conversation->addParticipant($authId);
                $conversation->addParticipant($targetUserId);
            }

            $message = $conversation->messages()->create([
                'user_id' => $authId,
                'content' => $validated['message'],
            ]);

            event(new MessageSent($message));

            return $this->sendResponse(
                $this->formatConversation($conversation->fresh(['participants']), Auth::user()),
                'Conversation started successfully.'
            );
        } catch (Exception $e) {
            return $this->sendError('Conversation could not be started.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a conversation's messages and participants.
     */
    public function show(Conversation $conversation)
    {
        try {
            $user = Auth::user();

            if (! $user->hasRole('program_staff') && ! $conversation->participants()->where('user_id', $user->id)->exists()) {
                return $this->sendError('You do not have access to this conversation.', [], 403);
            }

            $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();

            return $this->sendResponse([
                'conversation' => [
                    'id' => $conversation->id,
                    'subject' => $conversation->subject,
                    'type' => $conversation->type,
                ],
                'participants' => $conversation->participants->map(fn ($u) => $this->formatUser($u)),
                'messages' => $messages->map(fn ($m) => $this->formatMessage($m)),
            ], 'Conversation retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Conversation could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a message in a conversation and broadcast it in real time.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        try {
            $user = Auth::user();

            if (! $user->hasRole('program_staff') && ! $conversation->participants()->where('user_id', $user->id)->exists()) {
                return $this->sendError('You do not have access to this conversation.', [], 403);
            }

            $validated = $request->validate([
                'content' => 'required|string',
            ]);

            $message = $conversation->messages()->create([
                'user_id' => $user->id,
                'content' => $validated['content'],
            ]);

            event(new MessageSent($message));

            return $this->sendResponse($this->formatMessage($message->load('user')), 'Message sent successfully.');
        } catch (Exception $e) {
            return $this->sendError('Message could not be sent.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add a participant to the conversation.
     */
    public function addParticipant(Request $request, Conversation $conversation)
    {
        try {
            if (Auth::user()->hasRole('family_member')) {
                return $this->sendError('Family members cannot add participants.', [], 403);
            }

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $conversation->addParticipant($validated['user_id']);

            return $this->sendResponse(
                $conversation->fresh('participants')->participants->map(fn ($u) => $this->formatUser($u)),
                'Participant added successfully.'
            );
        } catch (Exception $e) {
            return $this->sendError('Participant could not be added.', ['error' => $e->getMessage()], 500);
        }
    }

    private function formatConversation(Conversation $conversation, User $forUser): array
    {
        $lastMessage = $conversation->messages->first();

        return [
            'id' => $conversation->id,
            'subject' => $conversation->subject,
            'type' => $conversation->type,
            'participants' => $conversation->participants->map(fn ($u) => $this->formatUser($u)),
            'last_message' => $lastMessage ? $this->formatMessage($lastMessage) : null,
        ];
    }

    private function formatMessage($message): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'content' => $message->content,
            'message_type' => $message->message_type,
            'attachments' => $message->attachments,
            'created_at' => $message->created_at->toIso8601String(),
            'user' => $this->formatUser($message->user),
        ];
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => trim($user->first_name.' '.$user->last_name),
            'role' => $user->getRoleNames()->first(),
        ];
    }
}
