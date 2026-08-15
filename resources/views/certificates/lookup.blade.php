@extends('layouts.app')

@section('title', 'Certificate Lookup')

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-10 md:py-14">
    <header class="reveal mb-10 rounded-2xl border border-teal-200 bg-gradient-to-br from-teal-50 via-white to-sky-50 px-6 py-8 md:px-8">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-teal-700">Past students</p>
        <h1 class="font-display mb-3 text-3xl font-bold text-cam-ink md:text-4xl">Find your certificate</h1>
        <p class="text-slate-600">Enter the reference number from CAM Solutions to view and download your certificate.</p>
    </header>

    <form method="GET" action="{{ route('certificates.lookup') }}" class="reveal mb-8 rounded-2xl border border-cam-line bg-white p-5 shadow-sm md:p-6">
        <label for="ref" class="mb-2 block text-sm font-medium text-slate-700">Reference number</label>
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="ref" id="ref" value="{{ $ref_input }}" required
                placeholder="e.g. CAM2026A3K9F2"
                autocomplete="off"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 font-mono uppercase text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-200">
            <button type="submit"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-600">
                Search
            </button>
        </div>
    </form>

    @if ($not_found)
    <div class="reveal rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
        No certificate found for reference <strong class="font-mono">{{ $ref_input }}</strong>. Check the number and try again, or contact us if you need help.
    </div>
    @endif

    @if ($certificate)
    <section class="reveal overflow-hidden rounded-2xl border border-teal-200 bg-white shadow-md shadow-slate-200/80">
        <div class="border-b border-slate-200 px-5 py-4 md:px-6">
            <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Certificate found</p>
            <p class="mt-1 font-mono text-lg font-semibold text-cam-steel">{{ $certificate->ref_no }}</p>
            <dl class="mt-3 grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
                @if ($certificate->student_name)
                <div><dt class="text-slate-500">Student</dt><dd class="font-medium text-slate-800">{{ $certificate->student_name }}</dd></div>
                @endif
                @if ($certificate->course_name)
                <div><dt class="text-slate-500">Course</dt><dd class="font-medium text-slate-800">{{ $certificate->course_name }}</dd></div>
                @endif
                @if ($certificate->issued_at)
                <div><dt class="text-slate-500">Issued</dt><dd>{{ $certificate->issued_at->format('d M Y') }}</dd></div>
                @endif
            </dl>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('certificates.download', ['certificate' => $certificate, 'ref' => $certificate->ref_no]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-600">
                    Download certificate
                </a>
                <a href="{{ route('certificates.view', ['certificate' => $certificate, 'ref' => $certificate->ref_no]) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-teal-400 hover:text-teal-700">
                    Open in new tab
                </a>
            </div>
        </div>
        <div class="bg-slate-50 p-3 md:p-4">
            @if ($certificate->isPdf())
            <iframe src="{{ route('certificates.view', ['certificate' => $certificate, 'ref' => $certificate->ref_no]) }}"
                class="h-[min(70vh,520px)] w-full rounded-lg border border-slate-200 bg-white"
                title="Certificate preview"></iframe>
            @elseif ($certificate->isImage())
            <img src="{{ route('certificates.view', ['certificate' => $certificate, 'ref' => $certificate->ref_no]) }}"
                alt="Certificate for {{ $certificate->ref_no }}"
                class="mx-auto max-h-[70vh] w-full rounded-lg border border-slate-200 object-contain">
            @else
            <p class="py-8 text-center text-sm text-slate-500">Preview not available for this file type. Use download instead.</p>
            @endif
        </div>
    </section>
    @endif
</div>
@endsection
