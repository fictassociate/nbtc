<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login
Route::get('/login', 'AuthController@index')->name('login.index');
Route::post('/login/login', 'AuthController@login')->name('login.login');
Route::get('/login/logout', 'AuthController@logout')->name('login.logout');

Route::middleware('session.has')->group(function () {
    Route::get('/', 'ProjectWeightController@index')->name('index');
    // Route::get('/dashboard/project/{year}/{indicator_id}', 'DashboardController@project')->name('project-chart');
    // Route::get('/dashboard/show_project', 'DashboardController@show_project')->name('show_project');

    // Project Weight
    Route::get('/project/dashboard', 'ProjectWeightController@index')->name('weight.subject');
    Route::get('/project/goal/{subjectId}', 'ProjectWeightController@goalTable')->name('weight.goal');
    Route::get('/project/indicator/{indId}', 'ProjectWeightController@indTable')->name('weight.indicator');
    Route::post('/project/weight/store', 'ProjectWeightController@store')->name('weight.store');

    // Master Data
    // Develop Plan
    Route::get('/develop_plan', 'DevelopPlanController@index')->name('develop_plan.index');
    Route::get('/develop_plan/create', 'DevelopPlanController@create')->name('develop_plan.create');
    Route::post('/develop_plan/store', 'DevelopPlanController@store')->name('develop_plan.store');
    Route::get('/develop_plan/edit/{id}', 'DevelopPlanController@edit')->name('develop_plan.edit');
    Route::post('/develop_plan/update/{id}', 'DevelopPlanController@update')->name('develop_plan.update');
    Route::get('/develop_plan/destroy/{id}', 'DevelopPlanController@destroy')->name('develop_plan.destroy');

    // Subject
    Route::get('/subject', 'SubjectController@index')->name('subject.index');
    Route::get('/subject/create', 'SubjectController@create')->name('subject.create');
    Route::post('/subject/store', 'SubjectController@store')->name('subject.store');
    Route::get('/subject/edit/{id}', 'SubjectController@edit')->name('subject.edit');
    Route::post('/subject/update/{id}', 'SubjectController@update')->name('subject.update');
    Route::get('/subject/destroy/{id}', 'SubjectController@destroy')->name('subject.destroy');
    // Ajax
    Route::get('/subject/select/plan', 'SubjectController@select_plan')->name('subject.select.plan');

    // Goal
    Route::get('/goal', 'GoalController@index')->name('goal.index');
    Route::get('/goal/create', 'GoalController@create')->name('goal.create');
    Route::post('/goal/store', 'GoalController@store')->name('goal.store');
    Route::get('/goal/edit/{id}', 'GoalController@edit')->name('goal.edit');
    Route::post('/goal/update/{id}', 'GoalController@update')->name('goal.update');
    Route::get('/goal/destroy/{id}', 'GoalController@destroy')->name('goal.destroy');
    // Ajax
    Route::get('/goal/select/subject', 'GoalController@select_subject')->name('goal.select.subject');
    Route::get('/goal/select/goal', 'GoalController@select_goal')->name('goal.select.goal');

    // Indicator
    Route::get('/indicator', 'IndicatorController@index')->name('indicator.index');
    Route::get('/indicator/create', 'IndicatorController@create')->name('indicator.create');
    Route::post('/indicator/store', 'IndicatorController@store')->name('indicator.store');
    Route::get('/indicator/edit/{id}', 'IndicatorController@edit')->name('indicator.edit');
    Route::post('/indicator/update/{id}', 'IndicatorController@update')->name('indicator.update');
    Route::get('/indicator/destroy/{id}', 'IndicatorController@destroy')->name('indicator.destroy');
    // Ajax
    Route::get('/indicator/select/subject', 'IndicatorController@select_subject')->name('indicator.select.subject');
    Route::get('/indicator/select/goal', 'IndicatorController@select_goal')->name('indicator.select.goal');
    Route::get('/indicator/data/goal', 'IndicatorController@data_indicator')->name('indicator.data.indicator');

    // Department
    Route::get('/department', 'DepartmentController@index')->name('department.index');
    Route::get('/department/create', 'DepartmentController@create')->name('department.create');
    Route::post('/department/store', 'DepartmentController@store')->name('department.store');
    Route::get('/department/edit/{department_code}', 'DepartmentController@edit')->name('department.edit');
    Route::post('/department/update/{department_code}', 'DepartmentController@update')->name('department.update');
    Route::get('/department/destroy/{department_code}', 'DepartmentController@destroy')->name('department.destroy');

    // User
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/store', 'UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::post('/user/update/{id}', 'UserController@update')->name('user.update');
    //Ajax
    Route::get('/user/select/ministry', 'UserController@select_ministry')->name('user.select.ministry');
    Route::get('/user/select/department', 'UserController@select_department')->name('user.select.department');


    // Eval Form
    Route::get('/eval/indicator', 'EvalIndicatorController@index')->name('eval.indicator.index');
    Route::post('/eval/indicator/update/{id}', 'EvalIndicatorController@update')->name('eval.indicator.update');
    // Ajax
    Route::get('/eval/indicator/eval', 'EvalIndicatorController@eval')->name('eval.indicator.eval');

    // Project
    Route::get('/project', 'ProjectController@index')->name('project.index');
    Route::get('/project/create', 'ProjectController@create')->name('project.create');
    Route::post('/project/store', 'ProjectController@store')->name('project.store');
    Route::get('/project/edit/{id}', 'ProjectController@edit')->name('project.edit');
    Route::get('/project/result/{id}', 'ProjectController@result')->name('project.result');
    Route::post('/project/result/store/{id}', 'ProjectController@result_store')->name('project.result.store');
    Route::post('/project/update/{id}', 'ProjectController@update')->name('project.update');
    Route::get('/result/destroy/begin/{id}', 'ProjectController@begin_destroy')->name('project.begin.destroy');
    Route::get('/result/destroy/mid/{id}', 'ProjectController@mid_destroy')->name('project.mid.destroy');
    Route::get('/result/destroy/end/{id}', 'ProjectController@end_destroy')->name('project.end.destroy');
    // Ajax
    Route::get('/project/result_begin', 'ProjectController@result_begin')->name('project.result.begin');
    Route::get('/project/result_mid', 'ProjectController@result_mid')->name('project.result.mid');
    Route::get('/project/result_end', 'ProjectController@result_end')->name('project.result.end');

    // Other
    Route::get('/session/dropdown_plan', 'OtherController@dropdown_plan')->name('session.plan');

    // Excel
    Route::get('/import/project', 'ExcelProjectController@index')->name('excel.project.index');
    Route::post('/import/project/store', 'ExcelProjectController@store')->name('excel.project.store');
});
