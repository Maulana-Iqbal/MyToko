<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>

        @if(isset($slider))
        @foreach($slider as $index=>$slideButton)
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$index}}"></button>
        @endforeach
        @endif
    </div>
    <div class="carousel-inner">
        @if(isset($slider))
        @foreach($slider as $index=>$slider)
        <div class="carousel-item @if($index==0) active @endif">
            <img src="/image/slide/{{$slider->image}}" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>{{$slider->title}}</h5>
                <p>{{$slider->subtitle}}</p>
            </div>
        </div>

        @endforeach
        @endif
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
