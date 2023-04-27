<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\ErrorHandler\Debug;

class UserController extends Controller
{
    public function show(){
        if(auth()->user()->roleChecker() == true)
            return abort(404);
        $users=User::where(['position' => 'Биржевой маклер'])->get();
        return view('users',['users' =>$users]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|min:5',
            'password' => 'required|min:5',
        ]);
        $user = User::where([
            'phone' => $request->phone,
            'password' => $request->password
        ])->first();
        if($user)
        {
            $rm = $request->get('remember')>0;
            Auth::login($user,$rm);
            return redirect()->intended();
        }
        else return redirect()->back()->withErrors(['notLogged' =>'Телефон или пароль неверен']);
    }
    public function changePasswordMod(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if ($request['old_password'] == auth()->user()->password) {
            auth()->user()->changePassword($request->get('new_password'));
            return back()->with("status", "Пароль успешно изменён");
        } else return redirect()->back()->withErrors(['oldPasswordNotValid' =>'Старый пароль не верный']);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'user_phone' => 'required',
            'user_password' => 'required',
        ]);
        $user=User::where(['phone'=> $request['user_phone']])->first();
        if (auth()->user()->roleChecker() == false && $user!=null && $user->roleChecker()==true) {
            $user->changePassword($request->get('user_password'));
            return back()->with("Status", "Пароль изменён.");
        } else return redirect()->back()->withErrors(['user_phone' =>'Старый пароль не верный']);
    }
    public function userProfile($id){
        try {
            $user = User::where(['id' => $id])->first();
        }
        catch (QueryException){
            return abort(404);
        }
        if(auth()->user()->id == $id)
            return view('userProfile', ['user' => $user]);
        if((auth()->user()->roleChecker() == false && $user->position=='Биржевой маклер')){
            $chartbyMonth = $user->getContractByMonth();
            $getallCount=$user->GetAllApplCount();
            $getallMoney=$user->getMoneyByMonth();
            return view('userProfile', [
                'allMoney' => json_encode(json_decode(json_encode($getallMoney)),true),
                'chartbyMonth' =>  json_encode(json_decode(json_encode($chartbyMonth)),true),
                'count' => json_encode(json_decode(json_encode($getallCount)),true),
                'user' => $user
            ]);
        }
        else return abort(404);
    }
    function delete($id){
        if(auth()->user()->roleChecker() == false and User::where(['id' =>$id])->exists()){
            User::destroy($id);
            return back()->with("Status", "Сотрудник удалён.");
        }
        else return abort(404);
    }
    public function editProfile(Request $request){
        if(auth()->user()->roleChecker() == true)
            return abort(404);
        $request->validate([
            'id' => 'required',
            'fullname' => 'required',
            'name' => 'required',
            'middlename' => 'required',
            'phone' => 'required',
            'adress' => 'required',
            'birthday' => 'required',
        ]);
        try{
            User::where(['id' => $request->get('id')])->first()->update([
                'fullname'=> $request->get('fullname'),
                'name' => $request->get('name'),
                'middlename' => $request->get('middlename'),
                'phone' => $request->get('phone'),
                'adress' => $request->get('adress'),
                'birthday' => $request->get('birthday')

            ]);
        }
        catch (QueryException){
            return redirect()->back()->withErrors(['fullname' =>'Что-то не так.']);
        }
        return back()->with("UserStatus", "Данные успешно изменены.");
    }
    public function addUser(Request $request){
        if(auth()->user()->roleChecker() == true)
            return abort(404);
        $request->validate([
            'name' => 'required',
            'fullname' => 'required',
            'middlename' => 'required',
            'u_password' => 'required',
            'u_phone' => 'required',
            'workdate' => 'required',
            'birthday' => 'required',
            'adress' => 'required',
        ]);
        if(User::where(['phone' => $request->get('u_phone')])->exists()){
            return redirect()->back()->withErrors(['u_phone' =>'Такой номер уже зарегистрирован.']);
        }
        if(User::where(['adress' => $request->get('adress')])->exists()){
            return redirect()->back()->withErrors(['adress' =>'Такой адрес уже зарегистрирован.']);
        }
        try{
            User::create([
                'name' => $request->get('name'),
                'fullname' => $request->get('fullname'),
                'middlename' => $request->get('middlename'),
                'password' => $request->get('u_password'),
                'phone' => $request->get('u_phone'),
                'workdate' => $request->get('workdate'),
                'birthday' => $request->get('birthday'),
                'adress' => $request->get('adress'),
                'position' => 'Биржевой маклер',
            ]);
            return back()->with("Status", "Пользователь добавлен.");
        }
        catch (QueryException){
            return redirect()->back()->withErrors(['name' =>'Что-то не так.']);
        }
    }
}
