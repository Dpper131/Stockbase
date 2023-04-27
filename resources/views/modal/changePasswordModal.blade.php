<div class="modal fade" id="ChangePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-black">Сменить пароль</h3>
                <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('changePassword') }}">
                    @csrf
                    @if ($errors->has('old_password') or $errors->has('new_password') or $errors->has('oldPasswordNotValid'))

                        <script language="JavaScript" type="text/javascript">

                            jQuery(document).ready(function($) {
                                $('#ChangePassword').modal('show');
                            });

                        </script>
                        <div class="alert alert-danger" role="alert">
                            <ul class="list-unstyled mb-0">
                                <li>Неверные данные. Попробуйте ещё раз </li>
                            </ul>
                        </div>
                    @endif
                    <div class="form-group form-floating mb-3">
                        <input type="password" class="form-control {{$errors->has('old_password') ? ' is-invalid' : null}}" name="old_password" value="{{ old('old_password') }}" placeholder="old password">
                        <label class="form-label" for="phone">Старый пароль</label>
                    </div>

                    <div class="form-group form-floating mb-3">

                        <input type="password" class="form-control {{$errors->has('new_password') ? ' is-invalid' : null}}" name="new_password" placeholder="new password">
                        <label class="form-label" for="new_password">Новый пароль</label>
                    </div>

                    <div class="form-group form-floating mb-3">

                        <input type="password" class="form-control" name="new_password_confirmation" placeholder="new password confirm">
                        <label class="form-label" for="new_password_confirmation">Повторите новый пароль</label>
                    </div>
                    <div class="modal-footer">
                        <button class="w-100 btn btn-lg btn btn-dark" type="submit">Сменить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
