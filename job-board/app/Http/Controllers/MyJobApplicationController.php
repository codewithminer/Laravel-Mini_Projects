<?php

namespace App\Http\Controllers;
// use Illuminate\Routing\Controller;

use App\Models\JobApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyJobApplicationController extends Controller
{

    use AuthorizesRequests;
    
    public function index()
    {

        return view('my_job_applications.index',
        [
            'applications' => auth()->user()->jobApplications()
                ->with([
                    'job_offer' => fn($query) => $query->withCount('jobApplications')
                        ->withAvg('jobApplications', 'expected_salary'),
                    'job_offer.employer'
                    ])
                ->latest()->get()
        ]);
    }

    public function destroy(JobApplication $myJobApplication)
    {
        // beacuse the name of the delete route is:
        //DELETE  my-job-applications/{my_job_application}
        //so the name of the parameter shuold be `myJobApplication`
        $myJobApplication->delete();
        return redirect()->back()->with(
            'success',
            'Job application removed.'
        );
    }
}
