<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Status;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getAll()
    {
        $tickets = Ticket::with(['category', 'status'])->get();
        return response()->json($tickets);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'status_id' => Status::defaultStatus()->id,
        ]);

        return response()->json($ticket, 201);
    }

    public function get($id)
    {
        $ticket = Ticket::with(['category', 'status'])->findOrFail($id);
        return response()->json($ticket);
    }

    public function getByStatus($status_id)
    {
        $tickets = Ticket::where('status_id', $status_id)
            ->with(['category', 'status'])
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found for this status.'], 404);
        }

        return response()->json($tickets);
    }

    public function update(Request $request, $id)
    {

        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status_id' => 'nullable|exists:statuses,id',
        ]);

        if (isset($validated['status_id'])) {
            try {

                $ticket->updateStatus($validated['status_id']);
            } catch (\InvalidArgumentException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }

        $ticket->update(array_filter($validated, fn($value) => !is_null($value)));

        return response()->json($ticket);
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json(null, 204);
    }

    public function getSLA()
    {
        $now = new \DateTime();
        $currentMonth = $now->format('m');
        $currentYear = $now->format('Y');
        $deadlineDays = 3;

        $tickets = Ticket::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();

        $totalTickets = $tickets->count();
        $withinDeadlineCount = $tickets->filter(function ($ticket) use ($deadlineDays) {
            if ($ticket->resolved_at) {
                $createdDate = new \DateTime($ticket->created_at);
                $resolvedDate = new \DateTime($ticket->resolved_at);
                $interval = $resolvedDate->diff($createdDate);
                return $interval->days <= $deadlineDays && $interval->invert == 0;
            }
            return false;
        })->count();

        $slaPercentage = $totalTickets > 0 ? ($withinDeadlineCount / $totalTickets) * 100 : 0;

        return response()->json([
            'sla_percentage' => round($slaPercentage, 2),
        ]);
    }
}
