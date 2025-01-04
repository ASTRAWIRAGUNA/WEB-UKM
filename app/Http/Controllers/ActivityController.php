<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityRequest;
use App\Models\Ukm;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ActivityController extends Controller
{

    public function index(Request $request): View
    {
        $activities = Activity::all();
        return view('activity.index', compact('activities'));
    }


    public function create(): View
    {
        $ukms = Ukm::all();
        return view('activity.create', compact( 'ukms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ukm_id' => 'required',
			'name' => 'required',
			'date' => 'required',
			'proof_photo' => 'required',
        ]);

        Activity::create([
            'ukm_id'        =>  $request->ukm_id,
			'name'          =>  $request->name,
			'date'          =>  $request->date,
			'proof_photo'   =>  $request->proof_photo,
        ]);

        return Redirect::route('activity.index')->with('success', 'Activity created successfully.');
    }

    public function show($id): View
    {
        $activity = Activity::find($id);

        return view('activity.show', compact('activity'));
    }

    public function edit($id): View
    {
        $ukms = Ukm::all();
        $activity = Activity::find($id);
        return view('activity.edit', compact('activity', 'ukms'));
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $activity->update($request->validated());

        return Redirect::route('activity.index')
            ->with('success', 'Activity updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Activity::find($id)->delete();

        return Redirect::route('activity.index')
            ->with('success', 'Activity deleted successfully');
    }   
}
