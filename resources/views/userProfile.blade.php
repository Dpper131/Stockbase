@extends('layouts.app-master')
@section('title','Профиль')
@section('content')
    @if (session()->has('UserStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('UserStatus') }}
        </div>
    @endif
            <div class="row m-2">
                    <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <p class="h3">Профиль</p>
                                <button class="btn btn-outline-dark" id="stockInfo" data-bs-toggle="modal" data-bs-target='#EditProfile'>Изменить</button>
                                <div class="modal fade" id="EditProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title text-black">Изменить профиль</h3>
                                                <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="{{ route('editProfile') }}">
                                                    @csrf
                                                    @if ($errors->has('fullname') or $errors->has('name') or $errors->has('middlename') or $errors->has('phone') or $errors->has('birthday') or $errors->has('adress'))

                                                        <script language="JavaScript" type="text/javascript">

                                                            jQuery(document).ready(function($) {
                                                                $('#EditProfile').modal('show');
                                                            });

                                                        </script>
                                                        <div class="alert alert-danger" role="alert">
                                                            <ul class="list-unstyled mb-0">
                                                                <li>Неверные данные. Попробуйте ещё раз </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <input type="hidden" name="id" id="id" value="{{$user->id}}"/>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="text" class="form-control {{$errors->has('fullname') ? ' is-invalid' : null}}" name="fullname" value="{{$user->fullname}}" placeholder="fullname">
                                                        <label class="form-label" for="fullname">Фамилия</label>
                                                    </div>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="text" class="form-control {{$errors->has('name') ? ' is-invalid' : null}}" name="name" value="{{$user->name}}" placeholder="name">
                                                        <label class="form-label" for="name">Имя</label>
                                                    </div>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="text" class="form-control {{$errors->has('middlename') ? ' is-invalid' : null}}" name="middlename" value="{{$user->middlename}}" placeholder="middlename">
                                                        <label class="form-label" for="middlename">Отчество</label>
                                                    </div>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="phone" class="form-control {{$errors->has('phone') ? ' is-invalid' : null}}" name="phone" value="{{$user->phone}}" placeholder="phone">
                                                        <label class="form-label" for="phone">Телефон</label>
                                                    </div>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="text" class="form-control {{$errors->has('adress') ? ' is-invalid' : null}}" name="adress" value="{{$user->adress}}" placeholder="adress">
                                                        <label class="form-label" for="adress">Адрес</label>
                                                    </div>
                                                    <div class="form-group form-floating mb-3">
                                                        <input type="date" class="form-control {{$errors->has('birthday') ? ' is-invalid' : null}}" name="birthday" value="{{$user->birthday}}" placeholder="birthday">
                                                        <label class="form-label" for="old_fullname">День рождения</label>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="w-100 btn btn-lg btn btn-dark" type="submit">Изменить</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Фамилия</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->fullname}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Имя</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->name}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Отчество</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->middlename}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Должность</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->position}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Зарплата</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->getSalary()}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Телефон</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->phone}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Адрес</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->adress}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">День рожденья</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->birthday}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Работаете с</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->workdate}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    @if(auth()->user()->roleChecker() == false and $user->roleChecker()==true)
        <div class="card m-3">
        <p class="card-header h3">Статистика</p>
        <div class="card-body row justify-content-md-start">
            <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="width:30%;">
                <div class="card-header">Всего обработано заявок:</div>
                <div class="card-body"><canvas id="myChart2"></canvas></div>
            </div>
            <div style="width: 70%">
                <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="">
                    <div class="card-header">Количество утверждённых договоров:</div>
                    <div class="card-body"><canvas id="myChart"></canvas></div>
                </div>
                <div class="p-2 bd-highlight card bg-light mb-1 border border-dark" style="">
                    <div class="card-header">Сумма, на которую утвердил договоры:</div>
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

                    labels: ['Сотрудник','Другие'],
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
    @endif
@endsection
