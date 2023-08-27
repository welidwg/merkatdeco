<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6 text-size-md w-100" id="addAccountForm"
    enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="" class="form-label">Login</label>
                <input type="text" required name="login" value="" class="form-control text-size-md shadow-none"
                    id="">
            </div>
        </div>


        <div class="col-12">
            <div class="mb-3">
                <label for="" class="form-label">Mot de passe</label>
                <input type="password" name="password" value="" class="form-control text-size-md shadow-none"
                    id="">
            </div>
        </div>
        <div class="col-12">

            <div class="mb-3">
                <label for="" class="form-label">Role</label>

                <select class="form-select text-size-md" id="" name="role">
                    <option value="0">Admin
                    </option>
                    <option value="1">Logistique
                    </option>
                    <option value="2">Fournisseur
                    </option>
                </select>

            </div>
        </div>


    </div>



    <button type="submit" class="btn btn-primary float-end" id="add_btn_prod">Ajouter</button>
</form>
<script>
    $("#addAccountForm").on("submit", (e) => {
        e.preventDefault()
        axios.post("{{ route('accounts.store') }}", $("#addAccountForm").serialize())
            .then(res => {
                $("#addAccountForm").trigger("reset")
                Swal.fire('Succès', "Le compte est bien ajouté.", "success")
                $("#table_container").load("{{ route('accounts.table') }}")


            })
            .catch(err => {
                console.error(err.response.data.error);
                Swal.fire('Erreur', "L'opération est échouée.<br>Message: " + err.response.data.error,
                    "error")

            })
    })
</script>
