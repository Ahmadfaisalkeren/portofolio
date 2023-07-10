<section id="skills" class="section-padding section-connector">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
        }

        .card-skills {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <h1>Skills</h1>
                    <p>Berikut ini beberapa kemampuan saya</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center card-container">
            @foreach ($skill as $item)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="box w-100 card-skills" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ $item->link }}" target="_blank">
                            <img class="mx-auto mt-3 img-skill d-block" src="{{ url(Storage::url($item->image)) }}" alt="">
                        </a>
                        <h5 class="text-center">{{ $item->skill }}</h5>
                        <p class="text-center text-secondary">{{ $item->skill_detail }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            equalizeCardHeight();
            $(window).resize(function() {
                equalizeCardHeight();
            });
        });

        function equalizeCardHeight() {
            $('.card-container').each(function() {
                let maxHeight = 0;
                $(this).find('.card-skills').each(function() {
                    $(this).css('height', 'auto');
                    let cardHeight = $(this).outerHeight();
                    if (cardHeight > maxHeight) {
                        maxHeight = cardHeight;
                    }
                });
                $(this).find('.card-skills').css('height', maxHeight);
            });
        }
    </script>
</section>
