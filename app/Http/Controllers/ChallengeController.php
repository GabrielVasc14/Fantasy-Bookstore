<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


class ChallengeController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        $all = Challenge::all();
        foreach ($all as $ch) {
            $user->challenges()->syncWithoutDetaching([$ch->id]);
        }

        $challenges = $user->challenges()->get();

        return view('challenges.index', compact('challenges'));
    }

    public function index_crud(): View
    {
        $challenges = Challenge::latest()->paginate(5);

        return view('challenges.index_crud', compact('challenges'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        return view('challenges.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'type' => 'required|in:reeding,reviews,purchases,generic',
        ], [
            'title.required' => 'Challenge name is mandatory',
            'description.required' => 'Challenge description is mandatory',
            'target.required' => 'Challenge target is mandatory',
            'period.required' => 'Challenge period is mandatory',
            'type.required' => 'Challenge type is mandatory',
        ], [
            'title' => 'Challenge name',
            'description' => 'Challenge description',
            'target' => 'Challenge target',
            'period' => 'Challenge period',
            'type' => 'Challenge type',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $challenges = new Challenge;
        $challenges->title = $request->title;
        $challenges->target = $request->target;
        $challenges->description = $request->description;
        $challenges->type = $request->type;
        $challenges->period = $request->period;
        $challenges->save();

        if ($challenges) {
            return response()->json(['success' => 'Challenge created successfully'], 200);
        } else {
            return response()->json(['error' => 'Challenge creation failed'], 422);
        }
    }

    public function show($id): View
    {
        $challenges = Challenge::findOrFail($id);
        return view('challenges.show', compact('challenges'));
    }

    public function edit($id): View
    {
        $challenges = Challenge::findOrFail($id);
        return view('challenges.edit', compact('challenges'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'type' => 'required|in:reading,reviews,purchases,generic',
        ], [
            'title.required' => 'Challenge name is mandatory',
            'description.required' => 'Challenge description is mandatory',
            'target.required' => 'Challenge target is mandatory',
            'period.required' => 'Challenge period is mandatory',
            'type.required' => 'Challenge type is mandatory',
        ], [
            'title' => 'Challenge name',
            'description' => 'Challenge description',
            'target' => 'Challenge target',
            'period' => 'Challenge period',
            'type' => 'Challenge type',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $challenges = Challenge::findOrFail($id);
        $challenges->title = $request->title;
        $challenges->target = $request->target;
        $challenges->description = $request->description;
        $challenges->type = $request->type;
        $challenges->period = $request->period;
        $challenges->save();

        if ($challenges) {
            return response()->json(['success' => 'Challenge updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Challenge update failed'], 422);
        }
    }

    public function destroy($id)
    {
        $challenges = Challenge::findOrFail($id);
        $challenges->delete();

        return redirect()->route('challenges.index_crud')
            ->with('success', 'Challenge deleted successfully');
    }

    /**
     * Increment the progress of a challenge for the authenticated user.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function increment($id)
    {
        $user = User::find(Auth::id());
        $challenge = Challenge::findOrFail($id);
        $pivot = $user->challenges()->where('challenge_id', $id)->first()->pivot;

        switch ($challenge->type) {
            case 'reading':
                // contar livros lidos
                $actual = $user->reviews()->count();
                if ($pivot->progress < $actual && $pivot->progress < $challenge->target) {
                    $pivot->progress++;
                }
                break;

            case 'reviews':
                //So acrescentar 1 na leitura se tiver mais reviews
                $actual = $user->reviews()->count();
                if ($pivot->progress < $actual && $pivot->progress < $challenge->target) {
                    $pivot->progress++;
                }
                break;

            case 'purchases':
                $actual = $user->orders()->sum('quantity');
                if ($pivot->progress < $actual && $pivot->progress < $challenge->target) {
                    $pivot->progress++;
                }
                break;
        }

        if ($pivot->progress >= $challenge->target) {
            $pivot->completed = true;
        }
        $pivot->save();

        return redirect()->route('challenges.index')
            ->with('success', 'Challenge progress updated successfully');
    }
}
