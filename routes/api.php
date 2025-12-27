<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KontenController;
use App\Http\Helpers\ResponseHelper;
use App\Models\AppVersion;
use App\Models\ModelVersion;
use Illuminate\Http\Request;
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

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/download-model', function (Request $request) {

    $data = ModelVersion::where('status', 1)->where('type', 'model')->where('id_model_version', $request->id)->first();

    if (! $data) {
        return response()->json([
            'status' => '404',
            'message' => 'file not found!',
        ], 404);
    }

    $path = storage_path('app/public/'.$data->path);

    if (! file_exists($path)) {
        return response()->json([
            'status' => '404',
            'message' => 'file not found!',
        ], 404);
    }

    return response()->download($path, 'model.tflite');
});

Route::get('/download-label', function (Request $request) {

    $data = ModelVersion::where('status', 1)->where('type', 'label')->where('id_model_version', $request->id)->first();

    if (! $data) {
        return response()->json([
            'status' => '404',
            'message' => 'file not found!',
        ], 404);
    }

    $path = storage_path('app/public/'.$data->path);

    if (! file_exists($path)) {
        return response()->json([
            'status' => '404',
            'message' => 'file not found!',
        ], 404);
    }

    return response()->download($path, 'labels.txt');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/app-version', function () {
        $get = AppVersion::where('status', 1)->first();
        $data = [];
        if ($get) {
            $data = $get;
        }

        return ResponseHelper::success($data, 'app version active');
    });

    Route::get('/model-version', function () {
        $get = ModelVersion::where('status', 1)->where('type', 'model')->first();

        $data = [];
        if ($get) {
            $data = [
                'id_model_version' => $get->id_model_version,
                'model_name' => $get->model_name,
                'version' => $get->version,
                'type' => $get->type,
                'descriptions' => $get->descriptions,
                'status' => $get->status,
                'download_url' => url('').'/api/download-model?id='.$get->id_model_version,
                'created_at' => $get->created_at,
                'updated_at' => $get->updated_at,
            ];
        }

        return ResponseHelper::success($data, 'success get model version');
    });

    Route::get('/label-version', function () {
        $get = ModelVersion::where('status', 1)->where('type', 'label')->first();
        $data = [];
        if ($get) {
            $data = [
                'id_model_version' => $get->id_model_version,
                'model_name' => $get->model_name,
                'version' => $get->version,
                'type' => $get->type,
                'descriptions' => $get->descriptions,
                'status' => $get->status,
                'download_url' => url('').'/api/download-label?id='.$get->id_model_version,
                'created_at' => $get->created_at,
                'updated_at' => $get->updated_at,
            ];
        }

        return ResponseHelper::success($data, 'success get label version');
    });
});

Route::get('/get-content/{key_name}', [KontenController::class, 'index'])->name('get_content');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
