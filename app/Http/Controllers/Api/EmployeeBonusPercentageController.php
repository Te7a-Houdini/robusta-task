<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\UserResource;

class EmployeeBonusPercentageController extends Controller
{
    public function update(Request $request,User $employee)
    {
        $request->validate([
            'bonus_percentage' => 'required|numeric'
        ]);

        $employee->update(['bonus_percentage' => $request->bonus_percentage]);

        return new UserResource($employee);
    }
}
