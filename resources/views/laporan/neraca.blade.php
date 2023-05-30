@extends('layouts.app')

@section('content')
    <style>
        h4 {
            font-size: 16px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Laporan Neraca</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- <form action="/print-laporan-kas" target="_blank" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h4>Filter</h4>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row input-daterange">
                                            <div class="col-md-6">
                                                <label for="from_date">Dari Tanggal</label>
                                                <input type="text" name="from_date" id="from_date" class="form-control"
                                                    placeholder="From Date" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="to_date input-daterange">Sampai Tanggal</label>
                                                <input type="text" name="to_date" id="to_date" class="form-control"
                                                    placeholder="To Date" readonly />
                                            </div>
                                        </div>

                                        <div class="row">



                                            @if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO')
                                                <div class="col-md-6">
                                                    <label for="website">Perusahaan</label>
                                                    <select name="website" id="website" class="form-control">
                                                        <option value="">Semua</option>
                                                        @foreach (dataWebsite() as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->nama_website }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Aksi</label>
                                        <div class="form-group">
                                            <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                            <a id="refresh" class="btn btn-outline-info mb-2">Reset</a><br>
                                            <input type="submit" name="print" value="Print"
                                                class="btn btn-outline-success">
                                            <input type="submit" name="export" value="Excel"
                                                class="btn btn-outline-warning">
                                            <input type="submit" name="pdf" value="PDF"
                                                class="btn btn-outline-danger">
                                        </div>
                                    </div>
                                </div>
                    </form>
                    <br /> -->
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Deskripsi</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Debit</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Kredit</h4>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <h4>Aset</h4>
                        </div>

                        <div class="col-md-12">
                            <h4 style="margin-left: 20px;">Aset Lancar</h4>
                        </div>
                        <?php
                        $totalAsetLancar = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($lancar as $item)
                            <?php
                            $totalSaldo = 0;
                            $jumlahName = '';
                            $n = count($item->akun);
                            ?>
                            @foreach ($item->akun as $index => $akun)
                                <?php
                                $index++;
                                if ($akun->tipe == 'header') {
                                    $indent = ' style="margin-left: 40px;"';
                                    $show = false;
                                    $jumlahName = strtoupper($akun->name);
                                } else {
                                    $indent = ' style="margin-left: 60px;"';
                                    $show = true;
                                }
                                ?>
                                <div class="col-md-6">
                                    <h6 <?= $indent ?>>
                                        @if ($show)
                                            ({{ $akun->kode }})
                                        @endif {{ $akun->name }}
                                    </h6>
                                </div>
                                <?php
                                $saldo = 0;
                                $debit = $akun->kas->where('akun_id', $akun->id)->sum('debit');
                                $kredit = $akun->kas->where('akun_id', $akun->id)->sum('kredit');
                                $saldo = (float) $debit - (float) $kredit;
                                $totalSaldo += $saldo;
                                $totalDebit += $debit;
                                $totalKredit += $kredit;
                                ?>


                                @if ($show)
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($debit, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($kredit, 0, ',', '.') }}</h6>
                                    </div>
                                @else
                                    <div class="col-md-6"></div>
                                @endif
                                {{-- @if ($index >= $n)
                                    <div class="col-md-12">
                                        <h6 style="margin-left: 40px;">JUMLAH {{ $jumlahName }}</h6>
                                    </div>
                                @endif --}}
                            @endforeach
                        @endforeach
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Jumlah Aset Lancar</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Total Aset Lancar</h4>
                        </div>
                        <?php
                        $totalAsetLancar = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalAsetLancar, 0, ',', '.') }}</h4>
                        </div>

                        <div class="col-md-12">
                            <h4 style="margin-left: 20px;">Aset Tetap</h4>
                        </div>
                        <?php
                        $totalAsetTetap = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($tetap as $item)
                            <?php
                            $totalSaldo = 0;
                            $jumlahName = '';
                            $n = count($item->akun);
                            ?>
                            @foreach ($item->akun as $index => $akun)
                                <?php
                                $index++;
                                if ($akun->tipe == 'header') {
                                    $indent = ' style="margin-left: 40px;"';
                                    $show = false;
                                    $jumlahName = strtoupper($akun->name);
                                } else {
                                    $indent = ' style="margin-left: 60px;"';
                                    $show = true;
                                }
                                ?>
                                <div class="col-md-6">
                                    <h6 <?= $indent ?>>
                                        @if ($show)
                                            ({{ $akun->kode }})
                                        @endif {{ $akun->name }}
                                    </h6>
                                </div>
                                <?php
                                $saldo = 0;
                                $debit = $akun->kas->where('akun_id', $akun->id)->sum('debit');
                                $kredit = $akun->kas->where('akun_id', $akun->id)->sum('kredit');
                                $saldo = (float) $debit - (float) $kredit;
                                $totalSaldo += $saldo;
                                $totalDebit += $debit;
                                $totalKredit += $kredit;
                                ?>

                                @if ($show)
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($debit, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($kredit, 0, ',', '.') }}</h6>
                                    </div>
                                @else
                                    <div class="col-md-6"></div>
                                @endif
                                {{-- @if ($index >= $n)
                                    <div class="col-md-12">
                                        <h6 style="margin-left: 40px;">JUMLAH {{ $jumlahName }}</h6>
                                    </div>
                                @endif --}}
                            @endforeach
                        @endforeach
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Jumlah Aset Tetap</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Total Aset Tetap</h4>
                        </div>
                        <?php
                        $totalAsetTetap = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalAsetTetap, 0, ',', '.') }}</h4>
                        </div>

                        <div class="col-md-6">
                            <h4>Jumlah Aset</h4>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $totalAset = $totalAsetLancar - $totalAsetTetap;
                            ?>
                            <h4 class="float-end">Rp. {{ number_format($totalAset, 0, ',', '.') }}</h4>
                        </div>
                        <hr>

                        <div class="col-md-12">
                            <h4>Hutang & Ekuitas</h4>
                        </div>
                        <div class="col-md-12">
                            <h4 style="margin-left: 20px;">Hutang</h4>
                        </div>
                        <?php
                        $totalHutang = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($hutang as $item)
                            <?php
                            $totalSaldo = 0;
                            $jumlahName = '';
                            $n = count($item->akun);
                            ?>
                            @foreach ($item->akun as $index => $akun)
                                <?php
                                $index++;
                                if ($akun->tipe == 'header') {
                                    $indent = ' style="margin-left: 40px;"';
                                    $show = false;
                                    $jumlahName = strtoupper($akun->name);
                                } else {
                                    $indent = ' style="margin-left: 60px;"';
                                    $show = true;
                                }
                                ?>
                                <div class="col-md-6">
                                    <h6 <?= $indent ?>>
                                        @if ($show)
                                            ({{ $akun->kode }})
                                        @endif {{ $akun->name }}
                                    </h6>
                                </div>
                                <?php
                                $saldo = 0;
                                $debit = $akun->kas->where('akun_id', $akun->id)->sum('debit');
                                $kredit = $akun->kas->where('akun_id', $akun->id)->sum('kredit');
                                $saldo = (float) $kredit - (float) $debit;
                                $totalSaldo += $saldo;
                                $totalDebit += $debit;
                                $totalKredit += $kredit;
                                ?>


                                @if ($show)
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($debit, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($kredit, 0, ',', '.') }}</h6>
                                    </div>
                                @else
                                    <div class="col-md-6"></div>
                                @endif
                                {{-- @if ($index >= $n)
                                    <div class="col-md-12">
                                        <h6 style="margin-left: 40px;">JUMLAH {{ $jumlahName }}</h6>
                                    </div>
                                @endif --}}
                            @endforeach
                        @endforeach
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Jumlah Hutang</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Total Hutang</h4>
                        </div>
                        <?php
                        $totalHutang = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalHutang, 0, ',', '.') }}</h4>
                        </div>
                        <hr>

                        <div class="col-md-12">
                            <h4 style="margin-left: 20px;">Ekuitas</h4>
                        </div>
                        <?php
                        $totalEkuitas = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($ekuitas as $item)
                            <?php
                            $totalSaldo = 0;
                            $jumlahName = '';
                            $n = count($item->akun);
                            ?>
                            @foreach ($item->akun as $index => $akun)
                                <?php
                                $index++;
                                if ($akun->tipe == 'header') {
                                    $indent = ' style="margin-left: 40px;"';
                                    $show = false;
                                    $jumlahName = strtoupper($akun->name);
                                } else {
                                    $indent = ' style="margin-left: 60px;"';
                                    $show = true;
                                }
                                ?>
                                <div class="col-md-6">
                                    <h6 <?= $indent ?>>
                                        @if ($show)
                                            ({{ $akun->kode }})
                                        @endif {{ $akun->name }}
                                    </h6>
                                </div>
                                <?php
                                $saldo = 0;
                                $debit = $akun->kas->where('akun_id', $akun->id)->sum('debit');
                                $kredit = $akun->kas->where('akun_id', $akun->id)->sum('kredit');
                                $saldo = (float) $kredit - (float) $debit;
                                $totalSaldo += $saldo;
                                $totalDebit += $debit;
                                $totalKredit += $kredit;
                                ?>

                                @if ($show)
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($debit, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="float-end">Rp. {{ number_format($kredit, 0, ',', '.') }}</h6>
                                    </div>
                                @else
                                    <div class="col-md-6"></div>
                                @endif
                                {{-- @if ($index >= $n)
                                    <div class="col-md-12">
                                        <h6 style="margin-left: 40px;">JUMLAH {{ $jumlahName }}</h6>
                                    </div>
                                @endif --}}
                            @endforeach
                        @endforeach
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Jumlah Ekuitas</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>

                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 style="margin-left: 20px;">Total Ekuitas</h4>
                        </div>
                        <?php
                        $totalEkuitas = ($totalAset-$totalHutang)-($totalKredit - $totalDebit);
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalEkuitas, 0, ',', '.') }}</h4>
                        </div>

                        <div class="col-md-6">
                            <h4>Total Hutang & Ekuitas</h4>
                        </div>
                        <?php
                        $total = $totalHutang + $totalEkuitas;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        })
    </script>
@endsection
