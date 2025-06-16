@extends('layouts.site')

@section('title', 'Bem-vindo à Confeitaria Delícia!')

@section('content')
    <h2 class="text-signature-title text-maroon text-center">Categorias</h2>
    @foreach ($categories as $category)
    @php
        // Buscar até 3 produtos da categoria que tenham imagem
        $productImages = $category->products()->whereNotNull('image')->take(3)->pluck('image')->toArray();
    @endphp
    <section class="container my-5">
        <div class="row align-items-center {{ $loop->index % 2 == 0 ? '' : 'flex-row-reverse' }}" data-aos="zoom-in">
            <div class="col-md-6">
                @if(count($productImages) > 0)
                    @component('components.carousel', [
                        'carouselId' => 'carouselCestas' . $category->id, 
                        'images' => $productImages
                    ])
                    @endcomponent
                @else
                    @component('components.carousel', [
                        'carouselId' => 'carouselCestas' . $category->id, 
                        'images' => ['logo.jpg']
                    ])
                    @endcomponent         
               @endif
            </div>
            <div class="col-md-6">
                <h2 class="text-maroon text-signature-title">{{ $category->name }}</h2>
                <p>{{ $category->description }}</p>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('shop', $category->id) }}" class="btn btn-maroon text-btn-comprar rounded-5">COMPRAR</a>
                </div>
            </div>
        </div>
    </section>
    @endforeach
@endsection
