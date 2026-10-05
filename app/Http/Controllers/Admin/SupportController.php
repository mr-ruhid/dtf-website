<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['order', 'assignedUser'])->withCount(['attachments', 'messages']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        return view('admin.support.index', compact('tickets', 'stats'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load([
            'order',
            'product',
            'assignedUser',
            'attachments',
            'messages.user',
        ]);

        $admins = User::where('role', 'admin')->where('status', 1)->orderBy('name')->get();

        return view('admin.support.show', compact('ticket', 'admins'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,waiting_customer,resolved,closed'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $ticket->updateStatus($data['status'], $data['note'] ?? null);

        return back()->with('status', 'Ticket status updated to ' . $ticket->fresh()->status_label . '.');
    }

    public function updatePriority(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $ticket->update($data);

        return back()->with('status', 'Ticket priority updated.');
    }

    public function updateCategory(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'category' => ['required', 'in:delivery,payment,product,print_quality,order_issue,account,other'],
        ]);

        $ticket->update($data);

        return back()->with('status', 'Ticket category updated.');
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $ticket->update($data);

        $message = $data['assigned_to']
            ? 'Ticket assigned to ' . User::find($data['assigned_to'])->name
            : 'Ticket unassigned';

        return back()->with('status', $message);
    }

    public function updateAdminNote(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $ticket->update($data);

        return back()->with('status', 'Internal note saved.');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'status' => ['nullable', 'in:open,in_progress,waiting_customer,resolved,closed'],
        ]);

        $ticket->messages()->create([
            'sender_type' => 'admin',
            'sender_name' => auth()->user()->name ?? 'Admin',
            'message' => $data['message'],
            'user_id' => auth()->id(),
        ]);

        if (!empty($data['status']) && $data['status'] !== $ticket->status) {
            $ticket->updateStatus($data['status']);
        } else {
            if ($ticket->status === 'open') {
                $ticket->updateStatus('in_progress');
            }
        }

        return back()->with('status', 'Reply sent.');
    }

    public function deleteAttachment(SupportTicket $ticket, SupportTicketAttachment $attachment)
    {
        if ($attachment->ticket_id !== $ticket->id) {
            abort(404);
        }

        if ($attachment->file_path && !str_starts_with($attachment->file_path, 'http')) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return back()->with('status', 'Attachment deleted.');
    }

    public function destroy(SupportTicket $ticket)
    {
        foreach ($ticket->attachments as $attachment) {
            if ($attachment->file_path && !str_starts_with($attachment->file_path, 'http')) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $ticket->delete();

        return redirect()->route('admin.support.index')->with('status', 'Ticket deleted successfully.');
    }
}
