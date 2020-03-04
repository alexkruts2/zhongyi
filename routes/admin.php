<?php
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin'], function() {
        Route::get('/setting','Admin\HomeController@setting')->name('admin.setting');
        Route::get('/accept/price','Admin\HomeController@setAcceptPrice')->name('admin.accept.price');

        Route::post('/setting','Admin\HomeController@saveQRImage');
        Route::get('/hospital','Admin\HomeController@hospitalView')->name('admin.hospital');
        Route::get('/hospital/getList','Admin\HomeController@getHospitalList');
        Route::post('/hospital/create','Admin\HomeController@createHospital');
        Route::post('/hospital/edit','Admin\HomeController@editHospital');

        Route::get('/', 'Admin\HomeController@dashboard')->name('admin.dashboard');
        Route::get('/department', 'Admin\HomeController@department')->name('admin.department');
        Route::group(['prefix' => 'doctor'], function () {
            Route::get('/department','Admin\HomeController@viewDepartment')->name('doctor.department');
            Route::get('/department/get','Admin\HomeController@getDepartments');
            Route::get('/getAllDepartment','Admin\HomeController@getAllDepartment');
            Route::post('/department/delete','Admin\HomeController@deleteDepartment');
            Route::post('/department/edit','Admin\HomeController@editDepartment');
            Route::post('/department/create','Admin\HomeController@createDepartment');

            Route::get('/create','Admin\HomeController@createDoctor')->name('doctor.create');
            Route::post('/create','Admin\HomeController@saveDoctor');
            Route::get('/view','Admin\HomeController@viewDoctor')->name('admin.doctor.view');
            Route::post('/get','Admin\HomeController@getDoctors');
            Route::post('/delete','Admin\HomeController@deleteDoctor');
            Route::post('/resetPassword','Admin\HomeController@resetDoctorPassword');

            Route::get('/edit/{id}','Admin\HomeController@editDoctor')->name('doctor.edit');
            Route::get('/detail/{id}','Admin\HomeController@detailDoctor')->name('doctor.edit');
        });

        Route::group(['prefix' => 'authority'], function (){
            Route::get('/view','Admin\HomeController@viewAuthority')->name('doctor.authority.view');
            Route::post('/getDoctors','Admin\HomeController@getDoctorsInAuthority')->name('doctor.authority.getDoctors');
            Route::post('/update','Admin\HomeController@updateAuthoriry')->name('doctor.authority.update');
        });
        Route::group(['prefix' => 'income'], function () {
            Route::get('/hospital', 'Admin\DoctorController@incomeHospital')->name('doctor.income.hospital');
            Route::post('/getHospitalProfit', 'Admin\DoctorController@getHospitalProfit')->name('doctor.income.getHospitalProfit');
            Route::post('/getDoctorProfit', 'Admin\DoctorController@getDoctorProfit')->name('doctor.income.getDoctorProfit');
            Route::get('/doctor', 'Admin\DoctorController@incomeDoctor')->name('doctor.income.doctor');

            Route::get('/all', 'Admin\DoctorController@incomeAll')->name('doctor.income.all');
            Route::get('/doctor', 'Admin\DoctorController@doctorAll')->name('doctor.income.all.doctor');
            Route::get('/getAllMonth', 'Admin\DoctorController@getAllMonthData')->name('doctor.income.getAllMonth');
            Route::get('/getAllDay', 'Admin\DoctorController@getAllDayData')->name('doctor.income.getAllDay');
            Route::get('/getAllWeek', 'Admin\DoctorController@getAllWeekData')->name('doctor.income.getAllWeekData');
            Route::get('/getHourlyData', 'Admin\DoctorController@getHourlyData')->name('doctor.income.getHourlyData');
            Route::get('/getDoctorAll', 'Admin\DoctorController@getDoctorAll')->name('doctor.income.getDoctorAll');
        });

    });
});
Route::group(['prefix' => 'doctor'], function () {
    Route::group(['middleware' => 'admin'], function() {
        Route::group(['prefix' => 'accept'], function () {
            Route::get('/', 'Admin\AcceptController@dashboard')->name('doctor.accept');
            Route::get('/patient/create','Admin\AcceptController@registerPatient')->name('accept.register.patient');
            Route::post('/patient/create','Admin\AcceptController@createPatient')->name('accept.register.patient');
            Route::post('/patient/uploadImage','Admin\AcceptController@uploadPhoto')->name('accept.patient.photo');
            Route::get('/guahao/view','Admin\AcceptController@guahaoView')->name('accept.guahao.view');
            Route::get('/guahao/edit/{id}','Admin\AcceptController@editGuahaoView')->name('accept.guahao.edit.view');
            Route::post('/guahao/update','Admin\AcceptController@updateGuahao')->name('accept.guahao.edit.data');
            Route::post('/guahao/list','Admin\AcceptController@getGuahao')->name('accept.guahao.get');
            Route::post('/guahao/delete','Admin\AcceptController@deleteGuahao')->name('accept.guahao.delete');
            Route::get('/guahao/detail','Admin\AcceptController@detailGuahao')->name('accept.guahao.detail');
            Route::get('/treatment/update','Admin\AcceptController@updateTreatment')->name('accept.treatment.detail');
            Route::get('/payment/create','Admin\AcceptController@paymentCreate')->name('accept.payment.create.view');
            Route::get('/payment/createWidthParam/{id}','Admin\AcceptController@createWidthParam')->name('accept.payment.create.view');
            Route::get('/payment/list','Admin\AcceptController@listPayment')->name('accept.payment.list.view');
            Route::get('/payment/getData','Admin\AcceptController@getPaymentData')->name('accept.payment.getdata');
            Route::get('/payment/done','Admin\AcceptController@donePayment')->name('accept.payment.done');
            Route::post('/payment/list','Admin\AcceptController@listPaymentData')->name('accept.payment.list.data');
        });


        Route::group(['prefix' => 'recipe'], function () {
            Route::get('/create','Admin\HomeController@createRecipeView')->name('doctor.recipe.create');
            Route::post('/create','Admin\HomeController@createRecipe');
            Route::post('/update','Admin\HomeController@updateRecipe');
            Route::get('/view','Admin\HomeController@viewRecipe')->name('doctor.recipe.view');
            Route::get('/getDatas','Admin\HomeController@getRecipeDatas')->name('doctor.recipe.view');
            Route::post('/delete','Admin\HomeController@deleteRecipe')->name('doctor.recipe.delete');
            Route::get('/edit/{id}','Admin\HomeController@editRecipe')->name('doctor.recipe.edit');

            Route::post('/uploadRecipes', 'Admin\DoctorController@uploadRecipes')->name('doctor.medicine.uploadMedicine');

            Route::get('/part','Admin\HomeController@viewRecipePart')->name('doctor.recipe.part');
            Route::get('/getPart','Admin\HomeController@getRecipePart')->name('doctor.recipe.getPart');
            Route::post('/part/delete','Admin\HomeController@deleteRecipePart');
            Route::post('/part/create','Admin\HomeController@createRecipePart');

            Route::get('/give','Admin\HomeController@giveMedicineView')->name('doctor.recipe.give');
            Route::post('/checkGuahao','Admin\HomeController@checkGuahao')->name('doctor.recipe.checkGuahao');
            Route::post('/giveMedicine','Admin\HomeController@giveMedicine')->name('doctor.recipe.giveMedicine');

        });

        Route::group(['prefix' => 'qa'], function (){
            Route::get('/create','Admin\HomeController@createQAView')->name('doctor.qa.create');
            Route::post('/create','Admin\HomeController@createQA')->name('doctor.qa.createQA');
            Route::get('/view','Admin\HomeController@viewQA')->name('doctor.qa.view');
            Route::post('/get','Admin\HomeController@getQADatas')->name('doctor.qa.get');
            Route::get('/edit/{id}','Admin\HomeController@editQAView')->name('doctor.qa.edit');
            Route::post('/editQA','Admin\HomeController@editQAData')->name('doctor.qa.edit');
            Route::get('/delete','Admin\HomeController@deleteQA')->name('doctor.qa.delete');
        });

        Route::group(['prefix' => 'medicine'], function () {
            Route::get('/view', 'Admin\DoctorController@viewMedicine')->name('doctor.medicine.view');
            Route::post('/uploadMedicine', 'Admin\DoctorController@uploadMedicine')->name('doctor.medicine.uploadMedicine');
            Route::post('/get', 'Admin\DoctorController@getMidicineDatas')->name('doctor.medicine.get');
            Route::post('/edit', 'Admin\DoctorController@editMidicineData')->name('doctor.medicine.edit');
            Route::post('/delete', 'Admin\DoctorController@deleteMidicineData')->name('doctor.medicine.delete');

            Route::get('/editPrice', 'Admin\DoctorController@editPriceView')->name('doctor.medicine.editPrice');
            Route::post('/savePrice', 'Admin\DoctorController@savePrice')->name('doctor.medicine.savePrice');


            Route::group(['prefix' => 'contrary'], function () {
                Route::get('/view', 'Admin\DoctorController@viewContrary')->name('doctor.medicine.contrary.view');
                Route::post('/upload', 'Admin\DoctorController@uploadContrary')->name('doctor.medicine.contrary.upload');
                Route::post('/get', 'Admin\DoctorController@getContraryDatas')->name('doctor.medicine.contrary.get');
                Route::post('/edit', 'Admin\DoctorController@editContraryData')->name('doctor.medicine.contrary.edit');
                Route::post('/delete', 'Admin\DoctorController@deleteContraryData')->name('doctor.medicine.contrary.delete');
            });
            Route::group(['prefix'=>'yield'],function(){
                Route::get('/view', 'Admin\DoctorController@yieldView')->name('doctor.medicine.yield.view');
                Route::get('/detail/{id}', 'Admin\DoctorController@detailYieldView')->name('doctor.medicine.yield.detail');
                Route::post('/getData', 'Admin\DoctorController@getYieldData')->name('doctor.medicine.yield.data');
                Route::post('/pay', 'Admin\DoctorController@payTreatment')->name('doctor.medicine.yield.pay');
            });
        });
        Route::group(['prefix' => 'inquiry'], function () {
            Route::get('/view', 'Admin\DoctorController@viewInquiry')->name('doctor.inquiry.view');
            Route::get('/create/{id}', 'Admin\DoctorController@createInquiryView')->name('doctor.inquiry.create.view');
            Route::post('/getGuahao', 'Admin\DoctorController@getGuahao')->name('doctor.inquiry.getGuahao');
            Route::post('/uploadVideo', 'Admin\DoctorController@uploadVideo')->name('doctor.inquiry.uploadVideo');
            Route::post('/getRecipe', 'Admin\DoctorController@getRecipe')->name('doctor.inquiry.getRecipe');
            Route::post('/completeTreatment', 'Admin\DoctorController@completeTreatment')->name('doctor.inquiry.completeTreatment');
            Route::get('/startTreatment', 'Admin\DoctorController@startTreatment')->name('doctor.inquiry.startTreatment');
        });
        Route::group(['prefix' => 'history'], function () {
            Route::get('/all', 'Admin\DoctorController@allHistoryView')->name('doctor.history.all.view');
            Route::get('/individual/{treat_id}', 'Admin\DoctorController@individualHistoryView')->name('doctor.history.individual.view');
            Route::get('/individual', 'Admin\DoctorController@individualHistoryViewNoParam');
            Route::get('/getTreat_id', 'Admin\DoctorController@getTreat_id');
            Route::post('/getAllData', 'Admin\DoctorController@getAllData')->name('doctor.history.all.data');
            Route::post('/getIndividualData', 'Admin\DoctorController@getIndividualData')->name('doctor.history.individual.data');
            Route::get('/detail/{treat_id}', 'Admin\DoctorController@detailTreatment')->name('doctor.inquiry.detailTreatment');
            Route::get('/getContraryIds', 'Admin\DoctorController@getContraryIds')->name('doctor.medicine.getContraryIds');
        });
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/change_password', 'Admin\DoctorController@change_password')->name('doctor.setting.change_password');
            Route::post('/change_password', 'Admin\DoctorController@change_password_data')->name('doctor.setting.change_password.data');
        });

    });
    Route::get('/getDoctors','Admin\AcceptController@getDoctorsInDepartment');
    Route::get('/getContraryMedicines','Admin\DoctorController@getContraryMedicines');
    Route::post('/inquiry/getTreatementData', 'Admin\DoctorController@getTreatementData')->name('doctor.inquiry.getTreatementData');

});


Route::get('logout', 'Admin\UserController@logout')->name('admin.logout');
