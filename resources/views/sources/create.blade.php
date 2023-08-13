<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6" id="src_form">
    <h5 class="mb-3">Ajouter une source</h5>
    @csrf
    @method('POST')
    <div class="mb-3">
        <label for="" class="form-label">libellé</label>
        <input type="text" name="label" required class="form-control shadow-none" id="">
    </div>


    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("#src_form").on("submit", (e) => {
        e.preventDefault();
        axios.post("{{ route('sources.store') }}", $("#src_form").serialize()).then((res) => {
            $("#src_form").trigger("reset")
            Swal.fire("Succès", "Source bien ajoutée", "success");
        }).catch((err) => {
            Swal.fire("Erreur", "L'opération est échouée,veuillez ressayer.", "error")
            console.error(err);
        })
    })
</script>
