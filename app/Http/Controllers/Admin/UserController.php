<?php


namespace App\Http\Controllers\Admin;


use App\doctor;
use App\Http\Controllers\Controller;
use App\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Shenjian\Model\App;

class UserController extends Controller
{
    public function login(Request $request) {
        validate($request->all(), [
            'user_name' => 'required',
            'password' => 'required'
        ]);

        $authData = [
            'name' => $request->get('user_name'),
            'password' => $request->get('password')
        ];
        $admin_name_exist = !is_null(admin::where('name', $request->get('user_name'))->first());
        $user_name_exist = !is_null(doctor::where('name', $request->get('user_name'))->first());
        //check user exist
        if (!$admin_name_exist) {
            if (!$user_name_exist) {
                return redirect()->route('login');
            }
        }
        //check admin login
        if ($admin_name_exist) {
            if (auth()->guard('admin')->attempt($authData)) {
                return redirect()->route('admin.doctor.view');
            } else {
                return redirect()->route('login');
            }
        }
        //check user login
        if (!is_null(doctor::where('name', $request->get('user_name'))->first())) {
            if (auth()->guard('doctor')->attempt($authData)) {
                   return redirect()->route('doctor.inquiry.view');
//                $doctor_id = auth()->guard('doctor')->id();
//                $doctor_department = doctor::find($doctor_id)->department->name;
//                if($doctor_department ==config('constant.accept'))//医院接受
//                    return redirect()->route('doctor.accept');
////                else if($doctor_department==config('constant.herbal'))//医院药材
////                    return redirect()->route('doctor.herbal');
//                else//一般医院
            } else {
                return redirect()->route('login');
            }
        }
    }

    public function logout(Request $request) {
        $id = auth()->guard('admin')->id();
        if(!empty($id)) {
            auth()->guard('admin')->logout();
        }
        auth()->guard('doctor')->logout();
        return redirect()->route('login');
    }
}
