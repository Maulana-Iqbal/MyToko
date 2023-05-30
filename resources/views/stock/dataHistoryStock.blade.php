@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Data Riwayat Stock</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                @include('stock.filterHistoryStock')
@include('stock.historyStock')
            </div>
        </div>
    </div>
</div>

@endsection
