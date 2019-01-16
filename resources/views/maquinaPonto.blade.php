<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="favicon2.ico" type="image/x-icon" />
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Máquina Ponto</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="/css/flipclock.css">
        <link rel="stylesheet" href="/css/bootstrap4.2.1.min.css">
        <style>
            html, body {
                background-color: #f4f3ef;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .hide {
                display: none;
            }
        </style>       
    </head>
    <body>
        <div class="container" style="padding-top:50px;">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">                            
                            <div class="clock"></div>                            
                            <div style="text-align: center;">
                                <h1 id="dataHorario"></h1>
                            </div>

                            <hr>
                            @if(\Session::has('success'))
                                <div class="alert alert-success" id="error" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <span class="sr-only">Erro: </span>
                                    <span id="msg-error"><b>{{ \Session::get('success') }}</b></span>
                                </div>
                            @endif

                            @if(isset($estadosVisiveis) && isset($funcionario))
                                <form action="/ativarPonto" novalidate method="POST" role="form">
                                    {{ csrf_field() }}
                                    <input type="text" name="chave" value="{{ $funcionario->chave }}" hidden>
                                    <input type="text" name="dataHora" id="dataHora" hidden>
                                    <div class="row" id="estadosVisiveis">
                                    @foreach($estadosVisiveis as $estado)
                                        <button type='input' name="idPonto" value="{{ $estado->id }}" class='col btn btn-outline-primary' data-toggle='tooltip' data-placement='bottom' title='Seu ponto será marcado como: {{$estado->descricao}}' style='margin: 0 5px;' onclick="capturaDataHora()">
                                            <b>{{ $estado->descricao }}</b>
                                        </button>
                                    @endforeach
                                    </div>
                                </form>
                            @endif                            
                        </div>
                    </div>
                </div>
                                
                <div class="col-4">
                    <form action="/pontos" novalidate method="POST" role="form">
                        {{ csrf_field() }}
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="chave" id="chave" placeholder="Chave de acesso" aria-label="Chave de acesso" aria-describedby="buscarPorChave">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="input" id="buscarPorChave" onclick="buscar()">
                                    <b>Buscar</b>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="text-center hide" id="loading" >
                        <strong>Carregando dados de hoje...</strong>
                        <div class="spinner-grow text-info" role="status">
                            <span class="sr-only">Carregando dados de hoje...</span>
                        </div>
                    </div>

                    @if(\Session::has('error'))
                        <div class="alert alert-danger" id="error" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Erro: </span>
                            <span id="msg-error"><b>{{ \Session::get('error') }}</b></span>
                        </div>
                    @endif                    
                    
                    @if(isset($funcionario))
                        <table class="table table-striped" id="tabela">
                            <thead>
                                <tr>
                                <th scope="col">{{ $funcionario->nome }}</th>
                                <th scope="col">Útilmo inicio</th>                            
                                <th scope="col">Último término</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>
                                    <th scope="row">Trabalho</th>
                                    @if (isset($pontoTrabalho['ini']))
                                        <td> {{date('H:i:s', strtotime($pontoTrabalho['ini']))}} </td>
                                    @else
                                        <td> - </td>
                                    @endif
                                    @if (isset($pontoTrabalho['fim']))
                                        <td> {{date('H:i:s', strtotime($pontoTrabalho['fim']))}} </td>
                                    @else
                                        <td> - </td>
                                    @endif
                                </tr>
                                <tr>                                        
                                    <th scope="row">Almoço</th>                                        
                                    @if (isset($pontoAlmoco['ini']))
                                        <td> {{date('H:i:s', strtotime($pontoAlmoco['ini']))}} </td>
                                    @else
                                        <td> - </td>
                                    @endif
                                    @if (isset($pontoAlmoco['fim']))
                                        <td> {{date('H:i:s', strtotime($pontoAlmoco['fim']))}} </td>
                                    @else
                                        <td> - </td>
                                    @endif                                        
                                </tr>
                                <tr>                                        
                                    <th scope="row">Lanche</th>
                                    @if (isset($pontoLanche['ini']))
                                        <td> {{date('H:i:s', strtotime($pontoLanche['ini']))}} </td>
                                    @else
                                        <td> - </td>
                                    @endif
                                    @if (isset($pontoLanche['fim']))
                                        <td> {{date('H:i:s', strtotime($pontoLanche['fim']))}} </td>
                                    @else
                                        <td> - </td>                                        
                                    @endif                                        
                                </tr>
                            </tbody>                                                                             
                        </table>
                    @endif    
                </div>
            </div>
        </div>        
    </body>

    <!-- Scripts -->
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/flipclock.min.js"></script>
    <script src="/js/bootstrap4.2.1.min.js"></script>
    <script type="text/javascript">
        window.onload = function() {

            var clock = $('.clock').FlipClock(new Date(), {
                clockFace: 'TwentyFourHourClock',
                language:  'pt-BR'
                // ... your options here
            });  
            
            var dataHorario = new Date();
            let dia = (dataHorario.getDate() < 10)?  '0'+dataHorario.getDate():dataHorario.getDate();
            let mes = ((dataHorario.getMonth()+1) < 10)?  '0'+(dataHorario.getMonth()+1):(dataHorario.getMonth()+1);
            jQuery('#dataHorario').html(dia+'/'+mes+'/'+dataHorario.getFullYear());                
            setInterval(
                function() {
                    var dataHorario = new Date();
                    let dia = (dataHorario.getDate() < 10)?  '0'+dataHorario.getDate():dataHorario.getDate();
                    let mes = ((dataHorario.getMonth()+1) < 10)?  '0'+(dataHorario.getMonth()+1):(dataHorario.getMonth()+1);
                    jQuery('#dataHorario').html(dia+'/'+mes+'/'+dataHorario.getFullYear());
                }, 1000
            );                
        }

        function capturaDataHora() {
            var dataHorario = new Date();
            let dia = (dataHorario.getDate() < 10)?  '0'+dataHorario.getDate():dataHorario.getDate();
            let mes = ((dataHorario.getMonth()+1) < 10)?  '0'+(dataHorario.getMonth()+1):(dataHorario.getMonth()+1);
            let ano = dataHorario.getFullYear();

            let hora    = (dataHorario.getHours() < 10)? '0'+dataHorario.getHours():dataHorario.getHours();
            let minuto  = (dataHorario.getMinutes() < 10)? '0'+dataHorario.getMinutes():dataHorario.getMinutes();
            let segundo =(dataHorario.getSeconds() < 10)? '0'+dataHorario.getSeconds():dataHorario.getSeconds();

            jQuery("#dataHora").val(dia+' '+mes+' '+ano+' '+hora+' '+minuto+' '+segundo);
        }

        function buscar() {
            jQuery('#tabela').hide();
            jQuery('#error').hide();
            jQuery('#loading').show();
        }            
    </script>
</html>
