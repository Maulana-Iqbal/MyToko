@extends('../index')

@section('content')
    <section>
        <div class="col-8" style="margin: auto; margin-top:50px;">
            <div class="card">
                <div class="card-header text-white" style="background-color: cornflowerblue;">
                    <h4>Pembayaran</h3>
                </div>
                <form action="/pembayaran/bayar" method="post" enctype="multipart/form-data">
                    @csrf
@include('pembayaran.inputBuktiBayar')
                    </form>
            </div>
        </div>
    </section>
@endsection
