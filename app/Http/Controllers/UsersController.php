<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Address;
use DB;

class UsersController extends Controller
{
    public function index(){
        $users = User::with(['company','address'])->get();

        return view('users',compact(['users']));
    }

    public function store(Request $request){
        $data = $request->data;
        DB::transaction(function() use($data){
            $company = new Company();
            $company->name = $data['company']['name'];
            $company->catchPhrase = $data['company']['catchPhrase'];
            $company->bs = $data['company']['bs'];
            $company->save();

            $address = new Address();
            $address->street = $data['address']['street'];
            $address->suite = $data['address']['suite'];
            $address->city = $data['address']['city'];
            $address->zipcode = $data['address']['zipcode'];
            $address->lat = $data['address']['geo']['lat'];
            $address->lng = $data['address']['geo']['lng'];
            $address->save();

            User::insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'address_id' => $address->id,
                'phone' => $data['phone'],
                'website' => $data['website'],
                'photo' => '',
                'company_id' => $company->id
            ]);
        });

        return response('Datos guardados correctamente',200);
    }
}
