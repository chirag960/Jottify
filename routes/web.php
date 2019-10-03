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

Route::get('/avatar',function(){                        //still testing
    $relative_path = '/media/user_profile_photo/asdsadas.png';
    $path = public_path().$relative_path;
    $avatar = new LasseRafn\InitialAvatarGenerator\InitialAvatar();
    return $avatar->name('Lasse')
    ->length(1)
    ->fontSize(0.5)
    ->size(96) // 48 * 2
    ->background('#8BC34A')
    ->color('#fff')
    ->generate()
    ->save($path);
});

Route::get('/taskdetails','TaskController@taskDetailsReport');

Route::get('/getRedis', function() {    //for testing
    print_r(app()->make('redis'));
});

Auth::routes();

Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware'=>'auth'], function(){

    Route::get('/profile',function(){
        return view('profile');
    })->middleware('cacheControl');
    
    Route::get('/projectAndTask','TaskController@titlesList');
    
    Route::get('/home', 'HomeController@index')->middleware('cacheControl');
    
    Route::patch('/profile','ProfileController@update');
    
    Route::post('/profile/image', 'ProfileController@updateImage');
    
    Route::get('/projects', 'ProjectController@index');
    
    Route::post('/projects', 'ProjectController@create');

});


Route::group(['prefix'=>'project/{id}','middleware'=> ['auth','checkProject']], function(){

    Route::get('/','ProjectController@getProjectDetails')->middleware('cacheControl');

    Route::get('statuses','StatusController@index');

    Route::get('tasks','TaskController@index');

    Route::get('allMembers','ProjectController@allUsers');

    //Route::get('member/{member_id}','ProjectController@memberDetails');



    Route::patch('/', 'ProjectController@update');

    Route::patch('Image','ProjectController@updateImage');

    Route::patch('star', 'ProjectController@updateStar');

    Route::patch('title','ProjectController@updateTitle');

    Route::patch('description','ProjectController@updateDescription');

    Route::post('task','TaskController@create');

    Route::post('status','StatusController@create');    

    Route::patch('status/{status_id}', 'StatusController@update');

    Route::delete('status/{status_id}', 'StatusController@archive');
});

Route::group(['prefix'=>'project/{id}', 'middleware' => ['auth','checkProject','checkProjectAdmin']], function(){
    
    Route::post('members','ProjectController@invite');

    Route::patch('member/{member_id}','ProjectController@updateMember');

    Route::delete('member/{member_id}','ProjectController@deleteMember');

    Route::delete('/','ProjectController@delete');  
    
    Route::delete('/status/{status_id}','StatusController@delete');

    Route::get('report','TaskController@report'); //still testing
});

Route::group(['prefix'=>'project/{project_id}/task/{id}','middleware'=> ['auth','checkTask']], function(){

    Route::get('/','TaskController@getTaskDetails')->middleware('cacheControl');

    //Route::get('members','TaskController@getTaskMembers');

    Route::get('checklist','ChecklistController@index');

    Route::get('attachments','AttachmentController@index');

    Route::get('comments','CommentController@index');

    Route::patch('checklist/{checklist_id}','ChecklistController@update');

    Route::post('attachment','AttachmentController@create');

    Route::post('comment','CommentController@create');

    Route::post('checklist','ChecklistController@create');

    Route::post('duedate','TaskController@updateduedate');

    Route::delete('/','TaskController@delete');

    Route::patch('status','TaskController@updateStatus');

    Route::patch('description','TaskController@updateDescription');
    
    Route::get('members','TaskController@getTaskMembers');

    Route::get('onlyMembers','TaskController@getOnlyTaskMembers');
    
    Route::post('members','TaskController@assignTask');

    Route::delete('attachment/{attachment_id}','AttachmentController@delete');

});
