<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Services\ParkingPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPrice = $this->total_price ?? ParkingPriceService::calculatePrice(
            $this->zone_id,
            $this->start_time,
            $this->stop_time
        );

        return [
            'id' => $this->id,
            'zone' => [
                'name' => $this->zone->name,
                'price_per_hour' => $this->zone->price_per_hour,
            ],
            'vehicle' => [
                'plate_number' => $this->vehicle->plate_number
            ],
            'start_time' => $this->start_time->toDateTimeString(),
            'stop_time' => $this->stop_time?->toDateTimeString(),
            'total_price' => $totalPrice,
        ];
    }
}