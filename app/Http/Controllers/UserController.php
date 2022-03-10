<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $config = Auth::user();
        return view('config.index', ['config' => $config]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $config = Auth::user();
        return view('config.index', ['config' => $config]);
    }

    public function create()
    {
        return view('auth.register');
    }

    public function insert(Request $request){

        try {
            
            $validator = $this->validator($request);
            
            if (!$validator->fails()) {
                
                $user = new User();
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->phone = $request['phone'];
                $user->password = Hash::make($request['password']);
                $user->save();
                
                Session::flash('success', 'Usuário cadastrado com sucesso!');

            } else {
                return back()->withErrors($validator)->withInput();
            }

        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors([$e->getMessage(), 'Erro ao cadastrar usuário!']);
        }

        return redirect('login');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {

            $user = User::findById(Auth::user()->id)->first();
            $user->name = $request->name;
            if($request->password){
                $user->password =  Hash::make($request->password);
            }
            $user->save();

            Session::flash('success', 'Usuário alterado com sucesso!');
            return redirect()->route('config');

        } catch (\Exception $e) {
            dd($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $user = User::findById(Auth::user()->id)->first();
        $user->delete();
        
        Session::flash('success', 'Usuário removido com sucesso!');

        return redirect()->route('home');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function validator(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'g-recaptcha-response' => 'required|captcha',
        ]);

        return $validator;
        
    }
}
