<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6" id="cat_form">
    <h5 class="mb-3">Ajouter une catégorie du commande</h5>
    @csrf
    @method('POST')
    <div class="mb-3">
        <label for="" class="form-label">libellé</label>
        <input type="text" name="label" required class="form-control shadow-none" id="">
    </div>
    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("#cat_form").on("submit", (e) => {
        e.preventDefault();
        axios.post("{{ route('order_cats.store') }}", $("#cat_form").serialize()).then((res) => {
            $("#cat_form").trigger("reset")
            console.log(res);
            Swal.fire("Succès", "Catégorie bien ajoutée", "success");
        }).catch((err) => {
            Swal.fire("Erreur", "L'opération est échouée,veuillez ressayer.", "error")
            console.error(err);
        })
    })
</script>
