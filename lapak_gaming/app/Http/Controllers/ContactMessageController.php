<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:general,payment,account,fraud'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'category.required' => 'Kategori pesan wajib dipilih.',
            'category.in' => 'Kategori pesan tidak valid.',
            'message.required' => 'Isi pesan wajib diisi.',
            'message.min' => 'Isi pesan minimal 10 karakter.',
        ]);

        ContactMessage::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'category' => $data['category'],
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Pesan berhasil dikirim. Tim kami akan menghubungi Anda melalui email.');
    }

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status', 'all');
        $status = in_array($status, ['all', 'new', 'read', 'replied'], true) ? $status : 'all';

        $messages = ContactMessage::query()
            ->with(['user', 'replier'])
            ->when($q, function ($query) use ($q): void {
                $query->where(function ($subQuery) use ($q): void {
                    $subQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('subject', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%");
                });
            })
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'counts', 'q', 'status'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->load(['user', 'replier']);
        $contactMessage->markRead();

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'reply_message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'reply_message.required' => 'Balasan wajib diisi.',
            'reply_message.min' => 'Balasan minimal 10 karakter.',
        ]);

        $contactMessage->forceFill([
            'admin_reply' => $data['reply_message'],
            'replied_at' => now(),
            'replied_by' => Auth::id(),
            'status' => 'replied',
        ])->save();

        Mail::to($contactMessage->email)->send(new ContactReplyMail($contactMessage->fresh(['replier'])));

        return back()->with('success', 'Balasan terkirim ke email pengguna.');
    }
}