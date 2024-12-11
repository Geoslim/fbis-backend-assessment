<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->{'amount'},
            'reference' => $this->{'reference'},
            'type' => $this->{'type'},
            'network' => $this->{'network'},
            'partner' => $this->{'partner'},
            'status' => $this->{'status'},
            'recipient' => $this->{'recipient'},
            'commission' => $this->{'commission'},
            'description' => $this->{'description'},
            'created_at' => $this->{'created_at'},
        ];
    }
}
