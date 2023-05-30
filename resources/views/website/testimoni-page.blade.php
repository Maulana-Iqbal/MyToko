@extends('../index')

@section('content')
<section id="testimonials" class="testimonials" style="margin-top: 100px;">

    <div class="container" data-aos="fade-up">

        <header class="section-header">
            <h2>Testimonials</h2>
            <p>Apa yang mereka katakan tentang kami?</p>
        </header>

        <div class="row">

            @foreach($testimoni as $tes)

            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="testimonial-item" style="min-height: auto;">
                    <!-- <div class="stars">
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
          </div> -->
                    <img src="testimoniimg/{{$tes->gambar_utama}}" class="img-fluid" alt="{{$tes->dari}}">
                    <div class="profile mt-auto">
                        <!-- <img src="testimoniimg/{{$tes->gambar_utama}}" class="testimonial-img" alt="{{$tes->dari}}"> -->
                        <h3>{{$tes->dari}}</h3>
                        <!-- <h4>Ceo &amp; Founder</h4> -->
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- End testimonial item -->


    </div>
    </div>

</section>
@endsection