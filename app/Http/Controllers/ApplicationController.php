<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Client;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    function getPage(?Request $request){
        If(auth()->user()->roleChecker() == true) {

            $app = Application::getAppLowInf($request->type ?? 'all');
            return view('appl',['app' => $app, 'uuid' => null]);
        }
         else return abort(404);
    }

    function getClientPage(string $uuid, ?Request $request){
        if(preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $uuid) && Client::where(['id'=>  $uuid])->first() != null) {
            error_log($request->type);
            $app = Application::getAppLowInfClient($request->type ?? 'all',  $uuid);
            return view('appl', ['app' => $app, 'uuid' => $uuid]);
        }
        else return redirect()->back()->withErrors(['uuid' =>'Неверный идентификатор']);
    }


    function delete(string $uuid,$id){
        if(Application::where(['id' =>$id, 'client_id' => $uuid, 'status' =>'ожидается'])->exists()){
            Application::destroy($id);
            return back()->with("deleteStatus", "Удалено");
        }
        else return abort(404);
    }

    public function add(Request $request){
        $request->validate([
            'uuid' => 'required',
            'vendor__code' => 'required',
            'price' => 'required',
            'ccount' => 'required',
            'type' => 'required',
        ]);
        if(($request->type == 'Покупка' || $request->type == 'Продажа') && Client::where(['id' => $request->uuid])->first() != null && Stock::where(['vendor_code' => $request->vendor__code])->first() != null){
            try{
                $c=new Application();
                $c->vendor_code=$request['vendor__code'];
                $c->type=$request['type'];
                $c->fixed_price=$request['price'];
                $c->count=$request['ccount'];
                $c->client_id=$request['uuid'];
                $c->status="ожидается";
                $c->save();
                return back()->with("ApplStatus", "Заявление добавлено!");
            }
            catch (QueryException $exception){
                error_log($exception);
                return redirect()->back()->withErrors(['count' =>'Что-то не так с введёными данными, попробуйте ещё раз.']);
            }
        }
        else return redirect()->back()->withErrors(['count' =>'Что-то не так с введёными данными, попробуйте ещё раз.']);
    }
    public function show($id,$uuid = null)
    {
        $app = Application::where(['id' => $uuid ?? $id])->first();
        if(($uuid!= null  || auth()->user()->roleChecker() == true) and $app!=null){
            $appFullInfo=$app->getAppInfo();
            return response()->json([
                'app' => $appFullInfo
            ]);
        }
        else return abort(404);
    }
    public function showContract($id,$uuid = null)
    {
        $app = Application::where(['id' => $id])->first();
        if(($uuid!= null  || auth()->user()->roleChecker() == true) and $app!=null){
            $appFullInfo=$app->checkAndContrInfo();
            return response()->json([
                'app' => $appFullInfo
            ]);
        }
        else return abort(404);
    }
    public function createContract(Request $request)
    {
        if(auth()->user()->roleChecker() == false)
            abort(404);
        $request->validate([
            'by_id' => 'required',
            'sll_id' => 'required',
        ]);
        if(Application::where(['id' => $request['by_id']])->exists() and Application::where(['id' => $request['sll_id']])->exists()){
            try{
                DB::select("select * from createcontract(?,?,?)",[$request['by_id'],$request['sll_id'],auth()->user()->id]);
                return back()->with("ContrStatus", "Контракт добавлен для заявлений ".$request['by_id']." и ".$request['sll_id'].".");
            }
            catch (QueryException){
                return redirect()->back()->withErrors(['by_id' =>'Что-то не так с базой данных']);
            }
        }
        else return redirect()->back()->withErrors(['by_id' =>'Что-то не с заявлениями.']);

    }
}
