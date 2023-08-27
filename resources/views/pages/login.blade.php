<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Se connecter</title>
    <link rel="stylesheet" href="{{ secure_asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/style.css') }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

</head>

<body class="container">
    <div class="bg-flou"></div>
    <div class="row d-flex flex-column vh-100 justify-content-center align-items-center w-100 form-container mx-auto">
        <div class="col-md-6">
            <div class="p">

                <form class="user bg-dark p-4 rounded-5 shadow-sm d-flex flex-column" id="user_auth_form">
                    @csrf
                    <div class="text-center">
                        <h4 class="text-light  fw-bolder mb-4">Connectez-vous</h4>
                    </div>
                    <div class="mb-3"><input id="exampleInputEmail"
                            class="form-control shadow-none form-control-user rounded-2 " type="text"
                            aria-describedby="" required placeholder="Votre login" name="login" />
                    </div>
                    <div class="mb-3">
                        <input id="" class="form-control form-control-user shadow-none  rounded-2"
                            type="password" placeholder="Mot de passe" name="password" required />
                    </div>
                    <button class="btn bg-light d-block btn-user mt-3 text-dark rounded-5 w-75 mx-auto  fw-bold"
                        type="submit">Connexion
                        <i class="fas fa-sign-in-alt"></i>
                        <div id="spinner" class="spinner-border mx-2 spinner-border-sm text-dark" role="status"
                            style="display: none">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>

                    <div class="errors text-danger text-center mt-3" id="errors"></div>
                </form>

            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
    integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $("#user_auth_form").on("submit", (e) => {
        // let data = new FormData()
        e.preventDefault();
        $(".errors").html("");
        $("#spinner").fadeIn();

        axios
            .post("{{ route('users.auth') }}", $("#user_auth_form").serialize())
            .then((res) => {
                console.log(res);
                // Swal.fire("Operation Réussite !", res.data.message, "success");
                $(".errors").html("");
                $("#user_auth_form").trigger("reset");
                $("#spinner").fadeOut();
                setTimeout(() => {
                    window.location.href = "/main";
                }, 600);

            })
            .catch((err) => {
                // console.error();
                let errors = err.response.data;
                console.log(err.response);
                $("#spinner").fadeOut();

                $("#errors").html(`<div class="alert alert-danger" role="alert">
    ${errors.message}
</div>`);
            });
    });
</script>

</html>
