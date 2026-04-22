<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $messages = ContactMessage::query()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard.contact.messages.index', [
            'messages' => $messages,
        ]);
    }

    public function show(ContactMessage $contact_message): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $contact_message->markRead();

        return view('dashboard.contact.messages.show', [
            'message' => $contact_message,
        ]);
    }

    public function destroy(ContactMessage $contact_message): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $contact_message->delete();

        return redirect()->route('dashboard.contact-messages.index')->with('success', 'Message removed.');
    }
}
