
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ config('app.name') }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo/favicon.png')}}">
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{asset('pages/css/styles.css')}}?v={{time()}}" rel="stylesheet" />
    </head>
    <body>
        <nav class="navbar navbar-light bg-green static-top">
            <div class="container">
                <a class="navbar-brand" href="#"></a>
                <a class="pull-right" href=""><img height="60px" src="{{asset('images/logo2.png')}}" alt=""></a>
            </div>
        </nav>
        <header class="masthead">
            <div class="p-3 ms-5">
                <h6 class="mb-0 text-white">BERANI</h6>
                <h1 class="mb-2 text-white">SEDEKAH</h1>
            </div>
            <div class="text-center">
                <img class="img-fluid" src="{{asset('pages/assets/img/2.png')}}" alt="">
            </div>
        </header>

        <div class="masthead-two">
            <div class="container" style="position: absolute; top:25%;">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <h1>Kenapa Harus Bergabung Bersama kami</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="showcase">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-12 p-5 bg-light text-center">
                        <h2 class="text-darkgreen">Berani Sedekah</h2>
                        <p class="lead mb-0">Berani sedekah platfom untuk kita gotong royong membantu sesama dengan sedekah akan mendapatkan imbalan secara langsung.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Showcases-->
        <section class="showcase img-woman">
            <div class="container-fluid showcase-img" style="padding: 20px">
                <div class="lead text-darkgreen text-auto-left" style="width:50%; margin-top: 5%;"><b>Tuhan menjamin orang yang menyedekahkan harta di jalan-Nya akan diberi balasan yang berlipat ganda. Mereka juga akan mendapatkan pahala yang mulia dari sedekahnya itu.</b></div>
            </div>
        </section>

        <!-- Footer-->
        <footer class="footer bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('login')}}" class="btn w-100 bg-white text-success btn-rounded btn-outline-success">Login</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset('pages/js/scripts.js')}}"></script>
    </body>
</html>
