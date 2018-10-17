<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\UserResource;

class EmployeesController extends Controller
{
    public function index()
    {
        return UserResource::collection(
            User::role('employee')->get()
        );
    }
}
