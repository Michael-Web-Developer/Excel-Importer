<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\UseCases\UploadFile\Actions\ProcessExcelFileAction;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Throwable;

class UploadFileController extends Controller
{
	public function upload(UploadFileRequest $request, ProcessExcelFileAction $excelFeedAction)
	{
        try {
            $file = $request->file('feed');
            $filePath = $file->path();
            $excelFeedAction->execute($filePath);

            return response()->json(['message' => 'File uploaded successfully.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Failed to upload file.'], Response::HTTP_SERVICE_UNAVAILABLE);
        }
	}
}
