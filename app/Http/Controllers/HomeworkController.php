<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function index()
    {
        $role = session('role', 'student');
        $studentName = session('student_name', '');
        $teacherHomeworks = Homework::where('role', 'teacher')->latest()->get();

        if ($role === 'teacher') {
            $studentHomeworks = Homework::where('role', 'student')->latest()->get();
        } else {
            $studentHomeworks = Homework::where('role', 'student')
                ->where('student_name', $studentName)
                ->latest()->get();
        }

        return view('homeworks.index', compact('teacherHomeworks', 'studentHomeworks', 'role', 'studentName'));
    }

    public function selectRole(Request $request)
    {
        $request->session()->put('role', $request->role);
        if ($request->role === 'student' && $request->student_name) {
            $request->session()->put('student_name', $request->student_name);
        }
        return redirect('/homeworks');
    }

    public function create()
    {
        $role = session('role', 'student');
        $studentName = session('student_name', '');
        return view('homeworks.create', compact('role', 'studentName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'attachment' => 'nullable|file|max:10240',
            'student_name' => 'nullable|string',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $role = session('role', 'student');
        $studentName = session('student_name', $request->student_name ?? '');

        Homework::create([
            'title' => $request->title,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'role' => $role,
            'submitted_by' => $role === 'teacher' ? 'Teacher' : $studentName,
            'student_name' => $role === 'student' ? $studentName : null,
        ]);

        return redirect('/homeworks')->with('success', $role === 'teacher' ? 'Homework assigned!' : 'Homework submitted!');
    }

    public function destroy($id)
    {
        Homework::findOrFail($id)->delete();
        return redirect('/homeworks')->with('success', 'Deleted successfully!');
    }
}