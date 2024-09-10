<?php

namespace App\Http\Controllers;

use App\Models\Status;


class StatusController extends Controller
{
    public function getAll()
    {
        $statuses = Status::all(['id', 'name']);
        return response()->json($statuses);
    }
}
