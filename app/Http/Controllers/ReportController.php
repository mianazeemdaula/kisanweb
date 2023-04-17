<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function getCropRatePdf()
    {
        $crops = \App\Models\Crop::with('types')->get();
        return view('admin.crop_rate', compact('crops'));
    }

    public function cropRatePdf(Request $request)
    {
        $date =  now();
        $rates =  \App\Models\CropRate::where('crop_type_id', $request->type_id)
        ->with(['city'])
        ->whereDate('rate_date',$date)->get();
        $type =  \App\Models\CropType::with('crop')->find($request->type_id);
        return view('reports.pdf.crop_rates', compact('rates','date','type'));
        // $pdf = Pdf::loadView('reports.pdf.crop_rates',compact('rates','date','type'));
        // return $pdf->output()->stream();
    }
}
