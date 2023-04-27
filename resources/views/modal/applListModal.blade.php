
<!-- Modal -->
<div class="modal fade" id="ApplModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close bg-dark text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="get" action="{{ route('client.redirect')}}">
                    @if ($errors->has('uuid'))
                        <script language="JavaScript" type="text/javascript">

                            jQuery(document).ready(function($) {
                                $('#ApplModal').modal('show');
                            });

                        </script>
                        <div class="alert alert-danger" role="alert">
                            <ul class="list-unstyled mb-0">
                                <li>Неверные данные. Попробуйте ещё раз</li>
                            </ul>
                        </div>
                    @endif
                    <div class="form-group form-floating mb-3">
                        <input type="text" class="form-control {{$errors->has('uuid') ? ' is-invalid' : null}}" name="uuid" value="{{ old('uuid') }}" placeholder="username">
                        <label class="form-label" for="uuid">Ваш уникальный идентификатор</label>
                    </div>


                    <div class="modal-footer">
                        <button class="w-100 btn btn-lg btn btn-dark" type="submit">Просмотреть заявки</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
