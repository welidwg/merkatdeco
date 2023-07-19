<form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6 text-size-md" id="addProdForm">
    @csrf
    @method('POST')
    <h5 class="mb-3 fw-bold">Ajouter un produit</h5>

    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Nom</label>
                <input type="text" required name="title" class="form-control shadow-none" id="">
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

                <select class="form-select" id="CategsSelect" name="category_id">
                    <option value="">----</option>
                    @foreach ($categs as $cat)
                        {{-- <span>{{ dd($cat->sub_categs) }}</span> --}}
                        <option sub="{{ count($cat->sub_categs) }}" value="{{ $cat->id }}">{{ $cat->label }}
                        </option>
                    @endforeach
                </select>
                <script>
                    $("#CategsSelect").on("change", (e) => {
                        var selectedOption = $("#CategsSelect").find("option:selected");
                        // console.log(selectedOption.val());
                        var subValue = selectedOption.attr("sub")
                        if (selectedOption.val() !== "") {
                            $("#add_btn_prod").attr("disabled", false);
                        }
                        if (subValue != 0 && subValue != undefined) {

                            axios.get(`/categories/subs/${selectedOption.val()}`)
                                .then(res => {
                                    if (res.data.length > 0) {
                                        $("#subCategsSelect").html("")
                                        $("#subCategsSelect").removeAttr("disabled");
                                        res.data.forEach(element => {
                                            var optionValue = element.id;
                                            var optionText = element.label;

                                            var newOption = $("<option>").val(optionValue).text(optionText);

                                            $("#subCategsSelect").append(newOption)
                                        });
                                    }

                                })
                                .catch(err => {
                                    console.error(err);
                                })

                        } else {
                            if (selectedOption.val() == "") {
                                $("#add_btn_prod").attr("disabled", true);
                            }
                            $("#subCategsSelect").attr("disabled", true);
                            $("#subCategsSelect").html('')
                            $("#subCategsSelect").append(`<option>----</option>`)

                        }

                    })
                </script>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="" class="form-label">Sous-catégorie</label>
                <select class="form-select" id="subCategsSelect" name="sub_category_id" disabled>
                    <option value="">----</option>
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
                            <input type="text" placeholder="ex: 181 x 106 cm" name="measuresArr[]"
                                class="form-control shadow-none" id="">
                        </div>
                        <div class="col-5">
                            <input type="number" step="0.1" placeholder="prix" min="0" name="pricesArr[]"
                                class="form-control shadow-none" id="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label for="" class="form-label">Couleurs <a id="add_color"><i class="fas fa-plus"
                            aria-hidden="true"></i></a></label>
                <div class="row" id="colors_content">
                    <div class="col-md-4 mb-3">
                        <input type="text" placeholder="couleur" name="colorsArr[]" class="form-control shadow-none"
                            id="">
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



    <button type="submit" class="btn btn-primary float-end" id="add_btn_prod" disabled>Ajouter</button>
</form>
<script>
    $("input,select,option,label").addClass("shadow-none text-size-md");

    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }

    function RemoveParentColor(e) {
        $(e).parent().parent().remove()
    }
    $("#add_attr").on("click", (e) => {
        $("#attr_content").append(`
          <div class=" row ">
                     <div class="col-6">
                        <input type="text" placeholder="attribut" name="attributes[]"
                            class="form-control text-size-md shadow-none" id="">
                    </div>
                    <div class="col-6">
                       
                            <div class="input-group mb-3 "  >
  <input type="text" placeholder="valeur" name="values[]"
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
                        <input type="text" placeholder="ex: 181 x 106 cm" name="measuresArr[]"
                            class="form-control shadow-none text-size-md" id="">
                    </div>
                    <div class="col-5">
                       
                            <div class="input-group mb-3"  >
  <input type="number" step="0.1" placeholder="prix" min="0" name="pricesArr[]"
                            class="form-control text-size-md shadow-none" id="">
  <span onclick="RemoveParent(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                    </div>
                   </div>
`)
    })

    $("#add_color").on("click", (e) => {
        $('#colors_content').append(`
<div class="col-md-4 mb-3">
    
                            <div class="input-group mb-3"  >
   <input type="text" placeholder="couleur" name="colorsArr[]" class="form-control shadow-none"
                            id="">
  <span onclick="RemoveParentColor(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                       
                    </div>
`)
    })

    $("#addProdForm").on('submit', (e) => {
        e.preventDefault();

        var attributeInputs = document.getElementsByName('attributes[]');
        var valueInputs = document.getElementsByName('values[]');
        var measuresInputs = document.getElementsByName('measuresArr[]');
        var pricesInputs = document.getElementsByName('pricesArr[]');
        var colorsInputs = document.getElementsByName('colorsArr[]');
        var mergedDetailsArray = [];
        var mergedMeasuressArray = [];
        var mergedColorsArray = [];

        for (var i = 0; i < attributeInputs.length; i++) {
            var attributeValue = attributeInputs[i].value;
            var valueValue = valueInputs[i].value;
            if (attributeValue != "" && valueValue != "") {
                mergedDetailsArray.push({
                    attribute: attributeValue,
                    value: valueValue
                });
            }
        }
        for (var i = 0; i < measuresInputs.length; i++) {
            var measureValue = measuresInputs[i].value;
            var priceValue = pricesInputs[i].value;

            if (measureValue != "" && priceValue != "") {
                mergedMeasuressArray.push({
                    measure: measureValue,
                    price: priceValue
                });
            }

        }
        for (var i = 0; i < colorsInputs.length; i++) {
            var colorVal = colorsInputs[i].value;
            if (colorVal != "") {
                mergedColorsArray.push({
                    color: colorVal,
                });
            }
        }
        let formdata = new FormData($("#addProdForm")[0]);
        formdata.append("measures", JSON.stringify(mergedMeasuressArray))
        formdata.append("details", JSON.stringify(mergedDetailsArray))
        formdata.append("colors", JSON.stringify(mergedColorsArray))
        axios.post("{{ route('products.store') }}", formdata)
            .then(res => {
                $("#addProdForm").trigger("reset")
                Swal.fire('Succès', "Le produit est bien ajouté.", "success")
                console.log('====================================');
                console.log(res);
                console.log('====================================');

            })
            .catch(err => {
                console.error(err.response.data.error);
                Swal.fire('Erreur', "L'opération est échouée.<br>Message: " + err.response.data.error,
                    "error")

            })


    })
</script>
