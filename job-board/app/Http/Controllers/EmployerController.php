<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class EmployerController extends BaseController
{
    use AuthorizesRequests;

    public function __construct(){
        // authorize resource -> laravel will automatically add the policies to the controller.
       $this->authorizeResource(Employer::class);
    }
 
    public function create()
    {
        return view('employer.create');
    }

    public function store(Request $request)
    {
        auth()->user()->employer()->create([
            'company_name' => $request->validate([
                'company_name' => 'required|min:3|unique:employers,company_name'
            ])['company_name']
        ]);

        return redirect()->route('jobs.index')->with(
            'success', 'Your employer account was created!');
    }
}
