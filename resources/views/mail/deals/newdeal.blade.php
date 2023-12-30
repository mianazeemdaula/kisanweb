<x-mail::message>
# محترم صارف اسلام علیکم

<x-mail::panel>
    ہمیں آپ کو یہ بتاتے ہوئے خوشی ہو رہی ہے کہ ہمارے پاس {{ $deal->type->crop->name_ur }} [{{ $deal->type->code }}] کی نئی ڈیل ہے

## ڈیل کی تفصیلات
### شمار: {{ $deal->qty }} {{ $deal->weight->name_ur }}
### قیمت: {{ $deal->demand }}
### تاریخ: {{ $deal->created_at->format('d-m-Y') }}
### معاہدے کی جگہ: {{ $deal->address }}

</x-mail::panel>

مزید تفصیلات کے لیے نیچے دیے گئے بٹن پر کلک کریں
<x-mail::button :url="url('deals/'.$deal->id)">
ڈیل کی تفصیلات دیکھیں
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
