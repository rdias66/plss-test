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
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Pendente,Resolvido',
        ]);

        if (isset($validated['status'])) {
            $ticket->updateStatus($validated['status']);
        }

        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json(null, 204);
    }
}
