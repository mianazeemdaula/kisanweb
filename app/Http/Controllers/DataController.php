<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;


use App\Models\Province;
use App\Models\District;
use App\Models\City;
use App\Models\CropRate;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use LevelUp\Experience\Models\Level;

class DataController extends Controller
{
    public function cities()
    {
        $path = public_path() . "/data/cities.json";
        $cities = json_decode(file_get_contents($path), true);
        foreach ($cities as $city) {
            $province = Province::updateOrCreate([
                'name'=> $city['province'],
                'code'=> $city['province_code'],
            ]);

            $district = District::updateOrCreate([
                'name'=> $city['district'],
                'code'=> $city['distric_code'],
                'province_id' => $province->id,
            ]);

            $city = City::updateOrCreate([
                'name'=> $city['name'],
                'district_id' => $district->id,
                'code' => $city['code'],
            ]);
        }
        return response()->json($cities, 200);
    }
    public function lastRatesUpdate(){
        $results = DB::table('crop_rates as cr')
            // ->select('cr.id', 'cr.city_id', 'cr.crop_type_id', 'cr.rate_date')
            ->whereIn(DB::raw('(cr.city_id, cr.crop_type_id, cr.rate_date)'), function ($query) {
                $query->select(DB::raw('city_id,crop_type_id, MIN(rate_date)'))
                    ->from('crop_rates')
                    ->groupBy('city_id', 'crop_type_id');
            })
            ->orderBy('cr.id')
            ->get();
        // return count($results);
        foreach ($results as $result) {
            $rate = CropRate::find($result->id);
            if($rate && ($rate->max_price_last == 0 || $rate->min_price_last == 0)){
                $rate->max_price_last = $rate->max_price;
                $rate->min_price_last = $rate->min_price;
                $rate->save();
            } 
            if($rate){
                $nexts  = CropRate::where((function ($q){
                    $q->where('max_price_last','<',1);
                    $q->orWhere('min_price_last','<',1);
                }))
                ->where('city_id',$rate->city_id)
                ->where('crop_type_id', $rate->crop_type_id)
                ->whereDate('rate_date','>', $rate->rate_date)
                ->orderBy('rate_date','asc')->limit(5)->get();
                $upcoming = $rate;
                foreach ($nexts as $next) {
                    $next->max_price_last = $upcoming->max_price;
                    $next->min_price_last = $upcoming->min_price;
                    $next->save();
                    $upcoming = $next;
                }
            }
        }
        return [CropRate::where('max_price_last','<',1)->orWhere('min_price_last','<',1)->count(), count($results)];
    }


    public function pointsLevel() {
        $level = Level::find(1);
        if(!$level){
            Level::add(
                ['level' => 1, 'next_level_experience' => null],
                ['level' => 2, 'next_level_experience' => 1000],
                ['level' => 3, 'next_level_experience' => 2000],
                ['level' => 4, 'next_level_experience' => 5000],
                ['level' => 5, 'next_level_experience' => 10000],
            );
        }
        
    }

    public function paymentAndSubscription()  {
        PaymentGateway::updateOrCreate([
            'name' => 'Paypal',
            'name_ur' => 'پے پال',
            'slug' => 'paypal',
        ],[
            'logo' => 'https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg',
            'config' => [
                'client_id' => 'sb',
                'client_secret' => 'sb',
                'mode' => 'sandbox',
                'currency' => 'USD',
            ],
            'active' => false,
        ]);

        PaymentGateway::updateOrCreate(
            ['name' => 'JazzCash', 'name_ur' => 'جاز کیش', 'slug' => 'jazzcash'],
            ['logo' => 'https://www.jazzcash.com.pk/wp-content/uploads/2019/10/JazzCash-Logo.png', 'config' => [], 'public_data' => ['account' => '03001234567', 'account_name' => 'JazzCash Account']]
        );
        PaymentGateway::updateOrCreate(
            ['name' => 'EasyPaisa', 'name_ur' => 'ایزی پیسہ', 'slug' => 'easypaisa'],
            ['logo' => 'https://www.easypaisa.com.pk/wp-content/uploads/2019/10/easypaisa-logo.png', 'config' => [],'public_data' => ['account' => '03001234567', 'account_name' => 'EasyPaisa Account']]
        );
        PaymentGateway::updateOrCreate(
            ['name' => 'Bank Transfer', 'name_ur' => 'بینک ٹرانسفر', 'slug' => 'bank-transfer'],
            ['logo' => 'https://www.easypaisa.com.pk/wp-content/uploads/2019/10/easypaisa-logo.png', 'config' => [],'public_data' => ['account' => '03001234567', 'account_name' => 'Bank Account'],
            'active' => false,],
        );

        $sub = Subscription::updateOrCreate(
            ['name' => 'Daily Rates', 'name_ur' => 'روانہ کی قیمتیں'],
            ['duration_unit' => 'month', 'description' => 'Free Plan', 'description_ur' => 'مفت پلان']
        );

        $sub->packages()->updateOrCreate(
            ['name' => 'Free Trial', 'name_ur' => 'فری ٹرائل', 'fee' => 0, 'duration' => 3, 'duration_unit' => 'day', 'trial' => true]
        );
        $sub->packages()->updateOrCreate(
            ['name' => 'Monthly','name_ur' => 'مہانہ وار','fee' => 200, 'duration' => 1, 'duration_unit' => 'month']
        );
        $sub->packages()->updateOrCreate(
            ['name' => 'Fournightly','name_ur' => 'مہینہ 3', 'fee' => 500, 'duration' => 3, 'duration_unit' => 'month']
        );
        $sub->packages()->updateOrCreate(
            ['name' => 'Yearly', 'name_ur' => 'سالانہ','fee' => 1800, 'duration' => 1, 'duration_unit' => 'year']
        );

        Subscription::updateOrCreate(
            ['name' => 'Selling Request', 'name_ur' => 'فروخت کی درخواست'],
            ['description' => 'Whatsapp the request of selling of crop', 'description_ur' => 'فروخت کی درخواست کو واٹس ایپ کریں']
        );
        return response()->json(['message' => 'done'], 200);
    }

    public function generateRatesImage()  {
        // Browsershot::url('https://google.com')->save('temp/rates.png');
        $image = \Image::canvas(800, 600, '#ff0000');
        $text = 'مرحباً بكم في لاراڤيل';
        $image->text($text, 500, 40, function($font) {
            $font->file(public_path('fonts/Jameel_Noori_Nastaleeq.ttf'));
            $font->size(24);
            $font->color('#ffffff');
            $font->align('right');
        });
       return $image->response();
        $image->save(public_path('temp/rates2.png'));
        // // Image draw table with text, rate min, rate max and trend

        // // save the image
        // $image->save(public_path('temp/rates2.png'));

    }
}
