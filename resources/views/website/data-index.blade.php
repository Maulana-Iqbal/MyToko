@extends('../index')

@section('content')
    @include('website.slider')


    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing">

        <div class="container aos-init" data-aos="fade-up">

            <header class="section-header">
                <p>Produk Terbaru</p>
            </header>

            <div class="row gy-4 aos-init" data-aos="fade-left">

                @if (isset($produkBaru))
                    @php $i=0; @endphp
                    @foreach ($produkBaru as $stock)
                    @if($stock->jumlah>0)
                        @php $produk=$stock->produk @endphp
                        <div class="col-md-3 aos-init" data-aos="zoom-in" data-aos-delay="100">
                            <div class="box" style="padding: 0">
                                <a href="/produk/detail/{{ $produk->slug }}">
                                    <img src="{{ asset('image/produk/large/' . $produk->gambar_utama) }}" class="img-fluid"
                                        style="width: 100%; padding: 0" alt="">
                                    <h3 style="color: #919191; font-size:12px; margin-top:5px; margin-bottom:5px;">
                                        {{ $produk->nama_produk }}</h3>
                                </a>
                                <ul style="font-size: 12px; margin-bottom:5px;">
                                    <li style="padding-bottom: 2px;">Kode : {{ $produk->kode_produk }}</li>
                                    <li style="padding-bottom: 2px;">{{ $produk->kategori->nama_kategori }}</li>
                                </ul>
                                @if ($produk->jenis_jual == 1)
                                    <a href="/produk/detail/{{ $produk->slug }}"
                                        class="btn btn-outline-primary btn-sm mb-2"><i class="fa fa-eye text-info"></i>
                                        Detail</a> <a href="/cart/add/{{ $stock->id }}"
                                        class="btn btn-outline-primary btn-sm mb-4"><i
                                            class="fa fa-cart-plus text-danger"></i> Add To Cart</a>
                                @elseif ($produk->jenis_jual == 2)
                                    <a style="font-size:12px"
                                        href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20{{$produk->kode_produk}}%20nama%20produk%20{{$produk->nama_produk}}"
                                        class="btn btn-outline-success mb-4"><i class="fa fa-whatsapp"></i>
                                        Pesan
                                        Sekarang</a>
                                @endif


                            </div>
                        </div>
                        @php $i++; @endphp
                        @endif
                    @endforeach
                @endif

            </div>

        </div>

    </section>
    <!-- End Pricing Section -->



    <!-- ======= Features Section ======= -->
    <section id="features" class="features">

        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h2>Features</h2>
                <!-- <p>Laboriosam et omnis fuga quis dolor direda fara</p> -->
            </header>

            <div class="row">

                <div class="col-lg-6">
                    <img src="{{ asset('fe_assets/img/features.png') }}" class="img-fluid" alt="">
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0 d-flex">
                    <div class="row align-self-center gy-4">

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="200">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Pengerjaan Cepat</h3>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="300">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Free Desain</h3>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="400">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Bervariasi</h3>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="500">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Exsklusif dan Unik</h3>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="600">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Cutom</h3>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="700">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>Berpengalaman</h3>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- / row -->

            <!-- Feature Tabs -->
            <div class="row feture-tabs" data-aos="fade-up">
                <div class="col-lg-6">
                    <h3>Sekilas Tentang Mengapa Merchandise Promosi Selalu Menjadi Pilihan</h3>

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-3">
                        <li>
                            <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Review</a>
                        </li>
                        <li>
                            <a class="nav-link" data-bs-toggle="pill" href="#tab2">Survey</a>
                        </li>
                        <li>
                            <a class="nav-link" data-bs-toggle="pill" href="#tab3">Trus Me</a>
                        </li>
                    </ul>
                    <!-- End Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="tab1">
                            <p>Berdasarkan survey yang dilakukan oleh British Promotional Merchandise Association
                                (BPMA), hasilnya menunjukkan:</p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>92% responden percaya bahwa barang promosi dapat meningkatkan brand awareness
                                    perusahaan mereka</h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>76% responden mengatakan bahwa merchandise promosi merupakan sarana beriklan
                                    yang lows cost high impact, dibandingkan dengan media iklan lainnya seperti tv,
                                    radio, majalah, dll</h4>
                            </div>
                        </div>
                        <!-- End Tab 1 Content -->

                        <div class="tab-pane fade show" id="tab2">
                            <p>Berdasarkan survey yang dilakukan oleh British Promotional Merchandise Association
                                (BPMA), hasilnya menunjukkan:</p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Lebih dari 52% responden cenderung membeli produk dari perusahaan yang
                                    memberikan souvenir promosi</h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>82% responden akan tetap menyimpan souvenir promosi yang mereka dapatkan. Ini
                                    berarti barang promosi memiliki jangka waktu promosi yang tidak terbatasi</h4>
                            </div>
                        </div>
                        <!-- End Tab 2 Content -->

                        <div class="tab-pane fade show" id="tab3">
                            <p>Beberapa Kunci Kami Menjadi Pilihan diantaranya: </p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Komunikatif adalah kunci dalam pemecahan masalah yang dapat menjawab keinginan
                                    Anda</h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Telah bekerja sama dengan ribuan klien di berbagai daerah dengan mengedepankan
                                    layanan</h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Pengerjaan dan pengiriman sesuai jadwal yang ditetapkan untuk kepentingan Anda
                                </h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Menyediakan layanan Lump Sum untuk mempermudah pemenuhan kebutuhan Anda</h4>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check2"></i>
                                <h4>Dinamis mengikuti tren, menjawab kebutuhan Anda, custom bukan menjadi masalah
                                </h4>
                            </div>
                        </div>
                        <!-- End Tab 3 Content -->

                    </div>

                </div>

                <div class="col-lg-6" style="text-align: center;">
                    <img src="{{ asset('fe_assets/img/f1.jpg') }}" class="img-fluid"
                        style="max-height: 400px; border-radius: 20px;" alt="">
                </div>

            </div>
            <!-- End Feature Tabs -->


        </div>

    </section>
    <!-- End Features Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">

        <div class="container aos-init" data-aos="fade-up">

            <header class="section-header">
                <h2>Services</h2>
                <p> Respon Cepat, Terpercaya dan Memuaskan </p>
            </header>

            <div class="row gy-4">

                <div class="col-lg-4 col-md-6 aos-init" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-box blue">
                        <img class="img-fluid" style="height: 100px;" src="{{ asset('fe_assets/img/respon.png') }}">
                        <h3> Respon Cepat </h3>
                        <p>&nbsp;</p>
                        <!-- <a href="#" class="read-more"><span>Read More</span> <i class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 aos-init" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-box orange">
                        <p><img class="img-fluid" style="height: 100px;" src="{{ asset('fe_assets/img/rekom.png') }}">
                        </p>
                        <h3>Terpercaya</h3>
                        <p>&nbsp;</p>
                        <!-- <a href="#" class="read-more"><span>Read More</span> <i class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 aos-init" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-box green">
                        <p><img class="img-fluid" style="height: 100px;" src="{{ asset('fe_assets/img/bukti.png') }}">
                        </p>
                        <h3>Kepuasan Terbukti </h3>
                        <p>&nbsp;</p>
                        <!-- <a href="#" class="read-more"><span>Read More</span> <i class="bi bi-arrow-right"></i></a> -->
                    </div>
                </div>



            </div>

        </div>

    </section>
    <!-- End Services Section -->








    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Our Achievements</h2>
                <p>Prestasi</p>
            </header>
            <div class="row gy-4">

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-emoji-smile"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="1852" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Happy Clients</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-journal-richtext" style="color: #ee6c20;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Projects</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-headset" style="color: #15be56;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-people" style="color: #bb0852;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Hard Workers</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- End Counts Section -->

    <!-- ======= Values Section ======= -->
    <section id="values" class="values">

        <div class="container" data-aos="fade-up">

            <!-- <header class="section-header">
                    <h2>Nilai Utama</h2>
                    <p>Mengapa Memilih Kami ?</p>
                </header>

                <div class="row">

                    <div class="col-lg-4 aos-init" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <img src="{{ asset('fe_assets/img/center.png') }}" alt="" width="579" height="430"
                                class="img-fluid">
                            <h3> Pelayanan dan Proses Cepat</h3>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0 aos-init" data-aos="fade-up" data-aos-delay="400">
                        <div class="box">
                            <img src="{{ asset('fe_assets/img/jk.jpg') }}" alt="" width="100%" class="img-fluid">
                            <h3>&nbsp; </h3>
                            <h3>&nbsp;</h3>
                            <h3>Jangkauan Koneksi Luas </h3>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0 aos-init" data-aos="fade-up" data-aos-delay="600">
                        <div class="box">
                            <img src="{{ asset('fe_assets/img/ker.jpg') }}" class="img-fluid" alt="">
                            <h3> Happy Shopping and Big Sale </h3>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                </div> -->


            <div class="section mcb-section   " style="padding-top:0px; padding-bottom:0px; background-color:">
                <div class="section_wrapper mcb-section-inner">
                    <div class="wrap mcb-wrap one  valign-middle clearfix" style="">
                        <div class="row mcb-wrap-inner">
                            <div class="column mcb-column one column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="">

                                    <header class="section-header">
                                        <!-- <h2>Nilai Utama</h2> -->
                                        <p>Alasan Kenapa Order Di {{ website(1)->nama_website }}?</p>
                                    </header>
                                </div>
                            </div>
                            <div class="column mcb-column one column_divider ">
                                <hr class="no_line">
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">
                                    <img class="m-3" src="/fe_assets/img/why/Garansi-kualitas.png" alt="heart"
                                        width="75" height="75">
                                    <br>

                                    <h5><strong>GARANSI KUALITAS</strong></h5>
                                    <p style="text-align: center;">Jika ada barang yang rusak atau cacat, Kami akan
                                        ganti
                                        dan biaya kirim menjadi tanggung jawab kami</p>


                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">
                                    <img class="m-3" src="/fe_assets/img/why/garansi-tukar-baru.png" alt="heart"
                                        width="75" height="75">
                                    <br>
                                    <h5><strong>GARANSI TUKAR BARU</strong></h5>
                                    <p style="text-align: center;">{{ website(1)->nama_website }} memberikan garansi tukar
                                        baru
                                        terhadap barang promosi
                                        kami dengan masa garansi sampai seumur hidup</p>

                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">
                                    <strong><img class="m-3" src="/fe_assets/img/why/garansi-harga.png" alt="money"
                                            width="75" height="75"><br>
                                    </strong>
                                    <h5><strong>GARANSI Harga Terbaik</strong></h5>
                                    <p style="text-align: center;">{{ website(1)->nama_website }} memberikan Harga Terbaik,
                                        silahkan
                                        berdiskusi dengan
                                        Project Manager kami untuk mendapatkan harga terbaik.</p>

                                </div>
                            </div>
                            <div class="column mcb-column one column_divider ">
                                <hr class="no_line">
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/cetak-kualitas-tinggi.png" alt="heart"
                                        width="75" height="75"><br>

                                    <h5><strong>Cetak Logo Dan Gambar Kualitas Tinggi</strong></h5>
                                    <p>Kualitas cetak logo &amp; gambar awet, tahan lama serta gambar tidak luntur.</p>

                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/produksi-besar.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>Kapasitas Produksi BESAR &amp; CEPAT</strong></h5>
                                    <p style="text-align: center;">Waktu produksi cepat dan Kapasitas produksi barang
                                        ratusan ribu
                                        per bulannya. </p>

                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/Gratis-jasa-desain.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>GRATIS Jasa Desain</strong></h5>
                                    <p style="text-align: center;">Anda belum punya desain? Tim Desainer Profesional
                                        {{ website(1)->nama_website }} akan
                                        membantu Anda</p>

                                </div>
                            </div>
                            <div class="column mcb-column one column_divider ">
                                <hr class="no_line">
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/berpengalaman.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>Berpengalaman Dan LEGALITAS Resmi</strong></h5>
                                    <p style="text-align: center;">{{ website(1)->nama_website }} merupakan perusahaan
                                        berbadan hukum
                                        CV. {{ website(1)->nama_website }}</p>

                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/order-mudah.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>Proses Order Mudah &amp; Cepat Respon </strong></h5>
                                    <p style="text-align: center;">{{ website(1)->nama_website }} memberikan kemudahan
                                        dalam proses
                                        order dalam kebutuhan
                                        anda</p>

                                </div>
                            </div>
                            <div class="col-md-4 column mcb-column one-third column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/gratis-biaya-kirim.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>GRATIS Biaya Kirim</strong></h5>
                                    <p style="text-align: center;">Untuk order minimal 100 pcs dengan tujuan pengiriman
                                        Pulau Sumatera
                                        &amp; via Cargo *Syarat &amp; Ketentuan Berlaku</p>

                                </div>
                            </div>
                            <div class="column mcb-column one column_divider ">
                                <hr class="no_line">
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 column mcb-column one column_column  column-margin-">
                                <div class="column_attr clearfix align_center" style="text-align:center">

                                    <img class="m-3" src="/fe_assets/img/why/gratis-souvenir.png" alt="heart"
                                        width="75" height="75"><br>
                                    <h5><strong>Gratis Souvenir Eksklusif</strong></h5>
                                    <p style="text-align: center;">Setiap pemesanan di {{ website(1)->nama_website }} akan
                                        mendapatkan
                                        Souvenir Eksklusif
                                        secara gratis</p>

                                </div>
                            </div>

                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- End Values Section -->

    <!-- ======= F.A.Q Section ======= -->
    <section id="faq" class="faq">

        <div class="container aos-init" data-aos="fade-up">

            <header class="section-header">
                <h2>F.A.Q</h2>
                <p>Frequently Asked Questions</p>
            </header>

            <div class="row">
                <div class="col-lg-6">
                    <!-- F.A.Q List 1-->
                    <div class="accordion accordion-flush" id="faqlist1">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-content-1">
                                    Mengapa Menggunakan Merchandise Promosi?
                                </button>
                            </h2>
                            <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    92% responden percaya bahwa barang promosi dapat meningkatkan brand awareness
                                    perusahaan mereka

                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-content-2">
                                    What Our Client Says?
                                </button>
                            </h2>
                            <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Kontak via email/sms langsung dibalas. Barang sampai tepat waktu (bahkan
                                    pengirimannya pakai ONS!), dengan kualitas barang yang memuaskan. Kalau di
                                    kantor ada acara, saya pasti pesan lagi di sini. Two thumbs up!.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-content-3">
                                    Kenapa Harus kami?
                                </button>
                            </h2>
                            <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Kami berpengalaman dalam memproduksi aneka macam souvenir promosi untuk berbagai
                                    macam kebutuhan acara promosi, branding, product launching, gathering, souvenir
                                    kantor, seminar, maupun pelatihan.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6">

                    <!-- F.A.Q List 2-->
                    <div class="accordion accordion-flush" id="faqlist2">

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2-content-1">
                                    Why Trust {{ website(1)->nama_website }}?
                                </button>
                            </h2>
                            <div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Karena kami percaya bahwa kepercayaan adalah modal utama bisnis kami dapat
                                    tumbuh dan berkembang secara berkelanjutan. Dengan berbekal komitmen dalam
                                    menyediakan merchandise promosi dan layanan dengan standar kualitas yang tinggi,
                                    kami terus dipercaya oleh perusahaan-perusahaan dan instansi dalam berbagai
                                    skala dan jenis usaha
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2-content-2">
                                    Bagaimana Komitment Kami Kedepan?
                                </button>
                            </h2>
                            <div id="faq2-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    kami terus berusaha untuk selalu memberikan produk dengan kualitas tertinggi
                                    dengan layanan yang terbaik bagi seluruh klien kami, Variasi barang promosi yang
                                    kami sediakan pun juga beragam dan dapat disesuaikan dengan segala macam acara
                                    dan kebutuhan. Kami telah menjalankan bisnis ini sejak tahun 2017 dan terus
                                    konsisten sampai saat ini menjaga kepercayaan klien
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2-content-3">
                                    Butuh faktur pajak untuk kelengkapan dokumen pemesanan?
                                </button>
                            </h2>
                            <div id="faq2-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Kami buatkan lengkap.
                                    Kami melayani pembayaran di akhir (LS) untuk pemerintahan dan beberapa instansi
                                    dengan ketentuan tertentu.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </section><!-- End F.A.Q Section -->




    <!-- ======= Clients Section ======= -->
    {{-- <section id="clients" class="clients">

        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h2>Our Clients</h2>
                <p>Mereka Yang Telah Menggunakan Layanan Kami</p>
            </header>

            <div class="clients-slider swiper">
                <div class="swiper-wrapper align-items-center">
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c1.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c2.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c3.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c4.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c5.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c6.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c7.jpg') }}" class="img-fluid"
                            alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('fe_assets/img/c8.jpg') }}" class="img-fluid"
                            alt=""></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

    </section> --}}
    
    <!-- End Clients Section -->


    <!-- ======= Recent Blog Posts Section ======= -->
    <section id="recent-blog-posts" class="recent-blog-posts">

        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h2>Blog</h2>
                <p>Posting Blog</p>
            </header>

            <div class="row">
                @foreach ($posts as $recent)
                    <div class="col-lg-4">
                        <div class="post-box">
                            <div class="post-img text-center"><img src="{{ asset('postImage/' . $recent->cover) }}"
                                    class="img-fluid" style="max-height: 150px" alt=""></div>
                            <span
                                class="post-date">{{ Carbon\Carbon::parse($recent->created_at)->diffForHumans() }}</span>
                            <h3 class="post-title">{{ $recent->title }}</h3>
                            <a href="{{ route('show', $recent->slug) }}"
                                class="readmore stretched-link mt-auto"><span>Read More</span><i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </section><!-- End Recent Blog Posts Section -->


    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">

        <div class="container aos-init" data-aos="fade-up">

            <header class="section-header">
                <h2>Contact</h2>
                <p>Contact Us</p>
            </header>

            <div class="row gy-4">

                <div class="col-lg-6">

                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-geo-alt"></i>
                                <h3>Alamat</h3>
                                <p>{{ strip_tags(website(1)->address) }}, {{ website(1)->webkecamatan->name }},
                                    {{ website(1)->webkota->name }}, {{ website(1)->webprovinsi->name }}
                                    {{ website(1)->kode_pos }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>Hubungi Kami</h3>
                                <p>{{ website(1)->contact }}</p>
                                <br><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-envelope"></i>
                                <h3>Email </h3>
                                <p>{{ strtolower(str_replace(' ', '', website(1)->nama_website)) }}@gmail.com</p>
                                <br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-clock"></i>
                                <h3>Open Hours</h3>
                                <p>Senin - Sabtu<br>
                                    08:00 - 17:00 WIB</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('fe_assets/img/cs2.png') }}" class="rounded mx-auto d-block"
                            style="max-height: 500px;" alt="">
                    </div>

                </div>

            </div>

        </div>

    </section>
    <!-- End Contact Section -->

@endsection
