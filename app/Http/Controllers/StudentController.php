<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\RandomString;
use App\Models\Participation;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $randomString = RandomString::inRandomOrder()->first();
        $participation = Participation::create([
            'student_id' => $student->id,
            'random_string_id' => $randomString->id,
            'score' => 0,
        ]);

        return response()->json(['student' => $student, 'random_string' => $randomString->random_string], 201);
    }
}
