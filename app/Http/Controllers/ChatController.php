<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $actor = $this->resolveActorContext($request->user());

        $startVendorId = null;

        if ($actor['role'] === 'user' && $request->filled('vendor')) {
            $startVendorId = (int) $request->query('vendor');
        }

        return view('chat.index', [
            'startVendorId' => $startVendorId,
            'actorRole' => $actor['role'],
        ]);
    }

    public function startWithVendor(Request $request, Vendor $vendor): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());

        if ($actor['role'] !== 'user') {
            abort(403, 'Hanya user yang bisa memulai chat dari halaman publik.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'vendor_id' => $vendor->id,
            ],
            [
                'last_message_at' => now(),
                'last_message_preview' => 'Percakapan dimulai.',
            ]
        );

        $conversation->loadMissing('vendor.district', 'user');

        return response()->json([
            'conversation_id' => $conversation->id,
            'conversation' => $this->serializeConversation($conversation, $actor),
            'created' => $conversation->wasRecentlyCreated,
        ]);
    }

    public function conversations(Request $request): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());
        $keyword = trim((string) $request->query('q', ''));

        $query = Conversation::query()
            ->with([
                'user',
                'vendor.district',
            ]);

        if ($actor['role'] === 'user') {
            $query->where('user_id', $actor['user_id'])
                ->when($keyword !== '', function ($conversationQuery) use ($keyword) {
                    $conversationQuery->whereHas('vendor', function ($vendorQuery) use ($keyword) {
                        $vendorQuery->where('store_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
                });
        } else {
            $query->where('vendor_id', $actor['vendor_id'])
                ->when($keyword !== '', function ($conversationQuery) use ($keyword) {
                    $conversationQuery->whereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone_number', 'like', "%{$keyword}%");
                    });
                });
        }

        $conversations = $query
            ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
            ->limit(100)
            ->get();

        return response()->json([
            'conversations' => $conversations
                ->map(fn (Conversation $conversation) => $this->serializeConversation($conversation, $actor))
                ->values(),
            'unread_count' => $this->totalUnreadCount($actor),
        ]);
    }

    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());
        $this->assertConversationAccess($conversation, $actor);

        $afterId = (int) $request->query('after_id', 0);
        $limit = max(1, min(200, (int) $request->query('limit', 100)));

        $messagesQuery = Message::query()
            ->where('conversation_id', $conversation->id)
            ->with('sender');

        if ($afterId > 0) {
            $messages = $messagesQuery
                ->where('id', '>', $afterId)
                ->orderBy('id')
                ->get();
        } else {
            $messages = $messagesQuery
                ->latest('id')
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();
        }

        $conversation->loadMissing('user', 'vendor.district');

        return response()->json([
            'conversation' => $this->serializeConversation($conversation, $actor),
            'messages' => $messages
                ->map(fn (Message $message) => $this->serializeMessage($message, $actor))
                ->values(),
            'last_message_id' => (int) ($conversation->messages()->max('id') ?? 0),
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());
        $this->assertConversationAccess($conversation, $actor);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $messageBody = trim($validated['body']);

        if ($messageBody === '') {
            return response()->json([
                'message' => 'Isi pesan tidak boleh kosong.',
            ], 422);
        }

        $message = DB::transaction(function () use ($conversation, $actor, $request, $messageBody) {
            $createdMessage = $conversation->messages()->create([
                'sender_id' => $request->user()->id,
                'sender_role' => $actor['role'],
                'body' => $messageBody,
            ]);

            $updates = [
                'last_message_at' => now(),
                'last_message_preview' => Str::limit($messageBody, 120),
            ];

            if ($actor['role'] === 'user') {
                $updates['unread_vendor_count'] = DB::raw('unread_vendor_count + 1');
            } else {
                $updates['unread_user_count'] = DB::raw('unread_user_count + 1');
            }

            $conversation->update($updates);

            return $createdMessage;
        });

        $message->loadMissing('sender');
        $conversation->refresh()->loadMissing('user', 'vendor.district');

        return response()->json([
            'message' => $this->serializeMessage($message, $actor),
            'conversation' => $this->serializeConversation($conversation, $actor),
            'unread_count' => $this->totalUnreadCount($actor),
        ]);
    }

    public function markAsRead(Request $request, Conversation $conversation): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());
        $this->assertConversationAccess($conversation, $actor);

        DB::transaction(function () use ($conversation, $actor) {
            if ($actor['role'] === 'user') {
                $conversation->messages()
                    ->where('sender_role', 'vendor')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                $conversation->update(['unread_user_count' => 0]);
            } else {
                $conversation->messages()
                    ->where('sender_role', 'user')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                $conversation->update(['unread_vendor_count' => 0]);
            }
        });

        return response()->json([
            'unread_count' => $this->totalUnreadCount($actor),
        ]);
    }

    public function unread(Request $request): JsonResponse
    {
        $actor = $this->resolveActorContext($request->user());

        return response()->json([
            'unread_count' => $this->totalUnreadCount($actor),
        ]);
    }

    private function resolveActorContext($authUser): array
    {
        if (!$authUser) {
            abort(401);
        }

        if ($authUser->role === 'user') {
            return [
                'role' => 'user',
                'user_id' => (int) $authUser->id,
            ];
        }

        if ($authUser->role === 'vendor') {
            $vendor = $authUser->vendor()->withTrashed()->first();

            if (!$vendor) {
                abort(403, 'Akun vendor belum terhubung dengan data toko.');
            }

            return [
                'role' => 'vendor',
                'user_id' => (int) $authUser->id,
                'vendor_id' => (int) $vendor->id,
            ];
        }

        abort(403, 'Role akun ini tidak diizinkan mengakses chat.');
    }

    private function assertConversationAccess(Conversation $conversation, array $actor): void
    {
        if ($actor['role'] === 'user' && (int) $conversation->user_id === (int) $actor['user_id']) {
            return;
        }

        if ($actor['role'] === 'vendor' && (int) $conversation->vendor_id === (int) $actor['vendor_id']) {
            return;
        }

        abort(403, 'Kamu tidak punya akses ke percakapan ini.');
    }

    private function totalUnreadCount(array $actor): int
    {
        if ($actor['role'] === 'user') {
            return (int) Conversation::where('user_id', $actor['user_id'])->sum('unread_user_count');
        }

        return (int) Conversation::where('vendor_id', $actor['vendor_id'])->sum('unread_vendor_count');
    }

    private function serializeConversation(Conversation $conversation, array $actor): array
    {
        $isUser = $actor['role'] === 'user';
        $counterpartName = $isUser
            ? ($conversation->vendor?->store_name ?? 'Vendor')
            : ($conversation->user?->name ?? 'User');
        $counterpartSubtitle = $isUser
            ? ($conversation->vendor?->district?->name ?? 'Vendor RENMOTE')
            : ($conversation->user?->email ?? '-');

        return [
            'id' => (int) $conversation->id,
            'counterpart_name' => $counterpartName,
            'counterpart_subtitle' => $counterpartSubtitle,
            'counterpart_avatar' => Str::upper(Str::substr($counterpartName, 0, 2)),
            'last_message_preview' => $conversation->last_message_preview ?: 'Belum ada pesan.',
            'last_message_at' => $conversation->last_message_at?->toIso8601String(),
            'last_message_label' => $conversation->last_message_at
                ? $conversation->last_message_at->format('d M H:i')
                : '-',
            'unread_count' => $isUser
                ? (int) $conversation->unread_user_count
                : (int) $conversation->unread_vendor_count,
        ];
    }

    private function serializeMessage(Message $message, array $actor): array
    {
        return [
            'id' => (int) $message->id,
            'body' => $message->body,
            'sender_id' => (int) $message->sender_id,
            'sender_role' => $message->sender_role,
            'is_mine' => (int) $message->sender_id === (int) $actor['user_id'],
            'created_at' => optional($message->created_at)->toIso8601String(),
            'created_label' => optional($message->created_at)->format('H:i'),
            'read_at' => optional($message->read_at)->toIso8601String(),
        ];
    }
}
