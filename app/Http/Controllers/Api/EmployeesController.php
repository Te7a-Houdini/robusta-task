<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Employee\StoreEmployeeRequest;

class EmployeesController extends Controller
{
    public function index()
    {
        return UserResource::collection(
            User::role('employee')->get()
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }
}
