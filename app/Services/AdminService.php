<?php

namespace App\Services;

use App\Services\Interfaces\AdminServiceInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\AdminRepositoryInterface as AdminRepository;


/**
 * Class AdminService
 * @package App\Services
 */
class AdminService implements AdminServiceInterface
{
    protected $adminRepository;
    

    public function __construct(
        AdminRepository $adminRepository,
    ){
        $this->adminRepository = $adminRepository;
    }

    public function login($request){
        $val = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($val->fails()) {
            return response()->json($val->errors(), 202);
        }
        // $admin =  $this->adminRepository->findByAdmin($request->username);
        // $condition=[
        //     ['username','=', $request->username]
        // ];
        // $admin =  $this->adminRepository->findByCondition( $condition);
        $admin = Admin::where('username',$request->username)->first();
       
        if(isset($admin)!=1)
        {
            return response()->json([
                'status' => false,
                'mess' => 'username'
            ]);
        }
        $check =  $admin->makeVisible('password');
       
        if(Hash::check($request->password,$check->password)){
                $success= $admin->createToken('Admin')->accessToken;
                return response()->json([
                    'status' => true,
                    'token' => $success,
                    'username'=>$admin->username
                ]);
        }else {
            return response()->json([
                    'status' => false,
                    'mess' => 'pass'
            ]);
        }
    }

    public function information(){
        
        $id = Auth::guard('admin')->user()->id;
        // $condition=[
        //     ['id','=', $id]
        // ];
        // $userAdmin =  $this->adminRepository->findByCondition( $condition);
        $userAdmin = Admin::where('id',$id)->first();
        return response()->json([
            'status'=>true,
            'data'=> $userAdmin,
        ]);
        
    }
    public function logout(){
        Auth::guard('admin')->user()->token()->revoke();
        return response()->json([
            'status'=>true
        ]);

    }

    public function index($request)
    {
        $query=Admin::orderBy('id','desc');
        if($request->data == 'undefined' || $request->data =="")
        {
            $list=$query;
        }
        else{
            $list=$query->where('username','like', '%' . $request->data . '%')
            ->orWhere('email','like', '%' . $request->data . '%');
        }
        $adminList=$list->paginate(5);
        return response()->json([
            'status'=>true,
            'adminList'=>$adminList,
        ]);
    }
    public function store($request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Vui lòng nhập tên đăng nhập và mật khẩu',
                    'errors'=>$validator->errors()
                ],422);
            }
            $check = Admin::where('username',$request->username)->first();
            if($check != '')
            {
               return response()->json([
                   'message'=>'Tên đăng nhập bị trùng ,vui lòng nhập lại',
                   'status'=>'false'
               ],202);
            }
            $data = $request->only([
                'username',
                'password',
                'email',
                'display_name',
                'avatar',
                'phone',
                'status',
                'depart_id',
            ]);
            // $this->adminRepository->create($data);
            $userAdmin = new Admin();
            $userAdmin -> username = $request['username'];
            $userAdmin -> password = Hash::make($request['password']);
            $userAdmin -> email = $request['email'];
            $userAdmin -> display_name = $request['display_name'];
            $userAdmin -> avatar = isset($request['avatar']) ? $request['avatar'] : null;
            $userAdmin -> skin = "";
            $userAdmin -> is_default = 0;
            $userAdmin -> lastlogin = 0;
            $userAdmin -> code_reset = Hash::make($request['password']);
            $userAdmin -> menu_order = 0;
            $userAdmin -> phone = $request['phone'];
            $userAdmin -> status = $request['status'];
            $userAdmin -> depart_id= $request['depart_id'];
            $userAdmin -> save();
            return response()->json([
                'status' => true,
                'userAdmin' => $userAdmin,
            ]);
       
    }
    public function edit($id)
    {
        $userAdminDetail = Admin::where('id',$id)
        ->select('username','email','display_name','status','phone')->first();
        return response()->json([
            'status'=>true,
            'userAdminDetail' => $userAdminDetail,      
        ]);
    }
    public function update($request,$id)
    {
        $userAdmin = Admin::where('id',$id)->first();
        if(!isset($userAdmin)){
            return response()->json([
                'message'=>'name',
                'status'=>'false'
            ],202);
        }
        $userAdmin->email = $request['email'] ? $request['email']:$userAdmin->email;
        $userAdmin->display_name = $request['name'] ? $request['name']: $userAdmin -> display_name ;
        $userAdmin->phone = $request['phone'] ? $request['phone']:$userAdmin ->phone;
        $userAdmin->status = $request['status'] ? $request['status']:$userAdmin->status;
        $userAdmin->depart_id= $request['depart_id'] ? $request['depart_id']:$userAdmin->depart_id;
        $userAdmin->save();
        return response()->json([
            'status' => true,
            'userAdmin' => $userAdmin,
        ]);
    }
    public function destroy($id){
        Admin::where("id", $id)->delete();
        return response()->json([
            'status' => true
        ]);
    }

}
