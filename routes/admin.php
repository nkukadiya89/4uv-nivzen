<?php

use App\Http\Controllers\Backend\AuthenticateController;
use App\Http\Controllers\Backend\BatchesContentController;
use App\Http\Controllers\Backend\BatchesController;
use App\Http\Controllers\Backend\CoursesController;
use App\Http\Controllers\Backend\ModulesController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\MachineUsersController;
use App\Http\Controllers\Backend\DistributorController;
use App\Http\Controllers\Backend\ProspectController;
use App\Http\Controllers\Backend\ToDoController;
use App\Http\Controllers\Backend\TrainingController;
use App\Http\Controllers\Backend\TrainingProgramsController;
use App\Http\Controllers\Backend\SupportRequestController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => [ 'guest.backend:backend']], function() {

    Route::get('/', [AuthenticateController::class, 'index'])->name('backend.main.index');;


    Route::get('/login', [AuthenticateController::class,'index'])->name('backend-index');;
    Route::post('/login', [ AuthenticateController::class,'loginValidate'])->name('backend.login');

    Route::any('register/add', [AuthenticateController::class, 'addUser'])->name('backend.register');

    Route::any('reset-password', [AuthenticateController::class, 'sendResetLinkEmail'])->name('backend.reset.password');

});

Route::group(['middleware' => ['web', 'backend:backend']], function() {

    // Manage dashboard
    Route::get('dashboard', [ AuthenticateController::class,'dashboard'])->name('backend.dashboard');
    //Route::get('profile', [ AuthenticateController::class,'myProfile'])->name('my-profile');
    Route::any('profile', [AuthenticateController::class,'updateProfile'])->name('profile');
    Route::post('logout', [ AuthenticateController::class,'logout'])->name('backend-logout');
    Route::get('change-password',[AuthenticateController::class,'displayChangePasswordForm'])->name('auth.change_password_form');
    Route::post('change-password',[AuthenticateController::class,'changePassword'])->name('auth.change_password');

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);
    Route::post('permissions/list-ajax', [PermissionController::class,'anyListAjax'])->name('permissions-list-ajax');

    Route::resource('roles',  RoleController::class);

    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

    // Manage user module
    Route::get('users', [UsersController::class,'index'])->name('users-manage');
    Route::post('users/list-ajax', [UsersController::class,'anyListAjax'])->name('users-list-ajax');
    Route::any('users/add', [UsersController::class,'showUsersForm'])->name('user-add-form');
    Route::post('users/add', [UsersController::class,'addUSer'])->name('user-add');
    Route::any('users/edit/{id}', [UsersController::class,'editUser'])->name('user-edit');
    Route::get('users/view/{id}', [UsersController::class,'show'])->name('user-view');
    Route::get('users/delete/{id}', [UsersController::class,'deleteUser'])->name('user-delete')->middleware('permission:delete user');
    Route::post('users/bulk-action', [UsersController::class, 'bulkAction'])->name('user.bulkAction');

    // Manage user module
    Route::get('machine-users', [MachineUsersController::class,'index'])->name('machine-users-manage');
    Route::post('machine-users/list-ajax', [MachineUsersController::class,'anyListAjax'])->name('machine-users-list-ajax');
    // Manage courses moddule
//    Route::get('courses', [CoursesController::class,'index'])->name('courses-manage');
//    Route::post('courses/list-ajax', [CoursesController::class,'anyListAjax'])->name('courses-list-ajax');
//    Route::any('courses/add', [CoursesController::class,'showCoursesForm'])->name('course-add-form');
//    Route::post('courses/add', [CoursesController::class,'addCourse'])->name('course-add');
//    Route::any('courses/edit/{id}', [CoursesController::class,'editCourse'])->name('course-edit');
//    Route::get('courses/view/{id}', [CoursesController::class,'show'])->name('course-view');
//    Route::get('courses/delete/{id}', [CoursesController::class,'deleteCourse'])->name('course-delete');


    // Manage  modules
//    Route::get('modules', [ModulesController::class,'index'])->name('modules-manage');
//    Route::post('modules/list-ajax', [ModulesController::class,'anyListAjax'])->name('modules-list-ajax');
//    Route::any('modules/add', [ModulesController::class,'showModulesForm'])->name('module-add-form');
//    Route::post('modules/add', [ModulesController::class,'addModule'])->name('module-add');
//    Route::any('modules/edit/{id}', [ModulesController::class,'editModule'])->name('module-edit');
//    Route::get('modules/delete/{id}', [ModulesController::class,'deleteModule'])->name('module-delete');

    // Manage batches modules

//    Route::get('batches', [BatchesController::class,'index'])->name('batches-manage');
//    Route::post('batches/list-ajax', [BatchesController::class,'anyListAjax'])->name('batches-list-ajax');
//    Route::any('batches/add', [BatchesController::class,'showBatcheForm'])->name('batche-add-form');
//    Route::post('batches/add', [BatchesController::class,'addBatche'])->name('batche-add');
//    Route::any('batches/edit/{id}', [BatchesController::class,'editBatche'])->name('batche-edit');
//    Route::any('batches/view/{id}', [BatchesController::class,'viewBatche'])->name('batche-view');
//    Route::get('batches/delete/{id}', [BatchesController::class,'deleteBatche'])->name('batche-delete');


//    Route::post('batches-content/list-ajax', [BatchesContentController::class,'anyListAjax'])->name('batches-content-list-ajax');
//    Route::any('batches-content/add/{id}', [BatchesContentController::class,'showBatcheContentForm'])->name('batche-content-add-form');
//    Route::post('batches-content/add/{id}', [BatchesContentController::class,'addBatcheContent'])->name('batche-content-add');
//    Route::any('batches-content/edit/{id}', [BatchesContentController::class,'editBatcheContent'])->name('batche-content-edit');
//    Route::any('batches-content/view/{id}', [BatchesContentController::class,'viewBatcheContent'])->name('batche-content-view');
//    Route::get('batches-content/delete/{id}', [BatchesContentController::class,'deleteBatcheContent'])->name('batche-content-delete');
//    Route::post('batches-content-upload', [BatchesContentController::class,'uploadLargeFiles'])->name('files.upload.large');


    // Manage Distributors modules
    Route::get('distributors', [DistributorController::class, 'index'])->name('distributors-manage');
    Route::post('distributors/list-ajax', [DistributorController::class,'anyListAjax'])->name('distributors-list-ajax');
    Route::any('distributors/add', [DistributorController::class,'showDistributorForm'])->name('distributor-add-form');
    Route::post('distributors/add', [DistributorController::class,'addDistributor'])->name('distributor-add');
    Route::any('distributors/edit/{id}', [DistributorController::class,'editDistributor'])->name('distributor-edit');
    Route::any('distributors/view/{id}', [DistributorController::class,'viewDistributor'])->name('distributor-view');
    Route::get('distributors/delete/{id}', [DistributorController::class,'deleteDistributor'])->name('distributor-delete');
    Route::post('distributors/bulk-action', [DistributorController::class, 'bulkAction'])->name('distributor.bulkAction');

    // Manage Training Programs modules
    Route::get('trainings', [TrainingController::class, 'index'])->name('trainings-manage');
    Route::post('trainings/list-ajax', [TrainingController::class,'anyListAjax'])->name('trainings-list-ajax');
    Route::any('trainings/add', [TrainingController::class,'showTrainingForm'])->name('training-add-form');
    Route::post('trainings/add', [TrainingController::class,'addTraining'])->name('training-add');
    Route::any('trainings/edit/{id}', [TrainingController::class,'editTraining'])->name('training-edit');
    //Route::post('trainings/{id}/remove-video', [TrainingController::class, 'removeVideo'])->name('training.remove_video');
    Route::delete('/training/{trainingId}/video/{videoLessonId}', [TrainingController::class, 'deleteVideo'])->name('training.deleteVideo');
    Route::delete('/delete-quiz/{id}', [TrainingController::class, 'deleteQuiz'])->name('training.deleteQuiz');
    Route::delete('/delete-quiz-option/{id}', [TrainingController::class, 'deleteQuizOption'])->name('training.deleteQuizOption');
    Route::any('trainings/view/{id}', [TrainingController::class,'viewTraining'])->name('training-view');
    Route::get('trainings/delete/{id}', [TrainingController::class,'deleteTraining'])->name('training-delete');
    Route::post('trainings/bulk-action', [TrainingController::class, 'bulkAction'])->name('training.bulkAction');
    Route::post('trainings/store-training-activity', [TrainingController::class, 'storeActivity'])->name('trainings-store-activity');
    Route::get('trainings/get-quiz-questions', [TrainingController::class, 'getQuizQuestions'])->name('trainings-get-questions');
    Route::post('trainings/submit-quiz-answers', [TrainingController::class, 'submitQuizAnswers'])->name('trainings-submit-answers');

    // Manage Prospects modules
    Route::get('prospects', [ProspectController::class, 'index'])->name('prospects-manage');
    Route::post('prospects/list-ajax', [ProspectController::class,'anyListAjax'])->name('prospects-list-ajax');
    Route::get('prospects/filter/{id}', [ProspectController::class, 'filterManage'])->name('prospects-filter-manage');
    Route::post('prospects/filter/ajax', [ProspectController::class, 'filterListAjax'])->name('prospects-filter-list-ajax');

    Route::any('prospects/add', [ProspectController::class,'showProspectForm'])->name('prospect-add-form');
    Route::post('prospects/add', [ProspectController::class,'addProspect'])->name('prospect-add');
    Route::post('prospects/convert', [ProspectController::class,'convertToDistributor'])->name('prospect-convert-distributor');
    Route::any('prospects/edit/{id}', [ProspectController::class,'editProspect'])->name('prospect-edit');
    Route::any('prospects/view/{id}', [ProspectController::class,'viewProspect'])->name('prospect-view');
    Route::get('prospects/delete/{id}', [ProspectController::class,'deleteProspect'])->name('prospect-delete');
    Route::post('prospects/bulk-action', [ProspectController::class, 'bulkAction'])->name('prospect.bulkAction');


    // Manage Todos modules
    Route::get('todos', [ToDoController::class, 'index'])->name('todos-manage');
    Route::post('todos/list-ajax', [ToDoController::class,'anyListAjax'])->name('todos-list-ajax');
    Route::any('todos/add', [ToDoController::class,'showTodoForm'])->name('todo-add-form');
    Route::post('todos/add', [ToDoController::class,'addTodo'])->name('todo-add');
    Route::any('todos/edit/{id}', [ToDoController::class,'editTodo'])->name('todo-edit');
    Route::any('todos/view/{id}', [ToDoController::class,'viewTodo'])->name('todo-view');
    Route::get('todos/delete/{id}', [ToDoController::class,'deleteTodo'])->name('todo-delete');
    Route::post('todos/bulk-action', [ToDoController::class, 'bulkAction'])->name('todo.bulkAction');

    // Support
    Route::get('support', [SupportRequestController::class, 'index'])->name('support-manage');
    Route::post('support/list-ajax', [SupportRequestController::class, 'anyListAjax'])->name('support-list-ajax');
    Route::get('support/add', [SupportRequestController::class, 'showSupportForm'])->name('support-add-form');
    Route::post('support/add', [SupportRequestController::class, 'addSupport'])->name('support-add');
    Route::any('support/edit/{id}', [SupportRequestController::class, 'editSupport'])->name('support-edit');
    Route::any('support/view/{id}', [SupportRequestController::class,'viewSupport'])->name('support-view');
    Route::get('support/delete/{id}', [SupportRequestController::class,'deleteSupport'])->name('support-delete');
    Route::post('support/bulk-action', [SupportRequestController::class, 'bulkAction'])->name('support.bulkAction');
});

