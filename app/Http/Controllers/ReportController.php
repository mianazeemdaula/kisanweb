<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;



class ReportController extends Controller
{
    public function getCropRatePdf()
    {
        $crops = \App\Models\Crop::with('types')->get();
        return view('admin.crop_rate', compact('crops'));
    }

    public function cropRatePdf(Request $request)
    {
        $filterDate =  \Carbon\Carbon::parse($request->date);
        $rates =  \App\Models\CropRate::where('crop_type_id', $request->type_id)
        ->with(['city'])
        ->whereDate('rate_date',$filterDate)->get();
        $type =  \App\Models\CropType::with('crop')->find($request->type_id);
        return view('reports.pdf.crop_rates', compact('rates','filterDate','type'));
        // $pdf = Pdf::loadView('reports.pdf.crop_rates',compact('rates','date','type'));
        // return $pdf->output()->stream();
    }

    public function sugarMillReport(Request $request){
        $filterDate =  \Carbon\Carbon::parse($request->date);
        $ids = \App\Models\SugarMillRate::whereDate('created_at', $filterDate)->pluck('sugar_mill_id');
        $data = \App\Models\SugarMill::with(['rate','city'])->whereIn('id', $ids)->get();
        return view('reports.pdf.sugermill_rates', compact('data','filterDate'));
    }

    function saveImage()  {
        $filterDate =  \Carbon\Carbon::parse('2023-06-20');
        $rates =  \App\Models\CropRate::where('crop_type_id',60)
        ->with(['city'])
        ->whereDate('rate_date',$filterDate)->get();
        $type =  \App\Models\CropType::with('crop')->find(60);
        return view('reports.pdf.crop_rates', compact('rates','filterDate','type'));
    }
}
