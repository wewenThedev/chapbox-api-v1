<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupermarketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'denomination'=> $this->denomination,
            'website'=> $this->website,
            'address_id'=> $this->address_id,
            /*$table->text('description')->nullable();
            $table->string('denomination')->nullable();
            $table->string('rccm')->nullable();
            $table->string('ifu')->nullable();
            $table->string('website')->nullable();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('logo_id')->nullable()->constrained('media')->onDelete('set null');
            $table->foreignId('market_manager_id')->constrained('users')->onDelete('cascade');
            */
        ];
    }
}
