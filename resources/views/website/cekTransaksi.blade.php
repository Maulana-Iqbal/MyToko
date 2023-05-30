@extends('../index')

@section('content')
    <style>
        .height {
            height: 100vh
        }

        .search {
            position: relative;
            box-shadow: 0 0 40px rgba(51, 51, 51, .1);
            /* margin-bottom: 30px; */
        }

        .search input {
            height: 50px;
            text-indent: 25px;
            border: 2px solid #d6d4d4
        }

        .search input:focus {
            box-shadow: none;
            border: 2px solid blue
        }

        .search .fa-search {
            position: absolute;
            top: 20px;
            left: 16px
        }

        .search button {
            position: absolute;
            top: 8px;
            right: 5px;
            height: 35px;
            width: 110px;
        }

    </style>
    <section style="margin-top: 50px; margin-bottom: 50px;">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-offset-3 col-md-6 col-offset-3">
                <div class="search"> <i class="fa fa-search"></i> <input type="text" id="search" name="search"
                        value="@if (isset($searchValue)) {{ $searchValue }} @endif" required class="form-control"
                        placeholder="Masukkan No Transaksi"> <button type="button" id="cari"
                        class="btn btn-success">Cari</button>
                </div>
                @if (isset($total))
                    <div>
                        <h5 class="text-overflow mb-2">Ditemukan <span class="text-danger">{{ $total }}</span>
                            hasil
                        </h5>
                    </div>
                @endif
                <div style="margin-bottom: 40px;"></div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('body').on('click', '#cari', function() {
                var id = $("#search").val();
                $.get("{{ url('/transaksi/detail') }}" + '/' + id, function(data) {
                    window.location.href = "/transaksi/detail/" + id;
                }).fail(function() {
                    alert('Transaksi Tidak Ditemukan');
                });

            });
        })
    </script>
@endsection
