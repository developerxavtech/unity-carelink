<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\IndividualProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $user = Auth::user();

        // If program staff, they can see all conversations.
        // Otherwise, only their own.
        if ($user->hasRole('program_staff')) {
            $conversations = Conversation::with([
                'participants',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                }
            ])->latest()->paginate(20);
        } else {
            $conversations = Conversation::forUser($user->id)
                ->with([
                    'participants',
                    'messages' => function ($q) {
                        $q->latest()->limit(1);
                    }
                ])->latest()->paginate(20);

            $conversations->getCollection()->transform(function ($conversation) use ($user) {
                $conversation->receiver = $conversation->participants
                    ->where('id', '!=', $user->id)
                    ->map(fn($u) => $u->first_name . ' ' . $u->last_name)
                    ->implode(', ');
                return $conversation;
            });
        }

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show the form for creating a new conversation.
     */
    public function create()
    {
        $user = Auth::user();
        $relevantUsers = collect();

        if ($user->hasRole('family_admin')) {
            // Families can start chats with other family members and assigned DSPs
            $relevantUsers = User::role('family_admin')
                ->where('id', '!=', $user->id)
                ->get()
                ->concat(User::role('family_member')->get())
                ->concat(User::role('dsp')->get());
        } elseif ($user->hasRole('dsp')) {
            // DSPs can only start chats with other DSPs
            $relevantUsers = User::role('dsp')->where('id', '!=', $user->id)->get();
        }

        return view('chat.create', compact('relevantUsers'));
    }

    /**
     * Store a newly created conversation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $authId = Auth::id();
        $targetUserId = $validated['user_id'];

        // Check if a conversation already exists between these two users
        // We look for conversations with exactly 2 participants, containing both users
        $conversation = Conversation::has('participants', '=', 2)
            ->whereHas('participants', function ($q) use ($authId) {
                $q->where('user_id', $authId);
            })
            ->whereHas('participants', function ($q) use ($targetUserId) {
                $q->where('user_id', $targetUserId);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'subject' => $validated['subject'],
                'type' => 'general',
            ]);

            $conversation->addParticipant($authId);
            $conversation->addParticipant($targetUserId);
        } else {
            // dd('Found existing conversation', $conversation->id);
        }

        $conversation->messages()->create([
            'user_id' => $authId,
            'content' => $validated['message'],
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Display the specified conversation.
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Access control
        if (!$user->hasRole('program_staff') && !$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();
        $participants = $conversation->participants;
        // For adding participants, list relevant users not already in chat
        $potentialParticipants = collect();
        if ($user->hasRole('family_admin')) {
            // Families can add other family members and DSPs
            $potentialParticipants = User::role('family_admin')
                ->where('id', '!=', $user->id)
                ->whereNotIn('id', $participants->pluck('id'))
                ->get()
                ->concat(
                    User::role('family_member')
                        ->whereNotIn('id', $participants->pluck('id'))
                        ->get()
                )
                ->concat(
                    User::role('dsp')
                        ->whereNotIn('id', $participants->pluck('id'))
                        ->get()
                );
        } elseif ($user->hasRole('dsp')) {
            // DSPs can only add other DSPs to the conversation
            $potentialParticipants = User::role('dsp')
                ->where('id', '!=', $user->id)
                ->whereNotIn('id', $participants->pluck('id'))
                ->get();
        }

        return view('chat.show', compact('conversation', 'messages', 'participants', 'potentialParticipants'));
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return back();
    }

    /**
     * Add a participant to the conversation.
     */
    public function addParticipant(Request $request, Conversation $conversation)
    {
        if (Auth::user()->hasRole('family_member')) {
            abort(403, 'Family members cannot add participants.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $conversation->addParticipant($validated['user_id']);

        return back()->with('success', 'Participant added!');
    }
}