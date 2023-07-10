<section id="contact" class="section-padding section-connector">
    <div class="container">
        <div class="row">
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <h1>Contact</h1>
                    <p>Berikut ini beberapa kontak media sosial saya</p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap">
            @foreach ($contact as $item)
                <div class="mx-auto col-md-2 mr-2 mb-3 box" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ $item->link }}" target="_blank">
                        <img class="d-flex mx-auto mt-3 img-contact" src="{{ url(Storage::url($item->image)) }}" alt="">
                    </a>
                    <h5 class="text-center">{{ $item->contact }}</h5>
                </div>
            @endforeach
        </div>
    </div>
</section>
