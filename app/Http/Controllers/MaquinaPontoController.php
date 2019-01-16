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
class MaquinaPontoController extends Controller {

    /**
     * Atribui um ponto ao funcionário
     * 
     * @param $request['chave']    Código de identificação do funcionário para bater o ponto
     * @param $request['idPonto']  Identificação do estado do ponto
     * @param $request['dataHora'] Momento da batida do ponto formatado assim: 'dia mês ano hora minuto segundo'
     */
    public function ativarEstado(Request $request) {
        try {
            list ($dia, $mes, $ano, $hora, $minuto, $segundo) = explode(" ", $request['dataHora']);

            //dd($hora." ".$minuto." ".$segundo." ".$dia." ".$mes." ".$ano);
            $dataHora = new Carbon($ano.'-'.$mes.'-'.$dia." ".$hora.":".$minuto.":".$segundo, 'America/Bahia');
            
            $funcionario = Funcionario::where('chave', '=', $request['chave'])->first();
            
            $funcionario->idEstadoPontoAtual = $request['idPonto'];
            
            if ($request['idPonto'] == 1) { // Entrou no trabalho
                //$this->entrarTrabalho($funcionario->id, $dataHora);
            } else if ($request['idPonto'] == 2) { // Saiu pra almoçar
                $funcionario->jaAlmocou = '1';
                //$this->sairAlmoco($funcionario->id, $dataHora);
            } else if ($request['idPonto'] == 3) { // Volta do almoço
                //$this->voltarAlmoco($funcionario->id, $dataHora);
            }  else if ($request['idPonto'] == 4) { // Saída do trabalho                
                //$this->sairTrabalho($funcionario->id, $dataHora);
                $funcionario = $this->resetJaAlgo($funcionario);
            } else if ($request['idPonto'] == 5) { // Saída para lanche
                //$this->sairLanche($funcionario->id, $dataHora);
            }  else if ($request['idPonto'] == 6) { // Volta do lanche
                //$this->voltarLanche($funcionario->id, $dataHora);
            }

            $ponto = new Ponto();
            $ponto->idFuncionario = $funcionario->id;
            $ponto->idEstadoPonto = $request['idPonto'];
            $ponto->horario       = $dataHora;
            $ponto->save();

            $funcionario->save();
            
            return redirect('/')->with(['success' => 'Operação realizada com sucesso']);
        } catch (\Exception $e) {}
        
        return redirect('/')->with(['error' => 'Operação não permitida']);
    }
    
    /**
     * Depois do estado IV resetar a flag de 'já saiu para lanchar no dia' e
     * 'Já almoçou'
     *
     * @param $funcionario funcionário que será modificado
     *
     * @return $funcionario O funcionário com as flags resetadas
     */
    private function resetJaAlgo($funcionario) {        
        $funcionario->jaLanchou = '0';
        $funcionario->jaAlmocou = '0';
        return $funcionario;
    }
}
