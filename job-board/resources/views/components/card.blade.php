
{{-- attributes->merge allows us to add more styles features inside 
the parent that uses this card component --}}
<article {{$attributes->merge(['class' => 'rounded-md border border-slate-300 bg-white p-4 mb-4 shadow-sm'])}}>
    {{$slot}}
</article>

{{-- another way: --}}
{{-- {{$attributes->class('rounded-md border border-slate-300 bg-white p-4 mb-4 shadow-sm')}} --}}