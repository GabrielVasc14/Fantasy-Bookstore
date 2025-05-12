<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RewardRedemption;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::all();
        $points = Auth::user()->points;

        return view('rewards.index', compact('rewards', 'points'));
    }

    public function index_crud(): View
    {
        $rewards = Reward::latest()->paginate(5);

        return view('rewards.index_crud', compact('rewards'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        return view('rewards.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cost_points' => 'required|numeric',
            'description' => 'required',
            'type' => 'required',
            'value' => 'nullable|numeric',
        ], [
            'name.required' => 'Reward name is mandatory',
            'cost_points.required' => 'Reward cost points is mandatory',
            'description.required' => 'Reward description is mandatory',
            'type.required' => 'Reward type is mandatory',
            'value.numeric' => 'Reward value must be a number',
        ], [
            'name' => 'Reward name',
            'cost_points' => 'Reward cost points',
            'description' => 'Reward description',
            'type' => 'Reward type',
            'value' => 'Reward value',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $rewards = new Reward;
        $rewards->name = $request->name;
        $rewards->cost_points = $request->cost_points;
        $rewards->description = $request->description;
        $rewards->type = $request->type;
        $rewards->value = $request->value;
        $rewards->save();

        if ($rewards) {
            return response()->json(['success' => 'Reward created successfully'], 200);
        } else {
            return response()->json(['error' => 'Reward creation failed'], 422);
        }
    }

    public function show($id): View
    {
        $rewards = Reward::findOrFail($id);

        return view('rewards.show', compact('rewards'));
    }

    public function edit($id): View
    {
        $rewards = Reward::findOrFail($id);

        return view('rewards.edit', compact('rewards'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cost_points' => 'required|numeric',
            'description' => 'required',
            'type' => 'required',
            'value' => 'nullable|numeric',
        ], [
            'name.required' => 'Reward name is mandatory',
            'cost_points.required' => 'Reward cost points is mandatory',
            'description.required' => 'Reward description is mandatory',
            'type.required' => 'Reward type is mandatory',
            'value.numeric' => 'Reward value must be a number',
        ], [
            'name' => 'Reward name',
            'cost_points' => 'Reward cost points',
            'description' => 'Reward description',
            'type' => 'Reward type',
            'value' => 'Reward value',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $rewards = Reward::findOrFail($id);
        $rewards->name = $request->name;
        $rewards->cost_points = $request->cost_points;
        $rewards->description = $request->description;
        $rewards->type = $request->type;
        $rewards->value = $request->value;
        $rewards->save();

        if ($rewards) {
            return response()->json(['success' => true, 'message' => 'Reward updated successfully'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'Error updating reward'], 422);
        }
    }

    public function destroy($id): RedirectResponse
    {
        $rewards = Reward::findOrFail($id);

        $rewards->delete();

        return redirect()->route('rewards.index_crud')
            ->with('success', 'Reward deleted successfully');
    }

    /**
     * Redeem a reward for the authenticated user.
     *
     * @param Reward $reward
     * @return RedirectResponse
     */
    public function redeem(reward $reward)
    {
        $user = User::find(Auth::id());

        if ($user->points < $reward->cost_points) {
            return back()->with('error', 'Not enough points.');
        }

        $user->decrement('points', $reward->cost_points);

        RewardRedemption::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
        ]);

        return back()->with('success', 'Reward Redeemed!');
    }
}
