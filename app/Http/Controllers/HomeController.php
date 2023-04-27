<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth::check()){
        if(auth()->user()->roleChecker() == true) {
            $chartbyMonth = auth()->user()->getContractByMonth();
            $getallCount=auth()->user()->GetAllApplCount();
            $getallMoney=auth()->user()->getMoneyByMonth();
            $countApl = Application::allcountNot();
            return view('home.dashboard', [
                'allMoney' => json_encode(json_decode(json_encode($getallMoney)),true),
                'chartbyMonth' =>  json_encode(json_decode(json_encode($chartbyMonth)),true),
                'count' => json_encode(json_decode(json_encode($getallCount)),true),
                'countApl' => $countApl
                ]);
        }
        else{
            return view('home.index');
        }
        }
        return view('home.index');
    }
}
