<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'name',
        'adress',
        'middlename',
        'birthday',
        'phone',
        'position',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    public function roleChecker(): ?bool
    {
        $check = null;
        $position = $this->position;
        if($position == 'Биржевой маклер')
            $check = true;
        if($position == 'Специалист')
            $check = false;
        return $check;
    }

    public function changePassword(string $newPassword)
    {
        $this->where('phone', $this['phone'])->update(['password' => $newPassword]);
    }
    public function localName(){
        $user = $this->where(['phone' => $this->phone])->first();
        return $user->fullname." ".$user->name;
    }
    public function getSalary(){
        return Position::where(['name' =>$this->position])->first()->salary;
    }
    public function getContractByMonth(){
        $databyMonth = DB::select("select date_month,coalesce(count,0) as count from(
SELECT date_trunc('Month',date_month)::date as date_month,count
   FROM   generate_series(date_trunc('year',current_date)
                        , date_trunc('year',current_date)+ interval '11 months'
                        , interval  '1 month') date_month
left join
(select date_trunc('Month',date)::date as date_month,count(id) as count from contracts where employee_id=? and date>date_trunc('year', current_timestamp)
group by date_month
order by date_month) as t using (date_month)) as t",[$this->id]);
        return $databyMonth;

    }
    public function GetAllApplCount(){
        $counts=DB::select("select count(application_id) filter(where contract_id not in(
select id from contracts where employee_id=?)) as all,count(application_id) filter(where contract_id in(
select id from contracts where employee_id=?))
from application_lists",[$this->id,$this->id]);
        return $counts;
    }
    public function getMoneyByMonth(){
        $databyMonth = DB::select("select date_month,coalesce(sum,0) as count from(
SELECT date_trunc('Month',date_month)::date as date_month,sum
   FROM   generate_series(date_trunc('year',current_date)
                        , date_trunc('year',current_date)+ interval '11 months'
                        , interval  '1 month') date_month
left join
(select date_trunc('Month',c.date)::date as date_month,sum(a.fixed_price*a.count) from contracts c
inner join application_lists al on contract_id=c.id
inner join applications a on al.application_id=a.id
 where employee_id=? and c.date>date_trunc('year', current_timestamp)
group by date_month
order by date_month) as t using (date_month)) as t",[$this->id]);
        return $databyMonth;

    }


}
