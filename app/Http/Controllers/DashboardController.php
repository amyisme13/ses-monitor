<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Recipient;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $mails = Mail::query()
            ->with('recipients')
            ->orderByDesc('sent_at')
            ->limit(5)
            ->get();

        $stats = DB::table('recipients')
            ->select([
                DB::raw('sum(total_count) as total'),
                DB::raw('sum(bounced_count) as bounced'),
                DB::raw('sum(delivered_count) as delivered'),
            ])
            ->first();

        return Inertia::render('Dashboard', compact('mails', 'stats'));
    }
}
