<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('/contacts')->group(function () {
        Route::get('/', [ContactController::class, 'getAllContact']);
        Route::post('/create', [ContactController::class, 'createContact']);
        Route::put('/update', [ContactController::class, 'updateMultiContact']);
        Route::delete('/delete', [ContactController::class, 'deleteMultiContract']);
    });
    Route::prefix('/managers')->group(function () {
        Route::get('/', [ContactController::class, 'getAllManager']);
        Route::post('/create', [ContactController::class, 'createManager']);
        Route::put('/update/{id}', [ContactController::class, 'updateManager']);
        Route::delete('/delete/{id}', [ContactController::class, 'deleteManager']);
    });
    Route::prefix('/lists')->group(function () {
        Route::get('/', [ContactController::class, 'getAllList']);
        Route::get('/{listId}', [ContactController::class, 'getContactsByList']);
        Route::post('/create', [ContactController::class, 'createList']);
        Route::put('/update/{listId}/', [ContactController::class, 'updateList']);
        Route::post('/add-contact/{listId}', [ContactController::class, 'addContacts']);
        Route::post('/{listId}/remove-contact/{contactId}', [ContactController::class, 'removeContactFromList']);
        Route::delete('/delete/{id}', [ContactController::class, 'deleteList']);
        Route::put('/update/contact/{listId}/', [ContactController::class, 'updateContactList']);
    });
    Route::prefix('/tags')->group(function () {
        Route::get('/', [TagController::class, 'getAllList']);
        Route::post('/create', [TagController::class, 'createTag']);
        Route::put('/update/{tagId}/', [TagController::class, 'update']);
        Route::post('/add-contact/{tagId}', [TagController::class, 'addContacts']);
        Route::put('/update/contact/{tagId}/', [TagController::class, 'updateContactTag']);
        Route::post('/{tagId}/remove-contact/{contactId}', [TagController::class, 'removeContactFromtag']);
        Route::delete('/delete/{id}', [TagController::class, 'deleteTag']);
    });
    Route::prefix('/opportunities')->group(function () {
        Route::get('/', [OpportunityController::class, 'getAllOpportunity']);
        Route::post('/create', [OpportunityController::class, 'createOpportunity']);
        Route::put('/update', [OpportunityController::class, 'updateMultiOpportunity']);
        Route::delete('/delete', [OpportunityController::class, 'deleteMultiContract']);
        Route::post('/add-tags/{opportunityId}', [OpportunityController::class, 'addTagsToOpportunity']);
        Route::post('/remove-tags/{opportunityId}', [OpportunityController::class, 'removeTagsFromOpportunity']);
        Route::get('/tags/{opportunityId}', [OpportunityController::class, 'getTagsForOpportunity']);
    });
    Route::prefix('/piplines')->group(function () {
        Route::get('/', [OpportunityController::class, 'getAllPiplines']);
        Route::get('/{id}', [OpportunityController::class, 'getDetailsPipLine']);
        Route::post('/create', [OpportunityController::class, 'createPipline']);
        Route::put('/update/{id}', [OpportunityController::class, 'updatePipline']);
        Route::delete('/delete/{id}', [OpportunityController::class, 'deletePipline']);
        Route::get('/columns/{pipelineId}', [OpportunityController::class, 'getColumnsInPipline']);
        Route::post('columns/create/{pipelineId}', [OpportunityController::class, 'addColumnToPipline']);
        Route::put('columns/update/{columnId}', [OpportunityController::class, 'updateColumn']);
        Route::delete('columns/delete/{id}', [OpportunityController::class, 'deleteColumn']);
        Route::post('columns/{pipelineColumnId}/add-opportunities/{opportunityId}', [OpportunityController::class, 'addOpportunityToColumn']);
    });
    Route::prefix('/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'getAllTask']);
        Route::post('/create', [TaskController::class, 'create']);
        Route::put('/update', [TaskController::class, 'updateMultiTask']);
        Route::delete('/delete', [TaskController::class, 'deleteMultiTask']);

    });
});
