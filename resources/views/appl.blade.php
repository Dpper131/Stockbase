@extends('layouts.app-master')
@section('title','Заявки')
@section('content')
    @if ($errors->has('by_id') || $errors->has('sll_id'))
        <div class="alert alert-danger">
            <strong>ОШИБКА!</strong><br>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    @if (session()->has('ContrStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('ContrStatus') }}
        </div>
    @endif
    @if($uuid !=null)
        @if ($errors->has('uuid') || $errors->has('count') || $errors->has('price') || $errors->has('vendor_code'))
            <div class="alert alert-danger">
                <strong>ОШИБКА!</strong><br>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        @if (session()->has('ApplStatus'))
            <div class="alert alert-success">
                <strong>Успешно!</strong> {{ session()->get('CompanyStatus') }}
            </div>
        @endif
        @if (session()->has('deleteStatus'))
            <div class="alert alert-success">
                <strong>Успешно!</strong> {{ session()->get('deleteStatus') }}
            </div>
        @endif
    <div class="m-3"><p class="h3">Добавить заявку:</p>
        <form class="row row-cols-lg-auto g-3 mb-lg-3 align-items-center" method="post" action="{{ route('addAppl') }}">
            @csrf
            <input type="hidden" name="uuid" value="{{$uuid}}" />
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" class="form-control form-control" id="type"  name="type" placeholder="type" />
                    <label for="type" class="form-label">Тип</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating">
                    <input type="number" class="form-control" id="vendor__code" name="vendor__code" placeholder="vendor code" />
                    <label for="vendor__code" class="form-label">Код ценной бумаги</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating">
                    <input class="form-control" type="number" id="ccount" name="ccount" placeholder="ccount" required>
                    <label class="form-label" for="ccount">Количество</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <input class="form-control" type="number" name="price" id="price" placeholder="price" required>
                    <label class="form-label" for="price">Цена за штуку</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-dark">Добавить</button>
            </div>
        </form>
    </div>
        @endif
    <div class="d-flex justify-content-between m-3">
        <p class="h3">Список заявок {{$uuid == null ? null :   \App\Models\Client::localName($uuid)}}</p>
        <div class="dropdown">
            <button
                class="btn btn-sm btn-dark dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-mdb-toggle="dropdown"
                aria-expanded="false"
            >
                Статус
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="?type=all">Все</a></li>
                <li><a class="dropdown-item" href="?type=yes">Согласовано</a></li>
                <li><a class="dropdown-item" href="?type=no">Не согласовано</a></li>
            </ul>
        </div>
    </div>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            <th scope="col">Тип предложения</th>
            <th scope="col">Ценная бумага</th>
            <th scope="col">Количество ценной бумаги</th>
            <th scope="col">Цена за штуку</th>
            <th scope="col">Дата</th>
        </tr>
        </thead>
        <tbody>
        @if($app)
        @foreach($app as $a)
            <tr>
                <td scope="row">{{$a['id']}}</td>
                <td>{{$a['type']}}</td>
                <td>{{\App\Models\Stock::where(['vendor_code'=>$a['vendor_code']])->first()->StockName()}}</td>
                <td>{{$a['count']}}</td>
                <td>{{round($a['fixed_price'],3)}}</td>
                <td>{{$a['date']}}</td>
                @if($a['status'] =='ожидается')
                    <td class="align-self-center">
                        @if(auth()->user() != null)
                        <button class="btn btn-outline-dark" id="applcreateContract" data-toggle="modal" data-target='#appl_modal' data-id="{{ $a['id'] }}"><i class="fa-solid fa-circle-question"></i></button>
                        @endif
                        @if($uuid != null)
                        <a class="btn btn-outline-danger" href="{{route('appl.delete', [$uuid, 'id' =>$a['id']])}}" onclick="return confirm('Вы уверены?')"><i class="fa-solid fa-circle-minus"></i></a>
                        @endif
                    </td>

                @endif
                @if($a['status'] == 'согласовано')
                    <td> <button class="btn btn-outline-light bg-dark " id="applInfo" data-toggle="modal" data-target='#appl_modalInfo' data-id="{{ $a['id'] }}">Договор</button></td>
                @endif
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
    <div class="modal fade" id="appl_modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" style="max-width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="h3 modal-title text-black" id="title">Информация по договору заявки</p>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-8">Номер договора:</div>
                            <div class="col-sm-4" id="contract_id">#</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Код ценной бумаги:</div>
                            <div class="col-sm-4" id="vendor_code">#</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Номер заявки покупки:</div>
                            <div class="col-sm-4" id="buy_code">#</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Номер заявки продажи:</div>
                            <div class="col-sm-4" id="sell_code">#</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Сумма:</div>
                            <div class="col-sm-4" id="sum"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Количество ценных бумаг:</div>
                            <div class="col-sm-4" id="count"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Одобрил биржевый маклер:</div>
                            <div class="col-sm-4" id="fio"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">Дата создания договора:</div>
                            <div class="col-sm-4" id="date"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="appl_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" style="max-width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="h3 modal-title text-black" id="title">Проверка на возможность создания договора...</p>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('appl.perform') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-8">Возможно создать договор:</div>
                                <div class="col-sm-4 mb-3" id="cont_bool"></div>
                            <div class="row">
                                <div class="col-sm-8">Номер заявки покупки:</div>
                                <div class="col-sm-4">
                                    <div class="form-outline mb-1">
                                        <input type="number" class="form-control" id="by_id"  name="by_id" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Номер заявки продажи:</div>
                                <div class="col-sm-4">
                                    <div class="form-outline mb-1">
                                        <input type="number" class="form-control" id="sll_id" name="sll_id" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Номер ценной бумаги:</div>
                                <div class="col-sm-4">
                                    <div class="form-outline mb-1">
                                        <input type="number" class="form-control" id="v_code" name="v_code" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Количество:</div>
                                <div class="col-sm-4">
                                    <div class="form-outline mb-1">
                                        <input type="number" class="form-control" id="ccount" name="ccount" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Сумма:</div>
                                <div class="col-sm-4">
                                    <div class="form-outline mb-1">
                                        <input type="number" class="form-control" id="summ" name="summm" value="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-md-center">
                            <button class="btn btn-dark" id="btnCrCtrc" disabled type="submit">Создать договор</button>
                        </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    @if(auth()->user() != null)
    <script>
        $(document).ready(function () {

            $('body').on('click', '#applInfo', function (event) {
                $('#contract_id').text('#');
                $('#vendor_code').text('#');
                $('#buy_code').text('#');
                $('#sell_code').text('#');
                $('#sum').text("$");
                $('#count').text("");
                $('#fio').text("");
                $('#date').text("");
                event.preventDefault();
                var id = $(this).data('id');

                $.get('appl/' + id, function (data) {
                    data =data.app[0];
                    if(data != null) {
                        $('#contract_id').append(data.contract_id);
                        $('#vendor_code').append(data.vendor_code);
                        $('#buy_code').append(data.buy_id);
                        $('#sell_code').append(data.sell_id);
                        $('#sum').text(data.sum + "$");
                        $('#count').text(data.count);
                        $('#fio').text(data.name);
                        $('#date').text(data.date);
                    }
                    $('#appl_modalInfo').modal('show');

                })
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            $('body').on('click', '#applcreateContract', function (event) {
                var id = $(this).data('id');
                $.get('appl/contr/' + id, function (data) {
                    data =data.app[0][0];
                    console.log(data);
                    if(data.stat === "Да") {
                        $('#cont_bool').text(data.stat);
                        $('#by_id').val(data.buyid);
                        $('#sll_id').val(data.sellid);
                        $('#v_code').val(data.valid_code);
                        $('#ccount').val(data.count);
                        $('#summ').val(data.sum);
                        $('#btnCrCtrc').prop('disabled',false);
                    }
                    else{
                        $('#cont_bool').text(data.stat);
                        $('#by_id').val("");
                        $('#sll_id').val("");
                        $('#v_code').val("");
                        $('#ccount').val("");
                        $('#summ').val("");
                        $('#btnCrCtrc').prop('disabled',true);
                    }

                    $('#appl_modal').modal('show');
                })
            })

        });
    </script>
    @endif
    @if($uuid != null)
        <script>
            $(document).ready(function () {

                $('body').on('click', '#applInfo', function (event) {
                    $('#contract_id').text('#');
                    $('#vendor_code').text('#');
                    $('#buy_code').text('#');
                    $('#sell_code').text('#');
                    $('#sum').text("$");
                    $('#count').text("");
                    $('#fio').text("");
                    $('#date').text("");
                    event.preventDefault();
                    var id = $(this).data('id');

                    $.get('/client/{{$uuid}}/' + id + '/showContract', function (data) {
                        data =data.app[0];
                        if(data != null) {
                            $('#contract_id').append(data.contract_id);
                            $('#vendor_code').append(data.vendor_code);
                            $('#buy_code').append(data.buy_id);
                            $('#sell_code').append(data.sell_id);
                            $('#sum').text(data.sum + "$");
                            $('#count').text(data.count);
                            $('#fio').text(data.name);
                            $('#date').text(data.date);
                        }
                        $('#appl_modalInfo').modal('show');

                    })
                });
            });
        </script>

        <script>
            $(document).ready(function () {

                $('body').on('click', '#applcreateContract', function (event) {
                    var id = $(this).data('id');
                    console.log(id);
                    $.get('/client/{{$uuid}}/' + id + '/showContract', function (data) {
                        data =data.app[0][0];
                        console.log(data);
                        if(data.stat === "Да") {
                            $('#cont_bool').text(data.stat);
                            $('#by_id').val(data.buyid);
                            $('#sll_id').val(data.sellid);
                            $('#v_code').val(data.valid_code);
                            $('#ccount').val(data.count);
                            $('#summ').val(data.sum);
                            $('#btnCrCtrc').prop('disabled',false);
                        }
                        else{
                            $('#cont_bool').text(data.stat);
                            $('#by_id').val("");
                            $('#sll_id').val("");
                            $('#v_code').val("");
                            $('#ccount').val("");
                            $('#summ').val("");
                            $('#btnCrCtrc').prop('disabled',true);
                        }

                        $('#appl_modal').modal('show');
                    })
                })

            });
        </script>
    @endif
@endsection
