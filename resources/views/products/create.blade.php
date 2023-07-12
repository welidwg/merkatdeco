<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6">
    <h5 class="mb-3 fw-bold">Ajouter un produit</h5>

    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Nom</label>
                <input type="text" name="title" class="form-control shadow-none" id="">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Stock</label>
                <input type="number" min="0" value="0" name="stock" class="form-control shadow-none"
                    id="">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Catégorie</label>
                <select class="form-select">
                    <option value="" selected>expander</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Sous-catégorie</label>
                <select class="form-select" disabled>
                    <option value="1">----</option>

                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label for="" class="form-label">Mesures <a id="add_measures"><i class="fas fa-plus"
                            aria-hidden="true"></i></a></label>
                <div class="row" id="measures_content">
                    <div class=" row mb-3">
                        <div class="col-7">
                            <input type="text" placeholder="ex: 181 x 106 cm" name="measures"
                                class="form-control shadow-none" id="">
                        </div>
                        <div class="col-5">
                            <input type="number" step="0.1" placeholder="prix" min="0" name="price"
                                class="form-control shadow-none" id="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label for="" class="form-label">Caractéristiques <a id="add_attr"><i class="fas fa-plus"
                            aria-hidden="true"></i></a></label>
                <div class="row" id="attr_content">

                </div>

            </div>
        </div>
    </div>



    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("input,select,option,label,a").addClass("shadow-none text-size-md");

    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }
    $("#add_attr").on("click", (e) => {
        $("#attr_content").append(`
          <div class=" row ">
                     <div class="col-6">
                        <input type="text" placeholder="attribut" name="attribute"
                            class="form-control text-size-md shadow-none" id="">
                    </div>
                    <div class="col-6">
                       
                            <div class="input-group mb-3 "  >
  <input type="text" placeholder="valeur" name="value"
                            class="form-control text-size-md shadow-none" id="">
  <span onclick="RemoveParent(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                    </div>
                   </div>
        `)
    })
    $("#add_measures").on("click", (e) => {
        $('#measures_content').append(`
  <div class=" row ">
                     <div class="col-7">
                        <input type="text" placeholder="ex: 181 x 106 cm" name="measures"
                            class="form-control shadow-none text-size-md" id="">
                    </div>
                    <div class="col-5">
                       
                            <div class="input-group mb-3"  >
  <input type="number" step="0.1" placeholder="prix" min="0" name="price"
                            class="form-control text-size-md shadow-none" id="">
  <span onclick="RemoveParent(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                    </div>
                   </div>
`)
    })
</script>
