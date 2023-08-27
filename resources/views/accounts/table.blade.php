<table class="table my-0 " id="table_index_accounts">
    <thead>
        <tr>
            <th>Login</th>
            <th>Role</th>

            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr>
                <td>{{ $account->login }}</td>
                <td>{{ $account->getRole($account->role) }}
                </td>
                <td>
                    <form action="{{ route('accounts.destroy', $account) }}" class="d-flex align-items-center "
                        id="form_delete_account{{ $account->id }}">
                        @csrf
                        @method('DELETE')
                        <a data-bs-toggle="offcanvas" data-bs-target="#canvas_{{ $account->id }}" class="text-primary "><i
                                class="far fa-eye "></i></a>
                        <button onclick="return confirm('Vous êtes sûr ?')" type="submit" href="#"
                            class="text-danger btn"><i class="far fa-times-circle "></i></i></a>
                    </form>
                    <script>
                        $("#form_delete_account{{ $account->id }}").on("submit", (e) => {
                            e.preventDefault();
                            axios.delete(e.target.action)
                                .then(res => {
                                    Swal.fire("Suppression réussite !", "", "success")
                                    $("#table_container").load("{{ route('accounts.table') }}")

                                })
                                .catch(err => {
                                    console.error(err);
                                })
                        });
                    </script>
                </td>

            </tr>
        @endforeach


    </tbody>
</table>
@foreach ($accounts as $account)
    <div class="offcanvas offcanvas-end text-size-md" data-bs-scroll="true" tabindex="-1" style="width: 600px"
        id="canvas_{{ $account->id }}" aria-labelledby="Enable both scrolling & backdrop">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">{{ $account->login }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex align-items-start">
            <form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6 text-size-md w-100"
                id="editAccountForm{{ $account->id }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Login</label>
                            <input type="text" required name="login" value="{{ $account->login }}"
                                class="form-control text-size-md shadow-none" id="">
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="mb-3">
                            <label for="" class="form-label">Role</label>

                            <select class="form-select text-size-md" id="" name="role">
                                <option value="{{ $account->role }}">{{ $account->getRole($account->role) }}
                                </option>
                                @if ($account->role != 0)
                                    <option value="0">Admin
                                    </option>
                                @endif
                                @if ($account->role != 1)
                                    <option value="1">Logistique
                                    </option>
                                @endif
                                @if ($account->role != 2)
                                    <option value="2">Fournisseur
                                    </option>
                                @endif

                            </select>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_pass" value=""
                                class="form-control text-size-md shadow-none" id="">
                        </div>
                    </div>



                </div>



                <button type="submit" class="btn btn-primary float-end" id="add_btn_prod">Enregistrer</button>
            </form>
            <script>
                $("#editAccountForm{{ $account->id }}").on("submit", (e) => {
                    e.preventDefault();
                    axios.post("{{ route('accounts.update', $account) }}", $("#editAccountForm{{ $account->id }}")
                            .serialize())
                        .then(res => {
                            Swal.fire('Succès', "Le compte est bien modifié.", "success")

                            $("#table_container").load("{{ route('accounts.table') }}")

                        })
                        .catch(err => {
                            console.error(err.response.data);
                            Swal.fire('Erreur', "L'opération est échouée.<br>Message: " + err.response.data.error,
                                "error")

                        })
                })
            </script>
        </div>
    </div>
@endforeach
