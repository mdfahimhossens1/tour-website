<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            'image' => $this->image
                ? asset('uploads/blogs/' . $this->image)
                : null,

            'short_description' => $this->short_description,

            'description' => $this->description,

            'meta_title' => $this->meta_title,

            'meta_description' => $this->meta_description,

            'status' => (bool) $this->status,

            'category' => $this->whenLoaded('category', function () {

                return [

                    'id' => $this->category->id,

                    'name' => $this->category->name,

                    'slug' => $this->category->slug,

                ];

            }),

            'created_at' => $this->created_at?->format('Y-m-d'),

        ];
    }
}