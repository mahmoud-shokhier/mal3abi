<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>mallabi-dashboard Demo Application</title>

    <base href="/dashboard" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="favicon.png" />
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpVhQiwAllg1RAFaxMWSpQruuGARy0Y1k&libraries=places">
    </script>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/styles.9558ef3a6675262ef785.css') }}">
</head>

<body>
    <ngx-app>Loading...</ngx-app>

    <style>
        @-webkit-keyframes spin {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spin {
            0% {
                -moz-transform: rotate(0);
            }

            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1003;
            background: #000000;
            overflow: hidden;
        }

        .spinner div:first-child {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            box-shadow: 0 3px 3px 0 rgba(255, 56, 106, 1);
            transform: translate3d(0, 0, 0);
            animation: spin 2s linear infinite;
        }

        .spinner div:first-child:after,
        .spinner div:first-child:before {
            content: "";
            position: absolute;
            border-radius: 50%;
        }

        .spinner div:first-child:before {
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            box-shadow: 0 3px 3px 0 rgb(255, 228, 32);
            -webkit-animation: spin 3s linear infinite;
            animation: spin 3s linear infinite;
        }

        .spinner div:first-child:after {
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            box-shadow: 0 3px 3px 0 rgba(61, 175, 255, 1);
            animation: spin 1.5s linear infinite;
        }
    </style>
    <div id="nb-global-spinner" class="spinner">
        <div class="blob blob-0"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
        <div class="blob blob-5"></div>
    </div>
    <script src="{{ asset('assets/dashboard/runtime-es2015.b8d9e50520e95e197ea3.js') }}" type="module"></script>
    <script src="{{ asset('assets/dashboard/runtime-es5.b8d9e50520e95e197ea3.js') }}" nomodule defer></script>
    <script src="{{ asset('assets/dashboard/polyfills-es5.2a36aeea2ea5b71a47b6.js') }}" nomodule defer></script>
    <script src="{{ asset('assets/dashboard/polyfills-es2015.16e48d77c15f955d67d7.js') }}" type="module"></script>
    <script src="{{ asset('assets/dashboard/scripts.637bd719aa9b7f5d4278.js') }}" defer></script>
    <script src="{{ asset('assets/dashboard/main-es2015.5f6187f29420923a4d7f.js') }}" type="module"></script>
    <script src="{{ asset('assets/dashboard/main-es5.5f6187f29420923a4d7f.js') }}" nomodule defer></script>
</body>

</html>