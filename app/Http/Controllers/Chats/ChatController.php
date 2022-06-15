<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Repositories\Eloquent\ChatRepository;
use App\Repositories\Eloquent\criteria\WithTrashed;
use App\Repositories\Eloquent\MessageRepository;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chat;
    protected $message;

    public function __construct(ChatRepository $chat,MessageRepository $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    public function sendMessage(Request $request)
    {
        $validated = $this->validate($request,[
            'recipient' => ['required'],
            'body' => ['required'],
        ]);

        $recipient = $request->recipient;
        $body = $request->body;
        $user = auth()->user();

        $chat = $user->getChatWithUser($recipient);

        if (!$chat)
        {
            $chat = $this->chat->create([]);
            $this->chat->createParticipants($chat->id,[$user->id,$recipient]);
        }
        $message = $this->message->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read' => null
        ]);

        return new MessageResource($message);
    }

    public function getUserChats()
    {
        $chats = $this->chat->getUserChats();
        return ChatResource::collection($chats);
    }

    public function getChatMessages($id)
    {
        $messages = $this->message->withCriteria([
            new WithTrashed()
        ])->findWhere('chat_id',$id);
        return MessageResource::collection($messages);
    }

    public function markAsRead($id)
    {
        $chat = $this->chat->find($id);
        $chat->markAsReadForUser(auth()->id());
        return response()->json(['message' => 'موفقیت آمیز'],'200');
    }

    public function destroyMessage($id)
    {
        $message = $this->message->find($id);
        $this->authorize('delete',$message);
        $message->delete();
        return response()->json(['message' => 'موفقیت آمیز'],'200');
    }
}
