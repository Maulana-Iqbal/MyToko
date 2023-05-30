<div>
    <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0"
    id="daftar-kategori">
    <div class="navbar-nav w-100" style="height: 410px; overflow: scroll;">

        @foreach ($kategori as $kategori)
        <?php
        $cekInduk=$kategori->where('induk_id',$kategori->id)->count();
        ?>
        @if($cekInduk>0)
        <div class="nav-item dropdown d-flex">
            <img width="25px" height="25px" class="rounded m-2" src="/image/kategori/tumb/{{$kategori->icon}}" alt=""> <a href="#" class="nav-link"  style="padding-left: 0; width:100%;" data-toggle="dropdown">{{$kategori->nama_kategori}} <i class="fa fa-angle-down float-right mt-1"></i></a>
            <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                @foreach ($kategori->where(['induk_id'=>$kategori->id])->get() as $child)
                <div class="d-flex">
                    <img width="25px" height="25px" class="rounded m-2" src="/image/kategori/tumb/{{$child->icon}}" alt=""> <a href="" class="dropdown-item" style="padding-left: 0">{{$child->nama_kategori}}</a>
                 </div>
                @endforeach
            </div>
        </div>
        @endif
        @if($kategori->induk_id==0)
        <div class="d-flex">
       <img width="25px" height="25px" class="rounded m-2" src="/image/kategori/tumb/{{$kategori->icon}}" alt=""> <a href="" class="nav-item nav-link" style="padding-left: 0">{{$kategori->nama_kategori}}</a>
    </div>
       @endif
        @endforeach
    </div>
</nav>
</div>
