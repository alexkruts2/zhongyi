<?php


namespace App\Http\Controllers\Admin;


use App\department;
use App\doctor;
use App\hospital;
use App\Http\Controllers\Controller;
use App\medicine;
use App\question;
use App\recipe;
use App\setting;
use App\treatment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function loginView() {
        $id = auth()->guard('admin')->id();
        if(!empty($id)) {
            return redirect()->route('admin.doctor.view');
        }else{
            $id = auth()->guard('doctor')->id();
            if(!empty($id))
                return redirect()->route('doctor.history.all.view');
        }

            return view('admin.auth.login');
    }
    public function test(){
        echo "OK";
    }

    public function dashboard() {
        return view('admin.home.dashboard');
    }
    public function department(){
        return view('admin.department.view');
    }
    public function viewDepartment(){
        return view('admin.doctor.department');
    }
    public function getDepartments(Request $request){
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
        $datas = department::select('*')->where('name','like','%'.$searchValue.'%')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = department::select('*')->where('name','like','%'.$searchValue.'%')->get();

        $departmentData = array();
        foreach($datas as $data) {
            $obj["created_at"] = date_format($data->created_at,'Y-m-d H:m:s');
            $obj["name"] = is_null($data->name) ? '' : $data->name;
            $obj["id"] = $data->id;
            array_push($departmentData,$obj);
        }

        $result = array(
            "aaData"=>$departmentData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function deleteDepartment(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $department = department::where("id",$request->get('id'));
        if (is_null($department)) {
            return error('找不到该数据');
        }
        $department->delete();
        return success();
    }
    public function editDepartment(Request $request){
        validate($request->all(), [
            'id' => 'required',
            'name' => 'required'
        ]);
        $department = department::where('id',$request->get('id'))->first();
        $department->update([
            'name'=>$request->get('name')
        ]);
        return success($department);

    }
    public function createDepartment(Request $request){
        validate($request->all(), [
            'name' => 'required'
        ]);
        $department_name = $request->get('name');
        $department_check = department::where("name",$department_name)->first();
        if(empty($department_check)){
            $department = department::create([
                'name'=>$department_name
            ]);
            return success([
                'id'=>$department->id
            ]);
        }else{
            return error('已经存在');
        }
    }
    public function createDoctor(){
        $departments = department::select('*')
            ->orderBy('name')->get();
        $hospitals = hospital::select('*')
            ->orderBy('name')->get();
        return view('admin.doctor.create')->with([
            'departments'=>$departments,
            'hospitals' => $hospitals
        ]);
    }

    public function saveDoctor(Request $request){
        validate($request->all(), [
            'hospital_name' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'introduction' => 'required',
            'department' => 'required',
            'from' => 'required',
            'to' => 'required'
        ]);
        $doctor = doctor::where('name',$request->get('name'))->where('state','NORMAL')->first();

        if(empty($request->get('id'))){
            if(!empty($doctor)){
                return error('医生姓名已经存在');
            }
            $result = doctor::updateOrCreate([
                "id"=>$request->get("id")
            ],[
                "hospital_id"=>$request->get("hospital_name"),
                "name"=>$request->get("name"),
                "phone"=>$request->get("phone"),
                "department_id"=>$request->get("department"),
                "introduction"=>$request->get("introduction"),
                "from"=>$request->get("from"),
                "to"=>$request->get("to"),
                "visiting_place"=>$request->get("visiting_place"),
                "password"=>bcrypt("12345678"),
                "state" => "NORMAL",
                'doctor_ratio' => $request->get('doctor_ratio'),
                'authority' => "[\"问诊\"]"
            ]);
        }else{
            if(!empty($doctor)){
                if($doctor->id!=$request->get('id') && $doctor->name==$request->get('name'))
                    return error('医生姓名已经存在');
            }
            $result = doctor::updateOrCreate([
                "id"=>$request->get("id")
            ],[
                "hospital_id"=>$request->get("hospital_name"),
                "name"=>$request->get("name"),
                "phone"=>$request->get("phone"),
                "department_id"=>$request->get("department"),
                "introduction"=>$request->get("introduction"),
                "from"=>$request->get("from"),
                "to"=>$request->get("to"),
                'doctor_ratio' => $request->get('doctor_ratio'),
                "visiting_place"=>$request->get("visiting_place"),
            ]);
        }
        return success($result);
    }
    public function viewDoctor(){

        $datas = department::select('*')
            ->orderBy('name')->get();

        return view('admin.doctor.view')->with('departments',$datas);
    }
    public function getDoctors(Request $request){
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

        $department = $columns[4]['search']['value'];
        if(empty($department)){
            $datas = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('state','NORMAL')
                ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
            $availableDoctors = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('state','NORMAL')->get();
        }else{
            $datas = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('department_id',$department)->where('state','NORMAL')
                ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
            $availableDoctors = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('department_id',$department)->where('state','NORMAL')->get();

        }

        $doctorData = array();
        foreach($datas as $data) {
            $data->department_name = $data->department->name;
            $data->hospital_name = hospital::where("id",$data->hospital_id)->first()->name;
            array_push($doctorData,$data);
        }


        $result = array(
            "aaData"=>$doctorData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDoctors),
        );
        return json_encode($result);
    }
    public function getAllDepartment(){
        $datas = department::select('*')->get();
        return success($datas);
    }
    public function deleteDoctor(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $doctor = doctor::where("id",$request->get('id'));
        if (is_null($doctor)) {
            return error('找不到该数据');
        }
        $doctor->update([
            'state' => 'DELETED'
        ]);
        return success();
    }

    public function editDoctor($id){
        $doctor = doctor::select('*')->where('id',$id)->get();
        $departments = department::select('*')
            ->orderBy('name')->get();
        $hospitals = hospital::select('*')
            ->orderBy('name')->get();
        return view('admin.doctor.edit')->with(['departments'=>$departments,'doctor'=>$doctor,'hospitals'=>$hospitals]);
    }
    public function detailDoctor($id){
        $doctor = doctor::select('*')->where('id',$id)->get();
        $departments = department::select('*')
            ->orderBy('name')->get();
        $hospitalName = hospital::where('id',$doctor[0]->hospital_id)->first()['name'];
        return view('admin.doctor.detail')->with(['departments'=>$departments,'doctor'=>$doctor,'hospital_name'=>$hospitalName]);
    }
    public function setting(){
        $weixin_url = setting::select('value')->where('name',config('asset.weixin_pay'))->get()[0]->value;
//        $zhifubao_url = setting::select('value')->where('name',config('asset.zhifubao_pay'))->get()[0]->value;
        $zhifubao_url = setting::select('value')->where('name','ZHIFUBAO_PAY')->get()[0]->value;
        $accept_price = setting::select('value')->where('name',config('asset.ACCEPT_PRICE'))->get()[0]->value;

        return view('admin.setting.payment')->with([
            'weixin_url' => $weixin_url,
            'zhifubao_url' => $zhifubao_url,
            'accept_price' => $accept_price
        ]);
    }
    public function saveQRImage(Request $request){
        $weixin_image = $request->hasFile('weixin_image') ? $request->file('weixin_image') : $request->input('weixin_image');
        if(!empty($weixin_image)){
            $filename = sha1($weixin_image->getClientOriginalName() . time()) . '.' . $weixin_image->getClientOriginalExtension();
            save_file_as($weixin_image, config('asset.image_path'), $filename);
            $result = setting::updateOrCreate([
                "name"=> config('asset.weixin_pay')
            ],[
                "value"=>config('asset.image_path').$filename
            ]);
        }
        $zhifubao_image = $request->hasFile('zhifubao_image') ? $request->file('zhifubao_image') : $request->input('zhifubao_image');
        if(!empty($zhifubao_image)){
            $filename = sha1($zhifubao_image->getClientOriginalName() . time()) . '.' . $zhifubao_image->getClientOriginalExtension();
            save_file_as($zhifubao_image, config('asset.image_path'), $filename);
            $result = setting::updateOrCreate([
                "name"=> config('asset.zhifubao_pay')
            ],[
                "value"=>config('asset.image_path').$filename
            ]);
        }

        return success("ok");
    }
    public function viewRecipePart(){
        return view('admin.recipe.part');
    }

    public function createRecipeView(Request $request){
        $datas = department::select('*')
            ->orderBy('name')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();
        return view('admin.recipe.create')->with(['departments'=>$datas,'medicines'=>$medicines]);
    }
    public function createRecipe(Request $request){
        validate($request->all(), [
            'department' => 'required',
            'medicine_name' => 'required',
            'prescription_name' => 'required'
        ]);
        $department_id = $request->get('department');
        $conditions = $request->get('disease');
        $other_condition = $request->get('other_condition');
        $medicine_names = $request->get('medicine_name');
        $min_weights = $request->get("min_weight");
        $max_weights = $request->get("max_weight");
        $prices = $request->get("price");
        $prescription_name = $request->get('prescription_name');
        $eating_method = $request->get('eating_method');
        $recipe_price = $request->get('recipe-price');
        $maizheng = $request->get('maizheng');

        $ban = $request->get('ban');

        $str_conditions = implode(', ', $conditions);
        $medicines = array();
        foreach($medicine_names as $key=>$medicine_name){
            $min_weight = $min_weights[$key];
            $max_weight = $max_weights[$key];
            $price = $prices[$key];
            $medicine = medicine::where('name',$medicine_name)->first();
            $item = array(
                'medicine_id' => $medicine->id,
                "medicine" => $medicine_name,
                "min_weight" => $min_weight,
                "max_weight" => $max_weight,
                "unit" => $medicine->unit,
                "option" => $medicine->option,
                "maizheng" => $maizheng,
                "price"=>$price
            );
            array_push($medicines,$item);
        }
        $recipe = recipe::create([
            'disease_name'=>'disease_name',
            'department_id'=>$department_id,
            'condition'=>$str_conditions,
            'other_condition'=>$other_condition,
            'medicine'=>json_encode($medicines),
            "eating_method" => $eating_method,
            "ban" => $ban,
            'prescription_name'=>$prescription_name,
            'price' => $recipe_price,
            'flag' => 'NORMAL'
        ]);
        return success([
            'id'=>$recipe->id
        ]);

    }
    public function viewRecipe(){
        return view('admin.recipe.view');
    }

    public function getRecipeDatas(Request $request){
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

        $orderColumn = $orderColumn=='department'?'department_id':$orderColumn;

        if(empty($searchValue)){
            $datas = recipe::select('*')->where('flag','!=','DELETED')
                ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
            $availableDatas = recipe::select('*')->where('flag','!=','DELETED')->get();
        }else{
            $datas = recipe::select('*')->OrWhere('prescription_name','like','%'.$searchValue.'%')->where('flag','!=','DELETED')
                ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
            $availableDatas = recipe::select('*')->where('prescription_name','like','%'.$searchValue.'%')->where('flag','!=','DELETED')->get();
        }

        $departmentData = array();
        foreach($datas as $data) {
            $obj["created_at"] = date_format($data->created_at,'Y-m-d H:m:s');
            $obj["disease_name"] = is_null($data->disease_name) ? '' : $data->disease_name;
            $obj["prescription_name"] = is_null($data->prescription_name) ? '' : $data->prescription_name;
            $obj["department"] = is_null($data->department_id) || $data->department_id < 1 ? '' : $data->department->name;
            $obj["price"] = is_null($data->price)  ? '0' : $data->price;
            $obj["id"] = $data->id;
            array_push($departmentData,$obj);
        }

        $result = array(
            "aaData"=>$departmentData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function deleteRecipe(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $recipe = recipe::where("id",$request->get('id'));
        if (is_null($recipe)) {
            return error('找不到该数据');
        }

//        $recipe->delete();
        $recipe->update([
           'flag' =>'DELETED'
        ]);
        return success();
    }
    public function deleteAllRecipe(Request $request){
//        $recipe = recipe::where("id",$request->get('id'));
        recipe::truncate();

        return success();
    }
    public function editRecipe($id){

        $recipe = recipe::where("id",$id)->first();
        $datas = department::select('*')
            ->orderBy('name')->get();
        $medicines = medicine::select('*')->orderBy('name')->get();

        $recipe_medicines = json_decode(getMedicineJSON($recipe->medicine));
        $conditions = explode(',',$recipe->condition);

        return view('admin.recipe.edit') -> with([
            'recipe'=>$recipe,
            'departments'=>$datas,
            'medicines'=>$medicines,
            'recipe_medicines' => $recipe_medicines,
            'conditions' => $conditions
        ]);
    }
    public function updateRecipe(Request $request){
        validate($request->all(), [
            'department' => 'required',
            'medicine_name' => 'required',
            'prescription_name' => 'required',
            'recipe_id' => 'required'
        ]);

        $id = $request->get('recipe_id');
        $department_id = $request->get('department');
        $conditions = $request->get('disease');
        $other_condition = $request->get('other_condition');
        $medicine_names = $request->get('medicine_name');
        $min_weights = $request->get("min_weight");
        $max_weights = $request->get("max_weight");
        $prices = $request->get("price");
        $prescription_name = $request->get('prescription_name');
        $recipe_price = $request->get('recipe-price');
        $maizheng = $request->get('maizheng');

        $eating_method = $request->get('eating_method');
        $ban = $request->get('ban');


        $str_conditions = implode(', ', $conditions);
        $medicines = array();
        foreach($medicine_names as $key=>$medicine_name){
            $min_weight = $min_weights[$key];
            $max_weight = $max_weights[$key];
            $price = $prices[$key];
            $medicine = medicine::where('name',$medicine_name)->first();
            $item = array(
                'medicine_id'=>$medicine->id,
                "medicine"=>$medicine_name,
                "min_weight"=>$min_weight,
                "max_weight"=>$max_weight,
                "unit" => $medicine->unit,
                "option" => $medicine->option,
                "price"=>$price
            );
            array_push($medicines,$item);
        }
        $recipe = recipe::updateOrCreate([
            'id'=>$id
        ],[
            'department_id'=>$department_id,
            'condition'=>$str_conditions,
            'other_condition'=>$other_condition,
            'medicine'=>json_encode($medicines),
            'prescription_name'=>$prescription_name,
            'eating_method' => $eating_method,
            'ban' => $ban,
            'price' => $recipe_price,
            "maizheng" => $maizheng,
            'flag' => 'NORMAL'
        ]);
        return success([
            'id'=>$recipe->id
        ]);
    }

    public function createQAView(){
        $departments = department::select('*')
            ->orderBy('name')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();

        return view('admin.qa.create')->with(['departments'=>$departments,'medicines' => $medicines]);
    }
    public function createQA(Request $request){
        validate($request->all(), [
            'title' => 'required',
            'recipes' => 'required',
            'questions' => 'required'
        ]);
        $title = $request->get('title');
        $department_id = $request->get('department');
        $recipes = $request->get('recipes');
        $disease_name = $request->get('disease_name');
        $questions = $request->get('questions');
        $number = random_str('alphanum',6);

        $biaozheng = $request->get('biaozheng');
        $lizheng = $request->get('lizheng');
        $biaoli = $request->get('biaoli');
        $mai = $request->get('maizheng');

        $medicines = $request->get('medicines');
        $fuDaiNumber = ""; // added by wangming

        $question = question::create([
           'questions' => $questions,
           'department_id'=>$department_id,
           'recipes' => json_encode($recipes),
           'title' => $title,
           'number' => $number,
           'disease_name' => '',
            'biaozheng' => $biaozheng,
            'lizheng' => $lizheng,
            'biaoli' => $biaoli,
            'maizheng' => $mai,
            'medicines' => ($medicines),
        ]);
        return success($question);
    }
    public function viewQA(){
        return view('admin.qa.view');
    }
    public function getQADatas(Request $request){
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

        $datas = question::select('*')->where('title','like','%'.$searchValue.'%')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = question::select('*')->where('title','like','%'.$searchValue.'%')->get();
        $qaData = array();
        foreach($datas as $data) {
            $obj["created_at"] = date_format($data->created_at,'Y-m-d H:m:s');
            $obj["id"] = $data->id;
            $obj["number"] = $data->number;
            $obj["title"] = $data->title;
            $obj["department"] = $data->department->name;
            array_push($qaData,$obj);
        }

        $result = array(
            "aaData"=>$qaData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function editQAView($id){
        $departments = department::select('*')
            ->orderBy('name')->get();

        $question = question::select('*')->where('id',$id)->first();
        $department_id = $question->department_id;
        $doctors = doctor::select('*')->where('department_id',$department_id)->orderBy('name')->get();
        $medicines = medicine::select('*')->where('flag','NORMAL')->orderBy('name')->get();

        return view('admin.qa.edit')->with([
            'question'=>$question,
            'departments'=>$departments,
            'department_id'=>$department_id,
            'doctors'=>$doctors,
            'biaozheng' => $question->biaozheng,
            'lizheng' => $question->lizheng,
            'biaoli' => $question->biaoli,
            'maizheng' => $question->maizheng,
            'fuDaiNumber' => $question->fuDaiNumber,
            'medicines' => $medicines,
            'recipes' => getRecipeJSON($question->medicines)
        ]);
    }
    public function editQAData(Request $request){
        validate($request->all(), [
            'question_id'=>'required',
            'title'=>'required',
            'doctor_id' => 'required',
            'recipes' => 'required',
            'questions' => 'required',
            'number' => 'required'
        ]);
        $question_id = $request->get('question_id');
        $title = $request->get('title');
        $department_id = $request->get('department');
        $recipes = $request->get('recipes');
        $questions = $request->get('questions');
        $number = $request->get('number');
        $biaozheng = $request->get('biaozheng');
        $lizheng = $request->get('lizheng');
        $biaoli = $request->get('biaoli');
        $mai = $request->get('maizheng');

        $medicines = $request->get('medicines');

        $fuDaiNumber = ""; // added by wangming

        $question = question::where('id',$question_id)->first();

        $question->update([
            'department_id' => $department_id,
            'questions' => $questions,
            'recipes' => json_encode($recipes),
            'title' => $title,
            'number' => $number,
            'disease_name' => '',
            'biaozheng' => $biaozheng,
            'lizheng' => $lizheng,
            'biaoli' => $biaoli,
            'maizheng' => $mai,
            'medicines' => ($medicines),
            'fuDaiNumber' => $fuDaiNumber
        ]);
        return success($question);
    }

    public function deleteQA(Request $request){
        validate($request->all(), [
            'id' => 'required'
        ]);
        $question = question::where("id",$request->get('id'));
        if (is_null($question)) {
            return error('找不到该数据');
        }

        $question->delete();
        return success();
    }

    public function viewAuthority(){
        return view('admin.authority.view');
    }
    public function getDoctorsInAuthority(Request $request){
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
        if($orderColumn=='department')
            $orderColumn = 'department_id';

        $datas = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('state','NORMAL')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = doctor::select('*')->where('name','like','%'.$searchValue.'%')->where('state','NORMAL')->get();

        $doctorData = array();
        foreach($datas as $data) {
            $data->department_name = $data->department->name;
            array_push($doctorData,$data);
        }

        $result = array(
            "aaData"=>$doctorData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas)
        );
        return json_encode($result);
    }
    public function updateAuthoriry(Request $request){
        validate($request->all(), [
            'id'=>'required',
            'authority'=>'required'
        ]);
        $doctor_id = $request->get('id');
        $authority = $request->get('authority');
        $doctor = doctor::where('id',$doctor_id)->first();
        $doctor->update([
            'authority' => $authority
        ]);
        return success($doctor);
    }
    public function hospitalView(){
        return view('admin.setting.hospital');
    }
    public function getHospitalList(Request $request){
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
        $datas = hospital::select('*')->where('name','like','%'.$searchValue.'%')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas = hospital::select('*')->where('name','like','%'.$searchValue.'%')->get();

        $departmentData = array();
        foreach($datas as $data) {
            $obj["created_at"] = date_format($data->created_at,'Y-m-d H:m:s');
            $obj["name"] = is_null($data->name) ? '' : $data->name;
            $obj["id"] = $data->id;
            array_push($departmentData,$obj);
        }

        $result = array(
            "aaData"=>$departmentData,
            "iTotalRecords"=>count($datas),
            "iTotalDisplayRecords"=>count($availableDatas),
        );
        return json_encode($result);
    }
    public function createHospital(Request $request){
        validate($request->all(), [
            'name' => 'required'
        ]);
        $hospital_name = $request->get('name');
        $hospital = hospital::create([
           'name' => $hospital_name
        ]);
        return success($hospital);
    }
    public function editHospital(Request $request){
        validate($request->all(), [
            'name' => 'required',
            'id' => 'required'
        ]);
        $hospital = hospital::where('id',$request->get('id'))->first();
        $hospital->update([
            'name'=>$request->get('name')
        ]);
        return success($hospital);

    }
    public function setAcceptPrice(Request $request){
        validate($request->all(), [
            'accept_price'=>'required'
        ]);
        $accept_price = $request->get('accept_price');
        $setting = setting::where('name','ACCEPT_PRICE')->first();
        $result = $setting->update([
            'value' => $accept_price
        ]);
        return success();
    }

    public function giveMedicineView(){
        $weixin_url = setting::select('value')->where('name',config('asset.weixin_pay'))->get()[0]->value;
        $zhifubao_url = setting::select('value')->where('name',config('asset.zhifubao_pay'))->get()[0]->value;

        return view('admin.recipe.give')->with([
            "weixin_url" => $weixin_url,
            "zhifubao_url" => $zhifubao_url
        ]);
    }
    public function getMedicineData(Request $request){
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

        $datas = treatment::select('treatments.*')->where('treatments.state',config('constant.treat_state.after_treating_pay'))
            ->where(function($query) use ($searchValue) {
                $query->where('patients.name','like','%'.$searchValue.'%')
                    ->orWhere('patients.phone_number','like','%'.$searchValue.'%');
            })
            ->join('patients', 'treatments.patient_id', '=', 'patients.id')
            ->orderBy($orderColumn, $orderDirection)->skip($start)->take($length)->get();
        $availableDatas =treatment::select('treatments.*')->where('treatments.state',config('constant.treat_state.after_treating_pay'))
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
            "iTotalRecords"=>$totalCount,
            "iTotalDisplayRecords"=>count($datas)
        );

        return json_encode($result);
    }
    public function checkGuahao(Request $request){
        validate($request->all(), [
            'guahao'=>'required'
        ]);
        $guahao = $request->get('guahao');
        $treatData = treatment::where('guahao',$guahao)->first();
        if(empty($treatData))
            return error('无效挂号');
        $state = $treatData->state;
        if($state!=config('constant.treat_state.after_treating_pay'))
            return error('无效挂号');

        $question_id = $treatData->question_id;
        $question = question::where('id',$question_id)->first();
        $treatData->recipe = getRecipeJSON($treatData->recipe);
        return success($treatData);
    }
    public function giveMedicine(Request $request){
        validate($request->all(), [
            'guahao'=>'required',
            'shippingType'=>'required'
        ]);
        $guahao = $request->get('guahao');
        $shippingType = $request->get('shippingType');
        $kuaidiCompany = $request->get('kuaidiCompany');
        $kuaidiNumber = $request->get('kuaidiNumber');


        $treatData = treatment::where('guahao',$guahao)->first();
        if(empty($treatData))
            return error('无效挂号');
        $state = $treatData->state;
        if($state!=config('constant.treat_state.after_treating_pay'))
            return error('无效挂号');
        $treatData->update([
            'shippingType'=>$shippingType,
            'kuaidiCompany' => $kuaidiCompany,
            'kuaidiNumber' => $kuaidiNumber,
            'state' => config('constant.treat_state.close')
        ]);
        return success();
    }
    public function resetDoctorPassword(Request $request){
        validate($request->all(), [
            'id'=>'required'
        ]);
        $doctor = doctor::where('id',$request->get('id'))->first();
        if(empty($doctor)){
            return error('参数错误');
        }
        $doctor->update([
            "password"=>bcrypt("12345678")
        ]);
        return success("OK");
    }
    public function getRecipes(Request $request){
        validate($request->all(),[
            'recipes'=>'required'
        ]);
        $recipes = $request->get("recipes");
        $arr_recipes = explode (",", $recipes);
        $recipes = recipe::select('id','medicine','prescription_name','other_condition','eating_method','ban','price')->whereIn('id',$arr_recipes)->get();
        $result = [];
        foreach($recipes as $recipe){
            $recipe->medicine = getMedicineJSON($recipe->medicine);
            array_push($result,$recipe);
        }

        return success($result);
    }

}
