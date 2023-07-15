<form class="border-1 shadow-sm p-3 rounded-4   col-md-6" id="formOrder">
    <h5 class="mb-3 fw-bold">Ajouter une commande</h5>
    @csrf
    @method('POST')

    <div class="row d-flex align-items-start justify-content-evenly text-size-md ">
        <div class="row " id="main_from">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label">Nom du client</label>
                    <input type="text" name="client" class="form-control shadow-none">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label">Téléphone</label>
                    <input type="number" min="0" name="phone" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label">Date du commande</label>
                    <input type="date" name="order_date" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="" class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control shadow-none" id="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="" class="form-label">Governorat</label>
                    <select class="form-select" name="governorate_id">
                        @foreach ($govs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-md-12">
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
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="" class="form-label">Produits</label>
                    <select class="form-select" id="selectProd">
                        <option value="">choisissez un produit</option>
                        @foreach ($prods as $item)
                            <option modal_id="modalProd{{ $item->id }}" value="{{ $item->title }}"
                                @if (count(json_decode($item->measures)) == 1) measure="{{ json_decode($item->measures)[0]->measure }}" @endif
                                prod_id="{{ $item->id }}" measures="{{ count(json_decode($item->measures)) }}">
                                {{ $item->title }} @if (count(json_decode($item->measures)) == 1)
                                    ({{ json_decode($item->measures)[0]->measure }})
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
                        if (selectedOption.attr("measures") > 1) {
                            $("#" + selectedOption.attr("modal_id")).modal("show")

                        } else {
                            if ($(`.inputTest${selectedOption.attr("prod_id")}`).length > 0) {
                                $(`.inputTest${selectedOption.attr("prod_id")}`).remove()
                            } else {
                                $("#prod_container").append(`
                       <div class="row mb-2 inputTest${selectedOption.attr("prod_id")}">
                        <div class="col-6 col-md-8"> <input readonly type="text" value="${selectedOption.val()+" ("+selectedOption.attr("measure")+")"}" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" id="">
                        </div>
                        <div class="col-6 col-md-4">
                           <div class="d-flex align-items-center justify-content-between">
                             <input type="number" placeholder="quantité" min="1" name="qtes_prod[]" required
                                class="form-control text-size-md  shadow-none" id="">
                               <a class="mx-1 text-danger" onclick="RemoveParent(this)"> <i class="fas fa-times" aria-hidden="true" ></i></a>
                                </div>
                        </div>
                        <input type="hidden" name="ids[]" value="${selectedOption.attr("prod_id") }"" class="form-control shadow-none" id="">
                        <input type="hidden" name="measures_prods[]" value="${selectedOption.attr("measure") }">
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
                @if (count(json_decode($item->measures)) > 1)
                    <div class="modal fade my-auto" id="modalProd{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitleId">Choisissez les dimensions:</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>

                                </div>
                                <div class="modal-body">
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach (json_decode($item->measures) as $it)
                                        @php
                                            $i++;
                                        @endphp
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                id="checkbox_{{ $item->id }}{{ $i }}"
                                                value="{{ $it->measure }}" prod_id={{ $item->id }}
                                                title="{{ $item->title }}">
                                            <label class="form-check-label"
                                                for="checkbox_{{ $item->id }}{{ $i }}">{{ $it->measure }}</label>
                                        </div>
                                        <script>
                                            $("#checkbox_{{ $item->id }}{{ $i }}").bind().on("click", (e) => {
                                                if ($("#checkbox_{{ $item->id }}{{ $i }}").is(":checked")) {
                                                    let title = $("#checkbox_{{ $item->id }}{{ $i }}").attr("title");
                                                    let id = $("#checkbox_{{ $item->id }}{{ $i }}").attr("prod_id");
                                                    let measure = $("#checkbox_{{ $item->id }}{{ $i }}").val();
                                                    $("#prod_container").append(`
                       <div class="row mb-2 inputTest{{ $item->id }}{{ $i }}">
                        <div class="col-6 col-md-8"> <input readonly type="text" value="${title +" ("+ measure+")"}" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" id="">
                        </div>
                        <div class="col-6 col-md-4">
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
                                        </script>
                                    @endforeach


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="button" class="btn btn-primary">Terminer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
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
        <div class="row col-12 col-md-6 flex-column " id="subcommand"
            style="display: none;border-left: 1px solid #ccc">
            <hr class="d-lg-none">
            <div class="col-md-3"><span class="fw-bold">Sous commande <a id="add_subOrder" class="text-primary"><i
                            class="fas fa-plus-circle" aria-hidden="true"></i></a></span></div> <br>
            <div class="row  subCommandContent" id="subCommandContent">
                <div class="col-6 col-md-4">
                    <div class="mb-3">
                        <label for="" class="form-label">Sous traitant</label>
                        <input type="text" name="sub_contractor" class="form-control shadow-none" id="">
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
                <div class="col-12 mb-3 ">
                    <select name="" class="form-control" id="product_linked">

                    </select>

                </div>

                <div class="col-12">
                    <div class="mb-3 text-size-md">
                        <label for="" class="form-label">Pièces <a id="add_piece" class="text-primary"><i
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
            $("#product_linked").html("")

            $(".prod_container input[type='text']").each(function() {
                var value = $(this).val();
                $("#product_linked").append(`
                <option>${value}</option>
                `)
            });
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
                                <input type="text" name="pieces[]" placeholder="pièce "
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

        //         <div class="col-6 col-md-4">
        //             <div class="mb-3">
        //                 <label for="" class="form-label">Sous traitant</label>
        //                 <input type="text" name="sub_contractor" class="form-control text-size-md  shadow-none" id="">
        //             </div>
        //         </div>
        //         <div class="col-6 col-md-4">
        //             <div class="mb-3">
        //                 <label for="" class="form-label">Téléphone</label>
        //                 <input type="number" name="phone_subc" class="form-control text-size-md shadow-none" id="">
        //             </div>
        //         </div>
        //         <div class="col-12 col-md-4">
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
        //                     <div class="col-6 col-md-8">
        //                         <input type="text" name="pieces[]" placeholder="nom du pièce"
        //                             class="form-control text-size-md shadow-none pieceInput mb-3" id="">
        //                     </div>
        //                     <div class="col-6 col-md-4">
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
                            <div class="col-6 col-md-8">
                                <input type="text" name="pieces[]" placeholder="pièce"
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
        var mergedArray = [];
        for (var i = 0; i < idsInputs.length; i++) {
            var id = idsInputs[i].value;
            var qte = qteInputs[i].value;
            var measure = measuresInputs[i].value;
            if (id != "" && qte != "" && measure != "") {
                mergedArray.push({
                    id: id,
                    qte: qte,
                    measure: measure
                });
            }
        }
        let formdata = new FormData($("#formOrder")[0]);
        formdata.append("products", JSON.stringify(mergedArray))
        console.log(mergedArray);
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
