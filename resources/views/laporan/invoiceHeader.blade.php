<style>
    .table td,
        .table th {
            padding: 4px;
            font-size: 12px;
        }
</style>
@if(!empty(website()->kop_surat))
@if (!isset($pdf))
<table width="100%" align="center" cellspacing="0" cellpadding="0">
    <tr>
        <td width="100%">
                <img width="100%" height="120px" src="{{ asset('image/website/kop/' . website()->kop_surat) }}">
        </td>
    </tr>
</table>
<hr>
@endif
@endif
