<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6">
    <h5 class="mb-3">Ajouter une categorie</h5>

    <div class="mb-3">
        <label for="" class="form-label">libellé</label>
        <input type="text" name="label" class="form-control shadow-none" id="">
    </div>
    <div class="mb-3 f">

        <button type="button" class="btn" id="sub_categ_btn"> <label class="" for=""> <i
                    class="fas fa-plus" aria-hidden="true"></i>
                sous_categorie</label></button>
    </div>
    <div class="" id="subCategs">

    </div>
    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("input,select,option,label").addClass("shadow-none text-size-md");

    $("#sub_categ_btn").on("click", (e) => {
        $('#subCategs').append(`
      <div class="input-group mb-3"  >
  <input type="text" class="form-control shadow-none text-size-md subCategsInput" placeholder="sous -catégorie  ${$(".subCategsInput").length+1}" aria-label="Dollar amount (with dot and two decimal places)">
  <span onclick="RemoveParent(this)" class="input-group-text"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
       `)

    });

    function RemoveParent(e) {
        $(e).parent().remove()
    }
</script>
