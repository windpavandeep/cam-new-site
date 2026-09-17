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
                <p class="mt-1 text-xs text-slate-500">Auto-generated; edit before uploading if needed.</p>
                @error('ref_no')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="student_name" class="mb-1 block text-sm font-medium text-slate-700">Student name</label>
                <input type="text" name="student_name" id="student_name" value="{{ old('student_name') }}" maxlength="150"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="father_name" class="mb-1 block text-sm font-medium text-slate-700">Father name</label>
                <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}" maxlength="150"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div class="sm:col-span-2">
                <label for="course_name" class="mb-1 block text-sm font-medium text-slate-700">Course name</label>
                <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" maxlength="200"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="start_date" class="mb-1 block text-sm font-medium text-slate-700">Start date</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('start_date')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="end_date" class="mb-1 block text-sm font-medium text-slate-700">End date</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('end_date')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
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
                        <th class="px-4 py-3">Father</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-700">
                    @foreach ($certificates as $item)
                    <tr>
                        <td class="px-4 py-3 font-mono font-semibold text-sky-700">{{ $item->ref_no }}</td>
                        <td class="px-4 py-3">{{ $item->student_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $item->father_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $item->course_name ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($item->start_date || $item->end_date)
                                {{ $item->start_date?->format('d M Y') ?? '—' }} – {{ $item->end_date?->format('d M Y') ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-[12rem] truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <button type="button"
                                class="js-edit-certificate text-sky-600 hover:text-sky-700"
                                data-id="{{ $item->id }}"
                                data-ref-no="{{ $item->ref_no }}"
                                data-student-name="{{ $item->student_name ?? '' }}"
                                data-father-name="{{ $item->father_name ?? '' }}"
                                data-course-name="{{ $item->course_name ?? '' }}"
                                data-start-date="{{ $item->start_date?->format('Y-m-d') ?? '' }}"
                                data-end-date="{{ $item->end_date?->format('Y-m-d') ?? '' }}"
                                data-issued-at="{{ $item->issued_at?->format('Y-m-d') ?? '' }}"
                                data-update-url="{{ route('dashboard.certificates.update', $item) }}">
                                Edit
                            </button>
                            <a href="{{ route('dashboard.certificates.download', $item) }}" class="ml-3 text-sky-600 hover:text-sky-700">Download</a>
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

    <div id="edit-certificate-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4" aria-hidden="true">
        <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Edit certificate</h3>
                <button type="button" id="close-edit-modal" class="text-slate-400 transition hover:text-slate-600" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="edit-certificate-form" method="POST" action="" class="grid gap-4 sm:grid-cols-2">
                @csrf
                @method('PUT')
                <div class="sm:col-span-2">
                    <label for="edit_ref_no" class="mb-1 block text-sm font-medium text-slate-700">Reference number <span class="text-red-400">*</span></label>
                    <input type="text" name="ref_no" id="edit_ref_no" required maxlength="64"
                        pattern="[A-Za-z0-9\-_]+"
                        autocomplete="off"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 font-mono uppercase text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div>
                    <label for="edit_student_name" class="mb-1 block text-sm font-medium text-slate-700">Student name</label>
                    <input type="text" name="student_name" id="edit_student_name" maxlength="150"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div>
                    <label for="edit_father_name" class="mb-1 block text-sm font-medium text-slate-700">Father name</label>
                    <input type="text" name="father_name" id="edit_father_name" maxlength="150"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div class="sm:col-span-2">
                    <label for="edit_course_name" class="mb-1 block text-sm font-medium text-slate-700">Course name</label>
                    <input type="text" name="course_name" id="edit_course_name" maxlength="200"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div>
                    <label for="edit_start_date" class="mb-1 block text-sm font-medium text-slate-700">Start date</label>
                    <input type="date" name="start_date" id="edit_start_date"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div>
                    <label for="edit_end_date" class="mb-1 block text-sm font-medium text-slate-700">End date</label>
                    <input type="date" name="end_date" id="edit_end_date"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div>
                    <label for="edit_issued_at" class="mb-1 block text-sm font-medium text-slate-700">Issue date</label>
                    <input type="date" name="issued_at" id="edit_issued_at"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
                <div class="sm:col-span-2 flex justify-end gap-3 pt-2">
                    <button type="button" id="cancel-edit-modal" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const input = document.getElementById('ref_no');
            const btn = document.getElementById('regenerate-ref-no');
            if (input && btn) {
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
            }

            const modal = document.getElementById('edit-certificate-modal');
            const form = document.getElementById('edit-certificate-form');
            const closeBtn = document.getElementById('close-edit-modal');
            const cancelBtn = document.getElementById('cancel-edit-modal');

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('.js-edit-certificate').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!form) return;
                    form.action = button.dataset.updateUrl || '';
                    document.getElementById('edit_ref_no').value = button.dataset.refNo || '';
                    document.getElementById('edit_student_name').value = button.dataset.studentName || '';
                    document.getElementById('edit_father_name').value = button.dataset.fatherName || '';
                    document.getElementById('edit_course_name').value = button.dataset.courseName || '';
                    document.getElementById('edit_start_date').value = button.dataset.startDate || '';
                    document.getElementById('edit_end_date').value = button.dataset.endDate || '';
                    document.getElementById('edit_issued_at').value = button.dataset.issuedAt || '';
                    openModal();
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) closeModal();
                });
            }
        })();
    </script>
    @endpush
@endsection
