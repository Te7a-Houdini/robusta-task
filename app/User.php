<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'salary',
        'bonus_percentage',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function employeeSalariesAndBonuses()
    {
        return self::role('employee')->get()->map(function($employee){
            return $employee->salaryAndBonus();
        });
    }

    public function salaryAndBonus()
    {
        return [
            'salary' => $this->salary,
            'bonus' => $this->bonus_percentage ? 
                        $this->salary * ($this->bonus_percentage/100) :
                         $this->salary * (10 / 100)
        ];
    }
}
