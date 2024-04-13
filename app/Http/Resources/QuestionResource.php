<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transforms the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $options = [];

        foreach ($this->options as $option) {
            $options[] = OptionResource::make($option);
        }

        if (empty($options)) {
            return [];
        }

        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'options' => $options,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
