<form class="border-1 shadow-sm p-3 rounded-4   col-lg-6" id="formOrder">
    <h5 class="mb-3 fw-bold">Ajouter une commande</h5>
    @csrf
    @method('POST')

    <div class="row d-flex align-items-start justify-content-evenly text-size-md ">
        <div class="row " id="main_from">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Nom du client</label>
                    <input type="text" name="client" class="form-control shadow-none">
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Téléphone</label>
                    <input type="number" min="0" name="phone" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Date du commande</label>
                    <input type="date" name="order_date" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="" class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="" class="form-label">Région</label>
                    <select class="form-select" name="governorate_id">
                        @foreach ($govs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="" class="form-label">Source</label>
                    <select class="form-select" name="source">
                        <option>Facebook</option>
                        <option>Instagram</option>
                        <option>Site Web</option>
                        <option>Téléphone</option>


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
            @foreach ($prods as $item)
                {{-- {{ print_r($item->measures) }} --}}
                <!-- Modal -->
                @if (count(json_decode($item->measures)) > 1 || count(json_decode($item->colors)) > 1)
                    <div class="modal fade my-auto" id="modalProd{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitleId">Choisissez les détails:</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>

                                </div>
                                <div class="modal-body">
                                    @if (count(json_decode($item->measures)) > 1)
                                        <span class="mb-2">Dimensions : </span>

                                        <div>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach (json_decode($item->measures) as $it)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ckb_{{ $item->id }}"
                                                        type="checkbox"
                                                        id="checkbox_{{ $item->id }}{{ $i }}"
                                                        value="{{ $it->measure }}" prod_id={{ $item->id }}
                                                        title="{{ $item->title }}">
                                                    <label class="form-check-label"
                                                        for="checkbox_{{ $item->id }}{{ $i }}">{{ $it->measure }}</label>
                                                </div>
                                                {{-- <script>
                                                    $("#checkbox_{{ $item->id }}{{ $i }}").bind().on("click", (e) => {
                                                        if ($("#checkbox_{{ $item->id }}{{ $i }}").is(":checked")) {
                                                            let title = $("#checkbox_{{ $item->id }}{{ $i }}").attr("title");
                                                            let id = $("#checkbox_{{ $item->id }}{{ $i }}").attr("prod_id");
                                                            let measure = $("#checkbox_{{ $item->id }}{{ $i }}").val();
                                                            $("#prod_container").append(`
                       <div class="row mb-2 inputTest{{ $item->id }}{{ $i }}">
                        <div class="col-6 col-lg-8"> <input readonly type="text" value="${title +" ("+ measure+")"}" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" id="">
                        </div>
                        <div class="col-6 col-lg-4">
                           <div class="d-flex align-items-center justify-content-between">
                             <input type="number" placeholder="quantité" min="1" name="qtes_prod[]" required
                                class="form-control text-size-md  shadow-none" id="">
                               <a class="mx-1 text-danger" onclick="RemoveParent(this)"> <i class="fas fa-times" aria-hidden="true" ></i></a>
                                </div>
                        </div>
                        <input type="hidden" name="ids[]" value="${id}">
                        <input type="hidden" name="measures_prods[]" value="${measure}">

                    </div>
                    `)
                                                        } else {
                                                            $(".inputTest{{ $item->id }}{{ $i }}").remove()
                                                        }

                                                    })
                                                </script> --}}
                                            @endforeach

                                        </div>
                                    @endif
                                    @if (count(json_decode($item->colors)) >= 1)
                                        @php
                                            $i = 0;
                                        @endphp
                                        <span class="mb-2">Couleur : </span>
                                        <div>
                                            @foreach (json_decode($item->colors) as $color)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        {{ $i == 1 ? 'checked' : ' ' }}
                                                        name="colors_radio{{ $item->id }}"
                                                        id="checkbox_color_{{ $item->id }}{{ $i }}"
                                                        value="{{ $color->color }}" prod_id={{ $item->id }}
                                                        title="{{ $item->title }}">
                                                    <label class="form-check-label"
                                                        for="checkbox_color_{{ $item->id }}{{ $i }}">{{ $color->color }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="button" class="btn btn-primary"
                                        id="btn_add_prod{{ $item->id }}">Terminer</button>
                                </div>
                                <script>
                                    $("#btn_add_prod{{ $item->id }}").on("click", (e) => {
                                        $("#modalProd{{ $item->id }}").modal("hide");
                                        var measures_checks = $(".ckb_{{ $item->id }}:checked")
                                        measures_checks.each(function() {
                                            let title = $(this).attr("title");
                                            let id = $(this).attr("prod_id");
                                            let measure = $(this).val();
                                            let color = $("input[name=colors_radio{{ $item->id }}]:checked").val()
                                            $("#prod_container").append(`
                       <div class="row mb-2 ">
                        <div class="col-6 col-lg-8"> <input readonly type="text" value="${title +" ("+ measure+") "+ color}" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" id="">
                        </div>
                        <div class="col-6 col-lg-4">
                           <div class="d-flex align-items-center justify-content-between">
                             <input type="number" placeholder="quantité" min="1" name="qtes_prod[]" required
                                class="form-control text-size-md  shadow-none" id="">
                               <a class="mx-1 text-danger" onclick="RemoveParent(this)"> <i class="fas fa-times" aria-hidden="true" ></i></a>
                                </div>
                        </div>
                        <input type="hidden" name="ids[]" value="${id}">
                        <input type="hidden" name="measures_prods[]" value="${measure}">
                        <input type="hidden" name="colors_prods[]" value="${color}">

                    </div>
                    `)

                                        });

                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
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


        // $("#subCommandContent").append(`
        // <div  class="row w-100 subCommandContent text-size-md" >
        //     <hr style="border:2px solid black">
        //           <a  onclick="RemoveParentt(this)"  class="text-end"><i class="fa fa-times" "></i></a>

        //         <div class="col-6 col-lg-4">
        //             <div class="mb-3">
        //                 <label for="" class="form-label">Sous traitant</label>
        //                 <input type="text" name="sub_contractor" class="form-control text-size-md  shadow-none" id="">
        //             </div>
        //         </div>
        //         <div class="col-6 col-lg-4">
        //             <div class="mb-3">
        //                 <label for="" class="form-label">Téléphone</label>
        //                 <input type="number" name="phone_subc" class="form-control text-size-md shadow-none" id="">
        //             </div>
        //         </div>
        //         <div class="col-12 col-lg-4">
        //             <div class="mb-3">
        //                 <label for="" class="form-label">date du commande</label>
        //                 <input type="date" name="start_date" class="form-control text-size-md shadow-none" id="">
        //             </div>
        //         </div>
        //         <div class="col-12">
        //             <div class="mb-3 text-size-md">
        //                 <label for="" class="form-label ">Pièces <a onclick="add_piece('piece_container${len+1}')" class="text-primary"><i
        //                             class="fas fa-plus-circle" aria-hidden="true"></i></a></label>
        //                 <div class="row">
        //                     <div class="col-6 col-lg-8">
        //                         <input type="text" name="pieces[]" placeholder="nom du pièce"
        //                             class="form-control text-size-md shadow-none pieceInput mb-3" id="">
        //                     </div>
        //                     <div class="col-6 col-lg-4">
        //                         <input type="number" min="1" name="qte[]" placeholder="quantité"
        //                             class="form-control text-size-md shadow-none qteInput mb-3" id="">
        //                     </div>
        //                 </div>

        //                 <div id="piece_container${len+1}" class="text-size-md"></div>
        //             </div>
        //         </div></div>
        // `)
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
                console.log(res.data.success)
            })
            .catch(err => {
                console.error(err.response.data);
                Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

            })

    })
</script>
