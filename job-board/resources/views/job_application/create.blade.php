<x-layout>
    <x-breadcrumps class="mb-4" :links="[
        'Jobs' => route('jobs.index'), 
        $job->title => route('jobs.show', $job),
        'Apply' => '#']">
    </x-breadcrumps>

    <x-job-card :$job />
    <x-card>
        <h2 class="mb-4 text-lg font-medium">
            Your job application
        </h2>
        <form action="{{route('jobs.application.store', $job)}}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="expected_salary" class="mb-2 block text-sm font-medium text-slate-900">
                    Expected salary
                </label>
                <x-text-input type="number" name="expected_salary" />
            </div>
            <x-button class="w-full">
                Apply
            </x-button>
        </form>
    </x-card>
</x-layout>