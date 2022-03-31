<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;
use Validator;
use Auth;
class UserController extends Controller
{
    public function __construct(User $User)
    {
      $this->User = $User;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = new Breeze('your_api_key');
        // $people = $breeze->url('http://127.0.0.1:8000/api/user-list/');
        // dd($people);
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $request->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=[
            'name' => 'required',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            $response = [
                'status' => false,
                'success' => 0,
                'message' => $validator->messages()
            ];
            return response()->json($response);
        }

        $data = $request->all();
        if($user = User::create($data)){
            $response = [
                'data' => $user,
                'status' => true,
                'success' => 1,
                'message' => 'User added successfully'
            ];
            return response()->json($response);
        }else{
            $response = [
                'status' => false,
                'success' => 0,
                'message' => 'Something probelm in internal system'
            ];
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findorfail($id);
        if($user){
              $showUser=[
                  'data' => $user,
                  'message' => 'User Detail',
                  'success' => '1',
                  'status' => 'true'
                ];
                return response()->json($showUser);
        } 
        else
        {
          $showUser=[
            'message' => 'Something probelm in internal system',
            'success' => '0',
            'status' => 'false'
          ];
          return response()->json($showUser);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            $response = [
                'status' => false,
                'success' => 0,
                'message' => $validator->messages()
            ];
            return response()->json($response);
        }

        $data = $request->all();
        $user = User::findorfail($id);
        if($user = $user->update($data)){
            $response = [
                'data' => $user,
                'status' => true,
                'success' => 1,
                'message' => 'User added successfully'
            ];
            return response()->json($response);
        }else{
            $response = [
                'status' => false,
                'success' => 0,
                'message' => 'Something probelm in internal system'
            ];
            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::where('id',$id)->delete();
        if($user){
          $del_user=[
            'message' => 'User Has Been Delete Successfully',
            'success' => '1',
            'status' => 'true'
          ];
        }else{
          $del_user=[
            'message' => 'Something probelm in internal system',
            'success' => '0',
            'status' => 'false'
          ];
        }
        return response()->json($del_user);
    }
}
