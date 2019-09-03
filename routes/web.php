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

Auth::routes(['verify' => true]);

Route::get('/project/{id}/sendmembers','ProjectController@invite');

Route::get('/project/{project_id}/task/{id}/members2','TaskController@assignTask2');

Route::get('/getRedis', function() {
    print_r(app()->make('redis'));
});


Route::get('/sendMail', function () {
    Mail::to('cjain960@gmail.com')->send(new InviteMail()); 
    return 'A message has been sent to Mailtrap!';
 
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile',function(){
    return view('profile');
});

Route::patch('/profile','ProfileController@update');

Route::get('/projectAndTask','TaskController@titlesList');

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

Route::patch('/user', 'ProfileController@update');

Route::post('/user/Image', 'ProfileController@updateImage');

Route::get('/projects', 'ProjectController@index')->middleware('verified');

Route::post('/projects', 'ProjectController@create')->middleware('verified');

//Route::get('/project/{id}','ProjectController@sendMail');

Route::middleware('verified','checkProject')->group(function(){

    Route::get('/project/{id}','ProjectController@getProjectDetails');

    Route::get('/project/{id}/statuses','StatusController@index');

    Route::get('/project/{id}/tasks','TaskController@index');
    
    Route::get('/project/{id}/allMembers','ProjectController@allUsers');

    Route::get('/project/{id}/report','ProjectController@generateReport');

    Route::patch('/project/{id}', 'ProjectController@update');

    Route::delete('/projects/{id}', 'ProjectController@delete');

    Route::patch('/project/{id}/Image','ProjectController@updateImage');

    Route::patch('/project/{id}/star', 'ProjectController@updateStar');

    Route::patch('/project/{id}/title','ProjectController@updateTitle');

    Route::patch('/project/{id}/description','ProjectController@updateDescription');

    Route::patch('/project/{id}/member/{member_id}','ProjectController@updateMember');

    Route::delete('/project/{id}/member/{member_id}','ProjectController@deleteMember');

});

Route::middleware('verified','checkProject','checkProjectAdmin')->group(function(){

    Route::post('/project/{id}/task','TaskController@create');

    Route::post('/project/{id}/members','ProjectController@invite');

    Route::post('project/{id}/status','StatusController@create');    

    Route::patch('project/{id}/status/{status_id}', 'StatusController@update');

    Route::delete('project/{id}/status/{status_id}', 'StatusController@archive');

});

Route::middleware('verified','checkTask')->group(function(){

Route::get('/project/{project_id}/task/{id}','TaskController@getTaskDetails');

Route::get('/project/{project_id}/task/{id}/members','TaskController@getTaskMembers');

Route::get('/project/{project_id}/task/{id}/checklist','ChecklistController@index');

Route::get('/project/{project_id}/task/{id}/attachments','AttachmentController@getTaskDetails');

Route::get('/project/{project_id}/task/{id}/comments','CommentController@index');

Route::patch('/project/{project_id}/task/{task_id}/checklist/{id}','ChecklistController@update');

Route::post('/project/{project_id}/task/{id}/attachment','AttachmentController@create');

Route::post('/project/{project_id}/task/{id}/comment','CommentController@create');

Route::post('/project/{project_id}/task/{id}/checklist','ChecklistController@create');

});


Route::middleware('verified','checkTask','checkTaskAdmin')->group(function(){

    Route::post('/project/{project_id}/task/{id}/duedate','TaskController@createduedate');

    Route::post('/project/{project_id}/task/{id}/description','TaskController@createDescription');

    Route::patch('/project/{project_id}/task/{id}/description','TaskController@createDescription');

    Route::patch('/project/{project_id}/task/{id}/status','TaskController@updateStatus');
    
    Route::post('/project/{project_id}/task/{id}/members','TaskController@assignTask');

});
