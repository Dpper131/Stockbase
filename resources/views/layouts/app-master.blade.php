<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{url('img/app.png')}}" size="72x72">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <link
        href="{{url('boostrap.css')}}"
        rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.1.0/mdb.min.css"
        rel="stylesheet"
    />
    <style>
        .dropdown-item:hover {background-color: #888b88 !important;}
        .list-group-item:hover {background-color: #888b88 !important;}
        .form-control:focus {
            border-color: #241e24;
            box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(23, 16, 23, 0.5);
        }
    </style>
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="{{url('bootstrap.js')}}"></script>
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.1.0/mdb.min.js"
></script>
<div class="d-flex" id="wrapper">
@auth
    <!-- Sidebar-->
        <div class="bg-dark d-flex flex-column" id="sidebar-wrapper">
            <div class="align-items-center p-2">
                <a class="list-group-item list-group-item-action p-3 bg-dark text-white" href="{{route('home.index')}}">
                    <i class="fa-solid fa-house-user"></i>
                    Главная
                </a>
                @if(auth()->user()->roleChecker() == true)
                    <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="{{route('appl.index')}}">
                        <i class="fa-solid fa-table"></i>
                        Заявки
                    </a>
                @endif
                <a class="list-group-item list-group-item-action p-3 bg-dark text-white" href="{{route('stocks.index')}}">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    Акции
                </a>
                @if(auth()->user()->roleChecker() == false)
                    <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="{{route('company.index')}}">
                        <i class="fa-solid fa-building"></i>
                        Компании
                    </a>
                    <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="{{route('users')}}">
                        <i class="fa-solid fa-users"></i>
                        Сотрудники
                    </a>
                @endif
            </div>
            <div class="list-group mt-auto p-2">
                <div class="bg-dark text-white">
                    Вы вошли как:<br> {{auth()->user()->localName()}}
                </div>
                <div class="bg-dark text-white">
                    <br>Уровень прав:<br> {{auth()->user()->position}}
                </div>
            </div>
        </div>
        <!-- Page content wrapper-->
    @endauth
    <div id="page-content-wrapper">
        <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-dark text-white border-bottom">
            <div class="container-fluid">
                @auth <button class="btn btn-sm order-1 order-lg-0 me-4 me-lg-0 bg-dark text-white" id="sidebarToggle"><i class="fa fa-bars" aria-hidden="true"></i></button>@endauth

                    <a href="/" class="navbar-brand text-white align-items-center">
                    <img src="{{url('img/app.png')}}" alt="logo" style="-webkit-filter: invert(1);filter: invert(1);" width="36" height="36"/>
                        <p class="h3 pt-sm-1">Stockbase</p>
                    </a>

                <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">

                            @auth
                                <div class="btn-group dropstart">
                                    <button class="btn btn-outline-light  m-auto" type="button" id="dropdownMenu" data-mdb-toggle="dropdown" aria-expanded="false">
                                        <img type="button" src="{{url('img/user.png')}}" alt="userlogo" style="-webkit-filter: invert(1);filter: invert(1);" width="16" height="16">
                                    </button>
                                    <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenu">
                                        <li><a class="dropdown-item text-white" type="button" href="{{ route('user.profile',auth()->user()->id)}}">Профиль</a></li>
                                        <li><a class="dropdown-item text-white" type="button" data-bs-toggle="modal" data-bs-target="#ChangePassword">Сменить пароль</a></li>
                                        <li><a class="dropdown-item text-white" type="button" href="{{ route('logout.perform') }}" >Выйти</a></li>
                                    </ul>
                                </div>
                                @extends('modal.changePasswordModal')
                            @endauth

                            @guest
                                <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#LoginModal">
                                    Войти
                                </button>
                                    @extends('modal.loginModal')
                                    <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#ApplModal">
                                     Проверить заявки
                                    </button>
                                @extends('modal.applListModal')
                            @endguest
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
        <div>@yield('content')</div>
    </div>
</div>



<script>
    window.addEventListener('DOMContentLoaded', event => {

        // Toggle the side navigation
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            // Uncomment Below to persist sidebar toggle between refreshes
            //if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            //    document.body.classList.toggle('sb-sidenav-toggled');
            //}
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }

    });
</script>


</body>
</html>
