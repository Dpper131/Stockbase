
<!-- Modal -->
<div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-black">Войти</h3>
                <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('login.perform') }}">
                    @if ($errors->has('phone') or $errors->has('password') or $errors->has('notLogged'))
                            <script language="JavaScript" type="text/javascript">

                                jQuery(document).ready(function($) {
                                    $('#LoginModal').modal('show');
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
                        <input type="tel" class="form-control {{$errors->has('phone') ? ' is-invalid' : null}}" name="phone" value="{{ old('phone') }}" placeholder="username">
                        <label class="form-label" for="phone">Номер телефона</label>
                    </div>

                    <div class="form-group form-floating mb-3">

                        <input type="password" class="form-control {{$errors->has('password') ? ' is-invalid' : null}}" name="password" value="{{ old('password') }}" placeholder="password">
                        <label class="form-label" for="password">Пароль</label>
                    </div>

                    <div class="form-group mb-3">
                        <input type="checkbox" class="form-check-input" name="remember">
                        <label for="remember" class="form-label">Запомнить?</label>
                    </div>



                    <div class="modal-footer">
                        <button class="w-100 btn btn-lg btn btn-dark" type="submit">Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
