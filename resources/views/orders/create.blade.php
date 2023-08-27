<form class="border-1  p-3 h-100  " id="formOrder">
    @csrf
    @method('POST')


    <div class="row d-flex align-items-start justify-content-evenly text-size-md ">
        <div class="row " id="main_from">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Nom du client</label>
                    <input type="text" name="client" class="form-control shadow-none" required>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Téléphone</label>
                    <input type="number" min="0" name="phone" class="form-control shadow-none" id=""
                        required>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Date du commande</label>
                    <input type="date" name="order_date" class="form-control shadow-none" required id="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="" class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control shadow-none" required id="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Catégorie</label>
                    <select class="form-select" name="category_id">
                        @foreach ($categs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Région</label>
                    <select class="form-select" name="governorate_id">
                        @foreach ($govs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Source</label>
                    <select class="form-select" name="source_id">
                        @foreach ($sources as $source)
                            <option value="{{ $source->id }}">{{ $source->label }}</option>
                        @endforeach


                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="" class="form-label">Status</label>
                    <select class="form-select" name="status_id">
                        @forelse ($status as $stat)
                            <option value="{{ $stat->id }}"> {{ $stat->label }} </option>

                        @empty
                        @endforelse

                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="" class="form-label">Produits</label>
                    <select class="form-select" id="selectProd">
                        <option value="">choisissez un produit</option>
                        @foreach ($prods as $item)
                            <option modal_id="modalProd{{ $item->id }}" value="{{ $item->title }}"
                                @if (count(json_decode($item->measures)) == 1) measure="{{ json_decode($item->measures)[0]->measure }}" @endif
                                @if (count(json_decode($item->colors)) == 1) color="{{ json_decode($item->colors)[0]->color }}" @endif
                                prod_id="{{ $item->id }}" measures="{{ count(json_decode($item->measures)) }}"
                                colors="{{ count(json_decode($item->colors)) }}">
                                {{ $item->title }} @if (count(json_decode($item->measures)) == 1 && count(json_decode($item->colors)) == 1)
                                    ({{ json_decode($item->measures)[0]->measure }})
                                    {{ json_decode($item->colors)[0]->color }}
                                @else
                                    >
                                @endif
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="mb-3 rounded-2 p-3 shadow-sm prod_container" id="prod_container">

                </div>
            </div>
            <script>
                $("#selectProd").on('change', (e) => {
                    var selectedOption = $("#selectProd").find("option:selected");
                    if (selectedOption.val() != "") {
                        if (selectedOption.attr("measures") > 1 || selectedOption.attr("colors") > 1) {
                            $("#" + selectedOption.attr("modal_id")).modal("show")

                        } else {
                            if ($(`.inputTest${selectedOption.attr("prod_id")}`).length > 0) {
                                $(`.inputTest${selectedOption.attr("prod_id")}`).remove()
                            } else {
                                $("#prod_container").append(`
                       <div class="row mb-2 inputTest${selectedOption.attr("prod_id")}">
                        <div class="col-6 col-lg-8"> <input readonly type="text" value="${selectedOption.val()+ " (" +selectedOption.attr("measure") +") " +selectedOption.attr("color") }" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" >
                        </div>
                        <div class="col-6 col-lg-4">
                           <div class="d-flex align-items-center justify-content-between">
                             <input type="number" placeholder="quantité" min="1" name="qtes_prod[]" required
                                class="form-control text-size-md  shadow-none" >
                               <a class="mx-1 text-danger" onclick="RemoveParent(this)"> <i class="fas fa-times" aria-hidden="true" ></i></a>
                                </div>
                        </div>
                        <input type="hidden" name="ids[]" value="${selectedOption.attr("prod_id") }"" class="form-control shadow-none" >
                        <input type="hidden" name="measures_prods[]" value="${selectedOption.attr("measure") }">
                        <input type="hidden" name="colors_prods[]" value="${selectedOption.attr("color") }">
                    </div>
                    `)
                            }

                        }

                    }


                })
            </script>

            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="" class="form-label">Remarques </label>
                    <div class="row" id="measures_content">
                        <textarea class="form-control shadow-none" name="details" cols="10" rows="4"></textarea>
                    </div>

                </div>
            </div>
            {{-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="has_subOrder">
                <label class="form-check-label" for="has_subOrder">Sous-commande </label>
            </div> --}}
        </div>

    </div>
    <button type="submit" class="btn btn-primary float-end">Ajouter</button>
</form>
<script>
    $("input,select,option").addClass("shadow-none text-size-md");

    $("#has_subOrder").on("click", (e) => {
        if ($("#has_subOrder").is(":checked")) {
            $("#subcommand").fadeIn()
            $("#formOrder").removeClass("col-lg-6");
            $("#formOrder").addClass("col-12");
            $('#main_from').addClass("col-lg-6")
            $('#main_from').addClass("col-12")
            $("#product_linked").html("")

            $(".prod_container input[type='text']").each(function() {
                var value = $(this).val();
                $("#product_linked").append(`
                <option>${value}</option>
                `)
            });
        } else {
            $("#subcommand").fadeOut();
            $("#formOrder").addClass("col-lg-6");
            $("#formOrder").removeClass("col-12");
            $('#main_from').removeClass("col-lg-6")
        }
    })
    $('#add_piece').on('click', () => {
        $('#piece_container').append(`
         <div class="row">
                            <div class="col-6 col-lg-8">
                                <input type="text" name="pieces[]" placeholder="pièce "
                                    class="form-control shadow-none text-size-md pieceInput mb-3" >
                            </div>
                            <div class="col-6 col-lg-4">
                                <div class="input-group ">
                                     <input type="number" min="1" name="qte[]" placeholder="quantité" class="form-control text-size-md shadow-none qteInput mb-3" />

                                      <span onclick="RemoveParent(this)" class="mx-auto"><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>
          </div>

`)
    })

    $("#add_subOrder").on('click', () => {
        let len = $(".subCommandContent").length;
        console.log(len);
        var clone = $('#subcommand:first').clone(true, true);
        clone.find('input').val('');
        $("#subCommandContent").append(
            `<div  class="row w-100 subCommandContent text-size-md" ><hr style="border:2px solid black"> <a  onclick="RemoveParentt(this)"  class="text-end"><i class="fa fa-times" "></i></a> ${clone.html()} </div>`
        )


    })

    function add_piece(id) {
        let len = $(".subCommandContent").length;

        $(`#${id}`).append(`
         <div class="row">
                            <div class="col-6 col-lg-8">
                                <input type="text" name="pieces[]" placeholder="pièce"
                                    class="form-control shadow-none text-size-md pieceInput mb-3" >
                            </div>
                            <div class="col-6 col-lg-4">
                                <div class="input-group ">
                                     <input type="number" min="1" name="qte[]" placeholder="quantité" class="form-control text-size-md shadow-none qteInput mb-3" />

                                      <span onclick="RemoveParent(this)" class="mx-auto"><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>
          </div>
`)
    }

    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }

    function RemoveParentt(e) {
        $(e).parent().remove()

    }
    $("#formOrder").on("submit", (e) => {
        e.preventDefault()
        var qteInputs = document.getElementsByName('qtes_prod[]');
        var idsInputs = document.getElementsByName('ids[]');
        var measuresInputs = document.getElementsByName('measures_prods[]');
        var colorsInputs = document.getElementsByName('colors_prods[]');
        var mergedArray = [];
        for (var i = 0; i < idsInputs.length; i++) {
            var id = idsInputs[i].value;
            var qte = qteInputs[i].value;
            var measure = measuresInputs[i].value;
            var color = colorsInputs[i].value;
            if (id != "" && qte != "" && measure != "" && color != "") {
                mergedArray.push({
                    id: id,
                    qte: qte,
                    measure: measure,
                    color: color
                });
            }
        }
        let formdata = new FormData($("#formOrder")[0]);
        formdata.append("products", JSON.stringify(mergedArray))
        axios.post("{{ route('orders.store') }}", formdata)
            .then(res => {
                $("#formOrder").trigger("reset");
                $("#prod_container").html("")
                Swal.fire("Succès", "Commande bien enregistré", "success")
                $("#table_order_container").load(
                    "{{ route('orders.table', ['cat' => 0, 'stat' => 0, 'reg' => 0, 'search' => 'all']) }}"
                )

            })
            .catch(err => {
                console.error(err.response.data);
                Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

            })

    })
</script>
