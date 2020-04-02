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
        $id = $request->get('medicine_id');
        $medicine = medicine::where('id',$id)->first();
        $medicine->update([
           'name'=>$request->get('medicine_name'),
           'usage'=>$request->get('usage'),
           'weight'=>$request->get('weight'),
           'price'=>$request->get('price'),
           'min_weight'=>$request->get('min_weight'),
           'max_weight'=>$request->get('max_weight'),
            'unit' => $request->get('unit'),
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

        $datas = treatment::select('treatments.*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.waiting_treatment'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy('treat_start', $orderDirection)->skip($start)->take($length)->get();
        $availableDatas =treatment::select('treatments.*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.waiting_treatment'))
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
    public function createInquiryView($id){
        $treatment = treatment::select('*')->where('treatments.id',$id)
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->first();
        $patient_id = $treatment->patient_id;
        $datas = treatment::select('*')->where('treatments.patient_id',$patient_id)->where('state','!=',config('constant.treat_state.waiting_treatment'))
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();

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
//            'original_recipe' => implode(',', $recipe_id),
            'treat_end' => $treat_end,
            'state' => $state,
            'price' => $total_price + $accept_price*1.0,
            'disease_name' => $disease_name,
            'recipe' => ($recipes_detail),
            'biaozheng' => $strbiaozheng,
            'lizheng' => $strlizheng,
            'biaoli' => $strbiaoli,
            'mai' => $strmai,
            'doctor_question' =>$doctor_question
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
            $biaozheng_default = explode('，','发热，汗出，恶风，鼻鸣干呕，头项强痛，恶寒，项背强几几，汗出恶风，汗遂漏不止，小便难，四肢微急难以屈伸 ，微寒，如虐状，热多寒少，身痒，面色有热色，大汗出，形似疟，热多寒少，无汗，自汗出，微恶寒，脚挛急，厥，足温，喘而汗出，身疼腰痛，骨节疼痛，无汗而喘，身疼痛，不汗出而烦躁，厥逆，筋惕肉瞤，身不疼但重，发汗，身无大热，发汗后，汗出而喘，发汗过多，欲得按，身为振振摇，大汗出，身热不去，身瞤动，振振欲擗地，身热恶风，颈项强，日晡所发潮热，潮热，身黄，项强，头痛发热，微盗汗出，日晡所小有潮热，心下痛不可近，支节烦疼，但头汗出，汗出不恶寒，汗出而喘，无大热，伤寒发热汗出不解，时时恶风，背微恶寒，发热无汗，风湿相抟，身体疼烦，不能自转侧，骨节疼烦掣痛（不得屈伸，近之则痛剧），恶风不欲去衣，身微肿，汗出而闷，身重，身难以转侧，口不仁，手足逆冷，自汗出，头痛，日久热不退，其背恶寒，厥逆，四肢沉重疼痛，身体痛，手足寒，骨节痛，手足厥逆（四逆），汗出而厥，手足厥寒，身体重，腰中冷如坐水中，形如水状，腹重如带五千钱，腰以下冷痛，手足厥寒，大汗出，热不去，汗出而厥，身有微热，头痛，大病瘥后腰以下有水气，身体强几几，无汗小便反少，卧不着席，脚挛急，齘齿，全身抽搐，湿家身烦疼，一身尽疼，汗出恶风，身体疼烦，不能自转侧，风湿相搏，骨节疼烦掣痛，手足不得屈伸、近之则痛剧，汗出短气，恶风不欲去衣，身微肿，骨节疼烦，半身不遂，但臂不遂，身痒而瘾疹；外证疮疡，诸肢节疼痛，身体尪赢，脚肿如脱，病历节不可屈伸、疼痛，脚气疼痛、不可屈伸，关节痛，外证身体不仁（身体麻痹不仁）、如风痹状（风痹要疼的，血痹不疼），面色薄，面色白，手足烦，手足烦热，酸削不能行，四肢酸疼，虚劳腰痛，肌肤甲错，两目黯黑。身体重，腰中冷，腰以下冷湿，腹重，振寒（寒战），发汗后脐下悸，其身甲错，腹皮急（肚皮拘急痉挛）、按之濡（软）、如肿状，手足厥冷，白汗出，逆冷，手足不仁，面色黧黑，手足痹，面翕热如醉状，一身面目黄肿，汗出恶风，恶风，一身悉肿，续自汗出，四肢肿，无大热，四肢聂聂动，黄汗病、身肿发热汗出而渴、状如风水、汗色黄如柏汁，黄汗病两胫自冷，历节病两胫发热，身甲错，恶疮，身瞤，腰髋弛痛，腰以上汗出、下无汗，有物在皮中状，身疼重，骨疼，身冷，恶寒，身痹不仁');
            $lizheng_default = explode('，','下利而渴），大汗出，热不去，下利厥逆而恶寒，吐逆，大下利而厥冷，里寒外热，下利谵语，下利后烦按之心下濡，呕，吐涎沫，脉微下利，热多欲饮水，气逆欲吐，大病瘥后劳复，不渴，大便坚硬不通，身热而渴，汗出恶寒，能食，温温欲吐，里急，后重，少腹满，腹痛，腹中痛，腹无积聚，腹满、饮食如故，脉浮而数，腹中寒气、雷鸣切痛，呕吐，痛而闭者（腹胀满而痛，大便不通），心胸中大寒痛（痛而不可触），呕不能饮食，腹中寒，胁下偏痛，寒疝绕脐痛，寒疝腹中痛，下利不饮食（有宿食），但欲饮热，自利、利反快，虽利、心下续坚满，病溢饮、水饮流到四肢当汗出而不汗出，心下痞坚，支饮胸满（大便不通），腹满（肚子胀）口舌干燥，呕家不渴，先渴后呕，呕吐、心下痞、眩悸，心胸中有停痰宿水，吐水，心胸间虚、气满、不能食，多唾口燥，从小腹上冲胸咽，面热如醉、为胃热上冲熏面，微热消渴，消渴、小便反多、以饮一斗、小便一斗，渴欲饮水、水入则吐，渴欲饮水不止，其人苦渴，渴欲饮水，口干舌燥，不渴，食已汗出，暮卧盗汗出，汗出已反发热，腹满胁鸣相逐');
            $biaoli_default = explode('，','胸满，小便不利，小便难，小便数，心烦，咽中干，烦躁，喘而胸满者，嗜卧，胸满胁痛，不汗出而烦躁，咳，少腹满，喘，咳而微喘，微喘，昼日烦躁不得眠，夜而安静，气上冲胸，起则头眩，烦，虚烦不得眠，心中懊侬，烦热，胸中窒，虚烦，卧起不安者，头眩，心烦腹满，往来寒热，胸胁苦满，嘿嘿不欲饮食，心烦喜呕，胁下痞硬，胁下满，呕而发热，腹中急痛，心中悸而烦，郁郁微烦，胸胁满而呕，热结膀胱，其人如狂，少腹急结，血自下，惊狂，卧起不安，气从少腹上冲心，发狂，少腹硬满，小便自利，瘀热在里，少腹硬，其人如狂，其人喜忘，有久瘀血，短气躁烦，心中懊侬，心下支结，胸胁满微结，引胁下痛，气上冲喉咽，不得息，小便自利，汗出短气，表有热，里亦有热，虚劳不足，汗出而闷，心动悸，独语如见鬼状，不识人，循衣摸床，惕而不安，微喘直视，发热谵语，腹满身重，难以转侧，但欲寐，心中烦，不得卧，咽痛、胸满、心烦，咽中伤生疮、不能语言、声不出者，小便自利，胸中气塞，短气，咽中干，虚赢少气，气上冲胸，口噤不得语，胸满，口噤，不呕，小便自利（小便频数），百合病（静默不言不语，昏昏然，卧起不安，饮食无常，冷热不定，口苦，小便赤，精神失常，癫，），狐惑病（默默欲眠，目不得闭，卧起不安，蚀于喉为惑，蚀于阴为狐，不欲饮食，恶闻食臭，其面目乍赤、乍黑、乍白。蚀于上部则声喝，蚀于下部则咽干），微烦，默默但欲卧，目赤如鸠眼，目四眦黑，左胁下脾肿大，病癥瘕，邪火内炽、吐血、衄血、三焦积热、眼目赤肿、口舌生疮、外证疮疡，头眩短气，目瞑，精自出，阴头寒，目眩（目眶痛），发落，男子失精，女子梦交，阴寒精自出，梦失精，咽干口燥，虚劳虚烦不得眠，小便自利，腹重，咳而上气、喉中水鸡声，咳逆上气，时时吐浊，但坐不得眠，咳而脉浮，咳而脉沉，火逆上气、咽喉不利，肺痈（喘不得卧，咳而胸满）、浓未成，咳而胸满，咽干不渴，时出浊唾腥臭，久久吐脓如米粥，咳而上气，喘，目如脱状，烦躁而喘，咳有微热、烦燥胸满，气上冲胸，温疟者其脉如平、身无寒但热、骨节疼烦、时呕，疟多寒者名日牡疟，疟病发渴，治疟寒多微有热、或但寒不热，胸满而短气，少腹弦急（比里急还厉害，），虚劳里急（里急就是少腹里急），少腹拘急（小腹痉挛、抽搐），往来寒热，气从少腹上至心，喘息咳唾，胸背痛，胸痹心痛，心痛彻背，胸痹不得卧，胸痹心中痞、留气结在胸，胁下逆抢心，胸痹、胸中气塞，喉中涩、噎塞习习如痒，胸痹缓急（时轻时重），心中痞，诸逆心悬痛，心痛彻背、背痛彻心，胸胁逆满，胸胁支满，目眩，心下有痰饮（微则短气、甚则悸），常欲蹈其胸上，肝着(瘀滞），悬饮内痛，咳家，咳家、咳烦、胸中痛，喘满，病支饮（咳逆倚息、短气不得卧、其形如肿),苦冒眩，支饮不得息（痰饮充斥、压迫肺，呼吸困难），脐下有悸，吐涎沫而癫眩，咳逆、倚息不得卧，小便难，时复冒，冲气即低、而反更咳、胸满、咳满，咳满即止、而更复渴、冲气复发，消渴小便反多、以饮一斗、小便一斗，小便如粟状、小腹弦急、痛引脐中，小便自利，烦躁，遗尿，心下坚大如盘、边如旋盘');
            $maizheng_default = explode('，','脉缓，脉浮缓，脉促，脉微缓，脉微，脉洪大，脉微弱，脉浮，脉沉，脉浮细，脉沉微，脉沉紧，脉浮数，脉微而沉，脉沉结，脉浮而动数，脉沉而紧，脉关上浮，寸脉微浮，脉浮，脉浮虚而涩，脉浮滑，脉结悸，脉结代，脉迟，脉弦，脉涩，脉滑，脉浮数，脉数，无脉，脉微欲绝，脉微而厥，脉细欲绝，脉弱，脉沉迟，脉浮虚而涩脉微而数，脉阴阳俱微，脉浮弱而涩，脉极虚芤迟，脉浮大，脉浮而数，脉紧弦，脉沉弦，脉伏，脉沉弦，脉沉紧，脉沉小，脉迟而涩');
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


        $sql = "SELECT  treatments.`id`,treatments.`hospital_profit` as price,patients.`name` AS patient_name,patients.`ID_Number`,
                    departments.`name` AS department_name,doctors.`name` AS doctor_name,treatments.`treat_start`  FROM treatments
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
                    departments.`name` AS department_name,doctors.`name` AS doctor_name,treatments.`treat_start`  FROM treatments
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
}
