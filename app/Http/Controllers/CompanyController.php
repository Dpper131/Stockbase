<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        if(auth()->user()->roleChecker() == false) {
            $companies=Company::all();
            return view('company',["companies" => $companies]);
        }
        else return abort(404);
    }
    function delete($name){
        if(auth()->user()->roleChecker() == false and Company::where(['name' =>$name])->exists()){
            Company::destroy($name);
            return back()->with("deleteStatus", "Удалена компания: ".$name);
        }
        else return abort(404);
    }
    public function addCompany(Request $request){
        if(auth()->user()->roleChecker() == true)
            return abort(404);
        $request->validate([
            'company_name' => 'required',
            'adress' => 'required',
            'fio' => 'required',
            'phone' => 'required',
        ]);
        $company = Company::where(['name' => $request['company_name']])->first();
        if($company == null){
            try{
            $c=new Company();
            $c->name=$request['company_name'];
            $c->adress=$request['adress'];
            $c->owner=$request['fio'];
            $c->phone=$request['phone'];
                $c->save();
                return back()->with("CompanyStatus", "Компания добавлена!");
            }
            catch (QueryException){
                return redirect()->back()->withErrors(['company_name' =>'Что-то не так с введёными данными, попробуйте ещё раз.']);
            }
        }
        else return redirect()->back()->withErrors(['company_name' =>'Компания с таким названием уже существует.']);
    }
    public function show($name)
    {
        $company = Company::where(['name' => $name])->first();
        if(auth()->user()->roleChecker() == false and $company!=null){
            return response()->json([
                'company' => $company
            ]);
        }
        else return abort(404);
    }

    public function editCompany(Request $request){
        if(auth()->user()->roleChecker() == true){
            return abort(404);
        }
        $request->validate([
            'old_name' => 'required',
            'e_name' => 'required',
            'e_adress' => 'required',
            'e_owner' => 'required',
            'e_phone' => 'required',
        ]);
        try{
            Company::where(['name' => $request['old_name']])->first()->update([
                'name'=> $request->get('e_name'),
                'adress' => $request->get('e_adress'),
                'owner' => $request->get('e_owner'),
                'phone' => $request->get('e_phone'),

            ]);
        }
        catch (QueryException){
            return redirect()->back()->withErrors(['e_name' =>'Что-то не так с данными. Попробуйте ещё раз.']);
        }
        return back()->with("CompanyStatus", "Данные успешно изменены.");
    }

}
