<?php
namespace App\Http\Controllers\Admin;

use App\contrary;
use App\department;
use App\doctor;
use App\hospital;
use App\Http\Controllers\Controller;
use App\Imports\ContraryImport;
use App\Imports\MedicineImport;
use App\medicine;
use App\patient;
use App\question;
use App\recipe;
use App\Rules\MatchOldPassword;
use App\setting;
use App\treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\Return_;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

class DoctorController extends Controller{
    public function dashboard(){
        return view('admin.common.dashboard');
    }

    public function viewMedicine(){
        return view('admin.medicine.view');
    }
    public function uploadRecipes(Request $request){
        $recipes = Excel::toArray(new MedicineImport,$request->file('file_1'));
        $totalNumber = count($recipes[0]);
        $insertNumber = 0;
        $updatedNumber = 0;
        $failureNumber = 0;

        foreach($recipes[0] as $row){
            if($row[0]=='方名'){
                $totalNumber--;
                continue;
            }
            if(empty($row[0])||empty($row[2])||empty($row[3])){
                $failureNumber++;
                continue;
            }

            $strMedicine = $row[2];

            $medicines = getMedicineDatas($strMedicine);


            $dbMedicines = array();
            foreach($medicines as $each){
                if(!empty($each)){
                    $medicine_name = $each['prescription_name'];
                    $medicine = medicine::where('name', $medicine_name)->first();
                    $min_weight = $each['weight'];
                    $max_weight = $each['weight'];
                    $unit = $each['unit'];
                    if($each['unit']==config('constant.unit.liang')){
                        $min_weight *= 50;
                        $max_weight *= 50;
                        $unit = config('constant.unit.gram');
                    }

                    if(empty($medicine)){
                        $medicine = medicine::create([
                            "name" => $medicine_name,
                            'unit' => $unit,
                            'option' => $each['option'],
                            'flag' => 'NORMAL'
                        ]);
                        $price = '0';
                        $unit = $each['unit'];
                    }else{
                        $medicine->update([
                            'option' => $each['option'],
                            'flag' => 'NORMAL'
                        ]);
                        $price = $medicine->price;
                    }

//                    updateRecipesByMedicineUnit($medicine->id,$each['unit']);
                    $item = array(
                        'medicine_id' => $medicine->id,
                        "medicine" => $medicine_name,
                        "min_weight" => $min_weight,
                        "max_weight" => $max_weight,
                        "unit" => $unit,
                        'option' => $each['option'],
                        'price' => $price
                    );
                    array_push($dbMedicines,$item);
                }
            }

            $recipe = recipe::where('prescription_name',$row[0])->first();
            if(!empty($recipe)){
                $updatedNumber++;
                $recipe->update([
                    'prescription_name'     => $row[0],
                    'other_condition'  => $row[1],
                    'eating_method'     => $row[3],
                    'medicine' => json_encode($dbMedicines),
                    'ban'     => $row[4],
                    'flag' => 'NORMAL'
                ]);
            }else{
                $insertNumber++;
                recipe::create([
                    'prescription_name'     => $row[0],
                    'other_condition'  => $row[1],
                    'eating_method'     => $row[3],
                    'medicine' => json_encode($dbMedicines),
                    'ban'     => $row[4],
                    'flag' => 'NORMAL'
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
                    'max_weight'     => $row[5],
                    'flag' => 'NORMAL'

                ]);
            }else{
                $insertNumber++;
                $medicineDb = medicine::create([
                    'name' => $row[0],
                    'usage' => $row[1],
                    'weight' => $row[2],
                    'price' => $row[3],
                    'min_weight' => $row[4],
                    'max_weight'     => $row[5],
                    'flag' => 'NORMAL'
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

        $datas = medicine::select('*')->where('name','like','%'.$searchValue.'%')->where('flag','NORMAL')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = medicine::select('*')->where('name','like','%'.$searchValue.'%')->where('flag','NORMAL')->get();

        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function editMidicineData(Request $request){
        validate($request->all(), [
            'medicine_id'=>'required'
        ]);
        $unit = $request->get('unit');
        $id = $request->get('medicine_id');
        $medicine = medicine::where('id',$id)->first();
        if(empty($unit))
            $unit = $medicine->unit;

        $medicine->update([
            'name'=>$request->get('medicine_name'),
            'usage'=>$request->get('usage'),
            'weight'=>$request->get('weight'),
            'price'=>$request->get('price'),
            'min_weight'=>$request->get('min_weight'),
            'max_weight'=>$request->get('max_weight'),
            'unit' => $unit,
            'flag' => 'NORMAL'
        ]);
        updateRecipesByMedicinePrice($id,$request->get('price'));
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
        $medicine->update([
            'flag'=>'DELETED'
        ]);
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

        $availableDatas = contrary::select('*')->where('name','like','%'.$searchValue.'%')->get();
        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
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

//        if(empty($doctor_id)){//admin
//            $datas = treatment::select('treatments.*')->where('state',config('constant.treat_state.waiting_treatment'))->orWhere('state',config('constant.treat_state.treating'))
//                ->join('patients', 'treatments.patient_id', '=', 'patients.id')
//                ->orderBy('treatments.created_at', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
//            $availableDatas = treatment::select('treatments.*')->where('state',config('constant.treat_state.waiting_treatment'))->orWhere('state',config('constant.treat_state.treating'))
//                ->join('patients', 'treatments.patient_id', '=', 'patients.id')
//                ->get();
//        }else {//doctor
                $datas = treatment::select('treatments.*')->where('doctor_id', $doctor_id)->where('state', config('constant.treat_state.waiting_treatment'))->orWhere('state',config('constant.treat_state.treating'))
                ->join('patients', 'treatments.patient_id', '=', 'patients.id')
                ->orderBy('treatments.created_at', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
                $availableDatas =treatment::select('treatments.*')->where('doctor_id', $doctor_id)->where('state', config('constant.treat_state.waiting_treatment'))->orWhere('state',config('constant.treat_state.treating'))
                    ->join('patients', 'treatments.patient_id', '=', 'patients.id')
                    ->get();
//        }
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

        $totalCount = count($availableDatas);

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

        $datas = treatment::select('treatments.*')->where('treatments.patient_id',$patient_id)->where('state',config('constant.treat_state.close'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', $orderDirection)->skip($start)->take($length)->get();
        $availableDatas =treatment::select('treatments.*')->where('treatments.patient_id',$patient_id)->where('state',config('constant.treat_state.close'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->get();
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
            $temp['disease_name'] = '';
            $temp['number'] = $i;
            $temp['date'] = $data->treat_start;
            $temp['shippingType'] = $data->shippingType;
            $original_recipe = $data->original_recipe;
            $arr_recipe = explode(',',$original_recipe);
            $temp['recipe'] = getPrescriptionName($data->recipe);
//            $recipe_name = '';
//            foreach($arr_recipe as $recipe_id){
//                $db_recipe = recipe::where('id',$recipe_id)->first();
//                if(empty($db_recipe))
//                    continue;
//                $recipe_name .= $db_recipe->prescription_name.',';
//            }
//            $temp['recipe'] = rtrim($recipe_name,',');

            array_push($guahaoDatas,$temp);
            $i++;
        }


        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function createInquiryView($id){
        $treatment = treatment::select('*')->where('treatments.id',$id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->first();
        $patient_id = $treatment->patient_id;
        $datas = treatment::select('*')->where('treatments.patient_id',$patient_id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();

        return view('admin.inquiry.create')->with([
            'treatment'=>$treatment,
            'history_number' => count($datas)*1,
            'medicines' => $medicines
            ]);
    }
    public function uploadVideo(Request $request){
        if(isset($_FILES["video"])) {
            $videoName = str_random(10) . '.' ;
            move_uploaded_file($_FILES["video"]["tmp_name"], public_path() . '/uploads/videos/' . $videoName. 'mp4');
//            exec("ffmpeg -i ".public_path() . '/uploads/videos/' . $videoName. 'webm'." ".public_path() . '/uploads/videos/'.$videoName."mp4",$out);
            return success($videoName. 'mp4');
        }else{
            return error('Invalid parameter');
        }
    }
    public function getRecipe(Request $request){

        validate($request->all(), [
            'question_id' => 'required'
        ]);
        $question = question::where('id',$request->get('question_id'))->first();
        $recipe_datas = getRecipeJSON($question->medicines);
        $question->medicines = $recipe_datas;
        $result = array(
            "recipe" => json_decode($recipe_datas),
            "question" => $question
        );
        return success($result);
    }

    public function getRecipeOther(Request $request){

        $biaozhengs = $request->get('biaozheng');
        $lizhengs = $request->post('lizheng');
        $biaolis = $request->post('biaoli');
        $maizhengs = $request->post('maizheng');

        if (empty($biaozhengs) && empty($lizhengs) && empty($biaolis) && empty($maizhengs)) {
            $result = \DB::select('SELECT *
                                FROM recipes
                                WHERE 1 != 1
                          ');
            return success($result);
        }

        $sql = 'SELECT * FROM recipes WHERE ';
        if (!empty($biaozhengs)) {
            $sql .= "( 1 != 1 ";
            for ($i = 0; $i < count($biaozhengs); $i ++) {
                $sql .= " OR other_condition LIKE '%" . $biaozhengs[$i] . "%'";
            }
            $sql .= ")";
        }
        if (!empty($lizhengs)) {
            if (!empty($biaozhengs))
                $sql .= " AND ";
            $sql .= " ( 1 != 1 ";
            for ($i = 0; $i < count($lizhengs); $i ++) {
                $sql .= " OR other_condition LIKE '%" . $lizhengs[$i] . "%'";
            }
            $sql .= ")";
        }
        if (!empty($biaolis)) {
            if (!empty($lizhengs) || !empty($biaozhengs))
                $sql .= " AND ";
            $sql .= " ( 1 != 1 ";
            for ($i = 0; $i < count($biaolis); $i ++) {
                $sql .= " OR other_condition LIKE '%" . $biaolis[$i] . "%'";
            }
            $sql .= ")";
        }
        if (!empty($maizhengs)) {
            if (!empty($lizhengs) || !empty($biaozhengs) || !empty($biaolis))
                $sql .= " AND ";
            $sql .= " ( 1 != 1 ";
            for ($i = 0; $i < count($maizhengs); $i ++) {
                $sql .= " OR other_condition LIKE '%" . $maizhengs[$i] . "%'";
            }
            $sql .= ")";
        }

        $result = \DB::select($sql);
        return success($result);
    }
    public function updateRecipe(Request $request){
        validate($request->all(), [
            'guahao' => 'required',
        ]);
        $guahao = $request->get('guahao');
        $strbiaozheng = '';
        $biaozheng = $request->get('biaozheng');
        if(!empty($biaozheng)){
            $strbiaozheng = implode (",", $biaozheng);
        }
        $lizheng = $request->get('lizheng');
        $strlizheng = '';
        if(!empty($lizheng)){
            $strlizheng = implode (",", $lizheng);
        }

        $biaoli = $request->get('biaoli');
        $strbiaoli = '';
        if(!empty($biaoli)){
            $strbiaoli = implode (",", $biaoli);
        }

        $mai = $request->get('maizheng');
        $strmai = '';
        if(!empty($mai)){
            $strmai = implode (",", $mai);
        }
        $accept_price = setting::where('name','ACCEPT_PRICE')->first()->value;

        $recipes_detail = $request->get('medicines');
        $past_history = $request->get('past_history');

        if(empty($recipes_detail)){
            $recipes_detail = '[]';
            $state = config('constant.treat_state.close');
        }else
            $state = config('constant.treat_state.before_treating_pay');
        $treatment = treatment::where('guahao',$guahao)->first();
        $total_price = $request->get('total_price');
        $db_past_history = $treatment->past_history;
        $db_past_history = empty($db_past_history)?$db_past_history:$db_past_history.",";

        $treatment->update([
            'state' => $state,
            'price' => $total_price + $accept_price*1.0,
            'recipe' => ($recipes_detail),
            'biaozheng' => $strbiaozheng,
            'lizheng' => $strlizheng,
            'biaoli' => $strbiaoli,
            'past_history' => $db_past_history.$past_history,
            'mai' => $strmai
        ]);
        return success($treatment);

    }

    public function completeTreatment(Request $request){
        validate($request->all(), [
            'guahao' => 'required',
        ]);

        $guahao = $request->get('guahao');
        $question_id = $request->get('question_title');
        $video_url = $request->get('video_url');
        $question_string = $request->get('question_string');
        $annotation_keys = $request->get('annotation_key');
        $annotation_values = $request->get('annotation_value');
        $total_price = $request->get('total_price');
        $disease_name = $request->get('disease_name');
        $comments = $request->get('comment');

        $strbiaozheng = '';
        $biaozheng = $request->get('biaozheng');
        if(!empty($biaozheng)){
            $strbiaozheng = implode (",", $biaozheng);
        }
        $lizheng = $request->get('lizheng');
        $strlizheng = '';
        if(!empty($lizheng)){
            $strlizheng = implode (",", $lizheng);
        }

        $biaoli = $request->get('biaoli');
        $strbiaoli = '';
        if(!empty($biaoli)){
            $strbiaoli = implode (",", $biaoli);
        }

        $mai = $request->get('maizheng');
        $strmai = '';
        if(!empty($mai)){
            $strmai = implode (",", $mai);
        }
        $doctor_question = $request->get('doctor_question');
        $past_history = $request->get('past_history');
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
        $treat_end = date("Y-m-d H:i:s");
        $treatment = treatment::where('guahao',$guahao)->first();
        $accept_price = setting::where('name','ACCEPT_PRICE')->first()->value;

        $recipes_detail = $request->get('medicines');
        if(empty($recipes_detail)){
            $recipes_detail = '[]';
            $state = config('constant.treat_state.close');
        }else
            $state = config('constant.treat_state.before_treating_pay');
        $treatment->update([
            'question_id'=>$question_id,
            'record_video' => $video_url,
            'question' => $question_string,
            'comment' => json_encode($annotations),
            'comment1' => $comments,
//            'original_recipe' => implode(',', $recipe_id),
            'treat_end' => $treat_end,
            'state' => $state,
            'price' => $total_price + $accept_price*1.0,
            'price_medicine' => $total_price,
            'disease_name' => $disease_name,
            'recipe' => ($recipes_detail),
            'biaozheng' => $strbiaozheng,
            'lizheng' => $strlizheng,
            'biaoli' => $strbiaoli,
            'mai' => $strmai,
            'doctor_question' =>$doctor_question,
            'past_history' => $past_history
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
        $history = treatment::where('id',$treat_id)->first();
        $historyData = [];
        $temp = $history;
        $temp->doctor_name = $history->doctor->name;
        $temp->patient_name = $history->patient->name;
        $temp->questions = json_decode($history->question);
        $temp->annotations = json_decode($history->comment);
        $temp->medicines = json_decode($history->recipe);
        $temp->houfang = json_decode($history->houfang);
        $recipe = recipe::where("id",$history->original_recipe)->first();
        $temp->recipe_name = empty($recipe)?'':$recipe->prescription_name;
        $question_id = $history->question_id;

        $biaozheng = "";
        $lizheng = "";
        $biaoli = "";
        $maizheng = "";
        if (is_null($question_id) || !isset($question_id)) {
            $biaozheng_default = config('constant.biaozheng_default');
            $lizheng_default = config('constant.lizheng_default');
            $biaoli_default = config('constant.biaoli_default');
            $maizheng_default = config('constant.maizheng_default');
            $biaozheng = json_encode($biaozheng_default);
            $lizheng = json_encode($lizheng_default);
            $biaoli = json_encode($biaoli_default);
            $maizheng = json_encode($maizheng_default);
        } else {
            $question = question::where('id',$question_id)->first();
            $biaozheng = $question->biaozheng;
            $lizheng = $question->lizheng;
            $biaoli = $question->biaoli;
            $maizheng = $question->maizheng;
        }

        array_push($historyData,$temp);
        $daiNumber = 0;
        return view('admin.inquiry.detail')->with([
            'histories' => $historyData,
            'biaozheng' => $biaozheng,
            'lizheng' => $lizheng,
            'biaoli' => $biaoli,
            'maizheng' => $maizheng,
            'daiNumber' => $daiNumber
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
            ->where('state',config('constant.treat_state.close'))
//            ->where('state','!=',config('constant.treat_state.accept'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', 'desc')->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = treatment::select('*')
            ->where('patients.name','like','%'.$searchValue.'%')
            ->where('state',config('constant.treat_state.close'))
//            ->where('state','!=',config('constant.treat_state.accept'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->get();

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
            $temp['shippingType'] = $data->shippingType;
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
            "iTotalDisplayRecords"=>count($availableDatas),
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
            return error('该身份证号无效，请输入别的身份证号');
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
        $availableDatas = treatment::select('treatments.*')
            ->where('guahao','like','%'.$searchValue.'%')
            ->where(function($query){
                $query->where('state',config('constant.treat_state.after_treating_pay'));
                $query->orWhere('state',config('constant.treat_state.close'));
            })
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->get();
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


        $result = array(
            "aaData"=>$guahaoDatas,
            "iTotalRecords"=>count($guahaoDatas),
            "iTotalDisplayRecords"=>count($availableDatas),
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
    function getAllMonthData(Request $request){
        $doctor_name = $request->get('doctor_name');

        if(!empty($doctor_name)){
            $result = \DB::select('SELECT MONTHNAME(treat_start) AS `month`,YEAR(treat_start) AS `year`, SUM(price) AS `sum`
                                    FROM treatments LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id` where (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\') and doctors.name=\''.$doctor_name.'\'
                                    GROUP BY YEAR(treat_start), MONTH(treat_start)
                                    ');
        }else{
            $result = \DB::select('SELECT MONTHNAME(treat_start) AS `month`,YEAR(treat_start) AS `year`, SUM(price) AS `sum`
                                    FROM treatments where (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\')
                                    GROUP BY YEAR(treat_start), MONTH(treat_start)');
        }
        return success($result);
    }
    function getAllDayData(Request $request){
        $doctor_name = $request->get('doctor_name');
        $month = date('m');

        if(!empty($doctor_name)) {
            $result = \DB::select('SELECT DAY(treat_start) AS `day`, SUM(price) AS `sum`
                                FROM treatments LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
                                WHERE MONTH(treat_start)>' . ($month - 1) . ' AND MONTH(treat_start)<' . ($month + 1) . ' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\') and doctors.name=\'' . $doctor_name . '\'
                                GROUP BY DAY(treat_start)
                          ');
        }else{
            $result = \DB::select('SELECT DAY(treat_start) AS `day`, SUM(price) AS `sum`
                                FROM treatments
                                WHERE MONTH(treat_start)>' . ($month - 1) . ' AND MONTH(treat_start)<' . ($month + 1) . ' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\')
                                GROUP BY DAY(treat_start)
                          ');
        }
        return success($result);
    }
    function getAllWeekData(Request $request){
        $doctor_name = $request->get('doctor_name');

        $mon_value= date('Y-m-d', strtotime('Sunday this week'));
        $sat_value = date('Y-m-d', strtotime('Saturday this week'));

        if(!empty($doctor_name)) {
            $result = \DB::select('SELECT DAY(treat_start) AS `day`, SUM(price) AS `sum`
                                FROM treatments LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
                                WHERE (treat_start)>\'' . $mon_value . '\' AND (treat_start)<\'' . $sat_value . '\' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\') and doctors.name=\'' . $doctor_name . '\'
                                GROUP BY DAY(treat_start)
                          ');
        }else{
            $result = \DB::select('SELECT DAY(treat_start) AS `day`, SUM(price) AS `sum`
                                FROM treatments
                                WHERE (treat_start)>\'' . $mon_value . '\' AND (treat_start)<\'' . $sat_value . '\' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\')
                                GROUP BY DAY(treat_start)
                          ');
        }
        return success($result);
    }
    function getHourlyData(Request $request){
        $doctor_name = $request->get('doctor_name');

        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $yesterday = $yesterday.' 23:59:59';
        $tomorrow = date('Y-m-d',strtotime("+1 days"));
        $tomorrow = $tomorrow.' 00:00:00';

        if(!empty($doctor_name)) {
            $result = \DB::select('
            SELECT HOUR(treat_start) AS `hour`, SUM(price) AS `sum`
            FROM treatments LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
            WHERE DAY(treat_start)> \'' . $yesterday . '\' AND DAY(treat_start)< \'' . $tomorrow . '\' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\') and doctors.name=\'' . $doctor_name . '\'
            GROUP BY HOUR(treat_start)
        ');
        }else{
            $result = \DB::select('
            SELECT HOUR(treat_start) AS `hour`, SUM(price) AS `sum`
            FROM treatments
            WHERE DAY(treat_start)> \'' . $yesterday . '\' AND DAY(treat_start)< \'' . $tomorrow . '\' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\')
            GROUP BY HOUR(treat_start)
        ');
        }
        return success($result);
    }

    public function doctorAll(){
        $hospitals = hospital::select('*')
            ->orderBy('name')->get();
        $departments = department::select('*')
            ->orderBy('name')->get();

        return view('admin.income.doctor')->with([
            "hospitals" => $hospitals,
            "departments" => $departments
        ]);
    }
    public function getDoctorAll(){
        $month = date('m');
        $sql = 'SELECT SUM(price) AS `sum`,doctors.`name`
            FROM treatments LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
            WHERE MONTH(treat_start)>'.($month-1).' AND MONTH(treat_start)<'.($month+1).' AND (treatments.state=\'AFTER_TREATING_PAY\' or treatments.state=\'CLOSE\')
            GROUP BY doctor_id';
        $result = \DB::select($sql);
        return success($result);
    }
    public function incomeHospital(){
        $hospitals = hospital::select('*')
            ->orderBy('name')->get();
        $departments = department::select('*')
            ->orderBy('name')->get();
        return view('admin.income.hospital')->with([
           "hospitals" => $hospitals,
           "departments" => $departments
        ]);
    }
    public function getHospitalProfit(Request $request){
        $hospital_id = $request->get('hospital');
        $department_id = $request->get("department");
        $from = $request->get('from');
        $to =  $request->get('to');

        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        $search = $request->get('search');
        $searchValue = $search['value'];
        switch ($orderColumn) {
            case 'patient_name':
                $orderColumn = "patients.`name`";
                break;
            case 'ID_Number':
                $orderColumn = "patients.`ID_Number`";
                break;
            case 'department_name':
                $orderColumn = "departments.`name`";
                break;
            case 'doctor_name':
                $orderColumn = "doctors.`name`";
                break;
            case 'price':
                $orderColumn = "treatments.`hospital_profit`";
                break;
            case 'treat_start':
                $orderColumn = "treatments.`treat_start`";
                break;
            default:
                $orderColumn = "treatments.`treat_start`";
        }

        $accept_price = setting::select('value')->where('name',config('asset.ACCEPT_PRICE'))->get()[0]->value;

        $sql = "SELECT  treatments.`id`,treatments.`hospital_profit` as price,patients.`name` AS patient_name,patients.`ID_Number`,treatments.`pay_type_guahao`,treatments.`pay_type_medicine`,
                        treatments.price_guahao,treatments.price_medicine,treatments.pay_type_guahao,treatments.pay_type_medicine,
                    departments.`name` AS department_name,doctors.`name` AS doctor_name,treatments.`treat_start`,treatments.updated_at  FROM treatments
                 LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
                 LEFT JOIN patients ON treatments.`patient_id` = patients.id
                 LEFT JOIN departments ON doctors.`department_id` = departments.`id`
                WHERE (treatments.state!='ACCEPT' and treatments.hospital_profit is not null) AND ";

        if(!empty($hospital_id))
            $sql .="doctors.`hospital_id`=".$hospital_id." AND ";
        if(!empty($department_id))
            $sql .="doctors.`department_id`=".$department_id." AND ";
        if(!empty($from))
            $sql .="treatments.`treat_start`>'".$from.":00' AND ";
        if(!empty($to))
            $sql .="treatments.`treat_end`<'".$to.":00' AND ";
        if(!empty($searchValue))
            $sql .=" patients.name like '%".$searchValue."%' AND ";
        $sql .=" 1=1 ";
        $sql .= " order by ".$orderColumn." ".$orderDirection;

        $datas = \DB::select($sql);

        foreach($datas as $data){
            $data->accept_price = $accept_price;
        }

        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($datas),
        );
        echo json_encode($result);
    }



    public function incomeDoctor(){
        $hospitals = hospital::select('*')
            ->orderBy('name')->get();
        $departments = department::select('*')
            ->orderBy('name')->get();
        return view('admin.income.doctor')->with([
            "hospitals" => $hospitals,
            "departments" => $departments
        ]);
    }
    public function getDoctorProfit(Request $request){
        $hospital_id = $request->get('hospital');
        $department_id = $request->get("department");
        $doctor_id = $request->get('doctor_id');
        $phone_number = $request->get('phone_number');
        $doctor_code = $request->get('doctor_code');
        $from = $request->get('from');
        $to =  $request->get('to');

        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderColumnIndex = $order[0]['column'];
        $orderColumn = $columns[$orderColumnIndex]['data'];
        $orderDirection = $order[0]['dir'];
        $search = $request->get('search');
        $searchValue = $search['value'];
        switch ($orderColumn) {
            case 'patient_name':
                $orderColumn = "patients.`name`";
                break;
            case 'ID_Number':
                $orderColumn = "patients.`ID_Number`";
                break;
            case 'department_name':
                $orderColumn = "departments.`name`";
                break;
            case 'doctor_name':
                $orderColumn = "doctors.`name`";
                break;
            case 'price':
                $orderColumn = "treatments.`doctor_profit`";
                break;
            case 'treat_start':
                $orderColumn = "treatments.`treat_start`";
                break;
            default:
                $orderColumn = "treatments.`treat_start`";
        }

        $sql = "SELECT  treatments.`id`,treatments.`doctor_profit` as price,patients.`name` AS patient_name,patients.`ID_Number`,
                        treatments.price_guahao,treatments.price_medicine,treatments.pay_type_guahao,treatments.pay_type_medicine,
                    departments.`name` AS department_name,doctors.`name` AS doctor_name,treatments.`treat_start`,treatments.updated_at  FROM treatments
                 LEFT JOIN doctors ON treatments.`doctor_id`=doctors.`id`
                 LEFT JOIN patients ON treatments.`patient_id` = patients.id
                 LEFT JOIN departments ON doctors.`department_id` = departments.`id`
                WHERE (treatments.state!='ACCEPT' and treatments.doctor_profit is not null) AND ";

        if(!empty($hospital_id))
            $sql .="doctors.`hospital_id`=".$hospital_id." AND ";
        if(!empty($department_id))
            $sql .="doctors.`department_id`=".$department_id." AND ";
        if(!empty($from))
            $sql .="treatments.`treat_start`>'".$from.":00' AND ";
        if(!empty($to))
            $sql .="treatments.`treat_end`<'".$to.":00' AND ";

        if(!empty($doctor_id))
            $sql .="treatments.`doctor_id`='".$doctor_id."' AND ";
        if(!empty($phone_number))
            $sql .="doctors.`phone`='".$phone_number."' AND ";
        if(!empty($doctor_code))
            $sql .="treatments.`doctor_id` = '".$doctor_code."' AND ";

        if(!empty($searchValue))
            $sql .=" patients.name like '%".$searchValue."%' AND ";
        $sql .=" 1=1 ";
        $sql .= " order by ".$orderColumn." ".$orderDirection;

//        echo $sql;
        $datas = \DB::select($sql);

        $result = array(
            "aaData"=>$datas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($datas),
        );
        echo json_encode($result);
    }

    public function editPriceView(){
        $medicines = medicine::where('price','<',1)->where('flag','NORMAL')->where(function($query){
            return $query->where('unit','公克')
                ->orWhere('unit','两')
                ->orWhereNull('unit');
        })->orderBy('price')->get();
        return view('admin.medicine.editPrice')->with([
            'medicines' => $medicines
        ]);
    }
    public function savePrice(Request $request){
        $params = $request->all();
        foreach($params as $key=>$value){
            if(substr( $key, 0, 6 ) === "price_"){
                $id = str_replace('price_','',$key);
                $medicine = medicine::where('id',$id)->first();
                $medicine->update([
                    'price'=> $value
                ]);
                updateRecipesByMedicinePrice($medicine->id,$value);
            }
        }
        return success("OK");
    }

    public function getContraryIds(Request $request){
        validate($request->all(), [
            'id'=>'required'
        ]);
        $medicine_id = $request->get('id');
        $medicine = medicine::where('id',$medicine_id)->first();
        if(empty($medicine)){
            return error('参数错误。');
        }
        $contrary = contrary::where('name',$medicine->name)->first();
        $result = [];

        if(!empty($contrary)){
            $strContrary = $contrary->contrary;
            $medicines = medicine::whereIn('name',explode(',',$strContrary))->get();
            foreach($medicines as $each)
                array_push($result,$each->id);
        }
        return success($result);
    }

    public function editInquiryView(Request $request){
        return view('admin.inquiry.editList');
    }
    public function getInquiryList(Request $request){
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
        $accept_price = setting::where('name','ACCEPT_PRICE')->first()->value;

        $datas = treatment::select('treatments.*')->where('treatments.state',config('constant.treat_state.before_treating_pay'))
            ->where(function($query) use ($searchValue) {
                $query->where('patients.name','like','%'.$searchValue.'%')
                    ->orWhere('patients.phone_number','like','%'.$searchValue.'%');
            })
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas =treatment::select('treatments.*')->where('treatments.state',config('constant.treat_state.before_treating_pay'))
            ->where(function($query) use ($searchValue) {
                $query->where('patients.name','like','%'.$searchValue.'%')
                    ->orWhere('patients.phone_number','like','%'.$searchValue.'%');
            })
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->get();
        $arrayDatas = [];
        foreach($datas as $data){
            $temp['id'] = $data->id;
            $temp['treat_start'] = $data->treat_start;
            $temp['guahao'] = $data->guahao;
            $temp['patient_name'] = $data->patient->name;
            $temp['phone_number'] = $data->patient->phone_number;
            $strRecipes = getPrescriptionName($data->recipe);
            $temp['recipe_name'] = $strRecipes;
            $temp['price'] = $data->price-$accept_price;
            $temp['doctor_name'] = $data->doctor->name;
            $temp['department_name'] = $data->doctor->department->name;
            array_push($arrayDatas,$temp);
        }
        $totalCount = count($availableDatas);
        $result = array(
            "aaData"=>$arrayDatas,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>$totalCount,
        );

        return json_encode($result);
    }
    public function editInquiry($id){
        $history = treatment::where('id',$id)->first();
        $historyData = [];
        $temp = $history;
        $temp->doctor_name = $history->doctor->name;
        $temp->patient_name = $history->patient->name;
        $temp->questions = json_decode($history->question);
        $temp->annotations = json_decode($history->comment);
        $temp->medicines = json_decode($history->recipe);
        $temp->houfang = json_decode($history->houfang);
        $recipe = recipe::where("id",$history->original_recipe)->first();
        $temp->recipe_name = empty($recipe)?'':$recipe->prescription_name;
        $question_id = $history->question_id;

        $biaozheng = "";
        $lizheng = "";
        $biaoli = "";
        $maizheng = "";
        if (is_null($question_id) || !isset($question_id)) {
            $biaozheng_default = config('constant.biaozheng_default');
            $lizheng_default = config('constant.lizheng_default');
            $biaoli_default = config('constant.biaoli_default');
            $maizheng_default = config('constant.maizheng_default');
            $biaozheng = json_encode($biaozheng_default);
            $lizheng = json_encode($lizheng_default);
            $biaoli = json_encode($biaoli_default);
            $maizheng = json_encode($maizheng_default);
        } else {
            $question = question::where('id',$question_id)->first();
            $biaozheng = $question->biaozheng;
            $lizheng = $question->lizheng;
            $biaoli = $question->biaoli;
            $maizheng = $question->maizheng;
        }

        $treatment = treatment::select('*')->where('treatments.id',$id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->first();
        $patient_id = $treatment->patient_id;
        $datas = treatment::select('*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.close'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();

        array_push($historyData,$temp);
        $daiNumber = 0;
        return view('admin.inquiry.edit')->with([
            'histories' => $historyData,
            'biaozheng' => $biaozheng,
            'lizheng' => $lizheng,
            'biaoli' => $biaoli,
            'maizheng' => $maizheng,
            'history_number' => count($datas)*1,
            'daiNumber' => $daiNumber,
            'medicines' =>$medicines
        ]);
    }
}
