<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
class CurentTenantMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $receiver = User::find($this->receiver);
        $sender = User::find($this->sender);

        return [
            'id' => $this->id,
            'sender' => $sender,
            'receiver' => $receiver,
            'curent_tenant_id' => $this->curent_tenant_id,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
