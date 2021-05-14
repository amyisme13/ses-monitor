<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Get total, bounced, delivered & mails count.
     *
     * @param \Illuminate\Support\Carbon $from
     * @param \Illuminate\Support\Carbon|null $to
     * @return array
     */
    private function getStats($from, $to = null)
    {
        $stats = DB::table('mail_recipients')
            ->select([
                DB::raw('count(*) as total'),
                DB::raw('sum(if(status = "bounce", 1, 0)) as bounced'),
                DB::raw('sum(if(status = "delivery", 1, 0)) as delivered'),
            ])
            ->where('created_at', '>', $from)
            ->when($to, function ($query, $to) {
                $query->where('created_at', '<', $to);
            })
            ->first();

        $mailsCount = Mail::query()
            ->where('created_at', '>', $from)
            ->when($to, function ($query, $to) {
                $query->where('created_at', '<', $to);
            })
            ->count();

        return [
            'total' => $stats->total,
            'bounced' => (int) $stats->bounced,
            'delivered' => (int) $stats->delivered,
            'mails' => $mailsCount,
        ];
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $data = Cache::rememberForever('dashboard-data', function () {
            $mails = Mail::latest()
                ->limit(5)
                ->get();

            $last7Days = now()->subDays(7);
            $last14Days = now()->subDays(14);

            $stats = $this->getStats($last7Days);
            $pastStats = $this->getStats($last14Days, $last7Days);

            return compact('mails', 'stats', 'pastStats');
        });

        return Inertia::render('Dashboard', $data);
    }
}
