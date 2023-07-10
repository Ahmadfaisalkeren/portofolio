<section id="certificate" class="section-padding section-connector">
    <div class="container">
        <div class="row">
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="setion-title text-center">
                    <h1>Certificate</h1>
                    <p>Berikut ini adalah beberapa Sertifikat kompetensi yang saya dapatkan</p>
                </div>
            </div>
        </div>
        <div class="row g-2">
            @foreach ($certificate as $item)
                <div class="col-lg-6 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="project rounded-4">
                        <img class="img-certificate" src="{{ url(Storage::url($item->image)) }}" alt="">
                        <div class="content">
                            <a href="{{ url(Storage::url($item->image)) }}" data-fancybox="gallery"
                                class="btn btn-outline-light rounded-3">
                                View Certificate
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
