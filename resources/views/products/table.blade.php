<table class="table my-0 " id="table_index_releve" style="table-layout: fixed">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Stock</th>
            {{-- <th class="d-none d-md-table-cell">Heure debut</th>
                            <th class="d-none d-md-table-cell">Heure fin</th>
                            <th class="d-none d-md-table-cell">Total Saisie</th> --}}
            {{-- <th class="d-none d-md-table-cell">Total Rapport</th> --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($prods as $prod)
            <tr>
                <td>{{ $prod->title }}</td>
                <td>{{ $prod->category->label }} @if ($prod->sub_category != null)
                        <span class="" style="font-size: 12px">
                            ({{ $prod->sub_category->label }})
                        </span>
                    @endif
                </td>
                <td>{{ $prod->stock }}</td>
                <td>
                    <form action="{{ route('products.destroy', $prod) }}" class="d-flex align-items-center "
                        id="form_delete_prod{{ $prod->id }}">
                        @csrf
                        @method('DELETE')
                        <a data-bs-toggle="offcanvas" data-bs-target="#canvas_{{ $prod->id }}"
                            class="text-primary "><i class="far fa-eye "></i></a>
                        <button onclick="return confirm('Vous êtes sûr ?')" type="submit" href="#"
                            class="text-danger btn"><i class="far fa-times-circle "></i></i></a>
                    </form>
                    <script>
                        $("#form_delete_prod{{ $prod->id }}").on("submit", (e) => {
                            e.preventDefault();
                            axios.delete(e.target.action)
                                .then(res => {
                                    Swal.fire("Suppression réussite !", "", "success")
                                    $("#table_container").load("{{ route('products.table') }}")

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
@foreach ($prods as $prod)
    <div class="offcanvas offcanvas-end text-size-md" data-bs-scroll="true" tabindex="-1" style="width: 600px"
        id="canvas_{{ $prod->id }}" aria-labelledby="Enable both scrolling & backdrop">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">{{ $prod->title }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex align-items-start">
            <form class="border-1 border-primary shadow-sm p-3 rounded-4 col-12 col-md-6 text-size-md w-100"
                id="editProdForm{{ $prod->id }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Nom</label>
                            <input type="text" required name="title" value="{{ $prod->title }}"
                                class="form-control text-size-md shadow-none" id="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Stock</label>
                            <input type="number" min="0" value="{{ $prod->stock }}" name="stock"
                                class="form-control text-size-md shadow-none" id="">
                        </div>
                    </div>
                    <div class="col-md-3">

                        <div class="mb-3">
                            <label for="" class="form-label">Catégorie</label>

                            <select class="form-select text-size-md" id="CategsSelect" name="category_id">
                                <option value="{{ $prod->category->id }}">{{ $prod->category->label }}
                                </option>

                            </select>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Sous-catégorie</label>
                            <select class="form-select text-size-md" id="subCategsSelect" name="sub_category_id"
                                @if ($prod->sub_category == null) disabled @endif>
                                @if ($prod->sub_category != null)
                                    <option value="{{ $prod->sub_category->id }}">
                                        {{ $prod->sub_category->label }}

                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Mesures <a id="add_measures{{ $prod->id }}"><i
                                        class="fas fa-plus" aria-hidden="true"></i></a></label>
                            @foreach (json_decode($prod->measures) as $measure)
                                <div class="row" id="measures_content{{ $prod->id }}">
                                    <div class=" row mb-3">
                                        <div class="col-7">
                                            <input type="text" placeholder="ex: 181 x 106 cm"
                                                name="measuresArr{{ $prod->id }}[]"
                                                class="form-control text-size-md shadow-none"
                                                value="{{ $measure->measure }}" id="">
                                        </div>
                                        <div class="col-5">
                                            <div class="input-group mb-1">
                                                <input type="number" step="0.1" placeholder="prix" min="0"
                                                    name="pricesArr{{ $prod->id }}[]"
                                                    class="form-control text-size-md shadow-none" id=""
                                                    value="{{ $measure->price }}">
                                                <span onclick="RemoveParent(this)"
                                                    class="input-group-text text-size-md"><a><i class="fas fa-times"
                                                            aria-hidden="true"></i></a></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Couleurs <a
                                    id="add_color{{ $prod->id }}"><i class="fas fa-plus"
                                        aria-hidden="true"></i></a></label>
                            <div class="row" id="colors_content{{ $prod->id }}">

                                @foreach (json_decode($prod->colors) as $color)
                                    <div class="col-lg-4 mb-3">
                                        <div class="input-group mb-1">

                                            <input type="text" name="colorsArr{{ $prod->id }}[]"
                                                class="form-control text-size-md shadow-none"
                                                value="{{ $color->color }}" id="">
                                            <span onclick="RemoveParentColor(this)"
                                                class="input-group-text text-size-md"><a><i class="fas fa-times"
                                                        aria-hidden="true"></i></a></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Caractéristiques <a
                                    id="add_attr{{ $prod->id }}"><i class="fas fa-plus"
                                        aria-hidden="true"></i></a></label>
                            <div class="row" id="attr_content{{ $prod->id }}">
                                @foreach (json_decode($prod->details) as $details)
                                    <div class=" row ">
                                        <div class="col-6">
                                            <input type="text" placeholder="attribut"
                                                name="attributes{{ $prod->id }}[]"
                                                value="{{ $details->attribute }}"
                                                class="form-control text-size-md shadow-none" id="">
                                        </div>
                                        <div class="col-6">

                                            <div class="input-group mb-3 ">
                                                <input type="text" placeholder="valeur"
                                                    name="values{{ $prod->id }}[]"
                                                    class="form-control text-size-md shadow-none"
                                                    value="{{ $details->value }}" id="">
                                                <span onclick="RemoveParent(this)"
                                                    class="input-group-text text-size-md"><a><i class="fas fa-times"
                                                            aria-hidden="true"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>



                <button type="submit" class="btn btn-primary float-end" id="add_btn_prod">Enregistrer</button>
            </form>
        </div>
    </div>
    <script>
        $("#editProdForm{{ $prod->id }}").on("submit", (e) => {
            e.preventDefault();
            var attributeInputs = document.getElementsByName('attributes{{ $prod->id }}[]');
            var valueInputs = document.getElementsByName('values{{ $prod->id }}[]');
            var measuresInputs = document.getElementsByName('measuresArr{{ $prod->id }}[]');
            var pricesInputs = document.getElementsByName('pricesArr{{ $prod->id }}[]');
            var colorsInputs = document.getElementsByName('colorsArr{{ $prod->id }}[]');
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
            for (var i = 0; i < colorsInputs.length; i++) {
                var color = colorsInputs[i].value;
                if (color != "") {
                    mergedColorsArray.push({
                        color: color,
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
            var formdata = new FormData($("#editProdForm{{ $prod->id }}")[0]);
            formdata.append("measures", JSON.stringify(mergedMeasuressArray))
            formdata.append("details", JSON.stringify(mergedDetailsArray))
            formdata.append("colors", JSON.stringify(mergedColorsArray))

            axios.post("{{ route('products.update', $prod) }}", formdata, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    // $("#editProdForm{{ $prod->id }}").trigger("reset")
                    Swal.fire('Succès', "Le produit est bien modifié.", "success")
                    // setTimeout(() => {
                    //     window.location.reload()
                    // }, 700);
                    $("#table_container").load("{{ route('products.table') }}")

                })
                .catch(err => {
                    console.error(err.response.data);
                    Swal.fire('Erreur', "L'opération est échouée.<br>Message: " + err.response.data.error,
                        "error")

                })
        })
        $("#add_measures{{ $prod->id }}").on("click", (e) => {
            $('#measures_content{{ $prod->id }}').append(`
  <div class=" row ">
                     <div class="col-7">
                        <input type="text" placeholder="ex: 181 x 106 cm" name="measuresArr{{ $prod->id }}[]"
                            class="form-control shadow-none text-size-md" id="">
                    </div>
                    <div class="col-5">
                       
                            <div class="input-group mb-3"  >
  <input type="number" step="0.1" placeholder="prix" min="0" name="pricesArr{{ $prod->id }}[]"
                            class="form-control text-size-md shadow-none" id="">
  <span onclick="RemoveParent(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                    </div>
                   </div>
`)
        })



        $("#add_color{{ $prod->id }}").on("click", (e) => {
            $('#colors_content{{ $prod->id }}').append(`
    <div class="col-lg-4 mb-3">
                                                        <div class="input-group mb-1">

                                                            <input type="text" name="colorsArr{{ $prod->id }}[]"
                                                                class="form-control text-size-md shadow-none" placeholder="couleur"
                                                                value="" id="">
                                                            <span onclick="RemoveParentColor(this)"
                                                                class="input-group-text text-size-md"><a><i
                                                                        class="fas fa-times"
                                                                        aria-hidden="true"></i></a></span>
                                                        </div>
                                                    </div>
`)
        })




        $("#add_attr{{ $prod->id }}").on("click", (e) => {
            $("#attr_content{{ $prod->id }}").append(`
          <div class=" row ">
                     <div class="col-6">
                        <input type="text" placeholder="attribut" name="attributes{{ $prod->id }}[]"
                            class="form-control text-size-md shadow-none" id="">
                    </div>
                    <div class="col-6">
                       
                            <div class="input-group mb-3 "  >
  <input type="text" placeholder="valeur" name="values{{ $prod->id }}[]"
                            class="form-control text-size-md shadow-none" id="">
  <span onclick="RemoveParent(this)" class="input-group-text text-size-md"><a  ><i class="fas fa-times" aria-hidden="true"></i></a></span>
</div>
                    </div>
                   </div>
        `)
        })
    </script>
@endforeach
<script>
    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }

    function RemoveParentColor(e) {
        $(e).parent().parent().remove()
    }
</script>
