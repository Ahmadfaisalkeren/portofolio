<section id="about" class="section-padding section-connector">
    <div class="container">
        @foreach ($about as $item)
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <h1>About Me</h1>
            </div>
            <div class="box text-center mt-3" data-aos="fade-up" data-aos-delay="100">
                <p>{{ $item->description }}</p>
            </div>
        @endforeach
    </div>
</section>
