@extends('layouts.dashboard')

@section('title', 'Home Slider')
@section('page-heading', 'Home Page Slider')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
    @endif

    <div class="rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3 mb-6 text-sm text-amber-800">
        <p class="font-medium">Recommended image size: <strong>{{ \App\Http\Controllers\SliderController::RECOMMENDED_WIDTH }}×{{ \App\Http\Controllers\SliderController::RECOMMENDED_HEIGHT }} px</strong> for best display on the home page slider.</p>
        <p class="mt-1 text-amber-700">Only <strong>JPG</strong> and <strong>PNG</strong> images are allowed. Max file size: 5 MB.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm mb-8">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">Upload slide</h2>
        <form method="POST" action="{{ route('dashboard.slider.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Image (JPG or PNG)</label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                Upload slide
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-slate-800 border-b border-slate-200">Current slides (order on home page)</h2>
        @if ($slides->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <p class="text-lg font-medium">No slides yet.</p>
            <p class="mt-1 text-sm">Upload an image above to add the first slide. The home page will show a placeholder until you add at least one slide.</p>
        </div>
        @else
        <ul class="divide-y divide-slate-200">
            @foreach ($slides as $slide)
            <li class="flex flex-wrap items-center gap-4 p-4 sm:p-6">
                <div class="flex-shrink-0 w-48 sm:w-64 aspect-[1920/600] rounded-lg overflow-hidden bg-slate-200">
                    <img src="{{ $slide->image_url }}" alt="Slide {{ $loop->iteration }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm text-slate-500">Slide #{{ $loop->iteration }}</p>
                </div>
                <form action="{{ route('dashboard.slider.destroy', $slide) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Remove this slide?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Remove
                    </button>
                </form>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
@endsection
