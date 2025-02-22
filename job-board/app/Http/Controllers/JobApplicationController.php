<?php

namespace App\Http\Controllers;
// use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    use AuthorizesRequests;

    public function create(JobOffer $job)
    {
        $this->authorize('apply', $job);
        return view('job_application.create', ['job' => $job]);
    }

    public function store(JobOffer $job, Request $request)
    {
        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            ...$request->validate([
                'expected_salary' => 'required|min:1|max:1000000'
            ])
        ]);
        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job application submitted.');
    }

    public function show(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
