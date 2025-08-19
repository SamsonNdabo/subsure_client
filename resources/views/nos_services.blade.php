@extends('Layouts.app')
@section('content')
    <section class="pt-5 pb-6 bg-white">
        <div class="container" id="services">
            <div class="heading heading-center mb-5">
                <h2 class="title-lg text-dark fw-bold">Nos Services</h2>
                <p class="text-muted mt-2">Découvrez une sélection variée de services adaptés à vos besoins.</p>
            </div>

            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='{
                                    "nav": true,
                                    "dots": false,
                                    "margin": 20,
                                    "loop": false,
                                    "responsive": {
                                        "0": {"items":1},
                                        "576": {"items":2},
                                        "768": {"items":3},
                                        "992": {"items":4}
                                    }
                                }'>

                @foreach($services as $service)
                    <div class="product product-11 text-center shadow-sm p-3 bg-light rounded-3">
                        <figure class="product-media mb-3">
                            <a href="{{ url('/details/' . $service['id']) }}">                                
                                <img src={{ env('API_BASE_URL') . '/service_image/' .$service['image'] }} alt="Image du Service" class="img-fluid rounded">
                            </a>

                        </figure>
                        <div class="product-body">
                            <h3 class="product-title mb-1">
                                <a href="{{ url('/nos_services/' . $service['id']) }}"
                                    class="text-dark">{{ $service['designation'] }}</a>
                            </h3>
                            <p class="product-desc text-muted small">{{ $service['description'] }}</p>
                        </div>
                        <div class="product-action mt-3">
                            <a href="{{ url('/nos_services/' . $service['id']) }}" class="btn btn-outline-primary btn-sm">
                                <span>Détails</span>
                            </a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
@endsection