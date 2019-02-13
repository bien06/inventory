<title>@yield('title')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
    <script src="https://use.fontawesome.com/07b0ce5d10.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>    

<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('home') }}">ALL CARD</a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{url('change_password')}}">Change Password</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>                    
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br/><br/>

    <div class='container'>
        @yield('content')
    </div>
</body>

<div id='js'>
    @yield('js')
    <script>
        $(document).ready(function () {
            var table = $('#userTable').DataTable();
            $('#userTable').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $('#eid').val('');
                    $('#ename').val('');
                    $('#euserid').val('');
                    $('#egood').val('');
                    $('#ereject').val('');
                    $(this).removeClass('selected');
                    $("button[name='submit']").attr('disabled', 'disabled');
                } else {
                    $('#eid').val($(this).data('id'));
                    $('#ename').val($(this).data('itemname'));
                    $('#euserid').val($(this).data('user'));
                    $('#egood').val($(this).data('good'));
                    $('#ereject').val($(this).data('reject'));
                    $("button[name='submit']").removeAttr('disabled');
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
        });
    </script>
    <script type="text/javascript">
        document.getElementById('void').onclick = function () {
            var reason = prompt("Please enter a reason:");
            if (reason === null || reason === '') {
                $.alert({
                    type: 'red',
                    icon: 'fa fa-warning',
                    btnClass: 'btn-red',
                    title: 'Error',
                    backgroundDismiss: true,
                    content: 'Please enter a reason!'
                });
                event.preventDefault();
            } else {
                document.getElementById("reason").value = reason;
                document.getElementById("itemClass").submit();
            }
        };
    </script>
    <script type="text/javascript">
        document.getElementById('update').onclick = function () {
            var reject = document.getElementById("ereject").value;
            if (reject == 0 || reject == null) {
                return confirm("Are you sure you want to update this item?");
            } else {
                var reason = prompt("Please enter a reason:");
                if (reason === null || reason === '') {
                    $.alert({
                        type: 'red',
                        icon: 'fa fa-warning',
                        btnClass: 'btn-red',
                        title: 'Error',
                        backgroundDismiss: true,
                        content: 'Please enter a reason!'
                    });
                    event.preventDefault();
                } else {
                    document.getElementById("reason").value = reason;
                    document.getElementById("itemClass").submit();
                }
            }
        };
    </script>    
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</div>
</head>
