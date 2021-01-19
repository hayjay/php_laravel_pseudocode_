<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\PdfService;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService) {
        $this->pdfService = $pdfService;
    }

    public function save(Request $request)
    {
        $validator = Validator::make([
            "file" => "'required|mimes:pdf'"
        ], $request);

        if (!$validator->validate()) {
            return response()->json([
                "message" => "failed to save", 
                "errors" => $validator->errors()
            ], 422);
        }

        try {
            $this->pdfService->searchFor($request->file, "Proposal");
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Proposal not found",
            ], 422);
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        try{
            $filePath = $this->pdfService->save($file, $fileName);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Error Occured while saving file",
            ], 422);
        }

        //I have made use of the updateOrCreate method here because the requirement is to check if we already have the same pdf with same name and size if so then we don't need to add a new row into the table we would need to update the existing one using the below condition
        
        $update_criteria = [
            "name" => $fileName,
            "size" => $fileSize
        ];

        Pdf::updateOrCreate([
            "path" => $filePath
        ], $update_criteria);

        return response()->json([
            "message" => "Pdf uploaded successfully!",
            "pdf" => $pdf
        ]);
    }
}
