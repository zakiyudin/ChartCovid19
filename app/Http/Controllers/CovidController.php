<?php

namespace App\Http\Controllers;

use App\Charts\CovidChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CovidController extends Controller
{
    public function chart()
    {
        $covid = collect(Http::get('https://api.kawalcorona.com/indonesia/provinsi')->json());
        $suspek = $covid->flatten(1);

        $label = $suspek->pluck('Provinsi');
        $color = $label->map(function ($item) {
            return '#' . substr(md5(mt_rand()), 0, 6);
        });

        $chart = new CovidChart;
        $chart->labels($label);
        $chart->dataset('Kasus Covid Di Indonesia', 'bar', $suspek->pluck('Kasus_Posi'))->backgroundColor($color);

        return view('corona', ['chart' => $chart]);
    }
}
