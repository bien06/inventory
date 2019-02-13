
<title>@yield('title')</title>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">

    <script type="text/javascript" src="https://use.fontawesome.com/07b0ce5d10.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<body>

    <style>
        #load{
            width:100%;
            height:100%;
            position:fixed;
            z-index:9999;
            background:url("https://www.creditmutuel.fr/cmne/fr/banques/webservices/nswr/images/loading.gif") no-repeat center center rgba(0,0,0,0)
        }        

    </style>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('dashboard') }}">ALL CARD</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id='new'>
                    <li class='{{ Request::is('dashboard') ? 'active' : '' }}'><a href='{{url('dashboard')}}'>Dashboard</a></li>
                    <li class="{{ Request::is('item') ? 'active' : '' }}"><a href="{{url('item')}}">Item Management </a></li>
                    <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{url('users')}}">User Management</a></li>
                    <li class="{{ Request::is('assignment') ? 'active' : '' }}"><a href="{{url('assignment')}}">Item Assignment</a></li>                    
                    <li class="{{ Request::is('query') ? 'active' : '' }}"><a href="{{url('query')}}">Queries </a></li>
                    @if(Auth::guard('admin_user')->user()->role == 1)
                    <li class="{{ Request::is('audit') ? 'active' : '' }}"><a href="{{url('audit')}}">Audit </a></li>
                    @endif
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('admin_user')->user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::guard('admin_user')->user()->role == 1)
                            <li><a href="{{url('admin')}}">Manage Admins</a></li>
                            <li class="divider"></li>
                            @endif
                            <li>
                                <a href="{{ url('admin_logout') }}"
                                   onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('admin_logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <br/><br/>

    <div id="load"></div>
    <div class='container' id ="contents">
        @yield('content')
    </div>
</body>

<div id='js'> @yield('js')

    <script type="text/javascript">
        document.onreadystatechange = function () {
            var state = document.readyState
            if (state == 'interactive') {
                document.getElementById('contents').style.visibility = "hidden";
            } else if (state == 'complete') {
                setTimeout(function () {
                    document.getElementById('interactive');
                    document.getElementById('load').style.visibility = "hidden";
                    document.getElementById('contents').style.visibility = "visible";
                }, 250);
            }
        };

        //script for item table
        $(document).ready(function () {
            var table = $('#item').DataTable();
            $('#item').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $('#rid').val('');
                    $('#eid').val('');
                    $('#ename').val('');
                    $('#ecount').val('');
                    $("button[name='submit']").attr('disabled', 'disabled');
                    $(this).removeClass('selected');
                } else {
                    $('#rid').val($(this).data('id'));
                    $('#eid').val($(this).data('id'));
                    $('#ename').val($(this).data('name'));
                    $('#ecount').val($(this).data('count'));
                    $("button[name='submit']").removeAttr('disabled');
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

            });
        });

        // script for item assign table
        $(document).ready(function () {
            var table = $('#itemAssign').DataTable();
            $('#itemAssign').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $('#eid').val('');
                    $('#ename').val('');
                    $('#ecount').val('');
                    $("button[name='submit']").attr('disabled', 'disabled');
                    $(this).removeClass('selected');
                } else {
                    $('#eid').val($(this).data('id'));
                    $('#ename').val($(this).data('name'));
                    $('#ecount').val($(this).data('count'));
                    $("button[name='submit']").removeAttr('disabled');
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

            });
        });

        //script for limit table
        $(document).ready(function () {
            var table = $('#limit').DataTable();
            $('#limit').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $('#lid').val('');
                    $('#llocation').val('');
                    $('#litem').val('');
                    $('#llimits').val('');
                    $('#litemid').val('');
                    $('#lbranchid').val('');
                    $("button[name='submit']").attr('disabled', 'disabled');
                    $(this).removeClass('selected');
                } else {
                    $('#lid').val($(this).data('id'));
                    $('#llocation').val($(this).data('location'));
                    $('#litem').val($(this).data('name'));
                    $('#llimits').val($(this).data('limits'));
                    $('#litemid').val($(this).data('item'));
                    $('#lbranchid').val($(this).data('branch'));
                    $("button[name='submit']").removeAttr('disabled');
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

            });
        });

        // script for admin user table
        $(document).ready(function () {
            var table = $('#userTbl').DataTable();
            $('#userTbl').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $('#eid').val('');
                    $('#rid').val('');
                    $('#ename').val('');
                    $('#elocation').val(0);
                    $('#eemail').val('');
                    $('#estatus').val('');
                    $("button[name='submit']").attr('disabled', 'disabled');

                    $(this).removeClass('selected');
                } else {
                    $('#eid').val($(this).data('id'));
                    $('#rid').val($(this).data('id'));
                    $('#ename').val($(this).data('name'));
                    $('#elocation').val($(this).data('location'));
                    $('#eemail').val($(this).data('email'));
                    $('#estatus').val($(this).data('status'));
                    $("button[name='submit']").removeAttr('disabled');

                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
        });

        // data tables without action
        $(document).ready(function () {
            $('#display').DataTable({
                "order": [[0, "desc"]],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]                
            });
            $('#displayUsers').DataTable({
                "order": [[0, "desc"]],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]                
            });
            $('#assigns').DataTable();
            $('#newuser').DataTable();
            $('#newstock').DataTable();

        });

    </script>
</div>

</head>
