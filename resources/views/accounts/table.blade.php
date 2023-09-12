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
                        <a data-bs-toggle="offcanvas" data-bs-target="#canvas_{{ $account->id }}"
                            class="text-primary mx-1"><i class="far fa-eye "></i></a>
                        @if ($account->role == 2)
                            <a data-bs-toggle="offcanvas" data-bs-target="#canvas_fournisseur_{{ $account->id }}"
                                class="text-primary mx-1"><i class="far fa-wallet" aria-hidden="true"></i></a>
                            <a data-bs-toggle="offcanvas" data-bs-target="#canvas_fournisseur_hist_{{ $account->id }}"
                                class="text-primary mx-1"><i class="far fa-list" aria-hidden="true"></i></a>
                        @endif
                        <button onclick="return confirm('Vous êtes sûr ?')" type="submit" href="#"
                            class="text-danger btn p-0 mx-1"><i class="far fa-times-circle "></i></i></a>
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
    @if ($account->role == 2)
        @php
            
            $total = 0;
        @endphp
        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
            id="canvas_fournisseur_{{ $account->id }}">
            <div class="offcanvas-header">
                <h5 class="" id="Enable both scrolling & backdrop">Transactions du fournisseur
                    <strong class="text-dark">{{ $account->login }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @php
                    $tres = 0;
                    
                @endphp
                @forelse ($account->prestations as $pres)
                    <div class="p-4 rounded-3 shadow-sm d-flex mb-3   justify-content-between text-dark">
                        <div class="d-flex flex-column justify-content-between mb-2">
                            <div><strong>Date d'affectation</strong> : {{ $pres->start_date }} </div>
                            <div><strong>Terminée le </strong> : {{ $pres->end_date }} </div>
                        </div>
                        @php
                            
                            foreach (json_decode($pres->pieces) as $piece) {
                                $total += $piece->price * $piece->qte;
                            }
                            
                        @endphp
                        <div class="d-flex flex-column justify-content-center align-self-end">
                            <span> <strong>Total :</strong> {{ $total }} TND</span>
                            <span> <strong>Avance :</strong> {{ $pres->advance }} TND</span>
                            @php
                                $rest = $total - $pres->advance;
                                $tres += $rest;
                            @endphp
                            <span> <strong>Reste : </strong> <span
                                    class="fw-bold {{ $rest > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ $rest > 0 ? '-' . $rest : '+' . abs($rest) }}
                                </span>
                                TND</span>

                        </div>
                    </div>
                    @php
                        $total = 0;
                    @endphp
                @empty
                    <div class="p-4 rounded-3 shadow-sm d-flex  justify-content-between">
                        <div>Aucune prestation</div>
                    </div>
                @endforelse
                <hr>
                <div class="px-4 py-2 rounded-3 shadow-sm d-flex mb-3   justify-content-between text-dark">
                    <strong class="text-dark">Solde : </strong> <span
                        class="fw-bold {{ $tres > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $tres > 0 ? ' - ' . $tres : ' + ' . abs($tres) }} TND</span>
                </div>


            </div>
        </div>
        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
            id="canvas_fournisseur_hist_{{ $account->id }}">
            <div class="offcanvas-header">
                <h5 class="" id="Enable both scrolling & backdrop">Historique du fournisseur
                    <strong class="text-dark">{{ $account->login }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                @forelse ($account->prestations as $sub)
                    <div class="p-4 rounded-3 shadow-sm d-flex flex-column justify-content-between mb-3">

                        <form id="edit_suborder_form_{{ $sub->id }}">

                            @csrf
                            @method('PUT')

                            <div class="row col-12  " id="subcommand">


                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Date du
                                            prestation</label>
                                        <input type="date" readonly
                                            value="{{ date('Y-m-d', strtotime($sub->start_date)) }}"
                                            name="start_date" class="form-control shadow-none" id="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Date prévue </label>
                                        <input type="date"
                                            value="{{ $sub->predicted_date != null ? date('Y-m-d', strtotime($sub->predicted_date)) : '' }}"
                                            name="predicted_date" class="form-control shadow-none" id="">
                                    </div>
                                </div>

                                @if ($sub->end_date != null)
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Terminée le
                                            </label>
                                            <input type="date" readonly
                                                value="{{ date('Y-m-d', strtotime($sub->end_date)) }}"
                                                name="end_date" class="form-control shadow-none bg-light"
                                                id="">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Status
                                            <i class="fas fa-circle text-{{ $sub->status->class }}"
                                                aria-hidden="true" style="font-size: 9px"></i>
                                        </label>
                                        <select class="form-select " id="" name="status_id">

                                            <option selected value="{{ $sub->status->id }}"
                                                class="text-{{ $sub->status->class }}">
                                                {{ $sub->status->label }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Avance</label>
                                        <input type="number" name="advance" class="form-control shadow-none"
                                            id="" value="{{ $sub->advance }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    @php
                                        $total_1 = 0;
                                    @endphp
                                    <div class="mb-3 text-size-md">
                                        <label for="" class="form-label">Pièces </label>
                                        <div class="row">
                                            <div class="col-6 col-lg-3 mb-3">
                                                <span for="" class=" text-size-md">Nom du
                                                    Pièce</span>

                                            </div>
                                            <div class="col-6 col-lg-2 mb-3">
                                                <span for="" class=" text-sm">Quantité</span>

                                            </div>
                                            <div class="col-6 col-lg-2 mb-3">
                                                <span for="" class=" text-sm">Prix</span>

                                            </div>
                                            <div class="col-6 col-lg-5 mb-3">
                                                <span for="" class=" text-sm">description</span>

                                            </div>
                                        </div>
                                        @foreach (json_decode($sub->pieces) as $piece)
                                            <div class="row">
                                                <div class="col-6 col-lg-3">
                                                    <input type="text" name="pieces{{ $sub->id }}[]"
                                                        placeholder="nom du pièce" value="{{ $piece->piece }}"
                                                        class="form-control shadow-none text-size-md  mb-3"
                                                        id="">
                                                </div>
                                                <div class="col-6 col-lg-2">
                                                    <input type="number" min="1"
                                                        name="qtes_pieces{{ $sub->id }}[]" placeholder=""
                                                        value="{{ $piece->qte }}"
                                                        class="form-control   text-size-md shadow-none  mb-3"
                                                        id="">
                                                </div>
                                                <div class="col-6 col-lg-2">
                                                    <input type="number" min="1"
                                                        name="price_pieces_{{ $sub->id }}[]" placeholder=""
                                                        value="{{ $piece->price }}"
                                                        class="form-control   text-size-md shadow-none  mb-3"
                                                        id="">
                                                </div>
                                                <div class="col-6 col-lg-5">

                                                    <div class=" d-flex align-items-center">
                                                        <input type="text" min="1"
                                                            name="desc_pieces_{{ $sub->id }}[]"
                                                            placeholder="description" value="{{ $piece->desc }}"
                                                            class="form-control   text-size-md shadow-none  mb-3"
                                                            id="">

                                                    </div>

                                                </div>
                                                <hr class="d-lg-none">
                                                @php
                                                    $total_1 += $piece->price * $piece->qte;
                                                @endphp
                                            </div>
                                        @endforeach



                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <span for="" class="text-size-md float-end">
                                        <strong>Total:</strong>
                                        {{ $total_1 }}
                                        TND</span>
                                </div>

                            </div>
                        </form>

                    </div>
                @empty
                    <div class="p-4 rounded-3 shadow-sm d-flex  justify-content-between">
                        <div>Aucune historique</div>
                    </div>
                @endforelse



            </div>
        </div>
    @endif
@endforeach
<script>
    $("#table_index_accounts").dataTable({
        searching: true,
        info: false
    })
</script>
