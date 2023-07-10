<section id="projects" class="section-padding section-connector">
    <div class="container">
        <div class="text-center" data-aos="fade-up" data-aos-delay="100">
            <h1>Project</h1>
        </div>
        <div class="row mt-5">
            @foreach ($project as $item)
                <div class="col-lg-6 col-sm-6 mb-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="project rounded-4">
                        <img class="img-project" src="{{ url(Storage::url($item->image)) }}" alt="">
                        <div class="content">
                            <a href="{{ $item->link }}" target="_blank" class="btn btn-explore rounded-3">View
                                Project</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
