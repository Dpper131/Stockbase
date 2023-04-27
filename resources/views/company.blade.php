@extends('layouts.app-master')
@section('title','Компании')
@section('content')
    @if (session()->has('deleteStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('deleteStatus') }}
        </div>
    @endif
    @if ($errors->has('e_owner') || $errors->has('e_name') || $errors->has('e_phone') || $errors->has('e_adress') || $errors->has('company_name') || $errors->has('phone') || $errors->has('adress') || $errors->has('fio'))
        <div class="alert alert-danger">
            <strong>ОШИБКА!</strong><br>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    @if (session()->has('CompanyStatus'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('CompanyStatus') }}
        </div>
@endif
            <div class="m-3"><p class="h3">Добавить:</p>
                <form class="row row-cols-lg-auto g-3 mb-lg-3 align-items-center" method="post" action="{{ route('addCompany') }}">
                    @csrf
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control" id="company_name"  name="company_name" placeholder="company name" />
                            <label for="company_name" class="form-label">Название компании</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="adress" name="adress" placeholder="adress" />
                            <label for="adress" class="form-label">Адрес</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" type="text" id="fio" name="fio" placeholder="fio" required>
                            <label class="form-label" for="count">ФИО владельца</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" type="tel" name="phone" id="phone" placeholder="phone" required>
                            <label class="form-label" for="count">Номер телефона</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">Добавить</button>
                    </div>
                </form>
            </div>

    <div class="d-flex justify-content-between m-3">
        <p class="h3">Список компаний</p>
    </div>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th scope="col">Название</th>
            <th scope="col">Адрес</th>
            <th scope="col">Владелец</th>
            <th scope="col">Номер телефона</th>
            <th scope="col">Добавлена</th>
        </tr>
        </thead>
        <tbody>
        @if($companies)
        @foreach($companies as $c)
            <tr>
                <td scope="row">{{$c['name']}}</td>
                <td>{{$c['adress']}}</td>
                <td>{{$c['owner']}}</td>
                <td>{{$c['phone']}}</td>
                <td>{{$c['date']}}</td>
                <td class="align-self-center">
                    <button class="btn btn-outline-primary" id="changeinfo" data-toggle="modal" data-target="#edit_modal" data-id="{{$c['name']}}"><i class="fa-solid fa-circle-question"></i></button>
                    <a class="btn btn-outline-danger" href="{{route('company.delete',$c['name'])}}" onclick="return confirm('Вы уверены?')"><i class="fa-solid fa-circle-minus"></i></a>

                </td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-black">Изменить компанию</h3>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('company.edit') }}">
                        @csrf
                        <input type="hidden" id="old_name" name="old_name" value="" placeholder="old name">
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control" id="e_name" name="e_name" value="" placeholder="name">
                            <label class="form-label" for="e_name">Название</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control" id="e_adress" name="e_adress" value="" placeholder="adress">
                            <label class="form-label" for="e_adress">Адрес</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control" id="e_owner" name="e_owner" value="" placeholder="owner">
                            <label class="form-label" for="middlename">ФИО владельца</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="tel" class="form-control" id="e_phone" name="e_phone" value="" placeholder="phone">
                            <label class="form-label" for="phone">Номер телефона</label>
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
    <script>
        $(document).ready(function () {

            $('body').on('click', '#changeinfo', function (event) {
                event.preventDefault();
                var id = $(this).data('id');
                $.get('company/' + id, function (data) {
                    if(data!=null){
                        data=data['company'];
                        $('#e_adress').val(data.adress);
                        $('#e_owner').val(data.owner);
                        $('#e_phone').val(data.phone);
                        $('#e_name').val(data.name);
                        $('#old_name').val(data.name);
                    }
                    $('#edit_modal').modal('show');

                })
            });
        });
    </script>
@endsection
