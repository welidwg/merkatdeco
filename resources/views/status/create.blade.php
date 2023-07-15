<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6" id="status_form">
    <h5 class="mb-3">Ajouter un status</h5>
    @csrf
    @method('POST')
    <div class="mb-3">
        <label for="" class="form-label">libellé</label>
        <input type="text" name="label" required class="form-control shadow-none" id="">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">couleur</label>
        <select name="class" id="" class="form-control">
            <option value="primary">Bleu</option>
            <option value="danger">Rouge</option>
            <option value="success">Vert</option>
            <option value="warning">Orangé</option>
        </select>
    </div>


    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("#status_form").on("submit", (e) => {
        e.preventDefault();
        axios.post("{{ route('status.store') }}", $("#status_form").serialize()).then((res) => {
            $("#status_form").trigger("reset")
            console.log(res);
            Swal.fire("Succès", "Status bien ajouté", "success");
        }).catch((err) => {
            Swal.fire("Erreur", "L'opération est échouée,veuillez ressayer.", "error")
            console.error(err);
        })
    })
</script>
