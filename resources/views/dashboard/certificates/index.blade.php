@extends('layouts.dashboard')

@section('title', 'Certificates')
@section('page-heading', 'Student certificates')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="mb-6 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
        Upload certificates for past students. Each entry needs a unique <strong>reference number</strong> students use on the
        <a href="{{ route('certificates.lookup') }}" class="font-semibold underline hover:text-sky-700" target="_blank" rel="noopener">public certificate lookup</a> page.
    </div>

    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">Add certificate</h2>
        <form method="POST" action="{{ route('dashboard.certificates.store') }}" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
            @csrf
            <div class="sm:col-span-2">
                <div class="mb-1 flex flex-wrap items-center justify-between gap-2">
                    <label for="ref_no" class="text-sm font-medium text-slate-700">Reference number <span class="text-red-400">*</span></label>
                    <button type="button" id="regenerate-ref-no"
                        class="text-xs font-medium text-sky-600 transition hover:text-sky-700">
                        Generate new
                    </button>
                </div>
                <input type="text" name="ref_no" id="ref_no" value="{{ old('ref_no', $suggested_ref_no) }}" required maxlength="64"
                    pattern="[A-Za-z0-9\-_]+"
                    autocomplete="off"
                    placeholder="e.g. CAM2026A3K9F2"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 font-mono uppercase text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                <p class="mt-1 text-xs text-slate-500">Auto-generated; you can edit it before uploading.</p>
                @error('ref_no')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="student_name" class="mb-1 block text-sm font-medium text-slate-700">Student name</label>
                <input type="text" name="student_name" id="student_name" value="{{ old('student_name') }}" maxlength="150"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="course_name" class="mb-1 block text-sm font-medium text-slate-700">Course name</label>
                <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" maxlength="200"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="issued_at" class="mb-1 block text-sm font-medium text-slate-700">Issue date</label>
                <input type="date" name="issued_at" id="issued_at" value="{{ old('issued_at') }}"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="file" class="mb-1 block text-sm font-medium text-slate-700">Certificate file <span class="text-red-400">*</span></label>
                <input type="file" name="file" id="file" required accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/*"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-sky-100 file:px-3 file:py-1.5 file:text-sm file:text-sky-800">
                @error('file')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-slate-500">PDF or image, max 20 MB.</p>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-500">
                    Upload certificate
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <h2 class="border-b border-slate-200 px-6 py-4 text-lg font-semibold text-slate-800">Uploaded certificates</h2>
        @if ($certificates->isEmpty())
        <p class="p-8 text-center text-slate-500">No certificates yet.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700/70 text-sm">
                <thead class="bg-slate-100/60 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Ref no</th>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-700">
                    @foreach ($certificates as $item)
                    <tr>
                        <td class="px-4 py-3 font-mono font-semibold text-sky-700">{{ $item->ref_no }}</td>
                        <td class="px-4 py-3">{{ $item->student_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $item->course_name ?? '—' }}</td>
                        <td class="px-4 py-3 max-w-[12rem] truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('dashboard.certificates.download', $item) }}" class="text-sky-600 hover:text-sky-700">Download</a>
                            <form method="POST" action="{{ route('dashboard.certificates.destroy', $item) }}" class="inline ml-3" onsubmit="return confirm('Delete this certificate?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        (function () {
            const input = document.getElementById('ref_no');
            const btn = document.getElementById('regenerate-ref-no');
            if (!input || !btn) return;

            const prefix = 'CAM' + new Date().getFullYear();
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

            function randomSuffix(length) {
                let out = '';
                const bytes = crypto.getRandomValues(new Uint8Array(length));
                for (let i = 0; i < length; i++) {
                    out += chars[bytes[i] % chars.length];
                }
                return out;
            }

            btn.addEventListener('click', function () {
                input.value = prefix + randomSuffix(6);
                input.focus();
            });
        })();
    </script>
    @endpush
@endsection
