<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class JobOfferController extends BaseController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', JobOffer::class);

        $filters = request()->only(
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category'
        );
        return view(
            'job_offer.index', 
            ['jobs'=>JobOffer::with('employer')->latest()->filter($filters)->get()]);
    }

    public function show(JobOffer $job)
    {
        $this->authorize('view', $job);
        return view('job_offer.show', ['job' => $job->load('employer.job_offers')]);
    }

}
