<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Funcionario;
use App\Models\Ponto;

class FuncionarioController extends Controller
{    
    /**
     * Habilita a tela de cadastro do funcionário
     */
    public function index() {        
        return view('admin', ['novoF' => true]);
    }

    /**
     * Cria um funcionário novo
     * 
     * @param $request['nome']  Nome do funcionário
     * @param $request['chave'] Chave de acesso do funcionário
    */
    public function criarFuncionario(Request $request) {
        try {
            if (!trim($request['nome']) || !trim($request['chave']))
                return redirect('/novo-funcionario')->with('error', 'Erro ao criar funcionário. Preencha os campos corretamente');

            $funcionario = Funcionario::where('chave', '=', trim($request['chave']))->first();
            if ($funcionario) 
                return redirect('/novo-funcionario')->with('error', 'Chave de acesso já existe');            

            $funcionario = new Funcionario();
            $funcionario->nome  = trim($request['nome']);
            $funcionario->chave = trim($request['chave']);
            $funcionario->save();

            return redirect('/novo-funcionario')->with('success', 'Funcionário criado com sucesso. CHAVE: '.$funcionario->chave);
        } catch (\Exception $e) {}
        
        return redirect('/novo-funcionario')->with('error', 'Erro ao criar funcionário. Preencha os campos corretamente');
    }

    /**
     * Remove um funcionário
     * 
     * @param $request['id'] ID do funcionário a ser removido     
    */
    public function removeFuncionario(Request $request) {
        try {
            \DB::beginTransaction();

            Funcionario::destroy($request['id']);
            Ponto::where('idFuncionario', '=', $request['id'])->delete();

            \DB::commit();
            return response(200);
        } catch (\Exception $e) {
            \DB::rollBack();
        }
        
            return false;        
    }

    
}
