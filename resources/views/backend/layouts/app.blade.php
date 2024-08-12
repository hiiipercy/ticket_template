<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | Myolbd Ticket Application</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('/') }}css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/') }}css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/') }}css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}css/select2.min.css">
    {{-- Datatable --}}
    <link href="{{ asset('/') }}css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.3/css/dataTables.responsive.css">
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('/') }}css/jquery-jvectormap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/') }}css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins -->
    <link rel="stylesheet" href="{{ asset('/') }}css/_all-skins.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}css/custom.css">
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        @stack('styles')
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include('backend.include.header')

        <!-- Left side column. contains the logo and sidebar -->
        @include('backend.include.sidebar')

        <!-- Content Wrapper. -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>{{ $title }}</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Dashboard</a>
                    </li>
                    @isset($breadcrumb)
                    @foreach ($breadcrumb as $title=>$url)
                    <li class="breadcrumb-item {{ $loop->last ? '' : 'active' }}">@if ($loop->last){{ $title }}@else <a
                            href="{{ $url }}">{{ $title }}</a>@endif</li>
                    @endforeach
                    @endisset
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
        </div>
        @include('backend.include.footer')

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('/') }}js/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('/') }}js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{{ asset('/') }}js/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/') }}js/adminlte.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ asset('/') }}js/jquery.sparkline.min.js"></script>
    <script src="{{ asset('/') }}js/select2.full.min.js"></script>
    <!-- jvectormap  -->
    <script src="{{ asset('/') }}js/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="{{ asset('/') }}js/jquery-jvectormap-world-mill-en.js"></script>
    <!-- include summernote css/js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Datatables --}}
    <script class="script" src="{{ asset('/') }}js/jquery.dataTables.min.js"></script>
    <script class="script" src="{{ asset('/') }}js/dataTables.bootstrap.min.js"></script>
    <script class="script" src="https://cdn.datatables.net/responsive/1.0.3/js/dataTables.responsive.min.js"></script>
    <script class="script" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('/') }}js/jquery.slimscroll.min.js"></script>

    <!--calender option -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- ChartJS -->
    <script src="{{ asset('/') }}js/Chart.js"></script>
    <!-- AdminLTE dashboard -->
    {{-- <script src="{{ asset('/') }}js/dashboard.js"></script> --}}
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('/') }}js/demo.js"></script>
    <script src="{{ asset('/') }}js/main.js"></script>
    <script>
        // ajax header setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // token
        var _token = "{{ csrf_token() }}";
        var table;

        // summernote
        $(document).ready(function() {
        $('#description_summernote').summernote();
        });

        // toastr alert message
        function notification(status, message) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            switch (status) {
                case 'success':
                    toastr.success(message);
                    break;

                case 'error':
                    toastr.error(message);
                    break;

                case 'warning':
                    toastr.warning(message);
                    break;

                case 'info':
                    toastr.info(message);
                    break;
            }
        }

        // session flash message
        @if(session()->get('success'))
        notification('success',"{{ session()->get('success') }}")
        @elseif(session()->get('error'))
        notification('error',"{{ session()->get('error') }}")
        @elseif(session()->get('info'))
        notification('info',"{{ session()->get('info') }}")
        @elseif(session()->get('warning'))
        notification('warning',"{{ session()->get('warning') }}")
        @endif

        $('.select2').select2();
        $('.select2-hide').select2({
            minimumResultsForSearch : -1
        });

        // search table
        $(document).on('keyup keypress','input[name="search_here"]',function(){
            table.ajax.reload();
        });
    </script>
    @stack('scripts')
</body>
</html>