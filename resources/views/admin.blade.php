<!doctype html>
<html lang="{{ app()->getLocale() }}" style="max-width: 100%; overflow-x: hidden;">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />        
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Administrador</title>

        <!-- Fonts and Icons -->                
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">        
        <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>    
        <link href="/css/themify-icons.css" rel="stylesheet">

        <!-- Styles -->        
        <link href="/css/paper-dashboard.css" rel="stylesheet"/>
        <link href="/css/animate.min.css" rel="stylesheet"/>
        <link href="/css/bootstrap3.3.7.min.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="wrapper">
            <div class="sidebar" data-background-color="white" data-active-color="info">        
            <!--
                Tip 1: you can change the color of the sidebars background using: data-background-color="white | black"
                Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
            -->
        
                <div class="sidebar-wrapper">
                    <div class="logo">
                        <a href="{{ url('/') }}" class="simple-text" data-toggle='tooltip' data-placement='bottom' title='Ir para a página de bater ponto'>
                            Máquina Ponto
                        </a>
                    </div>
        
                    <ul class="nav">
                        <li @if(isset($ponto)) class="active" @endif>
                            <a href="{{ url('/admin') }}">
                                <i class="ti-view-list-alt"></i>
                                <p>Registros</p>
                            </a>
                        </li>
                        <li @if(isset($funcionarios) || isset($novoF)) class="active" @endif>
                            <a href="{{ url('/admin-funcionarios') }}">
                                <i class="ti-user"></i>
                                <p>Funcionários</p>
                            </a>                            
                        </li>                        
                    </ul>
                </div>
            </div>
        
            <div class="main-panel">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="#">Administrativo Máquina Ponto</a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="ti-settings"></i>
                                        <p>Configuarações</p>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Sair</a></li>
                                    </ul>
                                </li>
                            </ul>
        
                        </div>
                    </div>
                </nav>
            
                {{--  CONTEÚDO LATERAL DIREITO  --}}
                <div class="content">
                    <div class="container-fluid">
                        {{--  LISTA DE PONTOS  --}}
                        @if(isset($ponto))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="header">
                                            <h4 class="title">Pontos funcionários</h4>
                                            <hr>
                                            
                                            <div class="container">
                                                <div class="row">
                                                    <form action="/admin" novalidate method="get" role="form">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">                                                        
                                                                    <input type="text" maxlength="30" class="form-control border-input" name="nome" placeholder="Nome do funcionário">
                                                                </div>
                                                            </div>

                                                            <div class='col-md-1'>
                                                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                    Situação
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                                        <li><a style="cursor: pointer;" onclick="filtrarPonto(null)">Todas</a></li>
                                                                    @foreach($estados as $estado)
                                                                        <li><a style="cursor: pointer;" onclick="filtrarPonto({{ $estado }})">{{ $estado->descricao }}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class='col-md-3'>
                                                                <input type='text' disabled class="form-control" id="estado" placeholder="Situação ponto" value="Todas" />
                                                                <input type='text' hidden id="estadoInput" name="estado"/>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">                                                            
                                                            <div class="col-md-4">
                                                                <label>Dia e horário de início</label>
                                                                <div class="form-group">    
                                                                    <input type="date" class="form-control border-input" id="dataI" name="dataIni">
                                                                </div>
                                                                <div class="form-group"> 
                                                                    <input type="time" class="form-control border-input" id="horaI" name="horaIni">
                                                                </div>                                                                
                                                            </div>
                                                                                                                        
                                                            <div class="col-md-4">
                                                                <label>Dia e horário de término</label>
                                                                <div class="form-group">    
                                                                    <input type="date" class="form-control border-input" id="dataF" name="dataFim">
                                                                </div>
                                                                <div class="form-group"> 
                                                                    <input type="time" class="form-control border-input" id="horaF" name="horaFim">
                                                                </div>                                                                
                                                            </div>
                                                        </div>

                                                        {{--  FILTRAR  --}}
                                                        <hr>
                                                        <div>
                                                            <button type="input" class="btn btn-info btn-fill btn-wd"><i class="ti-search"></i></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <br>                                                                                
                                        <div class="content table-responsive table-full-width">
                                            @if(empty($ponto) || count($ponto) == 0 )
                                                <h2 class="text-center">Não existem informações cadastradas</h2>
                                            @else
                                                <table class="table table-striped">
                                                    <thead>
                                                        {{--  <th>ID</th>  --}}
                                                        <th>Funcionário</th>
                                                        <th>Situação Ponto</th>
                                                        <th>Dia e Horário</th>
                                                    </thead>
                                                    <tbody>                                                    
                                                        @foreach($ponto as $pon)
                                                            <tr>
                                                                {{--  <td> {{ $pon->id }} </td>  --}}
                                                                <td> {{ $pon->nome }} </td>
                                                                <td> {{ $pon->descricao }} </td>
                                                                <td> {{ date('d/M/Y H:i:s', strtotime($pon->horario)) }} </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>                                        
                                    </div>
                                </div>
                            </div>                            
                        @endif                        

                        {{--  GRÁFICOS  --}}
                        @if(isset($horasTrabalhadas) && isset($horasAlmoco) && isset($horasLanche))
                            @if($horasTrabalhadas>0 && $horasAlmoco>0 && $horasLanche>0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="header">
                                                <h4 class="title">Análise de horas</h4>
                                                <p class="category">Combinando este(s) funcionário(s)</p>
                                            </div>
                                            <div class="content">
                                                <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                
                                                <div class="footer">
                                                    <div class="chart-legend">
                                                        <i class="fa fa-circle text-info"></i> Trabalhando ({{ $horasTrabalhadas }} horas)
                                                        <br>
                                                        <i class="fa fa-circle text-danger"></i> Almoçando ({{ $horasAlmoco }} horas)
                                                        <br>
                                                        <i class="fa fa-circle text-warning"></i> Lanchando ({{ $horasLanche }} horas)
                                                    </div>
                                                    <hr>
                                                    <div class="stats">
                                                        <i class="ti-timer"></i> Horas ativas de pontos do(s) funcionário(s)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{--  ESTATISTICAS  --}}
                        @if(isset($totalFuncionarios))
                            <div class="row">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="card">
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="icon-big icon-warning text-center">
                                                        <i class="ti-user"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xs-7">
                                                    <div class="numbers">
                                                        <p>Funcionários</p>
                                                        {{ $totalFuncionarios }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="footer">
                                                <hr />
                                                <div class="stats"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        @endif
                        
                        {{--  LISTA DOS FUNCIONÁRIOS  --}}
                        @if(isset($funcionarios))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="header">
                                            <h4 class="title">Lista de funcionários</h4>
                                            <br>
                                            <a href="{{ url('/novo-funcionario') }}" class="btn btn-info btn-fill btn-wd">Adicionar novo funcionário</a>                                            
                                        </div>
                                        <br>
                                        @if ($totalFuncionarios > 0)
                                            <div class="content table-responsive table-full-width">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <th>ID</th>
                                                        <th>Nome</th>
                                                        <th>Chave</th>
                                                        <th class="text-right">Remover Funcionário</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($funcionarios as $funcionario)
                                                            <tr>
                                                                <td>{{ $funcionario->id }}</td>
                                                                <td>{{ $funcionario->nome }}</td>
                                                                <td>{{ $funcionario->chave }}</td>
                                                                <td class="text-right">
                                                                    <button type="button" onclick="removerFuncionario({{$funcionario}})" class="btn btn-danger btn-fill btn-wd"><i class="ti-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{--  FORM NOVO USUÁRIO  --}}                        
                        @if(isset($novoF))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="header">
                                            <h4 class="title">Novo funcionário</h4>
                                        </div>
                                        <div class="content">
                                            <form action="/criar-funcionario" novalidate method="POST" role="form">
                                                {{ csrf_field() }}
                                                <div class="row">                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nome</label>
                                                            <input type="text" maxlength="30" class="form-control border-input" name="nome" placeholder="Nome do funcionário">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Chave de acesso do funcionário</label>
                                                            <input type="text" maxlength="10" class="form-control border-input" name="chave" placeholder="Chave de acesso do funcionário">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <button type="input" class="btn btn-info btn-fill btn-wd">Criar funcionário</button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </form>
                                            
                                            <br><br>                                            
                                            @if(\Session::has('success'))
                                                <div class="alert alert-success" id="error" role="alert">                                                    
                                                    <span class="sr-only">Erro: </span>
                                                    <span id="msg-error"><b>{{ \Session::get('success') }}</b></span>
                                                </div>
                                            @endif

                                            @if(\Session::has('error'))
                                                <div class="alert alert-danger" id="error" role="alert">                                                    
                                                    <span class="sr-only">Erro: </span>
                                                    <span id="msg-error"><b>{{ \Session::get('error') }}</b></span>
                                                </div>
                                            @endif  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
        
                {{--  REFERENCIA  --}}
                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>
        
                                <li>
                                    <a href="http://www.creative-tim.com">
                                        Creative Tim
                                    </a>
                                </li>
                                <li>
                                    <a href="http://blog.creative-tim.com">
                                        Blog
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.creative-tim.com/license">
                                        Licenses
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="copyright pull-right">
                            &copy; 2019, made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>
                        </div>
                    </div>
                </footer>                                
            </div>
        </div>        
    </body>
    
    <!-- Scripts -->
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/chartist.min.js"></script>
    <script src="/js/bootstrap3.3.7.min.js"></script>
    <script src="/js/sweetalert.min.js"></script>    
    <script type="text/javascript">
        window.onload = function() {
            
            let horasTrabalho = "{{ isset($horasTrabalhadas)? $horasTrabalhadas: 'null'}}";
            let horasAlmoco   = "{{ isset($horasAlmoco)? $horasAlmoco: 'null'}}";
            let horasLanche   = "{{ isset($horasLanche)? $horasLanche: 'null'}}";

            if (horasTrabalho != 'null' && horasAlmoco != 'null' && horasLanche != 'null') {
                horasTrabalho = parseFloat(horasTrabalho);
                horasAlmoco   = parseFloat(horasAlmoco);
                horasLanche   = parseFloat(horasLanche);

                let total = (horasTrabalho + horasAlmoco + horasLanche);
                let pT = (100*horasTrabalho/(total*1.0)).toFixed(2);
                let pA = (100*horasAlmoco/(total*1.0)).toFixed(2);
                let pL = (100*horasLanche/(total*1.0)).toFixed(2);

                Chartist.Pie('#chartPreferences', {
                    labels: [
                        pT+'%',
                        pA+'%',
                        pL+'%'
                    ],
                    series: [pT, pA, pL]
                });
            }
        }

        function removerFuncionario(funcionario) {
            console.log(funcionario);            

            swal({
                title: "Remover Funcionário!",
                text: "Tem certeza que deseja remover > "+funcionario.nome+" < do sistema?",
                type: "warning",                
                icon: "warning",
                buttons: {
                    cancel: "Cancelar",
                    catch: {
                        text: "Sim, remover!",
                        value: true,
                    },                    
                },
                dangerMode: true,                
            }).then(isConfirm => {
                if (!isConfirm) return;

                console.log("olá");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url     : '/remover-funcionario',                    
                    type    : 'post',
                    data    : {"id": funcionario.id},

                    success: function (result) {
                        if (result) {
                            swal("Sucesso!", "Funcionário removido!", "success").then(val => {
                                location.reload();
                            });
                        } else {
                            swal("Erro!", "Não foi possível remover este funcionário", "error");    
                        }
                    },
                    error: function (result) {
                        swal("Erro!", "Não foi possível remover este funcionário. Tente novamente mais tarde", "error");
                    }
                });
            });
        }

        function filtrarPonto(estadoPonto) {            
            if (estadoPonto) {
                jQuery("#estado").val(estadoPonto.descricao);
                jQuery("#estadoInput").val(estadoPonto.id);
            } else {
                jQuery("#estado").val('Todas');
                jQuery("#estadoInput").val(null);
            }
        }
                
        jQuery("#dataI").focusout(function () {
            console.log(jQuery("#dataI").val());
        });        
    </script>
</html>