<?php
namespace App\Http\Controllers\Admin;

use App\department;
use App\doctor;
use App\Http\Controllers\Controller;
use App\patient;
use App\recipe;
use App\setting;
use App\treatment;
use Illuminate\Http\Request;

class AcceptController extends Controller
{
    public function dashboard(){
        return view('admin.accept.dashboard');
    }
    public function registerPatient(){
        $datas = department::select('*')
            ->orderBy('name')->get();
        $guahaoID = random_str('num',6);
        $weixin_url = setting::select('value')->where('name',config('asset.weixin_pay'))->get()[0]->value;
        $zhifubao_url = setting::select('value')->where('name',config('asset.zhifubao_pay'))->get()[0]->value;

        return view('admin.accept.register_patient')->with([
            'departments'=>$datas,
            'guahaoID'=>$guahaoID,
            'weixin_url' => $weixin_url,
            'zhifubao_url' => $zhifubao_url
        ]);
    }
    public function getDoctorsInDepartment(Request $request){
        $department_id = $request->get('department_id');
        $datas = doctor::select('*')->where('department_id',$department_id)->orderBy('name')->get();
        return success($datas);
    }
    public function createPatient(Request $request){
        validate($request->all(), [
            'department' => 'required',
            'doctor_id' => 'required',
            'name' => 'required',
            'ID_number' => 'required',
            'phone_number' => 'required',
            'photo' => 'required',
            'guahaoID' => 'required'
        ]);

        $doctor_id = $request->get('doctor_id');
        $sex = $request->get('sex');
        $name = $request->get("name");
        $ID_Number = $request->get("ID_number");
        $phone_number = $request->get('phone_number');
        $guahaoID = $request->get('guahaoID');
        $photo = $request->get('photo');
        //check if user exist in database
        $patient = patient::where('ID_Number',$ID_Number)->first();
        if(empty($patient)){
            $patient = patient::create([
                'phone_number'=>$phone_number,
                'sex' => $sex,
                'ID_Number'=>$ID_Number,
                'name'=>$name,
                'img_url'=>$photo
            ]);
        }
        $acceptId = doctor()->id;

        $treatment = treatment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor_id,
            'request_time' => now(),
            'accept_by' => $acceptId,
            'state' => config('constant.treat_state.accept'),
            'guahao'=>$guahaoID
        ]);
        return success($treatment);

    }
    public function uploadPhoto(Request $request){
        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(10).'.'.'png';
        \File::put(public_path(). '/uploads/images/' . $imageName, base64_decode($image));
        return success($imageName);
    }
    public function guahaoView(Request $request){
        return view('admin.accept.guahao');
    }
    public function getGuahao(Request $request){
        validate($request->all(), [
            'length'=>'required'
        ]);
        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $searchValue = $search['value'];
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];

        $datas = treatment::select('treatments.*')->where('guahao','like','%'.$searchValue.'%')
            ->orWhere('disease_name','like','%'.$searchValue.'%')
            ->orWhere('ID_Number','like','%'.$searchValue.'%')
            ->orWhere('phone_number','like','%'.$searchValue.'%')
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $guahaoData = array();
        foreach($datas as $data) {
            $obj["id"] = $data->id;
            $obj['treat_start'] = $data->treat_start;
            $obj['guahao'] = $data->guahao;
            $obj['patient_name'] = $data->patient->name;
            $obj['patient_phone'] = $data->patient->phone_number;
            $obj['doctor_department'] = $data->doctor->department->name;
            $obj['doctor_name'] = $data->doctor->name;
            array_push($guahaoData,$obj);
        }
        $totalCount = treatment::all()->count();
        $result = array(
            "aaData"=>$guahaoData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>$totalCount,
        );
        return json_encode($result);
    }

    public function editGuahaoView($id){
        $treatment = treatment::where('id',$id)->first();
        $datas = department::select('*')
            ->orderBy('name')->get();
        $department_id = $treatment->doctor->department_id;
        $from = $treatment->doctor->from;
        $to = $treatment->doctor->to;
        $doctors = doctor::where('department_id',$department_id)->get();
        $sex = $treatment->patient->sex;
        $editable = $treatment->state==config('constant.treat_state.waiting_treatment');
        return view('admin.accept.guahao_edit')->with([
            "treatment" => $treatment,
            'department_id' => $department_id,
            'doctors' => $doctors,
            'from' => $from,
            'to' => $to,
            'sex' => $sex,
            'patient_name' => $treatment->patient->name,
            "ID_Number" => $treatment->patient->ID_Number,
            'phone_number' => $treatment->patient->phone_number,
            'editable' => true,
            'departments' => $datas
        ]);
    }
    public function updateGuahao(Request $request){
        validate($request->all(), [
            'guahaoID' => 'required',
            'doctor_id' => 'required',
            'sex' => 'required',
            'phone_number' => 'required'
        ]);
        $treatment = treatment::where('guahao',$request->get('guahaoID'))->first();
        $treatment->update([
           'doctor_id' => $request->get('doctor_id'),
        ]);
        $patient = patient::where('id', $treatment->patient_id)->first();
        $patient->update([
           'sex' => $request->get('sex'),
           'phone_number' => $request->get('phone_number')
        ]);
        return success('OK');
    }
    public function deleteGuahao(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $guahao = treatment::where("id",$request->get('id'));
        if (is_null($guahao)) {
            return error('找不到该数据');
        }
        $guahao->delete();
        return success();
    }

    public function detailGuahao(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $guahao = treatment::where("id",$request->get('id'))->first();
        $result = array(
            'department'=>$guahao->doctor->department->name,
            'doctor_name'=>$guahao->doctor->name,
            'work_from'=>$guahao->doctor->from,
            'work_to'=>$guahao->doctor->to,
            'name'=>$guahao->patient->name,
            'ID_Number'=>$guahao->patient->ID_Number,
            'patient_phone'=>$guahao->patient->phone_number,
            'sex' => $guahao->patient->sex,
            'guahao'=>$guahao->guahao
        );
        return success($result);
    }

    public function updateTreatment(Request $request){
        validate($request->all(), [
            'id' => 'required',
            'state' => 'required'
        ]);
        $id = $request->get('id');
        $state = $request->get('state');
        $treatment = treatment::where('id',$id)->first();
        $treatment->update([
            'state' => $state
        ]);
        return success($treatment);
    }
    public function paymentCreate(){
        $weixin_url = setting::select('value')->where('name',config('asset.weixin_pay'))->get()[0]->value;
        $zhifubao_url = setting::select('value')->where('name',config('asset.zhifubao_pay'))->get()[0]->value;

        return view('admin.accept.payment_create')->with([
            "weixin_url" => $weixin_url,
            "zhifubao_url" => $zhifubao_url
        ]);
    }
    public function getPaymentData(Request $request){
        validate($request->all(), [
            'guahao' => 'required'
        ]);
        $guahao = $request->get('guahao');
        $treatment = treatment::where('guahao',$guahao)->first();
        if(empty($treatment))
            return error('无效的挂号。');

        $state = $treatment->state;
        if($state==config('constant.treat_state.before_treating_pay')){
            $result = array(
                'id' => $treatment->id,
                "guahao" => $guahao,
                "patient_name" => $treatment->patient->name,
                "recipe" => recipe::where('id',$treatment->original_recipe)->first()->prescription_name,
                "price" => $treatment->price
            );
            return success($result);
        }else
            return error('无效的挂号。');
    }
    public function donePayment(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $id = $request->get('id');
        $treatment = treatment::where('id',$id)->first();
        $treatment->update([
            "state" => config('constant.treat_state.after_treating_pay')
        ]);
        return success("OK");
    }
    public function listPayment(){
        return view('admin.accept.payment_list');
    }
    public function listPaymentData(Request $request){
        validate($request->all(), [
            'length'=>'required'
        ]);
        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $searchValue = $search['value'];
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];

        $datas = treatment::select('treatments.*')->where('treatments.state',config('constant.treat_state.before_treating_pay'))
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $arrayDatas = [];
        foreach($datas as $data){
            $temp['id'] = $data->id;
            $temp['treat_start'] = $data->treat_start;
            $temp['patient_name'] = $data->patient->name;
            $temp['recipe_name'] = recipe::where('id',$data->original_recipe)->first()->prescription_name;
            $temp['price'] = $data->price;
            $temp['doctor_name'] = $data->doctor->name;
            array_push($arrayDatas,$temp);
        }
        $totalCount = count($datas);
        $result = array(
            "aaData"=>$arrayDatas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>$totalCount,
        );

        return json_encode($result);

    }
}


