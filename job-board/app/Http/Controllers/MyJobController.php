<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobOfferRequest;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class MyJobController extends BaseController
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('viewAnyEmployer', JobOffer::class);

        return view(
            'my_jobs.index',
            [
                'jobs' => $request->user()->employer
                    ->job_offers()
                    ->with(['employer', 'jobApplications', 'jobApplications.user'])
                    ->withTrashed()
                    ->get()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', JobOffer::class);
        return view('my_jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobOfferRequest $request)
    {
        $this->authorize('create', JobOffer::class);
        $request->user()->employer->job_offers()->create($request->validated());
        return redirect()->route('my-jobs.index')->with(
            'success', 'Job created successfully.'
        );
    }

    public function edit(JobOffer $myJob)
    {
        $this->authorize('update', $myJob);

        //IMPORTANT: the parameter must be the same as the route parameter which is $myJob
        // you can see the route parameter with the command: php artisan route:list
        return view('my_jobs.edit', ['job' => $myJob]);
    }

    public function update(JobOfferRequest $request, JobOffer $myJob)
    {
        $this->authorize('update', $myJob);
        $myJob->update($request->validated());

        return redirect()->route('my-jobs.index')->with(
            'success', 'Job updated successfully.'
        );
    }

    public function destroy(JobOffer $myJob)
    {
        $myJob->delete();
        
        return redirect()->route('my-jobs.index')->with(
            'success', 'Job deleted successfully.'
        );
    }
}
