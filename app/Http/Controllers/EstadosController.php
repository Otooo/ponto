<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Ponto;
use App\Models\Funcionario;
use App\Models\EstadoPonto;

/**
 * I   - TRABALHO
 * II  - SAI ALMOÇO
 * III - VOLTA ALMOÇO
 * IV  - EMBORA
 * V   - SAI LANCHE
 * VI  - VOLTA LANCHE
 */
class EstadosController extends Controller {
 
    /**
     * Apresenta a máquina de bater ponto
     */
    public function index() {
        return view('maquinaPonto');
    }

    /**
     * Recupera o estado anterior, estado atual do ponto 
     * do funcionário e verifica se já lanchou para retornar
     * os possíveis estados seguintes
     * 
     * @param $request['chave'] Código de identificação do funcionário para bater o ponto
     */
    public function checarEstadosVisiveis(Request $request) {        
        if ($request['chave']) {
            $funcionario = Funcionario::where('chave', '=', $request['chave'])->first();
            if ($funcionario) {                                
                if ($this->checarBateuPontoMenor5m($funcionario->id)) 
                    return redirect('/')->with(['error' => 'Funcionário já bateu ponto nos útimos 5 minutos. Tente novamente mais tarde']);
                
                $ponto = Ponto::where('idFuncionario', '=', $funcionario->id)
                ->groupBy('idEstadoPonto')
                ->orderBy('idEstadoPonto', 'asc')
                ->orderBy('horario', 'desc')
                ->get();                                

                $momentoAtual = Carbon::now('America/Bahia');
                $ponto = $ponto->filter(function ($p) use ($momentoAtual) {
                    $p_aux = new Carbon($p->horario, 'America/Bahia');
                    return ($momentoAtual->diffInDays($p_aux) == 0);
                });
                $trabalho = null;
                $almoco   = null;
                $lanche   = null;
                foreach($ponto as $p) {
                    if ($p->idEstadoPonto == 1) {
                        $trabalho['ini'] = $p->horario;
                    } else if ($p->idEstadoPonto == 4) {
                        $trabalho['fim'] = $p->horario;
                    } else if ($p->idEstadoPonto == 2) {
                        $almoco['ini'] = $p->horario;
                    } else if ($p->idEstadoPonto == 3) {
                        $almoco['fim'] = $p->horario;
                    } else if ($p->idEstadoPonto == 5) {
                        $lanche['ini'] = $p->horario;
                    } else if ($p->idEstadoPonto == 6) {
                        $lanche['fim'] = $p->horario;
                    }
                }
                
                $pontoAtual = $funcionario->idEstadoPontoAtual;
                $jaLanchou  = $funcionario->jaLanchou;
                $jaAlmocou  = $funcionario->jaAlmocou;
                
                if ($pontoAtual == 1) {                
                    // 2, 4, 5
                    $estadosVisiveis = EstadoPonto::whereIn('id', [2, 4, 5])->get();
                } else if ($pontoAtual == 2) {
                    // 3
                    $estadosVisiveis = EstadoPonto::whereIn('id', [3])->get();
                } else if ($pontoAtual == 3) {                                        
                    if ($jaLanchou != 1) {
                        // 4, 5
                        $estadosVisiveis = EstadoPonto::whereIn('id', [4, 5])->get();
                    } else {
                        // 4
                        $estadosVisiveis = EstadoPonto::whereIn('id', [4])->get();
                    }
                } else if ($pontoAtual == 4) {
                    // 1
                    $estadosVisiveis = EstadoPonto::whereIn('id', [1])->get();
                } else if ($pontoAtual == 5) {
                    // 6
                    $estadosVisiveis = EstadoPonto::whereIn('id', [6])->get();
                } else if ($pontoAtual == 6) {
                    if ($jaAlmocou != 1) {
                        // 2, 4
                        $estadosVisiveis = EstadoPonto::whereIn('id', [2, 4])->get();
                    } else {
                        // 4
                        $estadosVisiveis = EstadoPonto::whereIn('id', [4])->get();
                    }
                }          

                return view('maquinaPonto', [
                    'funcionario'          => $funcionario,                    
                    'estadosVisiveis'      => $estadosVisiveis,
                    'pontoTrabalho'        => $trabalho,
                    'pontoAlmoco'          => $almoco,
                    'pontoLanche'          => $lanche
                ]);                    
            }            
        }
        
        return redirect('/')->with(['error' => 'Funcionário não encontrado']);
    }

    /**
     * Verifica se o funcionário já bateu ponto nos últimos
     * cinco minutos
     * 
     * @param $idFuncionario ID do funcionário
     */
    public function checarBateuPontoMenor5m($idFuncionario) {
        // verifica 
        $ponto = Ponto::where('idFuncionario', '=', $idFuncionario)
        ->orderBy('horario', 'desc')
        ->first();        

        if(! $ponto)
            return false;

        $ultimoPonto = new Carbon($ponto->horario, 'America/Bahia');
        $momento     = Carbon::now('America/Bahia');

        if ($momento < $ultimoPonto)
            return false;
        
        $diferencaS = $momento->diffInSeconds($ultimoPonto);        

        if ($diferencaS <= 300)
            return true;

        return false;
    }

}