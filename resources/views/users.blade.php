@extends('layouts.app-master')
@section('title','Сотрудники биржевые маклеры')
@section('content')
    @if (session()->has('Status'))
        <div class="alert alert-success">
            <strong>Успешно!</strong> {{ session()->get('Status') }}
        </div>
    @endif
    <div class="d-flex justify-content-between m-3">
        <p class="col-8 h3">Список биржевых маклеров</p>
        <div>
            <button class="btn btn-dark" id="stockInfo" data-bs-toggle="modal" data-bs-target='#AddProfile'>Добавить</button>
        </div>
        <div>
            <a class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#UserChangePass">Изменить пароль сотрудника</a>
        </div>

    </div>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th scope="col">ФИО</th>
            <th scope="col">Телефон</th>
            <th scope="col">Пароль</th>
            <th scope="col">Адрес</th>
            <th scope="col">Работает с</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $c)
            <tr>
                <td scope="row">{{$c['fullname']." ".$c['name']." ".$c['middlename']}}</td>
                <td>{{$c['phone']}}</td>
                <td>{{$c['password']}}</td>
                <td>{{$c['adress']}}</td>
                <td>{{$c['workdate']}}</td>
                <td class="align-self-center">
                    <a class="btn btn-outline-primary" href="{{route('user.profile',$c['id'])}}"><i class="fa-solid fa-circle-info"></i></a>
                    <a class="btn btn-outline-danger" href="{{route('user.delete',$c['id'])}}" onclick="return confirm('Вы уверены?')"><i class="fa-solid fa-circle-minus"></i></a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="AddProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-black">Добавить биржевого маклера</h3>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('user.add') }}">
                        @csrf
                        @if ($errors->has('fullname') or $errors->has('name') or $errors->has('middlename') or $errors->has('u_phone') or $errors->has('birthday') or $errors->has('adress') or $errors->has('workdate') or $errors->has('u_password'))

                            <script language="JavaScript" type="text/javascript">

                                jQuery(document).ready(function($) {
                                    $('#AddProfile').modal('show');
                                });

                            </script>
                            <div class="alert alert-danger" role="alert">
                                <ul class="list-unstyled mb-0">
                                    <li>Неверные данные. Попробуйте ещё раз </li>
                                </ul>
                            </div>
                        @endif
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control {{$errors->has('fullname') ? ' is-invalid' : null}}" name="fullname" value="{{old('fullname')}}" placeholder="fullname">
                            <label class="form-label" for="fullname">Фамилия</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control {{$errors->has('name') ? ' is-invalid' : null}}" name="name" value="{{old('name')}}" placeholder="name">
                            <label class="form-label" for="name">Имя</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control {{$errors->has('middlename') ? ' is-invalid' : null}}" name="middlename" value="{{old('middlename')}}" placeholder="middlename">
                            <label class="form-label" for="middlename">Отчество</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="tel" class="form-control {{$errors->has('u_phone') ? ' is-invalid' : null}}" name="u_phone" value="{{old('u_phone')}}" placeholder="phone">
                            <label class="form-label" for="u_phone">Телефон</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="password" class="form-control {{$errors->has('u_password') ? ' is-invalid' : null}}" name="u_password" value="{{old('u_password')}}" placeholder="phone">
                            <label class="form-label" for="u_phone">Пароль для входа</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="text" class="form-control {{$errors->has('adress') ? ' is-invalid' : null}}" name="adress" value="{{old('adress')}}" placeholder="adress">
                            <label class="form-label" for="adress">Адрес</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="date" class="form-control {{$errors->has('workdate') ? ' is-invalid' : null}}" name="workdate" value="{{old('workdate')}}" placeholder="workdate">
                            <label class="form-label" for="old_fullname">Дата вступления на должность</label>
                        </div>
                        <div class="form-group form-floating mb-3">
                            <input type="date" class="form-control {{$errors->has('birthday') ? ' is-invalid' : null}}" name="birthday" value="{{old('birthday')}}" placeholder="birthday">
                            <label class="form-label" for="old_fullname">День рождения</label>
                        </div>
                        <div class="modal-footer">
                            <button class="w-100 btn btn-lg btn btn-dark" type="submit">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="UserChangePass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-black">Изменить пароль сотрудника</h3>
                    <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('user.chPassword') }}">
                        @if ($errors->has('user_phone') or $errors->has('user_password'))
                            <script language="JavaScript" type="text/javascript">

                                jQuery(document).ready(function($) {
                                    $('#UserChangePass').modal('show');
                                });

                            </script>
                            <div class="alert alert-danger" role="alert">
                                <ul class="list-unstyled mb-0">
                                    <li>Неверные данные. Попробуйте ещё раз</li>
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group form-floating mb-3">
                            <input type="tel" class="form-control {{$errors->has('user_phone') ? ' is-invalid' : null}}" name="user_phone" value="{{ old('user_phone') }}" placeholder="username">
                            <label class="form-label" for="user_phone">Номер телефона сотрудника</label>
                        </div>

                        <div class="form-group form-floating mb-3">

                            <input type="password" class="form-control {{$errors->has('new_password') ? ' is-invalid' : null}}" name="user_password" value="{{ old('user_password') }}" placeholder="new_password">
                            <label class="form-label" for="user_password">Новый пароль</label>
                        </div>



                        <div class="modal-footer">
                            <button class="w-100 btn btn-lg btn btn-dark" type="submit">Изменить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
