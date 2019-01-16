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
class AdminController extends Controller
{
    /**
     * Recupera informações sobre os horários dos funcionários
     * 
     * @param $request['dataIni']
     * @param $request['horaIni']
     * @param $request['dataFim']
     * @param $request['horaFim']
     * @param $request['nome']
     * @param $request['estado']
     */
    public function registros(Request $request) {                                
        $ponto = Ponto::join('FUNCIONARIO', 'FUNCIONARIO.id', '=', 'PONTO.idFuncionario')
            ->join('ESTADO_PONTO', 'ESTADO_PONTO.id' , '=', 'PONTO.idEstadoPonto')                
            ->orderBy('PONTO.id', 'FUNCIONARIO.id', 'PONTO_ESTADO.id');
        
        /** Inicio filtragem **/
        $filtro = $request->all();        

        // Inicio do horário
        if(!empty($filtro) && ($filtro['dataIni'] || $filtro['horaIni'])) {
            $completo = $filtro['dataIni'] .' '. $filtro['horaIni'];
            
            if ($filtro['dataIni'])
                $dataHora = new Carbon($completo, "America/Bahia");
            else
                $dataHora = Carbon::createFromTimeString($filtro['horaIni'], "America/Bahia");
        
            $ponto = $ponto->where('PONTO.horario', '>=', $dataHora);
        }

        // Fim do horário
        if(!empty($filtro) && ($filtro['dataFim'] || $filtro['horaFim'])) {
            $completo = $filtro['dataFim'] .' '. $filtro['horaFim'];
            
            if ($filtro['dataFim'])
                $dataHora = new Carbon($completo, "America/Bahia");
            else
                $dataHora = Carbon::createFromTimeString($filtro['horaFim'], "America/Bahia");
        
            $ponto = $ponto->where('PONTO.horario', '<=', $dataHora);
        }

        // Funcionário
        if(!empty($filtro) && trim($filtro['nome'])) {
            $ponto = $ponto->where(strtolower('FUNCIONARIO.nome'), 'like', '%'. strtolower(trim($filtro['nome'])) .'%');
        }

        // Tipo de ponto
        if(!empty($filtro) && $filtro['estado']) {
            $ponto = $ponto->where('ESTADO_PONTO.id', '=', $filtro['estado']);
        }
        /** Fim filtragem **/

        $ponto = $ponto->get(['PONTO.id', 'FUNCIONARIO.nome', 'ESTADO_PONTO.descricao', 'PONTO.horario', 'PONTO.idEstadoPonto']);
        
        $estados = EstadoPonto::all();

        $estatistica = $this->graficoHoras($ponto);        

        return view('admin', [        
            'ponto'            => $ponto,
            'estados'          => $estados,
            
            'horasTrabalhadas' => $estatistica['T'],
            'horasAlmoco'      => $estatistica['A'],
            'horasLanche'      => $estatistica['L']
        ]);
    }

    /**
     * Retorna a lista de funcionários juntamente
     * com a quantidade de funcionários cadastrados
     * no sistema
     */
    public function funcionarios() {
        $funcionarios = Funcionario::all();
        $totalFuncionarios = $funcionarios->count();

        return view('admin', [
            'funcionarios'         => $funcionarios,
            'totalFuncionarios'    => $totalFuncionarios,
        ]);
    }

    /**
     * Gera a combinação das horas para os estados
     * trabalhando, almoçando e lanchando a partir
     * da lista dos pontos (horários-estado) fornecido
     * 
     * @param $ponto Lista dos pontos a ser analisada
     * 
     * @return ['T', 'A', 'L'] Horas ativas para trabalho, almoço e lanche
     */
    private function graficoHoras($ponto) {
        $horarioAgora = new Carbon('now', "America/Bahia");        
        
        // Horas somadas de entradas
        $eT = 0;
        $eA = 0;
        $eL = 0;

        // Horas somadas de saidas
        $sT = 0;
        $vA = 0;
        $vL = 0;
        
        foreach ($ponto as $p) {
            $horario = new Carbon($p->horario, 'America/Bahia');
            switch ($p->idEstadoPonto) {
                case 1: // Entrou trabalho                    
                    $eT += $horarioAgora->diffInHours($horario);
                    break;
                case 2: // foi almoço
                    $eA += $horarioAgora->diffInHours($horario);
                    break;
                case 3: // voltou almoço
                    $vA += $horarioAgora->diffInHours($horario);
                    break;
                case 4: // Saiu trabalho
                    $sT += $horarioAgora->diffInHours($horario);
                    break;
                case 5: // foi lanche
                    $eL += $horarioAgora->diffInHours($horario);
                    var_dump($eL);
                    break;
                case 6: // voltou lanche
                    $vL += $horarioAgora->diffInHours($horario);
                    break;
            }
        }        
        
        $almoco   = $eA - $vA;
        $lanche   = $eL - $vL;
        $trabalho = $eT - $sT -$almoco-$lanche;

        return [
            'T' => $trabalho,
            'A' => $almoco,
            'L' => $lanche
        ];
    }
}
