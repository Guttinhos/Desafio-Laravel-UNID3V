<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;


class UserController extends Controller
{



    public function index(Request $request)
    {
        $user = new User;

        if ($request->has('action') && $request->get('action') === 'search') {
            $users = $user->filterAllUser($request);
        } else {
            $users = $user->OrderBy('name', 'asc')->paginate(20);
        }




        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.partials.create');
    }


    public function store(UserRequest $request)
    {
        try {

            $data = $request->all();
            $user = new User();
            $request->session()->flash('success', 'Usuário registrado com sucesso!');

            $user->create($data);
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Ocorreu um erro ao tentar gravar esses dados!' . $e->getMessage());
        }

        return redirect()->back();
    }


    public function show(User $user)
    {
    }



    public function edit(Request $request, User $user)
    {

        return view('users.partials.edit', compact('user'));
    }



    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();

        try {


            if (empty($request->get('password'))) {
                unset($data['password']);
            }


            $user->fill($data);
            $user->save();


            $request->session()->flash('success', 'Registro atualizado com sucesso!');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Ocorreu um erro ao atualizar esses dados!' . $e->getMessage());            //$e->getMessage()); //pegar o erro

        }



        return redirect()->back();
    }


    public function destroy(Request $request, User $user)
    {
        try {
            $user->delete();


            $request->session()->flash('success', 'Registro excluído com sucesso!');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Ocorreu um erro ao tentar excluir esses dados!');
        }

        return redirect()->back();
    }
}
