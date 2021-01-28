<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\PdfService;
use App\Http\Requests\PdfStoreRequest;

class PdfController extends Controller
{
    protected $pdfService;
    private $lang;

    public function __construct(PdfService $pdfService) {
        $this->pdfService = $pdfService;
        $this->lang = "pdfcontroller_messages.";

    }

    public function save(PdfStoreRequest $request)    {
        $validatedData = $request->validated();

        try {
            $this->pdfService->searchFor($request->file, "Proposal");
        } catch (\Throwable $th) {
            return response()->json([
                "message" => trans($this->lang.'not_found'),
            ], 422);
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        try{
            $filePath = $this->pdfService->save($file, $fileName);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => trans($this->lang."save_error"),
            ], 422);
        }

        /**
            I have made use of the updateOrCreate method here because the requirement is to check if we already have the same pdf with same name and size if so then we don't need to add a new row into the table we would need to update the existing one using the below condition
        */
        
        $update_criteria = [
            "name" => $fileName,
            "size" => $fileSize
        ];

        Pdf::updateOrCreate([
            "path" => $filePath
        ], $update_criteria);

        return response()->json([
            "message" => trans($this->lang."success"),
            "pdf" => $pdf
        ], 201);
    }
}
