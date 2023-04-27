@extends('layouts.app-master')
@section('title','Главная')
@section('content')
<div class="m-3">
    <p class="h3">Добрый день, {{auth()->user()->localName()}}</p>
    <hr>

    <div class="alert alert-dark alert-dismissible fade show" role="alert">
        <div>
            <p class="h5">Инструкция пользователя:</p>
            <ul>
                <li>Для перехода по страницам используйте боковое меню.</li>
                <li>На этой странице возможно просмотреть краткую статистику по эффективности вашей работы.</li>
                <li>В "Заявках" возможно удалять заявки и создавать договоры.</li>
                <li>Для просмотра акций используйте вкладку "Акции".</li>
                <li>Для просмотра или изменения информации вашего профиля используйте правую верхнюю кнопку.</li>
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <a {{($countApl->count>0) ? "href=/appl?type=no" : null }} class="h5 link-dark align-self-center">На данный момент необработаных заявок: {{$countApl->count}}</a>
    <div class="row justify-content-md-start">
        <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="width:30%;">
            <div class="card-header">Всего обработано заявок:</div>
            <div class="card-body"><canvas id="myChart2"></canvas></div>
        </div>
        <div style="width: 70%">
        <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="">
            <div class="card-header">Количество утверждённых вами договоров:</div>
            <div class="card-body"><canvas id="myChart"></canvas></div>
        </div>
            <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="">
                <div class="card-header">Сумма, на которую вы утвердили договоры:</div>
                <div class="card-body"><canvas id="myChart3"></canvas></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>
<script type="text/javascript">
    moment.locale('ru');
    var ctx = document.getElementById('myChart').getContext('2d');
    var d = '{{$chartbyMonth}}'.replace(/(&quot\;)/g,"\"");
    var data = JSON.parse(d);
    var fulldata = data.map(function(e) {
        return e.count;
    });
    var labels = data.map(function(e) {
        return e.date_month;
    });
    var cdata = {

        type: 'bar',
        data: {

            labels: labels,
            datasets: [{
                data: fulldata,
                backgroundColor: ['rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'],

                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1,
                fill:true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                },

                x: {
                    type: 'time',
                    time: {
                        unit: 'month',
                        displayFormats: {
                            month: 'MMM'
                        }
                    }

                }

            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            }
        }
    };
    new Chart(ctx,cdata);
</script>
<script type="text/javascript">
    var ctx = document.getElementById('myChart2').getContext('2d');
    var d = '{{$count}}'.replace(/(&quot\;)/g,"\"");
    var data = JSON.parse(d);
    var fulldata = data.map(function(e) {
        return e.all;
    });
    var labels = data.map(function(e) {
        return e.count;
    });
    var cdata = {

        type: 'pie',
        data: {

            labels: ['Вы','Другие'],
            datasets: [{
                data: [labels,fulldata],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)'],

                borderColor: [
                    'rgb(54, 162, 235)',

                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1,
                fill:true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            }
        }
    };
    new Chart(ctx,cdata);
</script>
<script type="text/javascript">
    moment.locale('ru');
    var ctx = document.getElementById('myChart3').getContext('2d');
    var d = '{{$allMoney}}'.replace(/(&quot\;)/g,"\"");
    var data = JSON.parse(d);
    var fulldata = data.map(function(e) {
        return e.count;
    });
    var labels = data.map(function(e) {
        return e.date_month;
    });
    var cdata = {

        type: 'bar',
        data: {

            labels: labels,
            datasets: [{
                data: fulldata,
                backgroundColor: ['rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'],

                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1,
                fill:true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                },

                x: {
                    type: 'time',
                    time: {
                        unit: 'month',
                        displayFormats: {
                            month: 'MMM'
                        }
                    }

                }

            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            }
        }
    };
    new Chart(ctx,cdata);
</script>
@endsection
