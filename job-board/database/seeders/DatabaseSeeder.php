<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'saeed ghadiri',
            'email' => 'saeedghadirii99@gmail.com'
        ]);
        
        User::factory(300)->create();

        $users = User::all()->shuffle();
        for($i=0; $i<20; $i++){
            Employer::factory()->create([
                'user_id' => $users->pop()->id
            ]);
        }

        $employers = Employer::all();
        for($i=0; $i<100; $i++){
            JobOffer::factory()->create([
                'employer_id' => $employers->random()->id
            ]);
        }
        
        foreach($users as $user){
            $jobs = JobOffer::inRandomOrder()->take(rand(0,4))->get();
            
            foreach($jobs as $job){
                JobApplication::factory()->create([
                    'job_offer_id' => $job->id,
                    'user_id' => $user->id
                ]);
            }
        }

        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
