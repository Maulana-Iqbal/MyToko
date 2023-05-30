<table class="table table-centered w-100 dt-responsive">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Akun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($akun as $akun)
            <tr>
                <td>{{$akun->kode}}</td>
                <td>{{$akun->name}}</td>
                <td width="40px"><button data-id="{{$akun->id}}" data-kode="{{$akun->kode}}" data-name="{{$akun->name}}" class="btn btn-primary">Pilih</button></td>
            </tr>
        @endforeach
    </tbody>
</table>
