<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Visita;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        return Inertia::render('Dashboard', [
            'mensajesUnread' => Contacto::where('is_read', false)
                ->orderByDesc('created_at')->take(5)->get(),
            'mensajesCount' => Contacto::where('is_read', false)->count(),
            'visitas' => [
                'total' => Visita::count(),
                'hoy' => Visita::whereDate('visited_at', $now->toDateString())->count(),
                'semana' => Visita::where('visited_at', '>=', $now->copy()->startOfWeek())->count(),
                'mes' => Visita::where('visited_at', '>=', $now->copy()->startOfMonth())->count(),
            ],
            'visitasPorHora' => $this->series('hour', 24),
            'visitasPorDia' => $this->series('day', 14),
            'visitasPorSemana' => $this->series('week', 12),
            'visitasPorMes' => $this->series('month', 12),
        ]);
    }

    /**
     * Agrupa las visitas por hora, día, semana o mes.
     */
    private function series(string $type, int $count): array
    {
        $now = Carbon::now();

        switch ($type) {
            case 'hour':
                $start = $now->copy()->startOfHour()->subHours($count - 1);
                $fmt = 'Y-m-d H';
                $step = fn (Carbon $c) => $c->addHour();
                $label = fn (Carbon $c) => $c->format('H:00');
                break;
            case 'day':
                $start = $now->copy()->startOfDay()->subDays($count - 1);
                $fmt = 'Y-m-d';
                $step = fn (Carbon $c) => $c->addDay();
                $label = fn (Carbon $c) => $c->format('d M');
                break;
            case 'week':
                $start = $now->copy()->startOfWeek()->subWeeks($count - 1);
                $fmt = 'o-W';
                $step = fn (Carbon $c) => $c->addWeek();
                $label = fn (Carbon $c) => $c->format('d M');
                break;
            default: // month
                $start = $now->copy()->startOfMonth()->subMonths($count - 1);
                $fmt = 'Y-m';
                $step = fn (Carbon $c) => $c->addMonth();
                $label = fn (Carbon $c) => $c->format('M y');
                break;
        }

        $counts = [];
        foreach (Visita::where('visited_at', '>=', $start)->pluck('visited_at') as $ts) {
            $key = Carbon::parse($ts)->format($fmt);
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        $labels = [];
        $data = [];
        for ($c = $start->copy(); $c <= $now; $c = $step($c)) {
            $labels[] = $label($c);
            $data[] = (int) ($counts[$c->format($fmt)] ?? 0);
        }

        return compact('labels', 'data');
    }
}
