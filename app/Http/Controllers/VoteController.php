<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Http\Resources\VoteResource;
use App\Jobs\StoreVoteJob;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class VoteController extends Controller
{
    /**
     * Displays a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Votes/Show', [
            'votes' => Vote::all(),
        ]);
    }

    /**
     * Shows the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Stores a newly created resource in the queue.
     *
     * @throws Exception
     */
    public function store(StoreVoteRequest $storeVoteRequest)
    {
        if (empty($storeVoteRequest['option_ids'])) {
            return response()->json([
                'saved' => false,
                'results' => [],
            ]);
        }

        $votes = [];
        $ipAddress = $storeVoteRequest->getClientIp();
        $userId = Auth::id();

        foreach ($storeVoteRequest['option_ids'] as $option) {
            StoreVoteJob::dispatch($userId, $option, $ipAddress);
            $votes[] = Option::query()->find($option);
        }

        $results = Question::getVoteResults();

        return response()->json([
            'saved' => true,
            'results' => $results,
            'votes' => $votes,
        ]);
    }


    /**
     * Displays the specified resource.
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function show(Request $request): Response|RedirectResponse
    {
        $user = User::query()->find(Auth::id());

        if (!$user instanceof User) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        }

        $votes = $user->votes;

        $hasVoteRecorded = !$votes->isEmpty();

        return Inertia::render('Votes/Show', [
            'votes' => $hasVoteRecorded ? VoteResource::collection($votes) : [],
            'hasVoteRecorded' => $hasVoteRecorded,
            'questions' => Question::getVoteResults(),
        ]);
    }

    /**
     * Shows the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Updates the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, Vote $vote)
    {
        //
    }

    /**
     * Removes the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
