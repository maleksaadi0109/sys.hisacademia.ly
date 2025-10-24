<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'La Academia Hispanolibia') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet"
          integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006288/BBBootstrap/choices.min.css?version=7.0.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

    <!-- jQuery - LOAD ONLY ONCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
<div id="loading">
    <p><img src="{{ asset('loading.gif') }}" /> الرجاء الانتظار</p>
</div>

<div class="min-h-screen">
    <!-- Page Header -->
    @if (isset($header))
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            {{ $header }}
        </header>
    @endif

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            @if (isset($nav))
                {{ $nav }}
            @endif

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    {{ $title }}
                </div>

                <!-- Content from child views -->
                {{ $slot }}
            </main>
        </div>
    </div>
</div>

<!-- Additional Scripts -->
<script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006273/BBBootstrap/choices.min.js?version=7.0.0"></script>
<script src="{{ asset('js/script.js') }}"></script>

<!-- SweetAlert Success Messages -->
@if (Session::has('success'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "{{ Session::get('success') }}",
            showConfirmButton: false,
            timer: 2500
        });
    </script>
@endif

<!-- SweetAlert Error Messages -->
@if (Session::has('error'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "{{ Session::get('error') }}",
            showConfirmButton: false,
            timer: 2500
        });
    </script>
@endif

<!-- CRITICAL: Stack for page-specific scripts -->
@stack('scripts')

</body>
</html>
