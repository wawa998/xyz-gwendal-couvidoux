<?php

namespace App\Http\Controllers;

use App\Models\Week;

class WeekController extends Controller
{
    /**
     * Current week.
     */
    public function index()
    {
        $week = Week::current();

        return view('app.weeks.show', [
            'current' => true,
            'week' => $week->loadCount('tracks'),
            'tracks' => $week->tracks()->with('user')->withCount('likes')->ranking()->get()
        ]);
    }

    /**
     * Show the given week.
     */
    public function show(Week $week)
    {
        return view('app.weeks.show', [
            'week' => $week->loadCount('tracks'),
            'tracks' => $week->tracks()->with('user')->withCount('likes')->ranking()->get()
        ]);
    }
}
