<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'page_count' => $this->page_count,
            'unit_count' => $this->unit_count,
            'isbn' => $this->isbn,
            'language' => $this->language,
            'binding' => $this->binding,
            'script' => $this->script,
            'dimensions' => $this->dimensions,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'image' => new ImageResource($this->whenLoaded('image')),
        ];
    }
}
