<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
{
    /**
     * Transforms the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'option_id' => $this->option->id,
            'option_text' => $this->option->option_text,
            'question_text' => $this->question->question_text,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
