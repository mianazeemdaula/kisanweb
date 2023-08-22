<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('css/pdf_tw.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @page {
            margin: 50px 50px 220px 80px;
        }

        @font-face {
            font-family: Jameel;
            src: url('../../fonts/Jameel_Noori_Nastaleeq.ttf');
        }

        body {
            font-family: Jameel;
        }

        .footer {
            position: fixed;
            bottom: -70px;
            left: 30px;
            right: 0px;
            background-color: white;
            height: 50px;
        }
    </style>
</head>

<body>
    <main>
        <div class="w-1/2 h-auto mx-auto relative">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="text-center text-2xl font-bold">{{ $type->crop->name_ur }} کے آج کے ریٹس</td>
                    </tr>
                    <tr>
                        <td class="text-center text-m font-bold"> {{ $filterDate->locale('ur')->dayName }}
                            {{ $filterDate->format('d-m-Y') }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="mt-2 mb-4 w-full">
                <thead class="bg-green-400">
                    <tr>
                        <th class="border">شہر</th>
                        <th class="border">کم ازکم</th>
                        <th class="border">زیادہ سے زیادہ</th>
                        <th class="border">رجحان</th>
                        <th class="border">شہر</th>
                        <th class="border">کم ازکم</th>
                        <th class="border">زیادہ سے زیادہ</th>
                        <th class="border">رجحان</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rates->chunk(2) as $rows)
                        <tr class="tr border-b text-xs border-gray-400">
                            @foreach ($rows as $rate)
                                <td class="text-center font-bold border border-gray-400 text-lg">
                                    {{ $rate->city->name_ur }}</td>
                                <td class="text-center border pl-1 border-gray-400 text-lg w-24">{{ $rate->min_price }}
                                </td>
                                <td class='text-center border border-gray-400 text-lg w-24'>{{ $rate->max_price }}</td>
                                <td class='text-center border border-gray-400 text-lg w-10'>
                                    @if ($rate->min_price + $rate->max_price > $rate->min_price_last + $rate->max_price_last)
                                        <i class="bi bi-arrow-up-short"></i>
                                    @elseif ($rate->min_price + $rate->max_price < $rate->min_price_last + $rate->max_price_last)
                                        <i class="bi bi-arrow-down-short"></i>
                                    @else
                                        {{ '-' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <div style="display:flex;justify-content: space-between">
                    <div class="text-xl">مزید فصلوں کے ریٹس دیکھنے کے لیے پلےسٹورسےکسان اسٹاک ایپ ڈاؤن لوڈ کریں۔</div>
                    <div class="text-xl">
                        kisanstock/
                        <img src="{{ asset('images/facebook.png') }}" class="w-4">
                        <img src="{{ asset('images/instagram.png') }}" class="w-4">
                        <img src="{{ asset('images/tik-tok.png') }}" class="w-4">
                        <img src="{{ asset('images/youtube.png') }}" class="w-4">
                        <img src="{{ asset('images/playstore.png') }}" class="w-4">
                    </div>
                </div>
            </div>
            <div class="absolute" style="left:50%; top:50%; transform: translate(-50%, -50%); z-index: -1">
                <img alt="logo" src="{{ asset('/images/logo.svg') }}" class="w-60" style="opacity: 0.4;">
            </div>
        </div>
    </main>
</body>

</html>
