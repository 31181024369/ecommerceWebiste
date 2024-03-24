<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\Interfaces\AdminServiceInterface as AdminService;


class AdminController extends Controller
{
    protected $adminService;
    public function __construct(AdminService $adminService){
        $this->adminService=$adminService;
    }
    /**
     * Display a listing of the resource.
     */

    public function login(Request $request){
       try{
            $login=$this->adminService->login($request);
            return $login;
       }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function information(){
        try{
            $information=$this->adminService->information();
            return $information;
            // $id = Auth::guard('admin')->user()->id;
            // $userAdmin = Admin::where('id',$id)->first();
            // return response()->json([
            //    'status'=>true,
            //    'data'=> $userAdmin,
            // ]);
         }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function logout(){

     try{
        $logout=$this->adminService->logout();
        return $logout;
     }catch(\Exception $e){
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 422);
    }

    }

    
    public function index(Request $request)
    {
        try{
           $index=$this->adminService->index($request);
           return $index;

        } catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
           $store=$this->adminService->store($request);
           return $store;
       }
       catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        try{
            $edit=$this->adminService->edit($id);
           return $edit;

        } catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try{
            $update=$this->adminService->update($request,$id);
           return $update;
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $destroy=$this->adminService->destroy($id);
           return $destroy;
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
