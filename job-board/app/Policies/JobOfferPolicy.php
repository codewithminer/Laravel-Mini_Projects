<?php

namespace App\Policies;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobOfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function viewAnyEmployer(User $user): bool
    {
        return true;
    }
    

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, JobOffer $jobOffer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->employer !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobOffer $jobOffer): bool|Response
    {
        if($jobOffer->employer->user_id !== $user->id){
            return false;
        }
        if($jobOffer->jobApplications->count() > 0){
            return Response::deny('Cannot update job with applications.');
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobOffer $jobOffer): bool
    {
        return $jobOffer->employer->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobOffer $jobOffer): bool
    {
        return $jobOffer->employer->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobOffer $jobOffer): bool
    {
        return $jobOffer->employer->user_id === $user->id;
    }

    public function apply(User $user, JobOffer $job): bool{
        return !$job->hasUserApplied($user);
    }
}
