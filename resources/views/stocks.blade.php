@extends('layouts.app-master')
@section('title','Акции')
@section('content')
    @if ($errors->has('countNotValid'))
        <div class="alert alert-danger">
            <strong>ОШИБКА!</strong> Количество должно быть больше и кратным прошлому количеству данной ценной бумаги.
        </div>
    @endif
    @if (session()->has('countStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> Дробление ценной бумаги было успешно.
        </div>
    @endif
    @if ($errors->has('count') || $errors->has('company') || $errors->has('type'))
        <div class="alert alert-danger">
            <strong>ОШИБКА!</strong><br>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    @if (session()->has('StockStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('StockStatus') }}
        </div>
    @endif
    @auth
        @if(auth()->user()->roleChecker() == false)
            <div class="m-3"><p class="h3">Добавить:</p>
            <form class="row row-cols-lg-auto g-3 mb-lg-3 align-items-center" method="post" action="{{ route('addStock') }}">
                @csrf
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control" id="company_name"  name="company_name" placeholder="company name" />
                        <label for="company_name" class="form-label">Название компании</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="type" name="type" placeholder="type" />
                        <label for="type" class="form-label">Тип ценной бумаги</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <input class="form-control" type="number" min="0" step="1" id="count" name="count" placeholder="count change" required>
                        <label class="form-label" for="count">Кол-во ценных бумаг</label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Добавить</button>
                </div>
            </form>
            </div>
            <p style="border-bottom: 1px solid #6a6a6a; padding-bottom: 20px; margin-bottom: 20px"></p>
        @endauth
    @endauth
    <div class="d-flex justify-content-between m-3">
        <p class="h3">Список акций</p>
        <div class="dropdown">
            <button
                class="btn btn-sm btn-dark dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-mdb-toggle="dropdown"
                aria-expanded="false"
            >
                Статистика за
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="?time=day">День</a></li>
                <li><a class="dropdown-item" href="?time=month">Месяц</a></li>
                <li><a class="dropdown-item" href="?time=year">Год</a></li>
            </ul>
        </div>
    </div>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th scope="col">Код ценной бумаги</th>
            <th scope="col">Тип</th>
            <th scope="col">Компания владелец</th>
            <th scope="col">Стоимость</th>
            <th scope="col">Изменение</th>
            <th scope="col">Объём</th>
            <th scope="col">Предложений</th>
        </tr>
        </thead>
        <tbody>
        @if($stocks)
        @foreach($stocks as $stock)
            <tr>
                <td scope="row">{{$stock['vendor_code']}}</td>
                <td>{{$stock['type']}}</td>
                <td>{{$stock['company_name']}}</td>
                <td>{{$stock['now_price'] != null ? round($stock['now_price'],2)."$" : "N/A"}}</td>
                <td class="{{$stock['change_perc'] != null ? ($stock['change_perc'] >=0 ? "text-success" : "text-danger") : ""}}">{{$stock['change_perc'] != null ? ($stock['change_perc'] >=0 ? "+" : "").round($stock['change_perc']*100,2)."%" : "N/A"}}</td>
                <td>{{$stock['volume'] != null ? round($stock['volume'],2)."$" : "N/A"}}</td>
                <td>{{$stock['offers_count'] != null ? $stock['offers_count'] : "N/A"}}</td>
                <td> <button class="btn btn-outline-dark" id="stockInfo" data-toggle="modal" data-target='#stock_modal' data-id="{{ $stock['vendor_code'] }}">Подробнее</button></td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
    <div class="modal fade" id="stock_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="h3 modal-title text-black" id="title"></p>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                    @auth
                        @if(auth()->user()->roleChecker() == false)
                            <hr class="bg-light border-1 w-100 border-top border-dark"/>
                            <p class="h4">Дробление ценной бумаги:</p>
                            <p id="oldCount">Сейчас:</p>

                            <form  method="post" action="{{ route('changeStockCount') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="valid_code" id="valid_code" value="" />
                                <!-- Name input -->
                                <div class="form-group form-floating mb-3">
                                    <input class="form-control" type="number" min="0" step="1" id="count" name="count" placeholder="count change" required>
                                    <label class="form-label" for="count">Введите новое количество кратное старому:</label>
                                </div>
                                <button type="submit" id="btn1" class="btn btn-light btn-outline-dark btn-block mb-4">Изменить</button>
                            </form>
                        @endif
                    @endauth
                    <hr class="bg-light border-1 w-100 border-top border-dark"/>
                    <p class="h4">Статистика рынка:</p>
                    <div class="d-flex flex-row bd-highlight mb-3 justify-content-between text-center">
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Рыночная капитализация рассчитывается путем умножения оборотного предложения актива на его текущую цену."></i> Рыночная<br>капитализация:</p>
                            <p class="card-body"  id="markup"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Общая стоимость всех транзакций для этого актива за последние 24 часа в долларах."></i> Объём(24 часа):</p>
                            <p class="card-body"  id="volume"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Популярность основана на относительной рыночной капитализации торгуемых активов."></i> Популярность:</p>
                            <p class="card-body"  id="popular"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Самая высокая цена, уплаченная за этот актив с момента его запуска или листинга."></i> Самая высокая цена:</p>
                            <p class="card-body" id ="mostHightValue"></p>
                        </div>
                    </div>
                    <div class="d-flex flex-row bd-highlight mb-3 justify-content-between text-center">
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Скользящее среднее цены за день."></i> Тренд (МА1):</p>
                            <p class="card-body"  id="trendma1"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Скользящее среднее цены за неделю."></i> Тренд (МА7):</p>
                            <p class="card-body"  id="trendma7"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Скользящее среднее цены за месяц."></i> Тренд (МА30):</p>
                            <p class="card-body"  id="trendma30"></p>
                        </div>
                        <div class="p-2 bd-highlight card bg-light mb-3 border border-dark" style="max-width: 15rem;">
                            <p class="card-header"><i class="fa-solid fa-circle-info" data-toggle="tooltip"  data-placement="bottom" title="Процентное соотношение запросов покупки и продажи актива."></i> Торговая деятельность:</p>
                            <p class="card-body"  id="marketdiy"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            $('[data-toggle="tooltip"]').tooltip({
                container : 'body'
            })
        })
    </script>
    <script>
        $(document).ready(function () {

            $('body').on('click', '#stockInfo', function (event) {

                event.preventDefault();
                var id = $(this).data('id');
                $.get('stock/' + id, function (data) {
                    @auth
                    @if(auth()->user()->roleChecker() == false)
                    $('#valid_code').val(data.data.vendor_code);
                    $('#oldCount').text('Сейчас: ' + data.data.count);
                    @endif
                    @endauth
                    $('#title').text(data.data.type + " " + data.data.company_name);
                    if(data.info != null) {
                        $('#markup').text(data.info.market_cup !==null ? data.info.market_cup + "$" : "N/A");
                        $('#volume').text(data.info.volume !==null ? parseFloat(data.info.volume).toFixed(2) + "$" : "N/A");
                        $('#mostHightValue').text(data.info.max_price !==null ? parseFloat(data.info.max_price).toFixed(2) + "$" : "N/A");
                        $('#popular').text(data.info.popularity !==null ? "#" + data.info.popularity : "N/A");
                        $('#marketdiy').text(data.info.market_perc ?? "N/A");
                        $('#trendma1').text(data.info.trend_ma1 !== null ? data.info.trend_ma1 + "$" : "N/A");
                        $('#trendma7').text(data.info.trend_ma7 !== null ? data.info.trend_ma7 + "$" : "N/A");
                        $('#trendma30').text(data.info.trend_ma30 !== null ? data.info.trend_ma30 + "$" : "N/A");
                    }
                    else{
                        $('#markup').text("N/A");
                        $('#volume').text("N/A");
                        $('#mostHightValue').text("N/A");
                        $('#popular').text("N/A");
                        $('#marketdiy').text("N/A");
                        $('#trendma1').text("N/A");
                        $('#trendma7').text("N/A");
                        $('#trendma30').text("N/A");
                    }
                    $('#stock_modal').modal('show');
                    let chartStatus = Chart.getChart("myChart"); // <canvas> id
                    if (chartStatus != undefined) {
                        chartStatus.destroy();
                    }
                    var labels = data.chart.map(function(e) {
                        return new Date(e.date.replace(' ', 'T'));
                    });
                    var d = data.chart.map(function(e) {
                        return e.fixed_price;
                    });
                    moment.locale('ru');
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgb(0,0,0)');
                    gradient.addColorStop(1, 'rgba(89,94,125,0.1)');
                    var cdata = {

                        type: 'line',
                        data: {

                            labels: [...labels],
                            datasets: [{
                                data: [...d],
                                backgroundColor: gradient,
                                fill:true
                            }]
                        },
                        options: {
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: 'month'
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: d.length> 0 ? 'Цена: ' + parseFloat(d[d.length-1]).toFixed(2) + "$" : "N/A",
                                }
                            }
                        }
                    };
                    new Chart(ctx,cdata);
                })
            });
        });
    </script>
@endsection
