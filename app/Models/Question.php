<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Returns all questions, options, vote counts.
     *
     * @return array
     */
    public static function getVoteResults(): array
    {
        return self::with(['options.votes'])
        ->get()
        ->mapWithKeys(function ($question) {
            return [
                $question->question_text => $question->options->mapWithKeys(function ($option) {
                    return [
                        $option->option_text => [
                            'count' => $option->votes->count(),
                            'id' => $option->id
                        ]
                    ];
                })
            ];
        })->toArray();
    }
}
