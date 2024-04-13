<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UniqueUserQuestion implements ValidationRule
{
    /**
     * Check if user has voted already.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $questionId = DB::table('options')
            ->where('id', $value)
            ->value('question_id');

        $exists = DB::table('votes')
            ->join('options', 'votes.option_id', '=', 'options.id')
            ->where('options.question_id', $questionId)
            ->where('votes.user_id', Auth::id())
            ->exists();

        if ($exists) {
            $fail('User already voted.');
        }
    }
}
