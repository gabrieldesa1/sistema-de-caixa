<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
   public function weekly(Request $request)
{
    $start = Carbon::now('America/Sao_Paulo')->startOfWeek();
    $end = Carbon::now('America/Sao_Paulo')->endOfWeek();

    $data = $request->user()
        ->transactions()
        ->select(
            DB::raw('DATE(created_at) as day'),
            DB::raw("SUM(CASE WHEN type = 'entrada' THEN amount ELSE 0 END) as entradas"),
            DB::raw("SUM(CASE WHEN type = 'saida' THEN amount ELSE 0 END) as saidas")
        )
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('day')
        ->orderBy('day')
        ->get();

    $totalEntradas = $data->sum('entradas');
    $totalSaidas = $data->sum('saidas');

    return [
        'totais' => [
            'entradas' => $totalEntradas,
            'saidas' => $totalSaidas,
            'resultado' => $totalEntradas - $totalSaidas,
        ],
        'grafico' => $data,
        'tabela' => $data
    ];
}
}
