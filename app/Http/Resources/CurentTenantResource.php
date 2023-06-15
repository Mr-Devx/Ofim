<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Car;
use App\Models\Payement;


class CurentTenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->created_by);
        $car = Car::find($this->car_id);
        $payement = Payement::find($this->payement_id);

        return [
            'id' => $this->id,
            'state' => $this->state,
            'payement' => $payement,
            'client' =>  $user,
            'car' => $car,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_an_extension' => $this->is_an_extension,
            'created_by' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

