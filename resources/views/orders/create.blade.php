<form class="border-1 border-primary shadow-sm p-3 rounded-4    col-md-6" id="formOrder">
    <h5 class="mb-3 fw-bold">Ajouter une commande</h5>

    <div class="row d-flex align-items-start justify-content-evenly text-size-md ">
        <div class="row " id="main_from">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Nom du client</label>
                    <input type="text" name="client" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label" name="governorate_id">Governorat</label>
                    <select class="form-select">
                        <option value="" selected>expand</option>
                        <option value="1">Monastir</option>
                        <option value="2">Gabes</option>
                        <option value="3">Mahdia</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="" class="form-label">Téléphone</label>
                    <input type="number" min="0" name="phone" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="" class="form-label" name="products[]">Produits</label>
                    <select class="form-select">
                        <option value="" selected>expand</option>
                        <option value="1">P1</option>
                        <option value="2">P2</option>
                        <option value="3">P3</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label for="" class="form-label">Autres détails </label>
                    <div class="row" id="measures_content">
                        <textarea class="form-control shadow-none" name="details" cols="10" rows="4"></textarea>
                    </div>

                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="has_subOrder">
                <label class="form-check-label" for="has_subOrder">Sous-commande </label>
            </div>
        </div>
        <div class="row col-12 col-md-6 flex-column " id="subcommand" style="display: none;border-left: 1px solid #ccc">
            <hr class="d-lg-none">
            <div class="col-md-3"><span class="fw-bold">Sous commande <a id="add_subOrder"><i class="fas fa-plus-circle"
                            aria-hidden="true"></i></a></span></div> <br>
            <div class="row w-100 " id="subCommandContent">
                <div class="col-6 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">Sous traitant</label>
                        <input type="text" name="client" class="form-control shadow-none" id="">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">Téléphone</label>
                        <input type="number" name="phone_subc" class="form-control shadow-none" id="">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">date du commande</label>
                        <input type="date" name="start_date" class="form-control shadow-none" id="">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 text-size-md">
                        <label for="" class="form-label">Pièces <a id="add_piece"><i
                                    class="fas fa-plus-circle" aria-hidden="true"></i></a></label>
                        <div class="row">
                            <div class="col-6 col-md-8">
                                <input type="text" name="pieces[]" placeholder="nom du pièce"
                                    class="form-control shadow-none pieceInput mb-3" id="">
                            </div>
                            <div class="col-6 col-md-4">
                                <input type="number" min="1" name="qte[]" placeholder="quantité"
                                    class="form-control shadow-none qteInput mb-3" id="">
                            </div>
                        </div>

                        <div id="piece_container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("input,select,option").addClass("shadow-none text-size-md");

    $("#has_subOrder").on("click", (e) => {
        if ($("#has_subOrder").is(":checked")) {
            $("#subcommand").fadeIn()
            $("#formOrder").removeClass("col-md-6");
            $("#formOrder").addClass("col-12");
            $('#main_from').addClass("col-md-6")
            $('#main_from').addClass("col-12")
        } else {
            $("#subcommand").fadeOut();
            $("#formOrder").addClass("col-md-6");
            $("#formOrder").removeClass("col-12");
            $('#main_from').removeClass("col-md-6")
        }
    })
    $('#add_piece').on('click', () => {
        $('#piece_container').append(`
         <div class="row">
                            <div class="col-6 col-md-8">
                                <input type="text" name="pieces[]" placeholder="pièce ${$(".pieceInput").length+1}"
                                    class="form-control shadow-none text-size-md pieceInput mb-3" >
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="input-group ">
                                     <input type="number" min="1" name="qte[]" placeholder="quantité" class="form-control text-size-md shadow-none qteInput mb-3" />

                                      <span onclick="RemoveParent(this)" class="mx-auto"><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>
          </div>

`)
    })

    $("#add_subOrder").on('click', () => {
        $("#subCommandContent").append(`
        <div  class="row w-100 text-size-md" style="width:100%;">
            <hr >
                  <a  onclick="RemoveParentt(this)"  class="text-end"><i class="fa fa-times" "></i></a>

                <div class="col-6 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">Sous traitant</label>
                        <input type="text" name="client" class="form-control text-size-md  shadow-none" id="">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">Téléphone</label>
                        <input type="number" name="phone_subc" class="form-control text-size-md shadow-none" id="">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">date du commande</label>
                        <input type="date" name="start_date" class="form-control text-size-md shadow-none" id="">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 text-size-md">
                        <label for="" class="form-label">Pièces <a id="add_piece"><i
                                    class="fas fa-plus-circle" aria-hidden="true"></i></a></label>
                        <div class="row">
                            <div class="col-6 col-md-8">
                                <input type="text" name="pieces[]" placeholder="nom du pièce"
                                    class="form-control text-size-md shadow-none pieceInput mb-3" id="">
                            </div>
                            <div class="col-6 col-md-4">
                                <input type="number" min="1" name="qte[]" placeholder="quantité"
                                    class="form-control text-size-md shadow-none qteInput mb-3" id="">
                            </div>
                        </div>

                        <div id="piece_container text-size-md"></div>
                    </div>
                </div></div>
        `)
    })

    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }

    function RemoveParentt(e) {
        $(e).parent().remove()

    }
</script>
