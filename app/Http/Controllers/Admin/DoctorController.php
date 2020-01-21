<?php
namespace App\Http\Controllers\Admin;

use App\contrary;
use App\doctor;
use App\Http\Controllers\Controller;
use App\Imports\ContraryImport;
use App\Imports\MedicineImport;
use App\medicine;
use App\question;
use App\recipe;
use App\Rules\MatchOldPassword;
use App\treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller{
    public function dashboard(){
        return view('admin.common.dashboard');
    }

    public function viewMedicine(){
        return view('admin.medicine.view');
    }

    public function uploadMedicine(Request $request){
        $medicines = Excel::toArray(new MedicineImport,$request->file('file_1'));
        $totalNumber = count($medicines[0]);
        $insertNumber = 0;
        $updatedNumber = 0;
        $failureNumber = 0;
        foreach($medicines[0] as $row){
            if($row[1]=='用处'){
                $totalNumber--;
                continue;
            }
            if(empty($row[0])||empty($row[2])||empty($row[3])){
                $failureNumber++;
                continue;
            }
            $medicine = medicine::where('name',$row[0])->first();
            if(!empty($medicine)){
                $updatedNumber++;
                $medicine->update([
                    'name'     => $row[0],
                    'usage'     => $row[1],
                    'weight'     => $row[2],
                    'price'     => $row[3],
                    'min_weight'     => $row[4],
                    'max_weight'     => $row[5]
                ]);
            }else{
                $insertNumber++;
                $medicineDb = medicine::create([
                    'name' => $row[0],
                    'usage' => $row[1],
                    'weight' => $row[2],
                    'price' => $row[3],
                    'min_weight' => $row[4],
                    'max_weight'     => $row[5]
                ]);
            }
        }
        $result = array(
          "insert_number" => $insertNumber,
          "update_number" => $updatedNumber,
          'fail_number' => $failureNumber,
          'total_number' => $totalNumber
        );
        return success($result);
    }
    public function getMidicineDatas(Request $request){
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

        $datas = medicine::select('*')->where('name','like','%'.$searchValue.'%')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();

        $totalCount = medicine::all()->count();

        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>$totalCount,
        );
        return json_encode($result);
    }
    public function editMidicineData(Request $request){
        validate($request->all(), [
            'medicine_id'=>'required'
        ]);
        $id = $request->get('medicine_id');
        $medicine = medicine::where('id',$id)->first();
        $medicine->update([
           'name'=>$request->get('medicine_name'),
           'usage'=>$request->get('usage'),
           'weight'=>$request->get('weight'),
           'price'=>$request->get('price'),
           'min_weight'=>$request->get('min_weight'),
           'max_weight'=>$request->get('max_weight')
        ]);
        return success($medicine);
    }
    public function deleteMidicineData(Request $request){
        validate($request->all(), [
            'id'=>'required'
        ]);
        $id = $request->get('id');
        $medicine = medicine::where('id',$id)->first();
        if (is_null($medicine)) {
            return error('找不到该数据');
        }
        $medicine->delete();
        return success();
    }
    public function viewContrary(){
        return view('admin.contrary.view');
    }
    public function uploadContrary(Request $request){
        $contraries = Excel::toArray(new MedicineImport,$request->file('file_1'));
        $totalNumber = count($contraries[0]);
        $insertNumber = 0;
        $updatedNumber = 0;
        $failureNumber = 0;
        foreach($contraries[0] as $row){
            if(strpos($row[0], '药材') !== false){
                $totalNumber--;
                continue;
            }
            if(empty($row[0])||empty($row[1])){
                $failureNumber++;
                continue;
            }
            $contrary = contrary::where('name',$row[0])->first();
            if(!empty($contrary)){
                $updatedNumber++;
                $contrary->update([
                    'name'     => $row[0],
                    'contrary'     => $row[1],
                ]);
            }else{
                $insertNumber++;
                $medicineDb = contrary::create([
                    'name' => $row[0],
                    'contrary' => $row[1],
                ]);
            }
        }
        $result = array(
            "insert_number" => $insertNumber,
            "update_number" => $updatedNumber,
            'fail_number' => $failureNumber,
            'total_number' => $totalNumber
        );
        return success($result);

    }
    public function getContraryDatas(Request $request){
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

        $datas = contrary::select('*')->where('name','like','%'.$searchValue.'%')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();

        $totalCount = contrary::all()->count();

        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>$totalCount,
        );
        return json_encode($result);
    }
    public function editContraryData(Request $request){
        validate($request->all(), [
            'contrary_id'=>'required'
        ]);
        $id = $request->get('contrary_id');
        $contrary = contrary::where('id',$id)->first();
        $contrary->update([
            'name'=>$request->get('medicine_name'),
            'contrary'=>$request->get('contrary')
        ]);
        return success($contrary);
    }
    public function deleteContraryData(Request $request){
        validate($request->all(), [
            'id'=>'required'
        ]);
        $id = $request->get('id');
        $contrary = contrary::where('id',$id)->first();
        if (is_null($contrary)) {
            return error('找不到该数据');
        }
        $contrary->delete();
        return success();
    }

    public function viewInquiry(Request $request){
        return view('admin.inquiry.view');
    }
    public function getGuahao(Request $request){
        $doctor_id = auth()->guard('doctor')->id();
        validate($request->all(), [
            'length'=>'required'
        ]);
        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        if($orderColumn=='patient_name')
            $orderColumn = 'patients.name';

        if(empty($doctor_id))//admin
            $datas = treatment::select('treatments.*')->where('state',config('constant.treat_state.waiting_treatment'))
                ->join('patients', 'treatments.patient_id', '=', 'patients.id')
                ->orderBy('treatments.created_at', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        else//doctor
            $datas = treatment::select('treatments.*')->where('doctor_id',$doctor_id)->where('state',config('constant.treat_state.waiting_treatment'))
                ->join('patients', 'treatments.patient_id', '=', 'patients.id')
                ->orderBy('treatments.created_at', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $guahaoDatas = [];
        foreach($datas as $data){
            $temp = array();
            $temp['id'] = $data->id;
            $temp['state'] = $data->state;
            $temp['guahao'] = $data->guahao;
            $temp['patient_name'] = $data->patient->name;
            $temp['ID_Number'] = $data->patient->ID_Number;
            array_push($guahaoDatas,$temp);
        }

        $totalCount = count($guahaoDatas);

        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>$totalCount,
        );
        return json_encode($result);
    }
    public function getTreatementData(Request $request){
        validate($request->all(), [
            'length'=>'required'
        ]);
        $id = $request->get('id');
        if(empty($id)){
            $result = array(
                "aaData"=>[],
                "iTotalRecords"=>0,
                "iTotalDisplayRecords"=>0,
            );
            return json_encode($result);
        }

        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        if($orderColumn=='patient_name')
            $orderColumn = 'patients.name';

        $treatment = treatment::select('treatments.*')->where('treatments.id',$id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->first();
        $patient_id = $treatment->patient_id;

        $datas = treatment::select('treatments.*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.waiting_treatment'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', $orderDirection)->skip($start)->take($length)->get();
        $guahaoDatas = [];
        $i = 1;
        $prev_patient_name = '';
        if(!empty($datas[0]))
            $prev_patient_name = $datas[0]->patient->name;

        foreach($datas as $data){
            if($prev_patient_name!=$data->patient->name){
                $i=1;
            }
            $prev_patient_name = $data->patient->name;

            $temp = array();
            $temp['id'] = $data->id;
            $temp['state'] = $data->state;
            $temp['guahao'] = $data->guahao;
            $temp['patient_name'] = $data->patient->name;
            $temp['doctor_name'] = $data->doctor->name;
            $temp['disease_name'] = $data->disease_name;
            $temp['number'] = $i;
            $temp['date'] = $data->treat_start;
            $original_recipe = $data->original_recipe;
            $arr_recipe = explode(',',$original_recipe);
            $recipe_name = '';
            foreach($arr_recipe as $recipe_id){
                $db_recipe = recipe::where('id',$recipe_id)->first();
                if(empty($db_recipe))
                    continue;
                $recipe_name .= $db_recipe->prescription_name.',';
            }
            $temp['recipe'] = rtrim($recipe_name,',');

            array_push($guahaoDatas,$temp);
            $i++;
        }


        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>count($guahaoDatas),
        );
        return json_encode($result);
    }
    public function createInquiryView($id){
        $treatment = treatment::select('*')->where('treatments.id',$id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->first();
        $patient_id = $treatment->patient_id;
        $datas = treatment::select('*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.waiting_treatment'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')->get();
        $medicines = medicine::select('*')->orderBy('name')->get();

        return view('admin.inquiry.create')->with([
            'treatment'=>$treatment,
            'history_number' => count($datas),
            'medicines' => $medicines
            ]);
    }
    public function uploadVideo(Request $request){
        if(isset($_FILES["video"])) {
            $videoName = str_random(10) . '.' . 'webm';
            move_uploaded_file($_FILES["video"]["tmp_name"], public_path() . '/uploads/videos/' . $videoName);
            return success($videoName);
        }else{
            return error('Invalid parameter');
        }
    }
    public function getRecipe(Request $request){

        validate($request->all(), [
            'question_id' => 'required'
        ]);
        $question = question::where('id',$request->get('question_id'))->first();
        $recipe_ids = json_decode($question->recipes);

        $recipe_datas = array();
        foreach($recipe_ids as $recipe_id){
            $recipe = recipe::where("id",$recipe_id)->first();
            array_push($recipe_datas,$recipe);
        }
        $result = array(
            "recipe" => $recipe_datas,
            "question" => $question
        );
        return success($result);
    }

    public function completeTreatment(Request $request){
        validate($request->all(), [
            'guahao' => 'required',
            'question_title' => 'required',
            'recipe' => 'required',
            'question_string' => 'required'
        ]);

        $guahao = $request->get('guahao');
        $question_id = $request->get('question_title');
        $video_url = $request->get('video_url');
        $question_string = $request->get('question_string');
        $recipe_id = $request->get('recipe');
        $annotation_keys = $request->get('annotation_key');
        $annotation_values = $request->get('annotation_value');
        $total_price = $request->get('total_price');
        $disease_name = $request->get('disease_name');

        $annotations = [];
        if(!empty($annotation_keys)){
            foreach($annotation_keys as $key=>$annotation_key){
                $temp = array(
                    'key' => $annotation_key,
                    'value' => $annotation_values[$key]
                );
                array_push($annotations,$temp);
            }
        }
        $medicine_names = $request->get('medicine_name');
        $min_weights = $request->get("min_weight");
        $max_weights = $request->get("max_weight");
        $weights = $request->get("mass");
        $prices = $request->get("price");

        $medicines = array();
        foreach($medicine_names as $key=>$medicine_name){
            $min_weight = $min_weights[$key];
            $max_weight = $max_weights[$key];
            $weight = $weights[$key];
            $price = $prices[$key];
            $medicine = medicine::where('name',$medicine_name)->first();
            $item = array(
                'medicine_id'=>$medicine->id,
                "medicine"=>$medicine_name,
                "min_weight"=>$min_weight,
                "max_weight"=>$max_weight,
                "weight" => $weight,
                "price"=>$price
            );
            array_push($medicines,$item);
        }
        $treat_end = date("Y-m-d H:i:s");
        $treatment = treatment::where('guahao',$guahao)->first();
        $treatment->update([
            'question_id'=>$question_id,
            'record_video' => $video_url,
            'question' => $question_string,
            'comment' => json_encode($annotations),
            'original_recipe' => $recipe_id,
            'treat_end' => $treat_end,
            'state' => config('constant.treat_state.before_treating_pay'),
            'price' => $total_price,
            'disease_name' => $disease_name,
            'recipe' => json_encode($medicines)
        ]);
        return success($treatment);
    }
    public function startTreatment(Request $request){
        validate($request->all(), [
            'guahao' => 'required'
        ]);
        $guahao = $request->get('guahao');
        $treatment = treatment::where('guahao',$guahao)->first();
        $treat_start = date("Y-m-d H:i:s");

        $treatment->update([
            'treat_start' => $treat_start,
            'state' => config('constant.treat_state.treating')
        ]);
        return success();
    }
    public function detailTreatment($treat_id){
        $histories = treatment::where('id',$treat_id)->get();
        $historyData = [];
        foreach($histories as $history){
            $temp = $history;
            $temp->doctor_name = $history->doctor->name;
            $temp->patient_name = $history->patient->name;
            $temp->questions = json_decode($history->question);
            $temp->annotations = json_decode($history->comment);
            $temp->medicines = json_decode($history->recipe);
            $recipe = recipe::where("id",$history->original_recipe)->first();
            $temp->recipe_name = empty($recipe)?'':$recipe->prescription_name;
            array_push($historyData,$temp);
        }
        return view('admin.inquiry.detail')->with([
            'histories' => $historyData,
        ]);
    }
    public function allHistoryView(){
        return view('admin.history.all');
    }

    public function getAllData(Request $request){
        validate($request->all(), [
            'length'=>'required'
        ]);
        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        $search = $request->get('search');
        $searchValue = $search['value'];

        if($orderColumn=='patient_name')
            $orderColumn = 'patients.name';

        $datas = treatment::select('treatments.*')
            ->where('patients.name','like','%'.$searchValue.'%')
            ->where('state','!=',config('constant.treat_state.waiting_treatment'))
            ->where('state','!=',config('constant.treat_state.accept'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $guahaoDatas = [];
        $i = 1;
        $prev_patient_name = '';
        if(!empty($datas[0]))
            $prev_patient_name = $datas[0]->patient->name;
        foreach($datas as $data){
            if($prev_patient_name!=$data->patient->name){
                $i=1;
            }
            $prev_patient_name = $data->patient->name;
            $temp = array();
            $temp['id'] = $data->id;
            $temp['state'] = $data->state;
            $temp['guahao'] = $data->guahao;
            $temp['patient_name'] = $data->patient->name;
            $temp['disease_name'] = $data->disease_name;
            $temp['number'] = $i;
            $temp['date'] = $data->treat_start;
            $original_recipe = $data->original_recipe;
            $arr_recipe = explode(',',$original_recipe);
            $recipe_name = '';
            foreach($arr_recipe as $recipe_id){
                $db_recipe = recipe::where('id',$recipe_id)->first();
                if(empty($db_recipe))
                    continue;
                $recipe_name .= $db_recipe->prescription_name.',';
            }
            $temp['recipe'] = rtrim($recipe_name,',');

            array_push($guahaoDatas,$temp);
            $i++;
        }

        $totalCount = treatment::all()->count();

        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>count($guahaoDatas),
        );
        return json_encode($result);
    }
    public function individualHistoryView($treat_id){
        $treatment = treatment::where('id',$treat_id)->first();
        return view('admin.history.individual')->with([
            'treatment'=>$treatment,
            'guahao'=>$treatment->guahao,
            'patient_name' => $treatment->patient->name,
            'ID_Number' => $treatment->patient->ID_Number,
            'sex' => $treatment->patient->sex,
        ]);
    }

    public function individualHistoryViewNoParam(){

        return view('admin.history.individual_noParam');
    }
    public function getTreat_id(Request $request){
        validate($request->all(), [
            'IDNumber'=>'required'
        ]);
        $IDNumber = $request->get("IDNumber");
        $data = treatment::select('treatments.*')->where('patients.ID_Number',$IDNumber)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')->first();
        if(empty($data))
            return error('无效的参数。');
        else
            return success($data->id);
    }

    public function getContraryMedicines(Request $request){
        validate($request->all(), [
            'medicine_id'=>'required'
        ]);
        $medicine_id = $request->get('medicine_id');
        $medicine = medicine::where("id",$medicine_id)->first();
        $contraries = contrary::where('name',$medicine->name)->get();
        return success($contraries);
    }

    public function change_password(Request $request){
        return view('admin.setting.change_password');
    }
    public function change_password_data(Request $request){
        $request->validate([
            'original_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'repeat_password' => ['same:new_password'],
        ]);
        doctor::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return success('ok');
    }
    public function incomeAll(){
        return view('admin.income.all');
    }
    public function yieldView(){
        return view('admin.yield.view');
    }
    public function getYieldData(Request $request){
        validate($request->all(), [
            'length'=>'required'
        ]);
        $columns = $request->get('columns');
        $length = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        $search = $request->get('search');
        $searchValue = $search['value'];

        if($orderColumn=='patient_name')
            $orderColumn = 'patients.name';

        $datas = treatment::select('treatments.*')
            ->where('guahao','like','%'.$searchValue.'%')
            ->where(function($query){
                $query->where('state',config('constant.treat_state.after_treating_pay'));
                $query->orWhere('state',config('constant.treat_state.close'));
            })
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $guahaoDatas = [];
        $i = 1;
        $prev_patient_name = '';
        if(!empty($datas[0]))
            $prev_patient_name = $datas[0]->patient->name;
        foreach($datas as $data){
            if($prev_patient_name!=$data->patient->name){
                $i=1;
            }
            $prev_patient_name = $data->patient->name;
            $temp = array();
            $temp['id'] = $data->id;
            $temp['state'] = $data->state;
            $temp['guahao'] = $data->guahao;
            $temp['patient_name'] = $data->patient->name;
            $temp['disease_name'] = $data->disease_name;
            $temp['number'] = $i;
            $temp['date'] = $data->treat_start;
            $original_recipe = $data->original_recipe;
            $arr_recipe = explode(',',$original_recipe);
            $recipe_name = '';
            foreach($arr_recipe as $recipe_id){
                $db_recipe = recipe::where('id',$recipe_id)->first();
                if(empty($db_recipe))
                    continue;
                $recipe_name .= $db_recipe->prescription_name.',';
            }
            $temp['recipe'] = rtrim($recipe_name,',');

            array_push($guahaoDatas,$temp);
            $i++;
        }

        $totalCount = treatment::all()->count();

        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>$totalCount,
        );
        return json_encode($result);
    }
    function detailYieldView($id){
        $treatment = treatment::where('id',$id)->first();
        $recipe_id = $treatment->original_recipe;
        $recipe_name = recipe::where('id',$recipe_id)->first()->prescription_name;
        return view('admin.yield.detail')->with([
            'guahao' => $treatment->guahao,
            'patient_name' => $treatment->patient->name,
            'ID_Number' => $treatment->patient->ID_Number,
            'doctor_name' => $treatment->doctor->name,
            'state' =>getStateWord($treatment->state),
            'disease_name' => $treatment->disease_name,
            'date' => $treatment->start,
            'recipes' => json_decode($treatment->recipe),
            "price" => $treatment->price,
            'recipe_name' => $recipe_name
        ]);
    }
    function payTreatment(Request $request){
        validate($request->all(), [
            'guahao'=>'required'
        ]);

        $guahao = $request->get('guahao');
        $treatment = treatment::where('guahao',$guahao)->first();
        $treatment->update([
           'state'=>config('constant.treat_state.close')
        ]);
        return success("OK");
    }
}
