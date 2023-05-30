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

                <h4 class="page-title">Laporan Laba Rugi</h4>
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
                            <h4>Pendapatan</h4>
                        </div>
                        <?php
                        $totalPendapatan = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($pendapatan as $item)
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
                            <h4 style="margin-left: 20px;">Jumlah Pendapatan</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>Total Pendapatan</h4>
                        </div>
                        <?php
                        $totalPendapatan = $totalKredit - $totalDebit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                        </div>


                        <hr>

                        <div class="col-md-12">
                            <h4>Harga Pokok Penjualan</h4>
                        </div>
                        <?php
                        $totalHpp = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($hpp as $item)
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
                            <h4 style="margin-left: 20px;">Jumlah Harga Pokok Penjualan</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>Total Harga Pokok Penjualan</h4>
                        </div>
                        <?php
                        $totalHpp = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalHpp, 0, ',', '.') }}</h4>
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <h4>Laba Rugi Kotor</h4>
                        </div>
                        <?php
                        $totalLLK = $totalPendapatan - $totalHpp;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalLLK, 0, ',', '.') }}</h4>
                        </div>
                        <hr>

                        <div class="col-md-12">
                            <h4>Beban</h4>
                        </div>
                        <?php
                        $totalBeban = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($beban as $item)
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
                            <h4 style="margin-left: 20px;">Jumlah Beban</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>Total Beban</h4>
                        </div>
                        <?php
                        $totalBeban = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalBeban, 0, ',', '.') }}</h4>
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <h4>Laba Rugi Operasional</h4>
                        </div>
                        <?php
                        $totalLLO = $totalLLK - $totalBeban;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalLLO, 0, ',', '.') }}</h4>
                        </div>
                        <hr>

                        <div class="col-md-12">
                            <h4>Pendapatan Lainnya</h4>
                        </div>
                        <?php
                        $totalPendapatanLain = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($pendapatanLain as $item)
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
                            <h4 style="margin-left: 20px;">Jumlah Pendapatan Lainnya</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>Total Pendapatan Lainnya</h4>
                        </div>
                        <?php
                        $totalPendapatanLain = $totalKredit - $totalDebit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalPendapatanLain, 0, ',', '.') }}</h4>
                        </div>



                        <div class="col-md-12">
                            <h4>Beban Lainnya</h4>
                        </div>
                        <?php
                        $totalBebanLain = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        ?>
                        @foreach ($bebanLain as $item)
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
                            <h4 style="margin-left: 20px;">Jumlah Beban Lainnya</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="float-end">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>Total Beban Lainnya</h4>
                        </div>
                        <?php
                        $totalBebanLain = $totalDebit - $totalKredit;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalBebanLain, 0, ',', '.') }}</h4>
                        </div>
                        <?php
                        $totalLain = $totalPendapatanLain - $totalBebanLain;
                        ?>
                        <hr>
                        <div class="col-md-6">
                            <h4>Laba Rugi Bersih</h4>
                        </div>
                        <?php
                        $totalLLB = $totalLLO + $totalLain;
                        ?>
                        <div class="col-md-6">
                            <h4 class="float-end">Rp. {{ number_format($totalLLB, 0, ',', '.') }}</h4>
                        </div>
                        <hr>

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
