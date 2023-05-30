{{-- <table width="100%" align="center" cellspacing="0" cellpadding="5">
<tr>
    <td width="100px">
        @if (isset($pdf))
        <img class="d-flex mr-2 mt-2" src="{{public_path('image/website/'.website()->icon)}}"
             height="100">
             @else
             <img class="d-flex mr-2 mt-2" src="{{asset('image/website/'.website()->icon)}}"
             height="100">
             @endif
    </td>
    <td align="right">
        <h1><u style="color:gold;"><span style="font-weight: 700; color:#42bb5b;">CV. {{ strtoupper(website()->nama_website) }}</span></u></h1>   
        <ul class="list list-unstyled mb-0 text-right" style="font-size: 14px; font-weight: 600;">
            <li>{{strip_tags(website()->address)}}, {{website()->webkecamatan->name}}</li>
            <li>{{website()->webkota->name}}, {{website()->webprovinsi->name}}, {{website()->kode_pos}}</li>
            <li>{{website()->contact}}</li>
        </ul>
    </td>
</tr>
</table>
<div style="width: 100%;">
    <div
        style="border-bottom: 2px solid gold;">
        
    </div>
</div> --}}

<table width="100%" align="center" cellspacing="0" cellpadding="0">
    <tr>
        <td width="100%">
            @if(!empty(website()->kop_surat))
            @if (isset($pdf))
                <img width="100%" height="120px" src="{{ public_path('image/website/kop/' . website()->kop_surat) }}">
            @else
                <img width="100%" height="120px" src="{{ asset('image/website/kop/' . website()->kop_surat) }}">
            @endif
            @endif
        </td>
    </tr>
</table>
<hr>
