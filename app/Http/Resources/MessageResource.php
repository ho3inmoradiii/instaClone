<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'chat_id' => $this->chat_id,
            'body' => $this->body,
            'last_read' => $this->last_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'chat' => new ChatResource($this->chat)
        ];
    }
}
