<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{asset('css/pdf_tw.css')}}" rel="stylesheet">
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
        <div class="w-1/2 h-auto mx-auto ">
            <div style="display: flex; justify-content: space-between">
                <div>IMG</div>
                <div>IMG</div>
                <div>IMG</div>
            </div>
            <p class="text-center">شهادة قيد ممتلك ثقافي في سجل التراث الثقافي العماني</p>
            <p class="text-center">اعملاً لأحكام الفصل الثالث المتعلق بسجل التراث الثقافي المنصوص عليه في المواد (٣٣-٣٨) من قانون التراث الثقافي رقم ٣٥/٢٠١٩ ، تشهد وزارة التراث والسياحة بأن الفاضل / الفاضلة /</p>
            <p class="text-center">يحمل بطاقة شخصية رقم</p>
            <p class="text-center"> قام بتقييد ممتلكه الثقافي في سجل التراث الثقافي العماني حسب الكشف المرفق . مساهمة منه / منها في حفظ وصون التراث الثقافي .</p>
            
            <table class="mt-2 mb-4 w-full">
                <thead class="">
                   <tr>
                    <th class="border" >الرمز</th>
                    <th  class="border">الاسم الشائع أو العلمي</th>
                    <th class="border" >صورة</th>
                   </tr>
                </thead>
                <tbody>
                    @foreach([1,2,3,4,5] as $rows)
                    <tr class="tr border-b text-xs">
                        <td class="text-center border">ID</td>
                        <td class="text-center border">Name</td>
                        <td class='text-center border'>Image</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="">ملاحظة:</p>
            <p class="text-center">حرر في تاريخ</p>
            <p class="text-center">{{ now() }}</p>
            <p class="">قسم سجل التراث الثقافي ومكافحة الاتجار الغير مشروع</p>
            
        </div>
    </main>
</body>
</html>