@extends('layouts.app-master')
@section('title','Stockbase')
@section('content')
<!--  ABOUT AREA START  -->
<header class="bg-dark py-5">
    <div class="px-5">
        <div class="row justify-content-lg-center">
            <div class="col-lg-6">
                <div class="text-center my-5">
                    <h1 class="display-5 fw-bolder text-white mb-2">Покупай акции не по удаче</h1>
                    <p class="lead text-white-50 mb-4">Быстрые транзакции, минимальное время отчётности, законно</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a class="btn btn-outline-light btn-lg px-4" href="{{route('stocks.index')}}">Список акций</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Features section-->
<section class="py-3 border-bottom" id="features">
    <div class="container px-5 my-5">
        <div class="row gx-5">
            <div class="col-lg-4 mb-5 mb-lg-0 card">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-collection"></i></div>
                <h2 class="h4 fw-bolder card-header">Большой ассортимент</h2>
                <p class="card-body">С нами работают самые именитые компании из разных стран.</p>
            </div>
            <div class="col-lg-4 mb-5 mb-lg-0 card">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-building"></i></div>
                <h2 class="h4 fw-bolder card-header">Постоянная поддержка</h2>
                <p class="card-body">В зависимости от ваших пожеланий и сообщений формируется лучшее средство свободной торговли.</p>
            </div>
            <div class="col-lg-4 mb-5 mb-lg-0 card">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                <h2 class="h4 fw-bolder card-header">Отслеживайте и меняйте подход</h2>
                <p class="card-body">Стоимость активов изменчива - не забывайте.</p>
            </div>
        </div>
    </div>
</section>

@endsection
