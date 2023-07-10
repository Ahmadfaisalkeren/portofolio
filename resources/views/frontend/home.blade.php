<section id="home" class="section-padding">
    <div class="container">
        <div class="row">
            @foreach ($home as $item)
                <div class="col-md-6 order-md-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex justify-content-center">
                        <img class="img-fluid img-profile mb-2" src="{{ url(Storage::url($item->image)) }}" alt="">
                    </div>
                </div>
                <div class="col-md-6 order-md-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex align-items-center h-100">
                        <div class="">
                            <h1>{{ $item->description }}</h1>
                            <p>{{ $item->description_2 }}</p>
                            <a href="#about" type="button" class="btn btn-sm btn-explore">Let's Explore</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
