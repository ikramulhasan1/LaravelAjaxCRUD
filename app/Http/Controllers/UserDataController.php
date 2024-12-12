<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use Illuminate\Support\Facades\Validator;

class UserDataController extends Controller
{
    public function showCrud()
    {
        return view('user_crud'); 
    }

    public function index()
    {
        $data = UserData::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'pdf' => 'nullable|mimes:pdf|max:2048', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        try {
            $userData = new UserData();
            $userData->name = $validatedData['name'];
            $userData->email = $validatedData['email'];
            $userData->phone = $validatedData['phone'];

            if ($request->hasFile('pdf')) {
                $pdfPath = $request->file('pdf')->store('pdfs', 'public');
                $userData->pdf_path = $pdfPath; 
            }
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public'); 
                $userData->image_path = $imagePath; 
            }

            $userData->save();

            return response()->json(['success' => 'Record added successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); 
        }
    }

    public function destroy($id)
    {
        $record = UserData::find($id);

        if (!$record) {
            return response()->json(['error' => 'Record not found!']);
        }

        if ($record->pdf && file_exists(storage_path("app/public/{$record->pdf}"))) {
            unlink(storage_path("app/public/{$record->pdf}"));
        }

        if ($record->image && file_exists(storage_path("app/public/{$record->image}"))) {
            unlink(storage_path("app/public/{$record->image}"));
        }
        $record->delete();

        return response()->json(['success' => 'Record deleted successfully!']);
    }
}
