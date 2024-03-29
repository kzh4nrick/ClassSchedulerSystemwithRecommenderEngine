<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\Block;
use App\Models\College;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\ClassSchedule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/subjects/create', function () {

//     request()->validate([
//         'Subject_Code' => 'required',
//         'Subject_Name' => 'required',
//         'Subject_Type' => 'required',
//         'Units' => 'required',
//     ]);  //For validation Daa

//     return Subject::create([
//         'Subject_Code' => request('Subject_Code'),
//         'Subject_Name' => request('Subject_Name'),
//         'Subject_Type' => request('Subject_Type'),
//         'Units' => request('Units'),
//     ]);
// });

// Route::put('/subjects/{id}', function (Subject $id) {
//     // $subject = Subject::findOrFail($id); 

//     request()->validate([
//         'Subject_Code' => 'required',
//         'Subject_Name' => 'required',
//         'Subject_Type' => 'required',
//         'Units' => 'required',
//     ]); //For validation Daa

//     $success = $id->update([
//         'Subject_Code' => request('Subject_Code'),
//         'Subject_Name' => request('Subject_Name'),
//         'Subject_Type' => request('Subject_Type'),
//         'Units' => request('Units'),
//     ]);

//     return [
//         'success' => $success
//     ];
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//UPDATE USER'S USERNAME
Route::put('/user/update/{userN}', function (Request $request, $userN) {
    $data = $request->all();
    
    if($userN != $data['username']){
        if(User::where('username', $data['username'])->exists()) {
            return [
                "success" => false,
                "response" => [
                    "error" => "Username already taken."
                ]
            ];
        }
    }
    
    $id = DB::table('users')->where('username', '=', $userN)->pluck('id')->first();

    $user = User::find($id);

    foreach ($data as $key => $value) {
        $user->{$key} = $value;
    }

    $result = $user->save();

    return ["success" => $result, "response" => ["user" => $user]];
});

//GET USER
Route::get("/user/{user}", function ($user) {
    $user = User::where('username', $user)->first();

    if (empty($user)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for User found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "user" => $user
        ]
    ];
});

//DELETE USER
Route::delete('/users/delete/{id}', function ($id) {
    // $user = User::where('username', $id)->first();
    $user = DB::table('users')
            ->join('departments', 'users.username', '=', 'departments.username')
            ->where('departments.id', $id)
            ->delete();

    // if (empty($user)) {
    //     $success = false;
    //     $response = ["error" => "User could not be deleted."];
    // } else {
    //     $success = $user->delete();
    //     $response = ["message" => "User deleted!"];
    // }

    // return ["success" => $success, "response" => $response];
    // if(empty($user)){   
    //     $success = false;
    //     $response = ["error" => "User could not be deleted."];
        
    // } else {
    //     $success = true;
    //     $response = ["message" => "User deleted!"];
    // }
    
    // return ["success" => $success, "response" => $response];
});

// GET ALL SUBJECTS
Route::get("/subjects/all", function () {
    $subjects = Subject::all();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL SUBJECTS BY DEPARTMENT WITH COURSE
Route::get("/subjects/department/{id}", function ($id) {
    $subjects = DB::table('subjects')
    ->join('courses', 'subjects.course_id', '=', 'courses.id')
    ->join('departments', 'courses.department_id', '=', 'departments.id')
    ->where('departments.id', $id)
    ->select('subjects.*', 'courses.Course_Code')
    ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL SUBJECTS BY DEPARTMENT
Route::get("/subjects/departments/{id}", function ($id) {
    $subjects = DB::table('subjects')
    // ->join('courses', 'subjects.course_id', '=', 'courses.id')
    ->where('subjects.department_id', '=', $id)
    //->whereNotNull('subjects.course_id')
    ->select('subjects.*')
    ->orderBy('subjects.Subject_Name')
    ->get();

    foreach ($subjects as $subject){
        if($subject->course_id == null)
            $subject->Course_Code = 'Open to All';
        else {
            $subject->Course_Code = DB::table('courses')->where('id', $subject->course_id)
                                ->pluck('courses.Course_Code')->first();
            }
    }
    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET THE NUMBER OF SUBJECTS BASED ON TYPE BY DEPARTMENT
Route::get("/num-subjects/departments/{id}", function ($id) {

    $core = Subject::where('subjects.department_id', '=', $id)
        ->where('Subject_Type', 'Core')->count();
    $major = Subject::where('subjects.department_id', '=', $id)
        ->whereIn('Subject_Type', ['Major', 'Elective'])->count();

    $countOf = collect(['core' => $core, 'major' => $major]);

    return [
        "success" => true,
        "response" => [
            "countOf" => $countOf
        ]
    ];

});

// GET ALL SUBJECTS BY COLLEGE WITH COURSE
Route::get("/subjects/college/{id}", function ($id) {
    $subjects = DB::table('subjects')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.id', $id)
        ->select('subjects.*', 'courses.Course_Code')
        ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL SUBJECTS BY COLLEGE
Route::get("/subjects/colleges/{id}", function ($id) {
    $subjects = DB::table('subjects')
        ->join('departments', 'subjects.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.id', $id)
        ->select('subjects.*')
        ->orderBy('subjects.Subject_Name')
        ->get();

    foreach ($subjects as $subject){
        if($subject->course_id == null)
            $subject->Course_Code = 'Open to All';
        else {
            $subject->Course_Code = DB::table('courses')->where('id', $subject->course_id)
                                ->pluck('courses.Course_Code')->first();
            }
    }

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL MAJOR/ELECTIVE SUBJECTS BY COURSE EDITING MODE (FOR CHAIR DEPT)
Route::get("/subjects/ME/courseEDIT/{id}/{s_id}", function ($id, $s_id) {
    $type = DB::table('curricula')
                ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
                ->where('curricula.id',$s_id)->pluck('subjects.Subject_Type')->first();

    $subjects = DB::table('subjects')
                ->where('course_id', $id)
                ->where('Subject_Type', $type)
                ->orderBy('subjects.Subject_Code')
                ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL MAJOR/ELECTIVE SUBJECTS BY COURSE (FOR CHAIR DEPT)
Route::get("/subjects/ME/course/{id}/{m}", function ($id, $me) {
    $subjects = DB::table('subjects')
                ->where('course_id', $id)
                ->where('Subject_Type', $me)
                ->orderBy('subjects.Subject_Code')
                ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL CORE SUBJECTS BY DEPARTMENT (FOR CHAIR DEPT)
Route::get("/subjects/Core/department/{id}", function ($id) {
    $subjects = DB::table('subjects')
                ->where('department_id', $id)
                ->where('Subject_Type', 'Core')
                ->orderBy('subjects.Subject_Code')
                ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

// GET ALL GEC SUBJECTS BY COLLEGE (FOR REGISTRAR)
Route::get("/subjects/GEC/college/{id}", function ($id) {
    $subjects = DB::table('subjects')
                ->join('departments', 'subjects.department_id', '=', 'departments.id')
                ->join('colleges', 'departments.college_id', '=', 'colleges.id')
                ->where('colleges.id', $id)
                ->where('subjects.Subject_Type', '=','GEC')
                ->select('subjects.*')
                ->orderBy('subjects.Subject_Code')
                ->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Subjects found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

//CREATE RECORD FOR SUBJECTS
Route::post('/subjects/create', function (Request $request) {
    $data = $request->all();

    if((!Subject::where('Subject_Code','=', $data['Subject_Code'])->exists())) {

        $subject = Subject::create([
            "Subject_Code" => $data["Subject_Code"],
            "Subject_Name" => $data["Subject_Name"],
            "Subject_Type" => $data["Subject_Type"],
            "course_id" => $data["course_id"],
            "department_id" => $data["department_id"]
        ]);

        if (empty($subject->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "subject" => $subject
                ]
            ];
        }
        
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "The Subject already exists."
            ]
        ];
    }

});

//GET A SINGLE SUBJECT
Route::get("/subjects/{id}", function (Request $request, $id) {
    $subject = Subject::find($id);

    if (empty($subject)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Subject found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "subject" => $subject
        ]
    ];
});

//GET THE DETAILS OF A SUBJECT BY CURRICULUM
Route::get("/subjects-curriculum/{id}/{AY}/{sem}/{c_id}/{YL}", function (Request $request, $id, $AY, $sem, $c_id, $YL) {
    $subject = DB::table('subjects')
            ->join('curricula', 'subjects.id', '=', 'curricula.subject_id')
            ->where('curricula.subject_id', $id)
            ->where('curricula.course_id', $c_id)
            ->where('curricula.academicYear', $AY)
            ->where('curricula.semester', $sem)
            ->where('curricula.yearLevel', $YL)
            ->select('subjects.id','curricula.lec', 'curricula.lab')
            ->first();

    if (empty($subject)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Subject found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "subject" => $subject
        ]
    ];
});

//DELETE SUBJECT
Route::delete('/subjects/delete/{id}', function (Request $request, $id) {
    $subject = Subject::find($id);

    if (empty($subject)) {
        $success = false;
        $response = ["error" => "Subject could not be deleted."];
    } else {
        $success = $subject->delete();
        $response = ["message" => "Subject deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//UPDATE RECORD FOR SUBJECT
Route::put('/subjects/update/{id}', function (Request $request, $id) {
    $data = $request->all();

    $subject = Subject::find($id);

    foreach ($data as $key => $value) {
        $subject->{$key} = $value;
    }

    $result = $subject->save();

    return ["success" => $result, "response" => ["subject" => $subject]];
});

// GET ALL COLLEGES
Route::get("/colleges/all", function () {
    $colleges = College::all();

    if (empty($colleges)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Colleges found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "colleges" => $colleges
        ]
    ];
});

// GET COLLEGE BY USER
Route::get("/colleges/{id}", function ($id) {
    $college = College::where('username', $id)->first(['id', 'College_Name', 'College_Code']);

    if (empty($college)) {
        $college = DB::table('departments')
                    ->where('departments.username', $id)
                    ->join('colleges', 'departments.college_id', '=', 'colleges.id')
                    ->select('departments.id', 'departments.Department_Name', 'departments.college_id', 'colleges.College_Name')
                    ->first();
        // return [
        //     "success" => false,
        //     "response" => [
        //         "error" => "No records of College found."
        //     ]
        // ];
    }

    return [
        "success" => true,
        "response" => [
            "college" => $college
        ]
    ];
});

// GET ALL DEPARTMENT BY COLLEGE
Route::get("/departments/{id}", function ($id) {
    $departments = DB::table('departments')
                    ->where('college_id', $id)
                    ->join('users', 'departments.username', '=', 'users.username')
                    ->select('departments.*', 'users.username', 'users.password')
                    ->get();

    if (empty($departments)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Departments found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "departments" => $departments
        ]
    ];
});

// GET A DEPARTMENT BY COLLEGE WITH USER
Route::get("/departments/user/{id}", function ($id) {
    $department = DB::table('departments')
                    ->where('departments.id', $id)
                    ->join('users', 'departments.username', '=', 'users.username')
                    ->select('departments.*', 'users.username', 'users.password')
                    ->first();

    if (empty($department)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Departments found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "department" => $department
        ]
    ];
});

//CREATE RECORD FOR DEPARTMENT
Route::post('/departments/create', function (Request $request) {
    $data = $request->all();

    if(!Department::where('Department_Name','=', $data['Department_Name'])->exists()) {

        $department = Department::create([
            "college_id" => $data["college_id"],
            "Department_Name" => $data["Department_Name"],
            "Department_Chair" => $data["Department_Chair"],
            "username" => $data["username"]
        ]);

        if (empty($department->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "department" => $department
                ]
            ];
        }
        
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "The Department already exists."
            ]
        ];
    }

});

//UPDATE RECORD FOR DEPARTMENT
Route::put('/departments/update/{id}', function (Request $request, $id) {
    $data = $request->all();

    $department = Department::find($id);

    foreach ($data as $key => $value) {
        $department->{$key} = $value;
    }

    $result = $department->save();

    return ["success" => $result, "response" => ["department" => $department]];
});

//DELETE DEPARTMENT
Route::delete('/departments/delete/{id}', function ($id) {
    $department = Department::find($id);

    if (empty($department)) {
        $success = false;
        $response = ["error" => "Department could not be deleted."];
    } else {
        $success = $department->delete();
        $response = ["message" => "Department deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

// GET ALL COURSES WITH COLLEGE DEPARTMENT
Route::get("/courses/colleges/all", function () {
    $courses = DB::table('courses')
        ->join('colleges', 'courses.college_id', '=', 'colleges.id')
        ->select('courses.*', 'colleges.College_Name', 'colleges.Campus')
        ->get();

    if (empty($courses)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Courses found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "courses" => $courses
        ]
    ];
});

// GET ALL COURSES BY COLLEGE WITH DEPARTMENT
Route::get("/courses/departments/{id}", function ($id) {
    $courses = DB::table('courses')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.id', $id)
        ->select('courses.*', 'departments.Department_Name')
        ->get();

    if (empty($courses)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Courses found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "courses" => $courses
        ]
    ];
});

// GET ALL COURSES BY COLLEGE DEPARTMENT
Route::get("/courses/department/{id}", function ($id) {
    $courses = DB::table('courses')
        ->where('department_id', $id)
        ->get();

    if (empty($courses)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Courses found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "courses" => $courses
        ]
    ];
});

// GET ALL COURSES
Route::get("/courses/all", function () {
    $courses = Course::all();

    if (empty($courses)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Courses found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "courses" => $courses
        ]
    ];
});

//CREATE RECORD FOR COURSE
Route::post('/courses/create', function (Request $request) {
    $data = $request->all();

    if(!Course::where('Course_Code','=', $data['Course_Code'])->exists()) {

        $course = Course::create([
            "Course_Code" => $data["Course_Code"],
            "Course_Name" => $data["Course_Name"],
            "department_id" => $data["department_id"]
        ]);

        if (empty($course->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "course" => $course
                ]
            ];
        }
        
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "The Course already exists."
            ]
        ];
    }

});

//CREATE RECORD FOR COURSE BLOCKS
Route::post('/courses/blocks/create', function (Request $request) {
    $data = $request->all();

    $block = Block::create([
        "course_id" => $data["course_id"],
        "academicYear" => $data["academicYear"],
        "semester" => $data["semester"],
        "first" => $data["first"],
        "second" => $data["second"],
        "third" => $data["third"],
        "fourth" => $data["fourth"],
    ]);

    if (empty($block->id)) {
        return [
            "success" => false,
            "response" => [
                "error" => "An unexpected error has occured."
            ]
        ];
    } else {
        return [
            "success" => true,
            "response" => [
                "block" => $block
            ]
        ];
    }
});

//GET A SINGLE COURSE
Route::get("/courses/{id}", function (Request $request, $id) {
    $course = Course::find($id);

    if (empty($course)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Course found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "course" => $course
        ]
    ];
});

//GET A SINGLE COURSE BLOCK
Route::get("/courses/block/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $block = Block::where('course_id', $id)
                ->where('academicYear', $AY)
                ->where('semester', $sem)
                ->first();

    return [
        "success" => true,
        "response" => [
            "block" => $block
        ]
    ];
});

//DELETE COURSE
Route::delete('/courses/delete/{id}', function (Request $request, $id) {
    $course = Course::find($id);

    if (empty($course)) {
        $success = false;
        $response = ["error" => "Course could not be deleted."];
    } else {
        $success = $course->delete();
        $response = ["message" => "Course deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//UPDATE RECORD FOR COURSE BLOCK
Route::put('/courses/block/update/{id}/{AY}/{sem}', function (Request $request, $id, $AY, $sem) {
    $data = $request->all();

    $block = Block::where('course_id', $id)
            ->where('academicYear', $AY)
            ->where('semester', $sem)
            ->first();

    foreach ($data as $key => $value) {
        $block->{$key} = $value;
    }

    $result = $block->save();

    return ["success" => $result, "response" => ["block" => $block]];
});

//UPDATE RECORD FOR COURSE
Route::put('/courses/update/{id}', function (Request $request, $id) {
    $data = $request->all();

    $course = Course::find($id);

    foreach ($data as $key => $value) {
        $course->{$key} = $value;
    }

    $result = $course->save();

    return ["success" => $result, "response" => ["course" => $course]];
});

// GET ALL FACULTIES WITH COLLEGE DEPARTMENT
Route::get("/faculties/colleges/all", function () {
    $faculties = DB::table('faculties')
        ->join('colleges', 'faculties.college_id', '=', 'colleges.id')
        ->select('faculties.*', 'colleges.College_Name', 'colleges.Campus')
        ->get();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET ALL FACULTIES BY COLLEGE WITH DEPARTMENT
Route::get("/faculties/college/{id}", function ($id) {
    $faculties = DB::table('faculties')
        ->join('departments', 'faculties.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.id', $id)
        ->select('faculties.*', 'departments.Department_Name')
        ->orderBy('faculties.Faculty_Name')
        ->get();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET ALL FACULTIES BY COLLEGE FOR REPORTS
Route::get("/faculties/college/reports/{id}/{AY}/{sem}", function ($id, $AY, $sem) {
    $faculties = DB::table('class_schedules')
                ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                ->join('departments', 'faculties.department_id', '=', 'departments.id')
                ->where('departments.college_id', $id)
                ->where('schedules.academicYear', $AY)
                ->where('schedules.semester', $sem)
                ->select('faculties.*')
                ->orderBy('faculties.Faculty_ID')
                ->orderBy('faculties.Faculty_Name')
                ->distinct()
                ->get(['faculties.id']);

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET ALL FACULTIES BY DEPARTMENT
Route::get("/faculties/department/{id}", function ($id) {
    $faculties = DB::table('faculties')
        ->where('department_id', $id)
        ->orderBy('Faculty_Name')
        ->get();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET ALL FACULTIES BY DEPARTMENT FOR REPORTS
Route::get("/faculties/department/reports/{id}/{AY}/{sem}", function ($id, $AY, $sem) {
    $faculties = DB::table('class_schedules')
            ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
            ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
            ->where('faculties.department_id', $id)
            ->where('schedules.academicYear', $AY)
            ->where('schedules.semester', $sem)
            ->select('faculties.*')
            ->orderBy('faculties.Faculty_ID')
            ->orderBy('faculties.Faculty_Name')
            ->distinct()
            ->get(['faculties.id']);

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET ALL FACULTIES BY SUBJECT-DEPARTMENT
Route::get("/faculties/department-subject/{subj}", function ($subj) {
    $faculties = DB::table('faculties')
        ->join('departments', 'faculties.department_id', '=', 'departments.id')
        ->join('subjects', 'departments.id', '=', 'subjects.department_id')
        ->where('subjects.id', '=', $subj)
        ->select('faculties.*')
        ->orderBy('faculties.Faculty_Name')
        ->get();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

// GET ALL FACULTIES
Route::get("/faculties/all", function () {
    $faculties = Faculty::all();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Faculties found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//CREATE RECORD FOR FACULTY
Route::post('/faculties/create', function (Request $request) {
    $data = $request->all();

    if(!Faculty::where('Faculty_ID','=', $data['Faculty_ID'])->exists()) {

        $faculty = Faculty::create([
            "Faculty_ID" => $data["Faculty_ID"],
            "Faculty_Name" => $data["Faculty_Name"],
            "department_id" => $data["department_id"]
        ]);

        if (empty($faculty->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "faculty" => $faculty
                ]
            ];
        }
        
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "The Faculty already exists."
            ]
        ];
    }

});

//GET A SINGLE FACULTY
Route::get("/faculties/{id}", function (Request $request, $id) {
    $faculty = Faculty::find($id);

    if (empty($faculty)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Faculty found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculty" => $faculty
        ]
    ];
});

//GET FACULTIES OF CURRICULUM
Route::get("/faculties-curriculum/{id}", function ($id) {
    $id = json_decode($id, true);

    $faculty = Faculty::whereIn('id', $id)->get();

    if (empty($faculty)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Faculty found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculty" => $faculty
        ]
    ];
});

//GET FACULTIES BY SUBJECT
Route::get("/faculties-subject/{id}/{AY}/{sem}/{c_id}/{YL}", function ($id, $AY, $sem, $c_id, $YL) {
    $curricula = DB::table('curricula')
                ->where('curricula.subject_id', $id)
                ->where('curricula.course_id', $c_id)
                ->where('curricula.academicYear', $AY)
                ->where('curricula.semester', $sem)
                ->where('curricula.yearLevel', $YL)
                ->pluck('curricula.faculties_id')->first();

    $ids = json_decode($curricula, true);

    $faculty = Faculty::whereIn('id', $ids)->orderBy('Faculty_Name')->get();

    if (empty($faculty)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Faculty found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculty" => $faculty
        ]
    ];
});

//DELETE FACULTY
Route::delete('/faculties/delete/{id}', function (Request $request, $id) {
    $faculty = Faculty::find($id);

    if (empty($faculty)) {
        $success = false;
        $response = ["error" => "Faculty could not be deleted."];
    } else {
        $success = $faculty->delete();
        $response = ["message" => "Faculty deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//UPDATE RECORD FOR FACULTY
Route::put('/faculties/update/{id}', function (Request $request, $id) {
    $data = $request->all();

    $faculty = Faculty::find($id);

    foreach ($data as $key => $value) {
        $faculty->{$key} = $value;
    }

    $result = $faculty->save();

    return ["success" => $result, "response" => ["faculty" => $faculty]];
});

// GET ALL CLASSROOMS WITH COLLEGE DEPARTMENT
Route::get("/classrooms/colleges/all", function () {
    $classrooms = DB::table('classrooms')
        ->join('colleges', 'classrooms.college_id', '=', 'colleges.id')
        ->select('classrooms.*', 'colleges.College_Name', 'colleges.Campus')
        ->get();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET ALL CLASSROOMS BY COLLEGE
Route::get("/classrooms/college/{id}", function ($id) {
    $classrooms = Classroom::where('college_id', $id)->orderBy('Building_No')->orderBy('Classroom_No')->get();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET ALL CLASSROOMS BY COLLEGE FOR REPORTS
Route::get("/classrooms/college/reports/{id}/{AY}/{sem}", function ($id, $AY, $sem) {
    $classrooms = DB::table('class_schedules')
                ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                ->where('classrooms.college_id', $id)
                ->where('schedules.academicYear', $AY)
                ->where('schedules.semester', $sem)
                ->select('classrooms.*')
                ->orderBy('classrooms.Building_No')
                ->orderBy('classrooms.Classroom_No')
                ->distinct()
                ->get(['classrooms.id']);

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET ALL CLASSROOMS BY DEPARTMENT FOR REPORTS
Route::get("/classrooms/department/reports/{id}/{AY}/{sem}", function ($id, $AY, $sem) {
    $classrooms = DB::table('class_schedules')
                ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                ->where('class_schedules.user_id', $id)
                ->where('schedules.academicYear', $AY)
                ->where('schedules.semester', $sem)
                ->select('classrooms.*')
                ->orderBy('classrooms.Building_No')
                ->orderBy('classrooms.Classroom_No')
                ->distinct()
                ->get(['classrooms.id']);

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET ALL CLASSROOMS PER COLLEGE BY SUBJECT
Route::get("/classrooms/college-subject/{s_id}/{type}", function ($s_id, $type) {
    if ($type == "Lecture")
        $classrooms = DB::table('classrooms')
            ->join('colleges', 'classrooms.college_id', '=', 'colleges.id')
            ->join('departments', 'departments.college_id', '=', 'classrooms.college_id')
            ->join('subjects', 'subjects.department_id', '=', 'departments.id')
            ->where('subjects.id', $s_id)
            ->where('classrooms.Classroom_Type', "Lecture")
            ->orderBy('classrooms.Building_No')
            ->orderBy('classrooms.Classroom_No')
            ->select('classrooms.*', 'colleges.College_Name', 'colleges.Campus')
            ->get();
    else
        $classrooms = DB::table('classrooms')
            ->join('colleges', 'classrooms.college_id', '=', 'colleges.id')
            ->join('departments', 'departments.college_id', '=', 'classrooms.college_id')
            ->join('subjects', 'subjects.department_id', '=', 'departments.id')
            ->where('subjects.id', $s_id)
            ->orderBy('classrooms.Building_No')
            ->orderBy('classrooms.Classroom_No')
            ->select('classrooms.*', 'colleges.College_Name', 'colleges.Campus')
            ->get();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET THE CLASSROOMS PER CURRICULUM BY SUBJECT
Route::get("/classrooms/curriculum-subject/{s_id}", function ($s_id) {
    $curricula = DB::table('curricula')
                ->where('curricula.subject_id', $s_id)
                ->first();

    $c = json_decode($curricula->classrooms_id, true);

    $classrooms = DB::table('classrooms')
    ->whereIn('classrooms.id', $c)
    ->get();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

// GET ALL CLASSROOMS
Route::get("/classrooms/all", function () {
    $classrooms = Classroom::all();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Classrooms found."
            ]
        ];
    } 

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

//CREATE RECORD FOR CLASSROOM
Route::post('/classrooms/create', function (Request $request) {
    $data = $request->all();

        $classroom = Classroom::create([
            "Building_No" => $data["Building_No"],
            "Classroom_No" => $data["Classroom_No"],
            "Classroom_Type" => $data["Classroom_Type"],
            "college_id" => $data["college_id"]
        ]);

        if (empty($classroom->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "classroom" => $classroom
                ]
            ];
        }

});

//GET A SINGLE CLASSROOM
Route::get("/classrooms/{id}", function (Request $request, $id) {
    $classroom = Classroom::find($id);

    if (empty($classroom)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classroom found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classroom" => $classroom
        ]
    ];
});

//GET CLASSROOMS IN CURRICULUM
Route::get("/classrooms-curriculum/{id}", function ($id) {
    $id = json_decode($id, true);

    $classroom = Classroom::whereIn('id', $id)->get();

    if (empty($classroom)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classroom found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classroom" => $classroom
        ]
    ];
});

//GET CLASSROOMS [LABORATORY] IN CURRICULUM
Route::get("/classrooms-curriculum/{id}/lab", function ($id) {
    $id = json_decode($id, true);

    $classroom = Classroom::whereIn('id', $id)->where('Classroom_Type', 'Laboratory')->get();

    if (empty($classroom)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classroom found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classroom" => $classroom
        ]
    ];
});

//GET CLASSROOMS BY SUBJECT
Route::get("/classrooms-subject/{id}/{AY}/{sem}/{c_id}/{YL}/{type}", function ($id, $AY, $sem, $c_id, $YL, $type) {
    $curricula = DB::table('curricula')
                ->where('curricula.subject_id', $id)
                ->where('curricula.course_id', $c_id)
                ->where('curricula.academicYear', $AY)
                ->where('curricula.semester', $sem)
                ->where('curricula.yearLevel', $YL)
                ->pluck('curricula.classrooms_id')->first();

    $ids = json_decode($curricula, true);

    if ($type == "Laboratory")
        $classroom = Classroom::whereIn('id', $ids)->where('Classroom_Type', 'Laboratory')
                    ->orderBy('Building_No')
                    ->orderBy('Classroom_No')->get();
    else
        $classroom = Classroom::whereIn('id', $ids)
                    ->orderBy('Building_No')
                    ->orderBy('Classroom_No')->get();

    if (empty($classroom)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classroom found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classroom" => $classroom
        ]
    ];
});

//DELETE CLASSROOM
Route::delete('/classrooms/delete/{id}', function (Request $request, $id) {
    $classroom = Classroom::find($id);

    if (empty($classroom)) {
        $success = false;
        $response = ["error" => "Classroom could not be deleted."];
    } else {
        $success = $classroom->delete();
        $response = ["message" => "Classroom deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//UPDATE RECORD FOR CLASSROOM
Route::put('/classrooms/update/{id}', function (Request $request, $id) {
    $data = $request->all();

    $classroom = Classroom::find($id);

    foreach ($data as $key => $value) {
        $classroom->{$key} = $value;
    }

    $result = $classroom->save();

    return ["success" => $result, "response" => ["classroom" => $classroom]];
});

//CREATE RECORD FOR CURRICULUM
Route::post('/curricula/create', function (Request $request) {
    $data = $request->all();

    if(!Curriculum::where('academicYear','=', $data['academicYear'])->where('semester','=', $data['semester'])->where('subject_id','=', $data['subject_id'])->where('course_id', '=', $data['course_id'])->exists()) {
        $curriculum = Curriculum::create([
            "academicYear" => $data["academicYear"],
            "semester" => $data["semester"],
            "course_id" => $data["course_id"],
            "yearLevel" => $data["yearLevel"],
            "subject_id" => $data["subject_id"],
            "Units" => $data["Units"],
            "lec" => $data["lec"],
            "lab" => $data["lab"],
            "faculties_id" => json_encode($data["faculties_id"]),
            "classrooms_id" => json_encode($data["classrooms_id"]),
            "user_id" => $data["user_id"]
        ]);

        if (empty($curriculum->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "curriculum" => $curriculum
                ]
            ];
        }
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "Invalid! Selected Course is already saved."
            ]
        ];
    }

});

// GET ALL CURRICULA BY COURSE FOR REG (GEC)
Route::get("/curricula/{AY}/{sem}/{id}/{user}", function ($AY, $sem, $id, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name')
        ->where('subjects.Subject_Type', 'GEC')
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.user_id', $user)
        ->get();

    foreach ($curricula->toArray() as $key => $value)
    {
        $value->faculties_id = json_decode($value->faculties_id, true);
        $value->classrooms_id = json_decode($value->classrooms_id, true);
    }

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA BY COURSE FOR DEPT (CORE)
Route::get("/curricula/core/{AY}/{sem}/{id}/{user}", function ($AY, $sem, $id, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name')
        ->where('subjects.Subject_Type', 'Core')
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.user_id', $user)
        ->get();

    foreach ($curricula->toArray() as $key => $value)
    {
        $value->faculties_id = json_decode($value->faculties_id, true);
        $value->classrooms_id = json_decode($value->classrooms_id, true);
    }

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA BY COURSE AND YEAR LEVEL (MAJOR & ELECTIVE)
Route::get("/curricula/YL/{AY}/{sem}/{id}/{yL}/{user}", function ($AY, $sem, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->whereIn('subjects.Subject_Type', ['Major', 'Elective'])
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.course_id', $id)
        ->where('curricula.user_id', $user)
        ->get();

    foreach ($curricula->toArray() as $key => $value)
    {
        $value->faculties_id = json_decode($value->faculties_id, true);
        $value->classrooms_id = json_decode($value->classrooms_id, true);
    }

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA LIST BY COLLEGE
Route::get("/curricula/{AY}/{sem}/{user}", function ($AY, $sem, $user) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('user_id', $user)
        // ->select('curricula.id', 'courses.Course_Name', 'colleges.College_Code')
        // ->groupBy('course_id')
        ->distinct()
        // ->select('curricula.id', 'courses.Course_Name', 'colleges.College_Code')
        ->get(['courses.Course_Name', 'colleges.College_Code', 'colleges.College_Name', 'colleges.Campus', 'colleges.id']);

    // $curricula = $curricul->unique('Course_Name')->all();

    // if (empty($curricula)) {
    //     return [
    //         "success" => true,
    //         "response" => [
    //             "error" => "No records of Curricula found."
    //         ]
    //     ];
    // }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA LIST BY DEPT CHAIR (FOR CORE)
Route::get("/curriculaCore/{AY}/{sem}/{user}", function ($AY, $sem, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('subjects.Subject_Type', 'Core')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('user_id', $user)
        ->distinct()
        ->get(['courses.Course_Name', 'colleges.College_Code', 'colleges.College_Name', 'colleges.Campus', 'colleges.id']);

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET COLLEGE LIST BY CURRICULA OF USER
Route::get("/curricula-college/{AY}/{sem}/{user}", function ($AY, $sem, $user) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('user_id', $user)
        ->distinct()
        ->get(['colleges.College_Code', 'colleges.College_Name', 'colleges.Campus', 'colleges.id']);

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET COURSES BY COLLEGE-CURRICULA
Route::get("/curricula-course/{AY}/{sem}/{user}/{c}", function ($AY, $sem, $user, $c) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('user_id', $user)
        ->where('colleges.id', $c)
        ->distinct()
        ->get(['courses.Course_Name', 'courses.id']);

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET YEAR LEVEL BY COURSE-CURRICULA
Route::get("/curricula-yl/{AY}/{sem}/{user}/{c}", function ($AY, $sem, $user, $c) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('user_id', $user)
        ->where('courses.id', $c)
        ->distinct()
        ->get('curricula.yearLevel');

        $curricula = Arr::pluck($curricula, 'yearLevel');
    
    foreach ($curricula as &$key) {
        switch ($key) {
            case 'First Year':
                $key = 1;
                break;
            case 'Second Year':
                $key = 2;
                break;
            case 'Third Year':
                $key = 3;
                break;
            case 'Fourth Year':
                $key = 4;
                break;
        }
    }
    sort($curricula);
    foreach ($curricula as &$key) {
        switch ($key) {
            case 1:
                $key = 'First Year';
                break;
            case 2:
                $key = 'Second Year';
                break;
            case 3:
                $key = 'Third Year';
                break;
            case 4:
                $key = 'Fourth Year';
                break;
        }
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

//GET ALL SUBJECTS OF CURRICULA BY COURSE & YEAR LEVEL & USER
Route::get("/curriculayl-subjects/{AY}/{sem}/{id}/{yL}/{user}", function ($AY, $sem, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('subjects.id', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', $user)
        ->get();

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/curriculayl/{AY}/{sem}/{id}/{yL}/{user}", function ($AY, $sem, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', $user)
        ->get();

        // foreach ($curricula->toArray() as $key => $value)
        // {
        //     $value->faculties_id = json_decode($value->faculties_id, true);
        //     $value->classrooms_id = json_decode($value->classrooms_id, true);
        // }

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET THE CURRICULA  THAT IS NOT BEEN SAVED IN THE CLASS SCHEDULES
// BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/curricula-notsaved/{AY}/{sem}/{sched}/{id}/{yL}/{user}", function ($AY, $sem, $sched, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', $user)
        ->get();

    $new = json_decode($curricula, true);
    foreach ($new as $key => $value){
        $subjects[$key] = $value['subject_id'];
    }

    $classschedules = DB::table('class_schedules')
                    ->where('schedule_id', $sched)
                    ->whereIn('class_schedules.subject_id', $subjects)
                    ->get();

    $subjectss = [];
    $news = json_decode($classschedules, true);
    foreach ($news as $key => $value){
        $subjectss[$key] = $value['subject_id'];
    }

    $subjectss = array_unique($subjectss);
    $subjectss = array_values($subjectss);

    $curricula = DB::table('curricula')
                    ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
                    ->where('academicYear', $AY)
                    ->where('semester', $sem)
                    ->where('curricula.course_id', $id)
                    ->where('curricula.yearLevel', $yL)
                    ->where('curricula.user_id', $user)
                    ->whereNotIn('subjects.id', $subjectss)
                    ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
                    ->get();

    if (empty($curricula)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET THE CURRICULA STATUS
// BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/curricula-status/{AY}/{sem}/{sched}/{id}/{yL}/{user}", function ($AY, $sem, $sched, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', $user)
        ->get();

    $new = json_decode($curricula, true);
    foreach ($new as $key => $value){
        $subjects[$key] = $value['subject_id'];
        $lec[$key] = $value['lec'];
        $lab[$key] = $value['lab'];
        $minsCurr[$key] = ($lec[$key] * 60) + ($lab[$key] * 3 * 60);
    }

    $classschedules = DB::table('class_schedules')
                    ->where('schedule_id', $sched)
                    ->whereIn('subject_id', $subjects)
                    ->get();

    $cs = json_decode($classschedules, true);
    foreach ($cs as $key => $value){
        $subjectsCS[$key] = $value['subject_id'];
        $time = strtotime($value['startTime']);
        $minsS = (date('H', $time) * 60) + date('i', $time);
        $time = strtotime($value['endTime']);
        $minsE = (date('H', $time) * 60) + date('i', $time);
        $minutes[$key] = $minsE - $minsS;
    }
    // $subjectsUn = array_unique($subjectsCS);
    // $subjectsUn = array_values($subjectsUn);
    foreach ($subjects as $key => $value){
        $mins[$key] = 0;
        foreach($cs as $key2 => $value2){
            if($value == $value2['subject_id']) {
                $mins[$key] = $mins[$key] + $minutes[$key2];
            }
        }
    }

    $curriculaStatus = array();
    foreach ($new as $key => $value){
        if($minsCurr[$key] - $mins[$key] == 0)
            $status = "Complete";
        else if($minsCurr[$key] - $mins[$key] < 0)
            $status = "Exceeded";
        else if($minsCurr[$key] - $mins[$key] == $minsCurr[$key])
            $status = "Not Saved";
        else if($minsCurr[$key] - $mins[$key] < $minsCurr[$key])
            $status = "Incomplete";

        $curriculaStatus[$key] =  array(
            "subject_id" => $value['subject_id'],
            "Subject_Name" => $value['Subject_Name'],
            "Subject_Code" => $value['Subject_Code'],
            "lec" => $value['lec'],
            "lab" => $value['lab'],
            "mins" => $mins[$key],
            "reqHrs" => $minsCurr[$key] / 60,
            "status" => $status
        );
    }

    if (empty($curriculaStatus)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curriculaStatus" => $curriculaStatus
        ]
    ];
});

// GET SUBJECTS BASED ON CURRICULA STATUS
// BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/subjects/curricula-status/{AY}/{sem}/{sched}/{id}/{yL}/{user}", function ($AY, $sem, $sched, $id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', $user)
        ->get();

    $new = json_decode($curricula, true);
    foreach ($new as $key => $value){
        $subjects[$key] = $value['subject_id'];
        $lec[$key] = $value['lec'];
        $lab[$key] = $value['lab'];
        $minsCurr[$key] = ($lec[$key] * 60) + ($lab[$key] * 3 * 60);
    }

    

    $classschedules = DB::table('class_schedules')
                    ->where('schedule_id', $sched)
                    ->whereIn('subject_id', $subjects)
                    ->get();

    $cs = json_decode($classschedules, true);
    foreach ($cs as $key => $value){
        $subjectsCS[$key] = $value['subject_id'];
        $time = strtotime($value['startTime']);
        $minsS = (date('H', $time) * 60) + date('i', $time);
        $time = strtotime($value['endTime']);
        $minsE = (date('H', $time) * 60) + date('i', $time);
        $minutes[$key] = $minsE - $minsS;
    }
    // $subjectsUn = array_unique($subjectsCS);
    // $subjectsUn = array_values($subjectsUn);
    foreach ($subjects as $key => $value){
        $mins[$key] = 0;
        foreach($cs as $key2 => $value2){
            if($value == $value2['subject_id']) {
                $mins[$key] = $mins[$key] + $minutes[$key2];
            }
        }
    }

    $curriculaStatus = array();
    foreach ($new as $key => $value){
        if($minsCurr[$key] - $mins[$key] != 0)
            $curriculaStatus[$key] =  array(
                "id" => $value['subject_id'],
                "Subject_Name" => $value['Subject_Name'],
                "Subject_Code" => $value['Subject_Code'],
                "Subject_Type" => $value['Subject_Type'],
                "lec" => $value['lec'],
                "lab" => $value['lab'],
            );
    }
    $curriculaStatus = array_values($curriculaStatus);

    return [
        "success" => true,
        "response" => [
            "curriculaStatus" => $curriculaStatus
        ]
    ];
});

// GET THE CURRICULA STATUS OTHER
// BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/curricula-status-other/{AY}/{sem}/{sched}/{id}/{yL}/{user}", function ($AY, $sem, $sched, $id, $yL, $user) {
    $curriculaStatus = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.user_id', '<>', $user)
        ->get();

    if(count($curriculaStatus) > 0){
        $new = json_decode($curriculaStatus, true);
        foreach ($new as $key => $value){
            $subjects[$key] = $value['subject_id'];
            $lec[$key] = $value['lec'];
            $lab[$key] = $value['lab'];
            $minsCurr[$key] = ($lec[$key] * 60) + ($lab[$key] * 3 * 60);
        }

        $classschedules = DB::table('class_schedules')
                        ->where('schedule_id', $sched)
                        ->whereIn('subject_id', $subjects)
                        ->get();

        $cs = json_decode($classschedules, true);
        foreach ($cs as $key => $value){
            $subjectsCS[$key] = $value['subject_id'];
            $time = strtotime($value['startTime']);
            $minsS = (date('H', $time) * 60) + date('i', $time);
            $time = strtotime($value['endTime']);
            $minsE = (date('H', $time) * 60) + date('i', $time);
            $minutes[$key] = $minsE - $minsS;
        }
        // $subjectsUn = array_unique($subjectsCS);
        // $subjectsUn = array_values($subjectsUn);
        foreach ($subjects as $key => $value){
            $mins[$key] = 0;
            foreach($cs as $key2 => $value2){
                if($value == $value2['subject_id']) {
                    $mins[$key] = $mins[$key] + $minutes[$key2];
                }
            }
        }

        $curriculaStatus = array();
        foreach ($new as $key => $value){
            if($minsCurr[$key] - $mins[$key] == 0)
                $status = "Complete";
            else if($minsCurr[$key] - $mins[$key] < 0)
                $status = "Exceeded";
            else if($minsCurr[$key] - $mins[$key] == $minsCurr[$key])
                $status = "Not Saved";
            else if($minsCurr[$key] - $mins[$key] < $minsCurr[$key])
                $status = "Incomplete";
            

            $curriculaStatus[$key] =  array(
                "subject_id" => $value['subject_id'],
                "Subject_Name" => $value['Subject_Name'],
                "Subject_Code" => $value['Subject_Code'],
                "lec" => $value['lec'],
                "lab" => $value['lab'],
                "mins" => $mins[$key],
                "reqHrs" => $minsCurr[$key] / 60,
                "status" => $status
            );
        }
    }

    if (empty($curriculaStatus)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Schedules found for this class by the other user/s."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curriculaStatus" => $curriculaStatus
        ]
    ];
});

// GET THE CURRICULA STATUS
// BY COLLEGE ON CURRICULA
Route::get("/curricula-status-college/{AY}/{sem}/{sched}/{id}/{yL}", function ($AY, $sem, $sched, $id, $yL) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('academicYear', $AY)
        ->where('semester', $sem)
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
        ->get();

    $new = json_decode($curricula, true);
    foreach ($new as $key => $value){
        $subjects[$key] = $value['subject_id'];
        $lec[$key] = $value['lec'];
        $lab[$key] = $value['lab'];
        $minsCurr[$key] = ($lec[$key] * 60) + ($lab[$key] * 3 * 60);
    }

    $classschedules = DB::table('class_schedules')
                    ->where('schedule_id', $sched)
                    ->whereIn('subject_id', $subjects)
                    ->get();

    $cs = json_decode($classschedules, true);
    foreach ($cs as $key => $value){
        $subjectsCS[$key] = $value['subject_id'];
        $time = strtotime($value['startTime']);
        $minsS = (date('H', $time) * 60) + date('i', $time);
        $time = strtotime($value['endTime']);
        $minsE = (date('H', $time) * 60) + date('i', $time);
        $minutes[$key] = $minsE - $minsS;
    }
    foreach ($subjects as $key => $value){
        $mins[$key] = 0;
        foreach($cs as $key2 => $value2){
            if($value == $value2['subject_id']) {
                $mins[$key] = $mins[$key] + $minutes[$key2];
            }
        }
    }

    $curriculaStatus = array();
    foreach ($new as $key => $value){
        if($minsCurr[$key] - $mins[$key] == 0)
            $status = "Complete";
        else if($minsCurr[$key] - $mins[$key] < 0)
            $status = "Exceeded";
        else if($minsCurr[$key] - $mins[$key] == $minsCurr[$key])
            $status = "Not Saved";
        else if($minsCurr[$key] - $mins[$key] < $minsCurr[$key])
            $status = "Incomplete";

        $curriculaStatus[$key] =  array(
            "subject_id" => $value['subject_id'],
            "Subject_Name" => $value['Subject_Name'],
            "Subject_Code" => $value['Subject_Code'],
            "lec" => $value['lec'],
            "lab" => $value['lab'],
            "mins" => $mins[$key],
            "reqHrs" => $minsCurr[$key] / 60,
            "status" => $status
        );
    }

    if (empty($curriculaStatus)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records of Curricula found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curriculaStatus" => $curriculaStatus
        ]
    ];
});

//GET A SINGLE CURRICULUM
Route::get("/curriculum/{id}", function (Request $request, $id) {
    $curriculum = DB::table('curricula')
                    ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
                    ->where('curricula.id', $id)
                    ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name')
                    ->first();

    $curriculum->faculties_id = json_decode($curriculum->faculties_id, true);
    $curriculum->classrooms_id = json_decode($curriculum->classrooms_id, true);

    if (empty($curriculum)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Curriculum found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "curriculum" => $curriculum
        ]
    ];
});

//DELETE CURRICULUM
Route::delete('/curricula/delete/{id}', function (Request $request, $id) {
    $curriculum = Curriculum::find($id);

    if (empty($curriculum)) {
        $success = false;
        $response = ["error" => "Curriculum could not be deleted."];
    } else {
        $success = $curriculum->delete();
        $response = ["message" => "Curriculum for this Course is deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//UPDATE RECORD FOR CURRICULUM
Route::put('/curricula/update/{AY}/{sem}/{cid}/{id}', function (Request $request, $AY, $sem, $cid, $id) {
    $data = $request->all();

    if(!Curriculum::where('subject_id','=', $data['subject_id'])
        ->where('academicYear', '=', $AY)
        ->where('semester', '=', $sem)
        ->where('course_id', '=', $cid)
        ->where('id', '<>', $id)
        ->exists()) {
        $curriculum = Curriculum::find($id);

        foreach ($data as $key => $value) {
            $curriculum->{$key} = $value;
        }

        $result = $curriculum->save();

        return ["success" => $result, "response" => ["curriculum" => $curriculum]];
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "Invalid! Subject is already added."
            ]
        ];
    }
});

//GET THE LISTS OF COURSES BY SELECTED COLLEGE
Route::get('/courses/college/{id}', function (Request $request, $id) {
    // $courses = Course::where('college_id', $id)->get();

    $courses = DB::table('courses')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->where('departments.college_id', '=', $id)
            ->select('courses.*')
            ->get();

    if (empty($courses)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Courses on this College."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "courses" => $courses
        ]
    ];
});


// GET TOTAL NO. OF CLASSES BY USER
Route::get('/curricula-totals/{AY}/{sem}/{user}', function ($AY, $sem, $user) {
        $curricula = DB::table('curricula')
            ->join('courses', 'courses.id', '=', 'curricula.course_id')
            ->where('curricula.academicYear', $AY)
            ->where('curricula.semester', $sem)
            ->where('curricula.user_id', $user)
            ->select('curricula.course_id','courses.Course_Name','curricula.yearLevel','courses.Course_Code')
            ->distinct()
            ->get();

    $i = 0;
    $scheds = array();

    foreach($curricula as $c){
        $yl = "";
        switch ($c->yearLevel) {
            case 'First Year':
                $yl = 'first';
                $yL = 1;
                break;
            case 'Second Year':
                $yl = 'second';
                $yL = 2;
                break;
            case 'Third Year':
                $yl = 'third';
                $yL = 3;
                break;
            case 'Fourth Year':
                $yl = 'fourth';
                $yL = 4;
                break;
        }

        $schedules = DB::table('schedules')
            ->where('schedules.academicYear', $AY)
            ->where('schedules.semester', $sem)
            ->where('schedules.course_id', $c->course_id)
            ->where('schedules.yearLevel', $c->yearLevel)
            ->get(['schedules.id', 'schedules.course_id', 'schedules.yearLevel', 'schedules.block']);
            
        foreach ($schedules as $key) {
            $scheds[$i] = array(
                "sched_id" => $key->id,
                "course_id" => $key->course_id,
                "yearLevel" => $key->yearLevel,
                "program_name" => $c->Course_Name,
                "block" => $key->block,
                "class_code" => $c->Course_Code." ".$yL."-".$key->block,
                "curr" => count($curricula)
            );
            $i++;
        }
    }

    return [
        "success" => true,
        "response" => [
            "total" => $scheds
        ]
    ];
});

// GET THE TOTAL LISTS OF CLASSES BY USER
Route::get('/curricula-totals-list/{AY}/{sem}/{user}', function ($AY, $sem, $user) {
    $curricula = DB::table('curricula')
        ->join('courses', 'courses.id', '=', 'curricula.course_id')
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->where('curricula.user_id', $user)
        ->select('curricula.course_id','courses.Course_Name','curricula.yearLevel','courses.Course_Code')
        ->distinct()
        ->get(['curricula.course_id', 'curricula.yearLevel']);

    $j = 0;
    $class = array();
    $b = ['A', 'B', 'C', 'D'];

    foreach($curricula as $c){
        $yl = "";
        switch ($c->yearLevel) {
            case 'First Year':
                $yl = 'first';
                $yL = 1;
                break;
            case 'Second Year':
                $yl = 'second';
                $yL = 2;
                break;
            case 'Third Year':
                $yl = 'third';
                $yL = 3;
                break;
            case 'Fourth Year':
                $yl = 'fourth';
                $yL = 4;
                break;
        }
        $blocks = DB::table('blocks')
            ->where('blocks.academicYear', $AY)
            ->where('blocks.semester', $sem)
            ->where('blocks.course_id', $c->course_id)
            ->value('blocks.'.$yl);
            
        for ($i=0; $i < $blocks; $i++) { 
            $class[$j] = array(
                "program_name" => $c->Course_Name,
                "yearLevel" => $c->yearLevel,
                "block" => $b[$i],
                "class_code" => $c->Course_Code." ".$yL."-".$b[$i]
            );
            $j++;
        }
    }

    return [
        "success" => true,
        "response" => [
            "classes" => $class
        ]
    ];
});

// GET TOTAL NO. OF CLASSES BY COLLEGE
Route::get('/curricula-college-totals/{AY}/{sem}/{username}', function ($AY, $sem, $username) {
    $curricula = DB::table('curricula')
        ->join('courses', 'courses.id', '=', 'curricula.course_id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.username', $username)
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->select('curricula.course_id','courses.Course_Name','curricula.yearLevel','courses.Course_Code')
        ->distinct()
        ->get();

    $i = 0;
    $scheds = array();

    foreach($curricula as $c){
        $yl = "";
        switch ($c->yearLevel) {
            case 'First Year':
                $yl = 'first';
                $yL = 1;
                break;
            case 'Second Year':
                $yl = 'second';
                $yL = 2;
                break;
            case 'Third Year':
                $yl = 'third';
                $yL = 3;
                break;
            case 'Fourth Year':
                $yl = 'fourth';
                $yL = 4;
                break;
        }

        $schedules = DB::table('schedules')
            ->where('schedules.academicYear', $AY)
            ->where('schedules.semester', $sem)
            ->where('schedules.course_id', $c->course_id)
            ->where('schedules.yearLevel', $c->yearLevel)
            ->get(['schedules.id', 'schedules.course_id', 'schedules.yearLevel', 'schedules.block']);
            
        foreach ($schedules as $key) {
            $scheds[$i] = array(
                "sched_id" => $key->id,
                "course_id" => $key->course_id,
                "yearLevel" => $key->yearLevel,
                "program_name" => $c->Course_Name,
                "block" => $key->block,
                "class_code" => $c->Course_Code." ".$yL."-".$key->block
            );
            $i++;
        }
    }

    return [
        "success" => true,
        "response" => [
            "total" => $scheds
        ]
    ];
});

// GET THE TOTAL LISTS OF CLASSES BY COLLEGE
Route::get('/curricula-college-totals-list/{AY}/{sem}/{username}', function ($AY, $sem, $username) {  
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('colleges.username', $username)
        ->where('curricula.academicYear', $AY)
        ->where('curricula.semester', $sem)
        ->select('curricula.course_id','courses.Course_Name','curricula.yearLevel','courses.Course_Code')
        ->distinct()
        ->get(['curricula.course_id', 'curricula.yearLevel']);

    $j = 0;
    $class = array();
    $b = ['A', 'B', 'C', 'D'];

    foreach($curricula as $c){
        $yl = "";
        switch ($c->yearLevel) {
            case 'First Year':
                $yl = 'first';
                $yL = 1;
                break;
            case 'Second Year':
                $yl = 'second';
                $yL = 2;
                break;
            case 'Third Year':
                $yl = 'third';
                $yL = 3;
                break;
            case 'Fourth Year':
                $yl = 'fourth';
                $yL = 4;
                break;
        }
        $blocks = DB::table('blocks')
            ->where('blocks.academicYear', $AY)
            ->where('blocks.semester', $sem)
            ->where('blocks.course_id', $c->course_id)
            ->value('blocks.'.$yl);
            
        for ($i=0; $i < $blocks; $i++) { 
            $class[$j] = array(
                "program_name" => $c->Course_Name,
                "yearLevel" => $c->yearLevel,
                "block" => $b[$i],
                "class_code" => $c->Course_Code." ".$yL."-".$b[$i]
            );
            $j++;
        }
    }

    return [
        "success" => true,
        "response" => [
            "classes" => $class
        ]
    ];
});

//CREATE RECORD FOR SCHEDULE
Route::post('/schedules/create', function (Request $request) {
    $data = $request->all();

    if(!Schedule::where('academicYear','=', $data['academicYear'])->where('semester','=', $data['semester'])->where('course_id','=', $data['course_id'])->where('yearLevel','=', $data['yearLevel'])->where('block','=', $data['block'])->exists()) {

        $schedule = Schedule::create([
            "academicYear" => $data["academicYear"],
            "semester" => $data["semester"],
            "course_id" => $data["course_id"],
            "yearLevel" => $data["yearLevel"],
            "block" => $data["block"]
        ]);

        if (empty($schedule->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured."
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "schedule" => $schedule
                ]
            ];
        }
    } else {
            return [
                "success" => false,
                "response" => [
                    "error" => "The Schedule already exists."
                ]
            ];
        }
});

//GET A SCHEDULE
Route::get("/schedules/{AY}/{sem}/{id}/{yL}/{b}", function (Request $request, $AY, $sem, $id, $yL, $b) {
    $schedule = Schedule::where('course_id', $id)
                          ->where('academicYear', $AY)
                          ->where('semester', $sem)
                          ->where('yearLevel', $yL)
                          ->where('block', $b)
                          ->value('id');

    if (empty($schedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Schedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "schedule" => $schedule
        ]
    ];
});

//GET A SCHEDULE'S INFO FOR PRINTING
Route::get("/schedules/info/{id}", function (Request $request, $id) {
    $schedule = DB::table('schedules')->where('schedules.id', $id)
                          ->join('courses', 'schedules.course_id', '=', 'courses.id')
                          ->join('departments', 'courses.department_id', '=', 'departments.id')
                          ->join('colleges', 'departments.college_id', '=', 'colleges.id')
                          ->select('schedules.*', 'courses.Course_Name', 'courses.Course_Code', 'departments.Department_Name', 'colleges.College_Name', 'colleges.College_Code', 'colleges.Campus')
                          ->first();

    if (empty($schedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Schedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "schedule" => $schedule
        ]
    ];
});

//GET LISTS OF SCHEDULES FOR VIEWING OF THE REGISTRAR
Route::get("/schedules/view/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $schedules = DB::table('schedules')
                          ->where('schedules.academicYear', $AY)
                          ->where('schedules.semester', $sem)
                          ->join('courses', 'schedules.course_id', '=', 'courses.id')
                          ->join('departments', 'courses.department_id', '=', 'departments.id')
                          ->join('colleges', 'departments.college_id', '=', 'colleges.id')
                          ->join('class_schedules', 'schedules.id', '=', 'class_schedules.schedule_id')
                          ->where('colleges.id', $id)
                          ->select('schedules.*', 'courses.Course_Name', 'courses.Course_Code')
                          ->orderBy('courses.Course_Code')
                          ->orderBy('schedules.block')
                          ->distinct()
                          ->get(['schedules.id']);
    
    foreach($schedules as $cs)
    {
        switch($cs->yearLevel){
            case "First Year":
                $cs->yearLevel = 1;
                break;
            case "Second Year":
                $cs->yearLevel = 2;
                break;
            case "Third Year":
                $cs->yearLevel = 3;
                break;
            case "Fourth Year":
                $cs->yearLevel = 4;
                break;
        }
    }
    $schedules = json_decode(json_encode($schedules), true);
    
   $yearLevel = array();
   $yearLevel = array_column($schedules, 'yearLevel');
   array_multisort($yearLevel, SORT_ASC, $schedules);

    if (empty($schedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record of Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "schedules" => $schedules
        ]
    ];
});

//GET THE LISTS OF MAJOR SUBJECTS BY COURSE
Route::get('/subjects/major/{m}/{id}', function (Request $request, $m, $id) {
    $subjects = Subject::where('Subject_Type', $m)->where('course_id', $id)->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Major Subjects."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

//GET THE LISTS OF NOT MAJOR SUBJECTS BY COURSE
Route::get('/subjects/notmajor/{id}', function (Request $request, $id) {
    $subject = Subject::whereNull('course_id');
    $subjects = Subject::where('course_id', '<>', $id)->union($subject)->get();

    if (empty($subjects)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Major Subjects."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "subjects" => $subjects
        ]
    ];
});

//GET THE LISTS OF MAJOR FACULTIES BY COLLEGE
Route::get('/faculties/major/{id}', function (Request $request, $id) {
    $faculties = Faculty::where('college_id', $id)->get();

    if (empty($faculties)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Major Faculties."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "faculties" => $faculties
        ]
    ];
});

//GET THE LISTS OF MAJOR CLASSROOMS BY COLLEGE
Route::get('/classrooms/major/{id}', function (Request $request, $id) {
    $classrooms = Classroom::where('college_id', $id)->get();

    if (empty($classrooms)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Major Classrooms."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classrooms" => $classrooms
        ]
    ];
});

//CREATE RECORD FOR CLASS SCHEDULE
Route::post('/classschedules/create/{AY}/{sem}', function (Request $request, $AY, $sem) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;
    // $schedules = ClassSchedule::chunkById(100, function($schedules) use ($data, $i, $count, $noConflict){
    foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                            ->where('schedules.academicYear', $AY)
                            ->where('schedules.semester', $sem)
                            ->cursor() as $schedule) {

        if( ($schedule['day'] == $data['day']) AND 
        ( ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime'])) AND
            date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) OR

          ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime'])) AND
            date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) ) OR

          ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime']))  AND
            date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) AND
            date('H:i', strtotime($schedule['startTime'])) < date('H:i', strtotime($data['endTime'])) ) OR

          ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime']))  AND
            date('H:i', strtotime($schedule['endTime'])) > date('H:i', strtotime($data['startTime'])) AND
            date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) )
        )
        {   
            if(($schedule['faculty_id'] == $data['faculty_id'])) {
                $noConflict=true;
                $conflict = 1;
                return [
                    "success" => false,
                    "response" => [
                        "error" => "Selected Faculty is not available on that time."
                    ]
                ];
                return false;
                    
            }
            else if(($schedule['classroom_id'] == $data['classroom_id'])) {
                $noConflict=true;
                $conflict = 2;
                return [
                    "success" => false,
                    "response" => [
                        "error" => "Selected Classroom is already occupied."
                    ]
                ];
                return false;
            }
            else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                ($schedule['faculty_id'] != $data['faculty_id']) AND
                ($schedule['classroom_id'] != $data['classroom_id'])){
                $noConflict=true;
                $conflict = 3;
                return [
                    "success" => false,
                    "response" => [
                        "error" => "A Schedule was already saved on this Time range of this Class."
                    ]
                ];
                return false;
            }
            else {
                $noConflict=false;
            }
            
        } else{
            $noConflict=false;
        }
    }

    if($noConflict == false)
    {
        $classschedule = ClassSchedule::create([
            "schedule_id" => $data["schedule_id"],
            "day" => $data["day"],
            "startTime" => $data["startTime"],
            "endTime" => $data["endTime"],
            "subject_id" => $data["subject_id"],
            "session" => $data["session"],
            "faculty_id" => $data["faculty_id"],
            "classroom_id" => $data["classroom_id"],
            "user_id" => $data["user_id"]
        ]);
                if (empty($classschedule->id)) {
                    return [
                        "success" => false,
                        "response" => [
                            "error" => "An unexpected error has occured."
                        ]
                    ];
                } else {
                    return [
                        "success" => true,
                        "response" => [
                            "classschedule" => $classschedule
                        ]
                    ];
                }
        
    } else {
        if($conflict == 1) {
            return [
                "success" => false,
                "response" => [
                    "error" => "Selected Faculty is not available on that time."
                ]
            ];
        }
        else if ($conflict == 2) {
            return [
                "success" => false,
                "response" => [
                    "error" => "Selected Classroom is already occupied."
                ]
            ];
        }
        else if ($conflict == 3) {
            return [
                "success" => false,
                "response" => [
                    "error" => "A Schedule was already saved on this Time range of this Class."
                ]
            ];
        }
    }
});

//GENERATE SCHEDULES FOR A CLASS
Route::post('/classschedules/generate/{AY}/{sem}', function (Request $request, $AY, $sem) {
    $data = $request->all();

    $startTimeM = trim($data['StartTimeM'], "'");
    $startTimeA = trim($data['StartTimeA'], "'");
    $subjects = json_decode($data['subjects'], true);
    $lec = json_decode($data['lec'], true);
    $lab = json_decode($data['lab'], true);
    $split_lec = json_decode($data['split_lec'], true);
    $split_lab = json_decode($data['split_lab'], true);
    $pdays = json_decode($data['prefDays'], true);
    $time_period = json_decode($data['time_period'], true);
    // $pdays = json_decode(trim($data['prefDays'], "'"),true);
    //     $tp = trim($data['time_period'], "[]");
    //     $tpp = str_ireplace( array('\'', '"'), '', $tp);
    // $time_period = explode(",",$tpp);
    $faculties = json_decode($data['faculties'], true);
    $classrooms_lec = json_decode($data['classrooms_lec'], true);
    $classrooms_lab = json_decode($data['classrooms_lab'], true);

    //prefDays convert to array of numbers and sort in ascending
    for($i=0; $i<count($pdays); $i++)
        $pd[$i] = explode(",",$pdays[$i]);
    for($i = 0; $i < count($pd); $i++){
        for($j = 0; $j < count($pd[$i]); $j++)
            $pd[$i][$j] = (int)$pd[$i][$j];
        sort($pd[$i]);
    }

    //prefDays convert to Date Format
    for($i = 0; $i < count($pd); $i++){
        for($j = 0; $j < count($pd[$i]); $j++)
            switch ($pd[$i][$j]) {
                case 0:
                    $pd[$i][$j] = "2022-08-01";
                    break;
                case 1:
                    $pd[$i][$j] = "2022-08-02";
                    break;
                case 2:
                    $pd[$i][$j] = "2022-08-03";
                    break;
                case 3:
                    $pd[$i][$j] = "2022-08-04";
                    break;
                case 4:
                    $pd[$i][$j] = "2022-08-05";
                    break;
                case 5:
                    $pd[$i][$j] = "2022-08-06";
                    break;
            }
    }
    //trim time_period \'
    // for($i = 0; $i < count($time_period); $i++)
    //     $time_period[$i] = trim($time_period[$i]);

    $savedCS = array();
    $isError = false;

    class SchedulesErrors
    {
        public $lecerror;
        public $laberror;
    }
    $classschederrors_ = array();

    for($i = 0; $i < count($subjects); $i++){
        $classschederrors_[$i] = new SchedulesErrors();

        $d = 0;

        $temp_pd = $pd[$i];

        $notYet = "first";
        $notYetTime = "first";
        $NONE = false;
        $notYetLab = "first";
        $notYetTimeLab = "first";
        $NONELAB = false;

        $startTimeMLec = $startTimeM;
        $startTimeMLab = $startTimeM;
        $startTimeALec = $startTimeA;
        $startTimeALab = $startTimeA;

        if($time_period[$i] == "Morning"){
            $startTime = $startTimeM;
            $maxTime = "12:00";
        }
        else if($time_period[$i] == "Afternoon"){
            $startTime = $startTimeA;
            $maxTime = "21:00";
        }

        $lecHours = $lec[$i];
        $labHours = $lab[$i] * 3;

        if ($lecHours > 0){
            $lecc = $lecHours / $split_lec[$i];

            if($lecc % $split_lec[$i] == 0){
                $endTime = strtotime("+$lecc hour", strtotime($startTime));
                $endTime = date('H:i', $endTime);
            } else {
                $hrs = floor($lecc);
                $mins = ($lecc - $hrs) * 60;
                $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                $endTime = date('H:i', $endTime);
            }

            if ($endTime > $maxTime){
                $maxT = strtotime($maxTime);
                $maxTH = date("H", $maxT);//maxTime hours
                $maxTH = 60 * $maxTH;
                $maxTM = date("i", $maxT);//maxTime minutes
                $maxTotM = $maxTH + $maxTM;//maxTime in minutes
                $endT = strtotime($endTime);
                $endTH = date("H", $endT);//endTime hours
                $endTH = 60 * $endTH;
                $endTM = date("i", $endT);//endTime minutes
                $endTotM = $endTH + $endTM;
                $diffME = $endTotM - $maxTotM;
                $endTime = date("H:i",strtotime("-$diffME minutes",strtotime($endTime)));
                $startTime = date("H:i",strtotime("-$diffME minutes",strtotime($startTime)));
    
                if($time_period[$i] == "Morning"){
                    if($lecc >= 6 && $lecc <= 8){
                        $startTime = "13:00";
                        $time_period[$i] = "Afternoon";
                        $startTimeALec = "13:00";
                        $hrs = floor($lecc);
                        $mins = ($lecc - $hrs) * 60;
                        $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                        $maxTime = "21:00";
                    }
                    else if($lecc > 8){
                        $lecc = $lecc / 2;
                        if($lecc <= 5){
                            $hrs = floor($lecc);
                            $mins = ($lecc - $hrs) * 60;
                            $startTime = "07:00";
                            $time_period[$i] = "Morning";
                            $startTimeMLec = "07:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "12:00";
                        } else {
                            $hrs = floor($lecc);
                            $mins = ($lecc - $hrs) * 60;
                            $startTime = "13:00";
                            $time_period[$i] = "Afternoon";
                            $startTimeALec = "13:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "21:00";
                        }
                    }
                }
                else if($time_period[$i] == "Afternoon"){
                    if($lecc > 8){
                        $lecc = $lecc / 2;
                        if($lecc <= 5){
                            $hrs = floor($lecc);
                            $mins = ($lecc - $hrs) * 60;
                            $startTime = "07:00";
                            $time_period[$i] = "Morning";
                            $startTimeMLec = "07:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "12:00";
                        } else {
                            $hrs = floor($lecc);
                            $mins = ($lecc - $hrs) * 60;
                            $startTime = "13:00";
                            $time_period[$i] = "Afternoon";
                            $startTimeALec = "13:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "21:00";
                        }
                    }
                    
                }
            }
        }
        
        $errorF = 0;$errorC = 0;$errorS = 0;
        $noConflict = null;
        while($lecHours > 0){
            while($d < count($pd[$i])){
                // if($DB_Days == $pd[$i][$d]){
                    // if ($endTime > $maxTime){
                    //     $maxT = strtotime($maxTime);
                    //     $maxTH = date("H", $maxT);//maxTime hours
                    //     $maxTH = 60 * $maxTH;
                    //     $maxTM = date("i", $maxT);//maxTime minutes
                    //     $maxTotM = $maxTH + $maxTM;//maxTime in minutes
                    //     $endT = strtotime($endTime);
                    //     $endTH = date("H", $endT);//endTime hours
                    //     $endTH = 60 * $endTH;
                    //     $endTM = date("i", $endT);//endTime minutes
                    //     $endTotM = $endTH + $endTM;
                    //     $diffME = $endTotM - $maxTotM;
                    //     $endTime = date("H:i",strtotime("-$diffME minutes",strtotime($endTime)));
                    //     $startTime = date("H:i",strtotime("-$diffME minutes",strtotime($startTime)));
                            
                    //     if($time_period[$i] == "Morning"){
                    //         if(startTime < "07:00"){
                    //             $startTime = "13:00";
                    //             $endTime = date('H:i', strtotime("+$lecc hour", strtotime($startTime)));
                    //             $maxTime = "20:00";
                    //         }
                    //     }
                    //     else if($time_period[$i] == "Afternoon"){
                    //         $lecc2 = $lecc / 2;
                    //         $hrs = floor($lecc2);
                    //         $mins = ($lecc2 - $hrs) * 60;
                    //         $startTime = "07:00";
                    //         $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                    //         $maxTime = "12:00";
                    //         while($endTime <= $maxTime){
                    //             foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                    //                                 ->where('schedules.academicYear', $AY)
                    //                                 ->where('schedules.semester', $sem)
                    //                                 ->cursor() as $schedule)
                    //             {
                    //                 if( ($schedule['day'] == $pd[$i][$d]) AND
                    //                     ( ( date('H:i', strtotime($schedule['startTime'])) >= $startTime AND
                    //                         date('H:i', strtotime($schedule['endTime'])) <= $endTime ) OR

                    //                         ( date('H:i', strtotime($schedule['startTime'])) >= $startTime  AND
                    //                         date('H:i', strtotime($schedule['endTime'])) >= $endTime AND
                    //                         date('H:i', strtotime($schedule['startTime'])) < $endTime ) OR

                    //                         ( date('H:i', strtotime($schedule['startTime'])) <= $startTime  AND
                    //                         date('H:i', strtotime($schedule['endTime'])) > $startTime AND
                    //                         date('H:i', strtotime($schedule['endTime'])) <= $endTime ) )
                    //                 )
                    //                 {   
                    //                     if(($schedule['faculty_id'] == $faculties[$i])) {
                    //                         $noConflict=true;
                    //                         break;
                    //                     }
                    //                     else if(($schedule['classroom_id'] == $classrooms[$i])) {
                    //                         $noConflict=true;
                    //                         break;
                    //                     }
                    //                     else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                    //                         ($schedule['faculty_id'] != $faculties[$i]) AND
                    //                         ($schedule['classroom_id'] != $classrooms[$i])){
                    //                         $noConflict=true;
                    //                         break;
                    //                     }
                    //                     else {
                    //                         $noConflict=false;
                    //                     }                            
                    //                 } else{
                    //                     $noConflict=false;
                    //                 }
                    //             }
                    //             if($noConflict == false)
                    //             {
                    //                 $classschedule = ClassSchedule::create([
                    //                     "schedule_id" => $data["schedule_id"],
                    //                     "day" => $pd[$i][$d],
                    //                     "startTime" => $startTime,
                    //                     "endTime" => $endTime,
                    //                     "subject_id" => $subjects[$i],
                    //                     "faculty_id" => $faculties[$i],
                    //                     "classroom_id" => $classrooms[$i],
                    //                     "user_id" => $data["user_id"]
                    //                 ]);
                    //                 $lecHours = $lecHours - $lecc2;
                    //                 $startTime = "13:00";
                    //                 $endTime = date('H:i', strtotime("+$lecc2 hour", strtotime($startTime)));
                    //                 $maxTime = "20:00";
                    //             }
                    //             // if(!(($DB_StartTime <= $startTime && $DB_EndTime > $startTime) || ($DB_StartTime < $endTime && $DB_EndTime >= $endTime)))
                    //             else{
                    //                 //add startTime and endTime
                    //                 $add = strtotime($startTime);
                    //                 $add = date("i", $add);
                    //                 $add = 60 - $add;
                    //                 $startTime = strtotime("+$add minutes", strtotime($startTime));
                    //                 $startTime = date('H:i', $startTime);
                    //                 $endTime = strtotime("+$lecc2 hour", strtotime($startTime));
                    //                 $endTime = date('H:i', $endTime);
                    //             }
                    //             if($lecHours <= 0)
                    //                 break;
                    //         }
                    //     }
                    // }
                    // else{
                        while($endTime <= $maxTime){
                            foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                                                ->where('schedules.academicYear', $AY)
                                                ->where('schedules.semester', $sem)
                                                ->cursor() as $schedule)
                            {
                                if( ($schedule['day'] == $pd[$i][$d]) AND
                                    ( ( date('H:i', strtotime($schedule['startTime'])) >= $startTime AND
                                        date('H:i', strtotime($schedule['endTime'])) <= $endTime ) OR

                                        ( date('H:i', strtotime($schedule['startTime'])) <= $startTime AND
                                        date('H:i', strtotime($schedule['endTime'])) >= $endTime ) OR

                                        ( date('H:i', strtotime($schedule['startTime'])) >= $startTime  AND
                                        date('H:i', strtotime($schedule['endTime'])) >= $endTime AND
                                        date('H:i', strtotime($schedule['startTime'])) < $endTime ) OR

                                        ( date('H:i', strtotime($schedule['startTime'])) <= $startTime  AND
                                        date('H:i', strtotime($schedule['endTime'])) > $startTime AND
                                        date('H:i', strtotime($schedule['endTime'])) <= $endTime ) )
                                )
                                {   
                                    if(($schedule['faculty_id'] == $faculties[$i])) {
                                        $errorF++;
                                        $noConflict=true;
                                        break;
                                    }
                                    else if(($schedule['classroom_id'] == $classrooms_lec[$i])) {
                                        $errorC++;
                                        $noConflict=true;
                                        break;
                                    }
                                    else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                                        ($schedule['faculty_id'] != $faculties[$i]) AND
                                        ($schedule['classroom_id'] != $classrooms_lec[$i])){
                                        $errorS++;
                                        $noConflict=true;
                                        break;
                                    }
                                    else {
                                        $noConflict=false;
                                    }                            
                                } else{
                                    $noConflict=false;
                                }
                            }
                            if($noConflict == false)
                            {
                                $classschedule = ClassSchedule::create([
                                    "schedule_id" => $data["schedule_id"],
                                    "day" => $pd[$i][$d],
                                    "startTime" => $startTime,
                                    "endTime" => $endTime,
                                    "subject_id" => $subjects[$i],
                                    "session" => "Lecture",
                                    "faculty_id" => $faculties[$i],
                                    "classroom_id" => $classrooms_lec[$i],
                                    "user_id" => $data["user_id"]
                                ]);
                                array_push($savedCS, $classschedule->id);

                                $lecHours = $lecHours - $lecc;
                                break;
                            }
                            // if(!(($DB_StartTime <= $startTime && $DB_EndTime > $startTime) || ($DB_StartTime < $endTime && $DB_EndTime >= $endTime)))
                            else{
                                //add startTime and endTime
                                $add = strtotime($startTime);
                                $add = date("i", $add);
                                if($add >= 30)
                                $add = 60 - $add;
                                else
                                $add = 30 - $add;
                                if($add == 60)
                                $add = 30;
                                $startTime = strtotime("+$add minutes", strtotime($startTime));
                                $startTime = date('H:i', $startTime);
                                $hrs = floor($lecc);
                                $mins = ($lecc - $hrs) * 60;
                                $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                                $endTime = date('H:i', $endTime);
                            }
                        }
                        //reset startTime, then add $d
                        
                    // }
                // }
                if ($lecHours <= 0){
                    $d++;
                    if($d == count($pd[$i])){
                        $d=0;
                        $new_pd = [0 => "2022-08-01", 1 => "2022-08-02", 2 => "2022-08-03", 3 => "2022-08-04", 4 => "2022-08-05"];
                        $pd[$i] = \array_diff($new_pd, $pd[$i]);
                        $pd[$i] = array_values($pd[$i]);
                    }
                    break;
                }
                else{
                    $d++;
                    if($NONE == true && $notYet == "fourth"){
                        $lecHours = 0;
                        $d = 0;
                        $pd[$i] = $temp_pd;

                        if($errorF > $errorC && $errorF > $errorS)
                            $classschederrors_[$i]->lecerror = "Unable to find available schedule for this Lecture Session. Please try to select another Faculty.";
                        else if($errorC >= $errorF && $errorC > $errorS)
                            $classschederrors_[$i]->lecerror = "Unable to find available schedule for this Lecture Session. Please try to select another Classroom.";
                        else
                            $classschederrors_[$i]->lecerror = "Unable to find available schedule for the Lecture Session. Please try to split the session.";

                        $isError = true;

                        if($time_period[$i] == "Morning"){
                            $startTime = $startTimeM;
                            $maxTime = "12:00";
                        }
                        else if($time_period[$i] == "Afternoon"){
                            $startTime = $startTimeA;
                            $maxTime = "21:00";
                        }
                        break;
                    } else {
                    if($d == count($pd[$i])){
                        if($notYet == "first"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYet = "second";
                            $notYetTime = "first";
                        }
                        else if($notYet == "second"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTime = "second";
                            $notYet = "third";
                        }
                        else if($notYet == "third"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTime = "second";
                            $notYet = "fourth";
                        }
                        else if($notYet == "fourth"){
                            $d = 0;
                            $new_pd = [0 => "2022-08-01", 1 => "2022-08-02", 2 => "2022-08-03", 3 => "2022-08-04", 4 => "2022-08-05"];
                            $pd[$i] = \array_diff($new_pd, $pd[$i]);
                            $pd[$i] = array_values($pd[$i]);
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTime = "first";
                            $notYet = "first";
                            $NONE = true;
                        }
                        // if($notYet == false){
                        //     $notYet = true;
                        //     if($time_period[$i] == "Morning"){
                        //         $startTime = "13:00";
                        //         $maxTime = "20:00";
                        //         $endTime = strtotime("+$lecc hour", strtotime($startTime));
                        //         $endTime = date('H:i', $endTime);
                        //         $d=0;
                        //     }
                        //     else if($time_period[$i] == "Afternoon"){
                        //         $startTime = "07:00";
                        //         $maxTime = "12:00";
                        //         $endTime = strtotime("+$lecc hour", strtotime($startTime));
                        //         $endTime = date('H:i', $endTime);
                        //         $d=0;
                        //     }
                        // } else if($notYet == true){
                        //     $d = 0;
                        //     $new_pd = [0 => "2022-08-01", 1 => "2022-08-02", 2 => "2022-08-03", 3 => "2022-08-04", 4 => "2022-08-05", 5 => "2022-08-06"];
                        //     $pd[$i] = \array_diff($new_pd, $pd[$i]);
                        //     $pd[$i] = array_values($pd[$i]);
                        //     $notYet = null;
                        // } else{
                        //     break;
                        // }
                    }
                    if ($endTime > $maxTime){
                        if($notYetTime == "first"){
                            if($time_period[$i] == "Morning"){
                                $startTime = $startTimeMLec;
                                $maxTime = "12:00";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $startTime = $startTimeALec;
                                $maxTime = "21:00";
                            }
                        }
                        else if($notYetTime == "second"){
                            if($time_period[$i] == "Morning"){
                                $startTime = "07:00";
                                $maxTime = "12:00";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $startTime = "13:00";
                                $maxTime = "21:00";
                            }
                        }
                        $hrs = floor($lecc);
                        $mins = ($lecc - $hrs) * 60;
                        $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                        $endTime = date('H:i', $endTime);
                    }
                    }
                }
                    
            } 
        }

        if ($labHours > 0){
            $labb = $labHours / $split_lab[$i];

            if($labb % $split_lab[$i] == 0){
                $endTime = strtotime("+$labb hour", strtotime($startTime));
                $endTime = date('H:i', $endTime);
            } else {
                $hrs = floor($labb);
                $mins = ($labb - $hrs) * 60;
                $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                $endTime = date('H:i', $endTime);
            }

            if ($endTime > $maxTime){
                $maxT = strtotime($maxTime);
                $maxTH = date("H", $maxT);//maxTime hours
                $maxTH = 60 * $maxTH;
                $maxTM = date("i", $maxT);//maxTime minutes
                $maxTotM = $maxTH + $maxTM;//maxTime in minutes
                $endT = strtotime($endTime);
                $endTH = date("H", $endT);//endTime hours
                $endTH = 60 * $endTH;
                $endTM = date("i", $endT);//endTime minutes
                $endTotM = $endTH + $endTM;
                $diffME = $endTotM - $maxTotM;
                $endTime = date("H:i",strtotime("-$diffME minutes",strtotime($endTime)));
                $startTime = date("H:i",strtotime("-$diffME minutes",strtotime($startTime)));
    
                if($time_period[$i] == "Morning"){
                    if($labb >= 6 && $labb <= 8){
                        $startTime = "13:00";
                        $time_period[$i] = "Afternoon";
                        $startTimeALec = "13:00";
                        $hrs = floor($labb);
                        $mins = ($labb - $hrs) * 60;
                        $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                        $maxTime = "21:00";
                    }
                    else if($labb > 8){
                        $labb = $labb / 2;
                        if($labb <= 4){
                            $hrs = floor($labb);
                            $mins = ($labb - $hrs) * 60;
                            $startTime = "08:00";
                            $time_period[$i] = "Morning";
                            $startTimeMLab = "08:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "12:00";
                        } else {
                            $hrs = floor($labb);
                            $mins = ($labb - $hrs) * 60;
                            $startTime = "13:00";
                            $time_period[$i] = "Afternoon";
                            $startTimeALab = "13:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "21:00";
                        }
                    }
                }
                else if($time_period[$i] == "Afternoon"){
                    if($labb > 8){
                        $labb = $labb / 2;
                        if($labb <= 4){
                            $hrs = floor($labb);
                            $mins = ($labb - $hrs) * 60;
                            $startTime = "08:00";
                            $time_period[$i] = "Morning";
                            $startTimeMLab = "08:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "12:00";
                        } else {
                            $hrs = floor($labb);
                            $mins = ($labb - $hrs) * 60;
                            $startTime = "13:00";
                            $time_period[$i] = "Afternoon";
                            $startTimeALab = "13:00";
                            $endTime = date('H:i', strtotime("+$hrs hour +$mins minutes", strtotime($startTime)));
                            $maxTime = "21:00";
                        }
                    }
                    
                }
            }
        }
        // if ($labHours > 0){
        //     $labb = $labHours / $split_lab[$i];

        //     if($labb % $split_lab[$i] == 0){
        //         $endTime = strtotime("+$labb hour", strtotime($startTime));
        //         $endTime = date('H:i', $endTime);
        //     } else {
        //         $hrs = floor($labb);
        //         $mins = ($labb - $hrs) * 60;
        //         $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
        //         $endTime = date('H:i', $endTime);
        //     }
        // }
        $errorF = 0;$errorC = 0;$errorS = 0;
        $noConflict = null;
        while($labHours > 0){
            while($d < count($pd[$i])){
                while($endTime <= $maxTime){
                    foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                                        ->where('schedules.academicYear', $AY)
                                        ->where('schedules.semester', $sem)
                                        ->cursor() as $schedule)
                    { 
                        if( ($schedule['day'] == $pd[$i][$d]) AND
                            ( ( date('H:i', strtotime($schedule['startTime'])) >= $startTime AND
                                date('H:i', strtotime($schedule['endTime'])) <= $endTime ) OR

                                ( date('H:i', strtotime($schedule['startTime'])) <= $startTime AND
                                date('H:i', strtotime($schedule['endTime'])) >= $endTime ) OR

                                ( date('H:i', strtotime($schedule['startTime'])) >= $startTime  AND
                                date('H:i', strtotime($schedule['endTime'])) >= $endTime AND
                                date('H:i', strtotime($schedule['startTime'])) < $endTime ) OR

                                ( date('H:i', strtotime($schedule['startTime'])) <= $startTime  AND
                                date('H:i', strtotime($schedule['endTime'])) > $startTime AND
                                date('H:i', strtotime($schedule['endTime'])) <= $endTime ) )
                        )
                        {   
                            if(($schedule['faculty_id'] == $faculties[$i])) {
                                $errorF++;
                                $noConflict=true;
                                break;
                            }
                            else if(($schedule['classroom_id'] == $classrooms_lab[$i])) {
                                $errorC++;
                                $noConflict=true;
                                break;
                            }
                            else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                                ($schedule['faculty_id'] != $faculties[$i]) AND
                                ($schedule['classroom_id'] != $classrooms_lab[$i])){
                                $errorS++;
                                $noConflict=true;
                                break;
                            }
                            else {
                                $noConflict=false;
                            }                            
                        } else{
                            $noConflict=false;
                        }
                    }
                    if($noConflict == false)
                    {
                        $classschedule = ClassSchedule::create([
                            "schedule_id" => $data["schedule_id"],
                            "day" => $pd[$i][$d],
                            "startTime" => $startTime,
                            "endTime" => $endTime,
                            "subject_id" => $subjects[$i],
                            "session" => "Laboratory",
                            "faculty_id" => $faculties[$i],
                            "classroom_id" => $classrooms_lab[$i],
                            "user_id" => $data["user_id"]
                        ]);
                        array_push($savedCS, $classschedule->id);

                        $labHours = $labHours - $labb;
                        break;
                    }
                    else{
                        //add startTime and endTime
                        $add = strtotime($startTime);
                        $add = date("i", $add);
                        if($add >= 30)
                        $add = 60 - $add;
                        else
                        $add = 30 - $add;
                        if($add == 60)
                        $add = 30;
                        $startTime = strtotime("+$add minutes", strtotime($startTime));
                        $startTime = date('H:i', $startTime);
                        $hrs = floor($labb);
                        $mins = ($labb - $hrs) * 60;
                        $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                        $endTime = date('H:i', $endTime);
                    }
                }

                if ($labHours <= 0){
                    $d++;
                    break;
                }
                else{
                    $d++;
                    if($NONELAB == true && $notYetLab == "fourth"){
                        $labHours = 0;
                        $d = 0;
                        $pd[$i] = $temp_pd;

                        if($errorF > $errorC && $errorF > $errorS)
                            $classschederrors_[$i]->laberror = "Unable to find available schedule for this Laboratory Session. Please try to select another Faculty.";
                        else if($errorC >= $errorF && $errorC > $errorS)
                            $classschederrors_[$i]->laberror = "Unable to find available schedule for this Laboratory Session. Please try to select another Classroom.";
                        else
                            $classschederrors_[$i]->laberror = "Unable to find available schedule for the Laboratory Session. Please try to split the session.";
                        
                        $isError = true;

                        if($time_period[$i] == "Morning"){
                            $startTime = $startTimeM;
                            $maxTime = "12:00";
                        }
                        else if($time_period[$i] == "Afternoon"){
                            $startTime = $startTimeA;
                            $maxTime = "21:00";
                        }
                        break;
                    } else {
                    if($d == count($pd[$i])){
                        if($notYetLab == "first"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetLab = "second";
                            $notYetTimeLab = "first";
                        }
                        else if($notYetLab == "second"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTimeLab = "second";
                            $notYetLab = "third";
                        }
                        else if($notYetLab == "third"){
                            $d = 0;
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTimeLab = "second";
                            $notYetLab = "fourth";
                        }
                        else if($notYetLab == "fourth"){
                            $d = 0;
                            $new_pd = [0 => "2022-08-01", 1 => "2022-08-02", 2 => "2022-08-03", 3 => "2022-08-04", 4 => "2022-08-05"];
                            $pd[$i] = \array_diff($new_pd, $pd[$i]);
                            $pd[$i] = array_values($pd[$i]);
                            if($time_period[$i] == "Morning"){
                                $time_period[$i] = "Afternoon";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $time_period[$i] = "Morning";
                            }
                            $notYetTimeLab = "first";
                            $notYetLab = "first";
                            $NONELAB = true;
                        }
                    }
                    if ($endTime > $maxTime){
                        if($notYetTimeLab == "first"){
                            if($time_period[$i] == "Morning"){
                                $startTime = $startTimeMLab;
                                $maxTime = "12:00";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $startTime = $startTimeALab;
                                $maxTime = "21:00";
                            }
                        }
                        else if($notYetTimeLab == "second"){
                            if($time_period[$i] == "Morning"){
                                $startTime = "07:00";
                                $maxTime = "12:00";
                            }
                            else if($time_period[$i] == "Afternoon"){
                                $startTime = "13:00";
                                $maxTime = "21:00";
                            }
                        }
                        $hrs = floor($labb);
                        $mins = ($labb - $hrs) * 60;
                        $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                        $endTime = date('H:i', $endTime);
                    }
                    }
                }
                    
            } 
        }
    }
    if ($isError == false) {
        return [
            "success" => true,
            "response" => [
                "schedulessaved" => false
            ]
        ];
    } else {
        for($i = 0; $i < count($savedCS); $i++){
            $classschedule = ClassSchedule::where('id', $savedCS[$i])->delete();
        }
        return [
            "success" => false,
            "response" => [
                "scheduleserror" => $classschederrors_
            ]
        ];
    }

});

//GET CLASS SCHEDULES FOR MERGING (MAJOR)
Route::get("/classschedules/merging/{s_id}/{c_id}/{yL}/{id}/{AY}/{sem}/{type}", function ($s_id, $c_id, $yL, $id, $AY, $sem, $type) {
    $classschedules = DB::table('class_schedules')
                        ->where('class_schedules.subject_id', $s_id)
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        // ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('schedules.course_id', $c_id)
                        ->where('schedules.yearLevel', $yL)
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('class_schedules.schedule_id', '!=',$id)
                        ->where('class_schedules.session', $type)
                        ->select('class_schedules.id', 'class_schedules.startTime', 'class_schedules.endTime', 'class_schedules.day', 'schedules.block', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for Class Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES FOR MERGING (MINOR)
Route::get("/classschedules/merging/{s_id}/{id}/{AY}/{sem}/{type}", function ($s_id, $id, $AY, $sem, $type) {
    $classschedules = DB::table('class_schedules')
                        ->where('class_schedules.subject_id', $s_id)
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('class_schedules.schedule_id', '!=',$id)
                        ->where('class_schedules.session', $type)
                        ->select('class_schedules.id','class_schedules.startTime', 'class_schedules.endTime', 'class_schedules.day', 'courses.Course_Code', 'schedules.yearLevel', 'schedules.block', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for Class Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES INFO FOR PRINTING [ALL SCHEDULES]
Route::get("/classschedules/class-info/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $csDistinct = DB::table('class_schedules')
                        ->where('schedule_id', $id)
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->select('class_schedules.subject_id', 'subjects.Subject_Code','subjects.Subject_Name', 'subjects.Subject_Type', 'curricula.lec', 'curricula.lab')
                        ->distinct()
                        ->get();

    $cs = DB::table('class_schedules')
                        ->where('schedule_id', $id)
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

    $csDistinct = json_decode($csDistinct, true);
    $cs = json_decode($cs, true);

    foreach($csDistinct as $key => $value){
        $i = 0;
        $timeLec[$key][$i] = "";
        $timeLab[$key][$i] = "";
        $dayLec[$key][$i] = "";
        $dayLab[$key][$i] = "";
        $roomLec[$key][$i] = "";
        $roomLab[$key][$i] = "";
        $facultyLec[$key][$i] = "";
        $facultyLab[$key][$i] = "";
        $hours[$key][$i] = 0;
        $hoursLec[$key][$i] = 0;
        $hoursLab[$key][$i] = 0;

        foreach($cs as $key2 => $value2){
            if($value2['subject_id'] == $value['subject_id'])
            {
                switch($value2['day']){
                    case "2022-08-01":
                        $d = "M";
                        break;
                    case "2022-08-02":
                        $d = "Tu";
                        break;
                    case "2022-08-03":
                        $d = "W";
                        break;
                    case "2022-08-04":
                        $d = "Th";
                        break;
                    case "2022-08-05":
                        $d = "F";
                        break;
                    case "2022-08-06":
                        $d = "Sa";
                        break;
                    case "2022-08-07":
                        $d = "Su";
                        break;
                }
                if($value2['session'] == "Lecture"){
                    $hoursLec[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                    $timeLec[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLec[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLec[$key][$i] = $d;
                    $facultyLec[$key][$i] = $value2['Faculty_Name'];
                }
                else {
                    $hoursLab[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                    $timeLab[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLab[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLab[$key][$i] = $d;
                    $facultyLab[$key][$i] = $value2['Faculty_Name'];
                }
                $hours[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                $i++;
            }
        }
    }
    foreach($csDistinct as $key => $value){
        $timeLecc[$key] = $timeLec[$key];
        $timeLabb[$key] = $timeLab[$key];
        $timeLec[$key] = array_values(array_filter(array_unique($timeLec[$key])));
        $timeLab[$key] = array_values(array_filter(array_unique($timeLab[$key])));
        $roomLec[$key] = array_values(array_filter(array_unique($roomLec[$key])));
        $roomLab[$key] = array_values(array_filter(array_unique($roomLab[$key])));
        $facultyLec[$key] = array_values(array_filter(array_unique($facultyLec[$key])));
        $facultyLab[$key] = array_values(array_filter(array_unique($facultyLab[$key])));
    }

    foreach($csDistinct as $key => $value){
        $newhours[$key] = 0.0;
        $newhoursLec[$key] = 0.0;
        $newhoursLab[$key] = 0.0;
        $newtimeLec[$key] = "";
        $newtimeLab[$key] = "";
        $newdayLec[$key] = "";
        $newdayLab[$key] = "";
        $newroomLec[$key] = "";
        $newroomLab[$key] = "";
        $newfactLec[$key] = "";
        $newfactLab[$key] = "";

        foreach($hours[$key] as $kh => $vh){
            $newhours[$key] = $newhours[$key] + $vh;
        }
        foreach($hoursLec[$key] as $khlec => $vhlec){
            $newhoursLec[$key] = $newhoursLec[$key] + $vhlec;
        }
        foreach($hoursLab[$key] as $khlab => $vhlab){
            $newhoursLab[$key] = $newhoursLab[$key] + $vhlab;
        }
        foreach($timeLec[$key] as $kt => $vt){
            $newtimeLec[$key] = $newtimeLec[$key]."\n".$vt;
            $i=0;
            foreach($timeLecc[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLc[$i] = $dayLec[$key][$ktl];
                }
            $dayLc = array_unique($dayLc);
            foreach($dayLc as $a => $b)
                $newdayLec[$key] = $newdayLec[$key].$b." ";
            $newdayLec[$key] = trim($newdayLec[$key])."\n";
            $dayLc = [];
        }
        foreach($timeLab[$key] as $kt => $vt){
            $newtimeLab[$key] = $newtimeLab[$key]."\n".$vt;
            $i=0;
            foreach($timeLabb[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLb[$i] = $dayLab[$key][$ktl];
                }
            $dayLb = array_unique($dayLb);
            foreach($dayLb as $a => $b)
                $newdayLab[$key] = $newdayLab[$key].$b." ";
            if(trim($newdayLab[$key]) != "")
                $newdayLab[$key] = trim($newdayLab[$key])." *\n";
            $dayLb = [];
        }
        foreach($roomLec[$key] as $kr => $vr){
            $newroomLec[$key] = $newroomLec[$key]."\n".$vr;
        }
        foreach($roomLab[$key] as $kr => $vr){
            $newroomLab[$key] = $newroomLab[$key]."\n".$vr;
        }
        foreach($facultyLec[$key] as $kf => $vf){
            $newfactLec[$key] = $newfactLec[$key]."\n".$vf;
        }
        foreach($facultyLab[$key] as $kf => $vf){
            $newfactLab[$key] = $newfactLab[$key]."\n".$vf;
        }
    }

    foreach($csDistinct as $key => $value){
        $newline1 = "";
        $newline2 = "";
        $newline3 = "";
        if(!(trim($newtimeLec[$key]) == "" || trim($newtimeLab[$key]) == "")){
            if(count($roomLec[$key]) > count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            else if(count($roomLec[$key]) < count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline2 = $newline2."\n";
                $newline1 = $newline1."\n";
            }
            else {
                $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            if(count($facultyLec[$key]) > count($timeLec[$key]))
                $newline3 = $newline3."\n";
            else if(count($facultyLec[$key]) < count($timeLec[$key]))
                foreach($timeLec[$key] as $a => $b)
                    $newline3 = $newline3."\n";
            else
                $newline3 = $newline3."\n";
        }
        $temp = $value;
        if(count($roomLec[$key]) == 1 && count($roomLab[$key]) == 1)
            if(trim($newroomLec[$key]) == trim($newroomLab[$key]))
                $temp['Room'] = trim($newroomLec[$key]);
            else
                $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        else
            $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        if(count($facultyLec[$key]) == 1 && count($facultyLab[$key]) == 1)
            if(trim($newfactLec[$key]) == trim($newfactLab[$key]))
                $temp['Faculty'] = trim($newfactLec[$key]);
            else
                $temp['Faculty'] = trim($newfactLec[$key]).$newline3.trim($newfactLab[$key]);
        else
            $temp['Faculty'] = trim($newfactLec[$key]).$newline3.trim($newfactLab[$key]);
        $temp['Hours'] = $newhours[$key];
        $temp['HoursLec'] = $newhoursLec[$key];
        $temp['HoursLab'] = $newhoursLab[$key];
        $temp['Time'] = trim($newtimeLec[$key]).$newline1.trim($newtimeLab[$key]);
        $temp['Day'] = trim($newdayLec[$key]).$newline1.trim($newdayLab[$key]);
        $csDistinct[$key] = $temp;
    }

    $classschedules = $csDistinct;

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for Class Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES INFO FOR PRINTING [ONLY THE SCHEDULES BY USER]
Route::get("/classschedules/class-info-user/{id}/{AY}/{sem}/{user}", function (Request $request, $id, $AY, $sem, $user) {
    $csDistinct = DB::table('class_schedules')
                        ->where('class_schedules.schedule_id', $id)
                        ->where('class_schedules.user_id', $user)
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->select('class_schedules.subject_id', 'subjects.Subject_Code','subjects.Subject_Name', 'subjects.Subject_Type', 'curricula.lec', 'curricula.lab')
                        ->distinct()
                        ->get();

    $cs = DB::table('class_schedules')
                        ->where('class_schedules.schedule_id', $id)
                        ->where('class_schedules.user_id', $user)
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

    $csDistinct = json_decode($csDistinct, true);
    $cs = json_decode($cs, true);

    foreach($csDistinct as $key => $value){
        $i = 0;
        $timeLec[$key][$i] = "";
        $timeLab[$key][$i] = "";
        $dayLec[$key][$i] = "";
        $dayLab[$key][$i] = "";
        $roomLec[$key][$i] = "";
        $roomLab[$key][$i] = "";
        $facultyLec[$key][$i] = "";
        $facultyLab[$key][$i] = "";
        $hours[$key][$i] = 0;
        $hoursLec[$key][$i] = 0;
        $hoursLab[$key][$i] = 0;

        foreach($cs as $key2 => $value2){
            if($value2['subject_id'] == $value['subject_id'])
            {
                switch($value2['day']){
                    case "2022-08-01":
                        $d = "M";
                        break;
                    case "2022-08-02":
                        $d = "Tu";
                        break;
                    case "2022-08-03":
                        $d = "W";
                        break;
                    case "2022-08-04":
                        $d = "Th";
                        break;
                    case "2022-08-05":
                        $d = "F";
                        break;
                    case "2022-08-06":
                        $d = "Sa";
                        break;
                    case "2022-08-07":
                        $d = "Su";
                        break;
                }
                if($value2['session'] == "Lecture"){
                    $hoursLec[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                    $timeLec[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLec[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLec[$key][$i] = $d;
                    $facultyLec[$key][$i] = $value2['Faculty_Name'];
                }
                else {
                    $hoursLab[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                    $timeLab[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLab[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLab[$key][$i] = $d;
                    $facultyLab[$key][$i] = $value2['Faculty_Name'];
                }
                $hours[$key][$i] = (((int)substr($value2['endTime'], 0, 2)*60+(int)substr($value2['endTime'], 3, 2)) - ((int)substr($value2['startTime'], 0, 2)*60+(int)substr($value2['startTime'], 3, 2))) / 60;
                $i++;
            }
        }
    }
    foreach($csDistinct as $key => $value){
        $timeLecc[$key] = $timeLec[$key];
        $timeLabb[$key] = $timeLab[$key];
        $timeLec[$key] = array_values(array_filter(array_unique($timeLec[$key])));
        $timeLab[$key] = array_values(array_filter(array_unique($timeLab[$key])));
        $roomLec[$key] = array_values(array_filter(array_unique($roomLec[$key])));
        $roomLab[$key] = array_values(array_filter(array_unique($roomLab[$key])));
        $facultyLec[$key] = array_values(array_filter(array_unique($facultyLec[$key])));
        $facultyLab[$key] = array_values(array_filter(array_unique($facultyLab[$key])));
    }

    foreach($csDistinct as $key => $value){
        $newhours[$key] = 0.0;
        $newhoursLec[$key] = 0.0;
        $newhoursLab[$key] = 0.0;
        $newtimeLec[$key] = "";
        $newtimeLab[$key] = "";
        $newdayLec[$key] = "";
        $newdayLab[$key] = "";
        $newroomLec[$key] = "";
        $newroomLab[$key] = "";
        $newfactLec[$key] = "";
        $newfactLab[$key] = "";

        foreach($hours[$key] as $kh => $vh){
            $newhours[$key] = $newhours[$key] + $vh;
        }
        foreach($hoursLec[$key] as $khlec => $vhlec){
            $newhoursLec[$key] = $newhoursLec[$key] + $vhlec;
        }
        foreach($hoursLab[$key] as $khlab => $vhlab){
            $newhoursLab[$key] = $newhoursLab[$key] + $vhlab;
        }
        foreach($timeLec[$key] as $kt => $vt){
            $newtimeLec[$key] = $newtimeLec[$key]."\n".$vt;
            $i=0;
            foreach($timeLecc[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLc[$i] = $dayLec[$key][$ktl];
                }
            $dayLc = array_unique($dayLc);
            foreach($dayLc as $a => $b)
                $newdayLec[$key] = $newdayLec[$key].$b." ";
            $newdayLec[$key] = trim($newdayLec[$key])."\n";
            $dayLc = [];
        }
        foreach($timeLab[$key] as $kt => $vt){
            $newtimeLab[$key] = $newtimeLab[$key]."\n".$vt;
            $i=0;
            foreach($timeLabb[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLb[$i] = $dayLab[$key][$ktl];
                }
            $dayLb = array_unique($dayLb);
            foreach($dayLb as $a => $b)
                $newdayLab[$key] = $newdayLab[$key].$b." ";
            if(trim($newdayLab[$key]) != "")
                $newdayLab[$key] = trim($newdayLab[$key])." *\n";
            $dayLb = [];
        }
        foreach($roomLec[$key] as $kr => $vr){
            $newroomLec[$key] = $newroomLec[$key]."\n".$vr;
        }
        foreach($roomLab[$key] as $kr => $vr){
            $newroomLab[$key] = $newroomLab[$key]."\n".$vr;
        }
        foreach($facultyLec[$key] as $kf => $vf){
            $newfactLec[$key] = $newfactLec[$key]."\n".$vf;
        }
        foreach($facultyLab[$key] as $kf => $vf){
            $newfactLab[$key] = $newfactLab[$key]."\n".$vf;
        }
    }

    foreach($csDistinct as $key => $value){
        $newline1 = "";
        $newline2 = "";
        $newline3 = "";
        if(!(trim($newtimeLec[$key]) == "" || trim($newtimeLab[$key]) == "")){
            if(count($roomLec[$key]) > count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            else if(count($roomLec[$key]) < count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline2 = $newline2."\n";
                $newline1 = $newline1."\n";
            }
            else {
                $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            if(count($facultyLec[$key]) > count($timeLec[$key]))
                $newline3 = $newline3."\n";
            else if(count($facultyLec[$key]) < count($timeLec[$key]))
                foreach($timeLec[$key] as $a => $b)
                    $newline3 = $newline3."\n";
            else
                $newline3 = $newline3."\n";
        }
        $temp = $value;
        if(count($roomLec[$key]) == 1 && count($roomLab[$key]) == 1)
            if(trim($newroomLec[$key]) == trim($newroomLab[$key]))
                $temp['Room'] = trim($newroomLec[$key]);
            else
                $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        else
            $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        if(count($facultyLec[$key]) == 1 && count($facultyLab[$key]) == 1)
            if(trim($newfactLec[$key]) == trim($newfactLab[$key]))
                $temp['Faculty'] = trim($newfactLec[$key]);
            else
                $temp['Faculty'] = trim($newfactLec[$key]).$newline3.trim($newfactLab[$key]);
        else
            $temp['Faculty'] = trim($newfactLec[$key]).$newline3.trim($newfactLab[$key]);
        $temp['Hours'] = $newhours[$key];
        $temp['HoursLec'] = $newhoursLec[$key];
        $temp['HoursLab'] = $newhoursLab[$key];
        $temp['Time'] = trim($newtimeLec[$key]).$newline1.trim($newtimeLab[$key]);
        $temp['Day'] = trim($newdayLec[$key]).$newline1.trim($newdayLab[$key]);
        $csDistinct[$key] = $temp;
    }

    $classschedules = $csDistinct;

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for Class Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES BY CLASS
Route::get("/classschedules/class/{id}", function (Request $request, $id) {
    $classschedules = DB::table('class_schedules')
                        ->where('schedule_id', $id)
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->select('class_schedules.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No', 'schedules.academicYear', 'schedules.semester', 'schedules.course_id', 'schedules.yearLevel')
                        ->get();

        foreach($classschedules as $cs)
        {
            switch($cs->day){
                case "2022-08-01":
                    $cs->dayy = "Monday";
                    break;
                case "2022-08-02":
                    $cs->dayy = "Tuesday";
                    break;
                case "2022-08-03":
                    $cs->dayy = "Wednesday";
                    break;
                case "2022-08-04":
                    $cs->dayy = "Thursday";
                    break;
                case "2022-08-05":
                    $cs->dayy = "Friday";
                    break;
                case "2022-08-06":
                    $cs->dayy = "Saturday";
                    break;
                case "2022-08-07":
                    $cs->dayy = "Sunday";
                    break;
            }
            $lec = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('course_id', $cs->course_id)
                ->where('yearLevel', $cs->yearLevel)
                ->where('subject_id', $cs->subject_id)
                ->value('lec');
            $cs->lec = $lec;
            $lab = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('course_id', $cs->course_id)
                ->where('yearLevel', $cs->yearLevel)
                ->where('subject_id', $cs->subject_id)
                ->value('lab');
            $cs->lab = $lab;
        }

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for Class Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES INFO BY FACULTY FOR PRINTING
Route::get("/classschedules/faculty-info/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $csDistinct = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('faculties.id', '=', $id)
                        ->select('class_schedules.subject_id', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type', 'courses.Course_Code', 'schedules.yearLevel', 'schedules.block', 'curricula.lec', 'curricula.lab')
                        ->distinct()
                        ->get();

    $cs = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('faculties.id', '=', $id)
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No', 'courses.Course_Code', 'schedules.yearLevel', 'schedules.block', 'curricula.lec', 'curricula.lab')
                        ->get();

    foreach($csDistinct as $css)
    {
        switch($css->yearLevel){
            case "First Year":
                $css->yearLevel = 1;
                break;
            case "Second Year":
                $css->yearLevel = 2;
                break;
            case "Third Year":
                $css->yearLevel = 3;
                break;
            case "Fourth Year":
                $css->yearLevel = 4;
                break;
        }
    }

    foreach($cs as $css)
    {
        switch($css->yearLevel){
            case "First Year":
                $css->yearLevel = 1;
                break;
            case "Second Year":
                $css->yearLevel = 2;
                break;
            case "Third Year":
                $css->yearLevel = 3;
                break;
            case "Fourth Year":
                $css->yearLevel = 4;
                break;
        }
    }

    $csDistinct = json_decode($csDistinct, true);
    $cs = json_decode($cs, true);

    foreach($csDistinct as $key => $value){
        $i = 0;
        $timeLec[$key][$i] = "";
        $timeLab[$key][$i] = "";
        $dayLec[$key][$i] = "";
        $dayLab[$key][$i] = "";
        $roomLec[$key][$i] = "";
        $roomLab[$key][$i] = "";
        
        foreach($cs as $key2 => $value2){
            if($value2['subject_id'] == $value['subject_id'] && $value2['yearLevel'] == $value['yearLevel'] && $value2['block'] == $value['block'])
            {
                switch($value2['day']){
                    case "2022-08-01":
                        $d = "M";
                        break;
                    case "2022-08-02":
                        $d = "Tu";
                        break;
                    case "2022-08-03":
                        $d = "W";
                        break;
                    case "2022-08-04":
                        $d = "Th";
                        break;
                    case "2022-08-05":
                        $d = "F";
                        break;
                    case "2022-08-06":
                        $d = "Sa";
                        break;
                    case "2022-08-07":
                        $d = "Su";
                        break;
                }
                if($value2['session'] == "Lecture"){
                    $timeLec[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLec[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLec[$key][$i] = $d;
                }
                else {
                    $timeLab[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $roomLab[$key][$i] = $value2['Building_No']."-".$value2['Classroom_No'];
                    $dayLab[$key][$i] = $d;
                }
                $i++;
            }
        }
    }
    foreach($csDistinct as $key => $value){
        $timeLecc[$key] = $timeLec[$key];
        $timeLabb[$key] = $timeLab[$key];
        $timeLec[$key] = array_values(array_filter(array_unique($timeLec[$key])));
        $timeLab[$key] = array_values(array_filter(array_unique($timeLab[$key])));
        $roomLec[$key] = array_values(array_filter(array_unique($roomLec[$key])));
        $roomLab[$key] = array_values(array_filter(array_unique($roomLab[$key])));
    }
    
    foreach($csDistinct as $key => $value){
        $newtimeLec[$key] = "";
        $newtimeLab[$key] = "";
        $newdayLec[$key] = "";
        $newdayLab[$key] = "";
        $newroomLec[$key] = "";
        $newroomLab[$key] = "";
        foreach($timeLec[$key] as $kt => $vt){
            $newtimeLec[$key] = $newtimeLec[$key]."\n".$vt;
            $i=0;
            foreach($timeLecc[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLc[$i] = $dayLec[$key][$ktl];
                }
            $dayLc = array_unique($dayLc);
            foreach($dayLc as $a => $b)
                $newdayLec[$key] = $newdayLec[$key].$b." ";
            $newdayLec[$key] = trim($newdayLec[$key])."\n";
            $dayLc = [];
        }
        foreach($timeLab[$key] as $kt => $vt){
            $newtimeLab[$key] = $newtimeLab[$key]."\n".$vt;
            $i=0;
            foreach($timeLabb[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLb[$i] = $dayLab[$key][$ktl];
                }
            $dayLb = array_unique($dayLb);
            foreach($dayLb as $a => $b)
                $newdayLab[$key] = $newdayLab[$key].$b." ";
            if(trim($newdayLab[$key]) != "")
                $newdayLab[$key] = trim($newdayLab[$key])." *\n";
            $dayLb = [];
        }
        foreach($roomLec[$key] as $kr => $vr){
            $newroomLec[$key] = $newroomLec[$key]."\n".$vr;
        }
        foreach($roomLab[$key] as $kr => $vr){
            $newroomLab[$key] = $newroomLab[$key]."\n".$vr;
        }
    }

    foreach($csDistinct as $key => $value){
        $newline1 = "";
        $newline2 = "";
        if(!(trim($newtimeLec[$key]) == "" || trim($newtimeLab[$key]) == "")){
            if(count($roomLec[$key]) > count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            else if(count($roomLec[$key]) < count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline2 = $newline2."\n";
                $newline1 = $newline1."\n";
            }
            else {
                $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
        }
        $temp = $value;
        if(count($roomLec[$key]) == 1 && count($roomLab[$key]) == 1)
            if(trim($newroomLec[$key]) == trim($newroomLab[$key]))
                $temp['Room'] = trim($newroomLec[$key]);
            else
                $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        else
            $temp['Room'] = trim($newroomLec[$key]).$newline2.trim($newroomLab[$key]);
        $temp['Time'] = trim($newtimeLec[$key]).$newline1.trim($newtimeLab[$key]);
        $temp['Day'] = trim($newdayLec[$key]).$newline1.trim($newdayLab[$key]);
        $temp['Class'] = $value['Course_Code']." ".$value['yearLevel']."-".$value['block'];
        $csDistinct[$key] = $temp;
    }

    $classschedules = $csDistinct;

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "Unable to print Schedule for this Faculty. No saved Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES BY FACULTY
Route::get("/classschedules/faculty/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $classschedules = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('faculties.id', '=', $id)
                        ->select('class_schedules.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No', 'courses.Course_Code', 'schedules.academicYear', 'schedules.semester', 'schedules.yearLevel', 'schedules.block')
                        ->get();

    foreach($classschedules as $cs)
    {
        switch($cs->yearLevel){
            case "First Year":
                $cs->yearLevel = 1;
                break;
            case "Second Year":
                $cs->yearLevel = 2;
                break;
            case "Third Year":
                $cs->yearLevel = 3;
                break;
            case "Fourth Year":
                $cs->yearLevel = 4;
                break;
        }
        switch($cs->day){
            case "2022-08-01":
                $cs->dayy = "Monday";
                break;
            case "2022-08-02":
                $cs->dayy = "Tuesday";
                break;
            case "2022-08-03":
                $cs->dayy = "Wednesday";
                break;
            case "2022-08-04":
                $cs->dayy = "Thursday";
                break;
            case "2022-08-05":
                $cs->dayy = "Friday";
                break;
            case "2022-08-06":
                $cs->dayy = "Saturday";
                break;
            case "2022-08-07":
                $cs->dayy = "Sunday";
                break;
        }
        $lec = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('subject_id', $cs->subject_id)
                ->value('lec');
            $cs->lec = $lec;
            $lab = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('subject_id', $cs->subject_id)
                ->value('lab');
            $cs->lab = $lab;
    }

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for this Faculty Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES INFO BY CLASSROOM FOR PRINTING
Route::get("/classschedules/classroom-info/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $csDistinct = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('curricula', function ($join) use ($AY, $sem){
                            $join->on('subjects.id', '=', 'curricula.subject_id')
                            ->where('curricula.academicYear', $AY)
                            ->where('curricula.semester', $sem);
                        })
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('classrooms.id', '=', $id)
                        ->select('class_schedules.subject_id', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type', 'courses.Course_Code', 'schedules.yearLevel', 'schedules.block', 'curricula.lec', 'curricula.lab')
                        ->distinct()
                        ->get();

    $cs = DB::table('class_schedules')
                ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                ->join('courses', 'schedules.course_id', '=', 'courses.id')
                ->join('curricula', function ($join) use ($AY, $sem){
                    $join->on('subjects.id', '=', 'curricula.subject_id')
                    ->where('curricula.academicYear', $AY)
                    ->where('curricula.semester', $sem);
                })
                ->where('schedules.academicYear', $AY)
                ->where('schedules.semester', $sem)
                ->where('classrooms.id', '=', $id)
                ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No', 'courses.Course_Code', 'schedules.yearLevel', 'schedules.block', 'curricula.lec', 'curricula.lab')
                ->get();

    foreach($csDistinct as $css)
    {
        switch($css->yearLevel){
            case "First Year":
                $css->yearLevel = 1;
                break;
            case "Second Year":
                $css->yearLevel = 2;
                break;
            case "Third Year":
                $css->yearLevel = 3;
                break;
            case "Fourth Year":
                $css->yearLevel = 4;
                break;
        }
    }

    foreach($cs as $css)
    {
    switch($css->yearLevel){
        case "First Year":
            $css->yearLevel = 1;
            break;
        case "Second Year":
            $css->yearLevel = 2;
            break;
        case "Third Year":
            $css->yearLevel = 3;
            break;
        case "Fourth Year":
            $css->yearLevel = 4;
            break;
        }
    }

    $csDistinct = json_decode($csDistinct, true);
    $cs = json_decode($cs, true);

    foreach($csDistinct as $key => $value){
    $i = 0;
    $timeLec[$key][$i] = "";
    $timeLab[$key][$i] = "";
    $dayLec[$key][$i] = "";
    $dayLab[$key][$i] = "";
    $facultyLec[$key][$i] = "";
    $facultyLab[$key][$i] = "";

        foreach($cs as $key2 => $value2){
            if($value2['subject_id'] == $value['subject_id'] && $value2['yearLevel'] == $value['yearLevel'] && $value2['block'] == $value['block'])
            {
                switch($value2['day']){
                    case "2022-08-01":
                        $d = "M";
                        break;
                    case "2022-08-02":
                        $d = "Tu";
                        break;
                    case "2022-08-03":
                        $d = "W";
                        break;
                    case "2022-08-04":
                        $d = "Th";
                        break;
                    case "2022-08-05":
                        $d = "F";
                        break;
                    case "2022-08-06":
                        $d = "Sa";
                        break;
                    case "2022-08-07":
                        $d = "Su";
                        break;
                }
                if($value2['session'] == "Lecture"){
                    $timeLec[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $dayLec[$key][$i] = $d;
                    $facultyLec[$key][$i] = $value2['Faculty_Name'];
                }
                else {
                    $timeLab[$key][$i] = substr($value2['startTime'], 0, -3)."-".substr($value2['endTime'], 0, -3);
                    $dayLab[$key][$i] = $d;
                    $facultyLab[$key][$i] = $value2['Faculty_Name'];
                }
                $i++;
            }
        }
    }

    foreach($csDistinct as $key => $value){
        $timeLecc[$key] = $timeLec[$key];
        $timeLabb[$key] = $timeLab[$key];
        $timeLec[$key] = array_values(array_filter(array_unique($timeLec[$key])));
        $timeLab[$key] = array_values(array_filter(array_unique($timeLab[$key])));
        $facultyLec[$key] = array_values(array_filter(array_unique($facultyLec[$key])));
        $facultyLab[$key] = array_values(array_filter(array_unique($facultyLab[$key])));
    }

    foreach($csDistinct as $key => $value){
        $newfactLec[$key] = "";
        $newfactLab[$key] = "";
        $newtimeLec[$key] = "";
        $newtimeLab[$key] = "";
        $newdayLec[$key] = "";
        $newdayLab[$key] = "";
        foreach($timeLec[$key] as $kt => $vt){
            $newtimeLec[$key] = $newtimeLec[$key]."\n".$vt;
            $i=0;
            foreach($timeLecc[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLc[$i] = $dayLec[$key][$ktl];
                }
            $dayLc = array_unique($dayLc);
            foreach($dayLc as $a => $b)
                $newdayLec[$key] = $newdayLec[$key].$b." ";
            $newdayLec[$key] = trim($newdayLec[$key])."\n";
            $dayLc = [];
        }
        foreach($timeLab[$key] as $kt => $vt){
            $newtimeLab[$key] = $newtimeLab[$key]."\n".$vt;
            $i=0;
            foreach($timeLabb[$key] as $ktl => $vtl)
                if($vtl == $vt){
                    $i++;
                    $dayLb[$i] = $dayLab[$key][$ktl];
                }
            $dayLb = array_unique($dayLb);
            foreach($dayLb as $a => $b)
                $newdayLab[$key] = $newdayLab[$key].$b." ";
            if(trim($newdayLab[$key]) != "")
                $newdayLab[$key] = trim($newdayLab[$key])." *\n";
            $dayLb = [];
        }
        foreach($facultyLec[$key] as $kr => $vr){
            $newfactLec[$key] = $newfactLec[$key]."\n".$vr;
        }
        foreach($facultyLab[$key] as $kr => $vr){
            $newfactLab[$key] = $newfactLab[$key]."\n".$vr;
        }
    }

    foreach($csDistinct as $key => $value){
        $newline1 = "";
        $newline2 = "";
        if(!(trim($newtimeLec[$key]) == "" || trim($newtimeLab[$key]) == "")){
            if(count($facultyLec[$key]) > count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
            else if(count($facultyLec[$key]) < count($timeLec[$key])){
                foreach($timeLec[$key] as $a => $b)
                    $newline2 = $newline2."\n";
                $newline1 = $newline1."\n";
            }
            else {
                $newline1 = $newline1."\n";
                $newline2 = $newline2."\n";
            }
        }
        $temp = $value;
        if(count($facultyLec[$key]) == 1 && count($facultyLab[$key]) == 1)
            if(trim($newfactLec[$key]) == trim($newfactLab[$key]))
                $temp['Faculty'] = trim($newfactLec[$key]);
            else
                $temp['Faculty'] = trim($newfactLec[$key]).$newline2.trim($newfactLab[$key]);
        else
            $temp['Faculty'] = trim($newfactLec[$key]).$newline2.trim($newfactLab[$key]);
        $temp['Time'] = trim($newtimeLec[$key]).$newline1.trim($newtimeLab[$key]);
        $temp['Day'] = trim($newdayLec[$key]).$newline1.trim($newdayLab[$key]);
        $temp['Class'] = $value['Course_Code']." ".$value['yearLevel']."-".$value['block'];
        $csDistinct[$key] = $temp;
    }

    $classschedules = $csDistinct;

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "Unable to print Schedule for this Classroom. No saved Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET CLASS SCHEDULES BY CLASSROOM
Route::get("/classschedules/classroom/{id}/{AY}/{sem}", function (Request $request, $id, $AY, $sem) {
    $classschedules = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->where('schedules.academicYear', $AY)
                        ->where('schedules.semester', $sem)
                        ->where('classrooms.id', '=', $id)
                        ->select('class_schedules.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No', 'courses.Course_Code', 'schedules.academicYear', 'schedules.semester', 'schedules.yearLevel', 'schedules.block')
                        ->get();

    foreach($classschedules as $cs)
    {
        switch($cs->yearLevel){
            case "First Year":
                $cs->yearLevel = 1;
                break;
            case "Second Year":
                $cs->yearLevel = 2;
                break;
            case "Third Year":
                $cs->yearLevel = 3;
                break;
            case "Fourth Year":
                $cs->yearLevel = 4;
                break;
        }
        switch($cs->day){
            case "2022-08-01":
                $cs->dayy = "Monday";
                break;
            case "2022-08-02":
                $cs->dayy = "Tuesday";
                break;
            case "2022-08-03":
                $cs->dayy = "Wednesday";
                break;
            case "2022-08-04":
                $cs->dayy = "Thursday";
                break;
            case "2022-08-05":
                $cs->dayy = "Friday";
                break;
            case "2022-08-06":
                $cs->dayy = "Saturday";
                break;
            case "2022-08-07":
                $cs->dayy = "Sunday";
                break;
        }
        $lec = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('subject_id', $cs->subject_id)
                ->value('lec');
            $cs->lec = $lec;
            $lab = DB::table('curricula')
                ->where('academicYear', $cs->academicYear)
                ->where('semester', $cs->semester)
                ->where('subject_id', $cs->subject_id)
                ->value('lab');
            $cs->lab = $lab;
    }

    if (empty($classschedules)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No records for this Classroom Schedules found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedules" => $classschedules
        ]
    ];
});

//GET A SINGLE CLASSSCHEDULE
Route::get("/classschedules/{id}", function (Request $request, $id) {
    $classschedule = DB::table('class_schedules')
                    ->where('class_schedules.id', $id)
                    ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                    ->select('class_schedules.session', 'class_schedules.startTime', 'class_schedules.endTime', 'class_schedules.day', 'subjects.Subject_Type', 'class_schedules.subject_id', 'class_schedules.faculty_id', 'class_schedules.classroom_id')
                    ->first();

    // $classschedule = $classschedules->find($id);

    if (empty($classschedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classschedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedule" => $classschedule
        ]
    ];
});

//GET A SINGLE CLASSSCHEDULE (FOR VIEWING MODE)
Route::get("/classschedules-viewing/{id}", function (Request $request, $id) {
    $classschedule = DB::table('class_schedules')
                    ->where('class_schedules.id', $id)
                    ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                    ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                    ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                    ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Code', 'subjects.Subject_Type', 'classrooms.Building_No', 'classrooms.Classroom_No', 'classrooms.Classroom_Type', 'faculties.Faculty_ID', 'faculties.Faculty_Name')
                    ->first();

    switch ($classschedule->day){
        case "2022-08-01":
            $classschedule->day = "Monday";
            break;
        case "2022-08-02":
            $classschedule->day = "Tuesday";
            break;
        case "2022-08-03":
            $classschedule->day = "Wednesday";
            break;
        case "2022-08-04":
            $classschedule->day = "Thursday";
            break;
        case "2022-08-05":
            $classschedule->day = "Friday";
            break;
        case "2022-08-06":
            $classschedule->day = "Saturday";
            break;
    }
    $classschedule->startTime = date_format(new DateTimeImmutable ($classschedule->startTime), 'h:i a');
    $classschedule->endTime = date_format(new DateTimeImmutable ($classschedule->endTime), 'h:i a');

    if (empty($classschedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Classschedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedule" => $classschedule
        ]
    ];
});

//GET A SINGLE CLASSSCHEDULE FOR MERGING
Route::get("/classschedules-m/{id}", function (Request $request, $id) {
    $classschedule = DB::table('class_schedules')
                    ->where('class_schedules.id', $id)
                    ->first();

    if (empty($classschedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Class Schedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedule" => $classschedule
        ]
    ];
});

//GET FACULTY BY CLASS SCHEDULE
Route::get("/classschedules-m-faculty/{id}", function (Request $request, $id) {
    $classschedule = DB::table('class_schedules')
                    ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                    ->where('class_schedules.id', $id)
                    ->select('class_schedules.faculty_id', 'faculties.Faculty_Name', 'faculties.Faculty_ID')
                    ->first();

    // $classschedule = $classschedules->find($id);

    if (empty($classschedule)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No record for Class Schedule found."
            ]
        ];
    }

    return [
        "success" => true,
        "response" => [
            "classschedule" => $classschedule
        ]
    ];
});

//UPDATE RECORD FOR CLASS SCHEDULE
Route::put('/classschedules/update/{id}/{AY}/{sem}', function (Request $request, $id, $AY, $sem) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;

    // $schedules = ClassSchedule::chunkById(100, function($schedules) use ($data, $i, $count, $noConflict){
       foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                ->where('schedules.academicYear', $AY)
                ->where('schedules.semester', $sem)
                ->select('class_schedules.*')
                ->cursor() as $schedule) {
                if($schedule['id'] <> $id){

                    if( ($schedule['day'] == $data['day']) AND 
                        ( ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime'])) AND
                        date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) OR

                        ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime'])) AND
                        date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) ) OR
            
                        ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime']))  AND
                        date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) AND
                        date('H:i', strtotime($schedule['startTime'])) < date('H:i', strtotime($data['endTime'])) ) OR
            
                        ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime']))  AND
                        date('H:i', strtotime($schedule['endTime'])) > date('H:i', strtotime($data['startTime'])) AND
                        date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) )
                    )
                    {
                        if(($schedule['faculty_id'] == $data['faculty_id'])) {
                            $noConflict=true;
                            $conflict = 1;
                            return [
                                "success" => false,
                                "response" => [
                                    "error" => "Selected Faculty is not available on that time."
                                ]
                            ];
                            return false;
                                
                        }
                        else if(($schedule['classroom_id'] == $data['classroom_id'])) {
                            $noConflict=true;
                            $conflict = 2;
                            return [
                                "success" => false,
                                "response" => [
                                    "error" => "Selected Classroom is already occupied."
                                ]
                            ];
                            return false;
                        }
                        else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                                ($schedule['faculty_id'] != $data['faculty_id']) AND
                                ($schedule['classroom_id'] != $data['classroom_id'])){
                            $noConflict=true;
                            $conflict = 3;
                            return [
                                "success" => false,
                                "response" => [
                                    "error" => "A Schedule was already saved on this Time range of this Class."
                                ]
                            ];
                            return false;
                        }
                        else {
                            $noConflict=false;
                        }
                    } else{
                        $noConflict=false;
                    }
                }
            
        }

        // foreach (ClassSchedule::cursor() as $schedule)
        // {
        //     if($schedule['id'] <> $id){
        //         dump($schedule);
        //         if( ($schedule['day'] == $data['day']) AND 
        //         ( ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime'])) AND
        //             date('H:i', strtotime($schedule['endTime'])) > date('H:i', strtotime($data['startTime'])) ) OR
        //         ( date('H:i', strtotime($schedule['startTime'])) < date('H:i', strtotime($data['endTime']))  AND
        //             date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) ) )
        //         )
        //         {
        //             if(($schedule['faculty_id'] == $data['faculty_id'])) {
        //                 $noConflict=true;
        //                 $conflict = 1;
        //                 return [
        //                     "success" => false,
        //                     "response" => [
        //                         "error" => "Selected Faculty is not available on that time."
        //                     ]
        //                 ];
        //                 return false;
                            
        //             }
        //             else if(($schedule['classroom_id'] == $data['classroom_id'])) {
        //                 $noConflict=true;
        //                 $conflict = 2;
        //                 return [
        //                     "success" => false,
        //                     "response" => [
        //                         "error" => "Selected Classroom is already occupied."
        //                     ]
        //                 ];
        //                 return false;
        //             }
        //             else if(($schedule['schedule_id'] == $data['schedule_id']) AND
        //                     ($schedule['faculty_id'] != $data['faculty_id']) AND
        //                     ($schedule['classroom_id'] != $data['classroom_id'])){
        //                 $noConflict=true;
        //                 $conflict = 3;
        //                 return [
        //                     "success" => false,
        //                     "response" => [
        //                         "error" => "A Schedule was already saved on this Time range of this Class."
        //                     ]
        //                 ];
        //                 return false;
        //             }
        //             else {
        //                 $noConflict=false;
        //             }
        //         } else{
        //             $noConflict=false;
        //         }
        //     }
        // }

    if($noConflict == false)
    {
        $classschedule = ClassSchedule::find($id);

        foreach ($data as $key => $value) {
            $classschedule->{$key} = $value;
        }

        $result = $classschedule->save();

        return ["success" => $result, "response" => ["classschedule" => $classschedule]];
        
    } else {
        if($conflict == 1) {
            return [
                "success" => false,
                "response" => [
                    "error" => "Selected Faculty is not available on that time."
                ]
            ];
        }
        else if ($conflict == 2) {
            return [
                "success" => false,
                "response" => [
                    "error" => "Selected Classroom is already occupied."
                ]
            ];
        }
        else if ($conflict == 3) {
            return [
                "success" => false,
                "response" => [
                    "error" => "This Schedule is not available for this Class."
                ]
            ];
        }
    }
});

//DELETE A CLASS SCHEDULE
Route::delete('/classschedules/delete/{id}', function (Request $request, $id) {
    $classschedule = ClassSchedule::find($id);

    if (empty($classschedule)) {
        $success = false;
        $response = ["error" => "Class Schedule could not be deleted."];
    } else {
        $success = $classschedule->delete();
        $response = ["message" => "Class Schedule deleted!"];
    }

    return ["success" => $success, "response" => $response];
});

//DELETE ALL SCHEDULES OF A CLASS FOR RESET TO ENABLE GENERATE SCHEDULES
Route::delete('/classschedules/delete-all/{sched}/{user}', function ($sched, $user) {
    $classschedule = DB::table('class_schedules')
                        ->where('schedule_id', $sched)
                        ->where('user_id', $user)
                        ->delete();

    return ["success" => true, "response" => ["message" => "All of the Saved Schedules on this Class are deleted!"]];
});

//MERGING OF CLASS SCHEDULES
Route::put('/classschedules/merge/{id}/{id2}/{AY}/{sem}/{changed}', function (Request $request, $id, $id2, $AY, $sem, $changed) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;

    foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
            ->where('schedules.academicYear', $AY)
            ->where('schedules.semester', $sem)
            ->cursor() as $schedule) {

        if( ($schedule['day'] == $data['day']) AND 
        ( ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime'])) AND
        date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) OR

        ( date('H:i', strtotime($schedule['startTime'])) >= date('H:i', strtotime($data['startTime']))  AND
        date('H:i', strtotime($schedule['endTime'])) >= date('H:i', strtotime($data['endTime'])) AND
        date('H:i', strtotime($schedule['startTime'])) < date('H:i', strtotime($data['endTime'])) ) OR

        ( date('H:i', strtotime($schedule['startTime'])) <= date('H:i', strtotime($data['startTime']))  AND
        date('H:i', strtotime($schedule['endTime'])) > date('H:i', strtotime($data['startTime'])) AND
        date('H:i', strtotime($schedule['endTime'])) <= date('H:i', strtotime($data['endTime'])) ) )
        )
        {
            if(($changed == 'changed') AND ($schedule['faculty_id'] == $data['faculty_id'])) {
                $noConflict=true;
                $conflict = 1;
                break;
            }
            else if(($schedule['schedule_id'] == $data['schedule_id'])){
                $noConflict=true;
                $conflict = 2;
                break;
            }
            else {
                $noConflict=false;
            }
        } else{
            $noConflict=false;
        }
    }

    if($noConflict == false)
    {
        $classschedule = DB::table('class_schedules')
                            ->where('id', $id)
                            ->update(['day' => $data['day'],
                                      'startTime' => $data['startTime'],
                                      'endTime' => $data['endTime'],
                                      'subject_id' => $data['subject_id'],
                                      'faculty_id' => $data['faculty_id'],
                                      'classroom_id' => $data['classroom_id']]);

        if($changed == 'changed') {
        $classschedule2 = DB::table('class_schedules')
                            ->where('id', $id2)
                            ->update(['day' => $data['day'],
                                    'startTime' => $data['startTime'],
                                    'endTime' => $data['endTime'],
                                    'subject_id' => $data['subject_id'],
                                    'faculty_id' => $data['faculty_id'],
                                    'classroom_id' => $data['classroom_id']]);
        }

        return ["success" => true, "response" => ["classschedule" => $classschedule]];
    } else {
        // if($conflict == 1) {
        //     return [
        //         "success" => false,
        //         "response" => [
        //             "error" => "Merging can't be done. The Faculty of the selected Class is not available for this schedule."
        //         ]
        //     ];
        // }
        // else if ($conflict == 2) {
        //     return [
        //         "success" => false,
        //         "response" => [
        //             "error" => "Merging can't be done. The selected schedule is already occupied for this Class."
        //         ]
        //     ];
        // }
        $noConflictz = null;
        $range = strtotime($data['endTime']) - strtotime($data['startTime']);
        $hrs = date('H', $range);
        $mins = date('i', $range);
        $startTime = '07:30';
        $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
        $endTime = date('H:i', $endTime);
        $days = ["2022-08-01", "2022-08-02", "2022-08-03", "2022-08-04", "2022-08-05", "2022-08-06"];
        $d = 0;
        $maxTime = '12:00';
        while($d < count($days)){
            while($endTime <= $maxTime){
                foreach (ClassSchedule::join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                                    ->where('schedules.academicYear', $AY)
                                    ->where('schedules.semester', $sem)
                                    ->cursor() as $schedule)
                {
                    if( ($schedule['day'] == $days[$d]) AND
                        ( ( date('H:i', strtotime($schedule['startTime'])) >= $startTime AND
                            date('H:i', strtotime($schedule['endTime'])) <= $endTime ) OR

                            ( date('H:i', strtotime($schedule['startTime'])) <= $startTime AND
                            date('H:i', strtotime($schedule['endTime'])) >= $endTime ) OR

                            ( date('H:i', strtotime($schedule['startTime'])) >= $startTime  AND
                            date('H:i', strtotime($schedule['endTime'])) >= $endTime AND
                            date('H:i', strtotime($schedule['startTime'])) < $endTime ) OR

                            ( date('H:i', strtotime($schedule['startTime'])) <= $startTime  AND
                            date('H:i', strtotime($schedule['endTime'])) > $startTime AND
                            date('H:i', strtotime($schedule['endTime'])) <= $endTime ) )
                    )
                    {   
                        if(($schedule['faculty_id'] == $data['faculty_id'])) {
                            // $errorF++;
                            $noConflictz=true;
                            break;
                        }
                        else if(($schedule['classroom_id'] == $data['classroom_id'])) {
                            // $errorC++;
                            $noConflictz=true;
                            break;
                        }
                        else if(($schedule['schedule_id'] == $data['schedule_id']) OR ($schedule['schedule_id'] == $data['schedule_id2'])){
                            // $errorS++;
                            $noConflictz=true;
                            break;
                        }
                        else {
                            $noConflictz=false;
                        }                            
                    } else{
                        $noConflictz=false;
                    }
                }
                if($noConflictz == false)
                {
                    $classschedule = DB::table('class_schedules')
                                        ->where('id', $id)
                                        ->update(['day' => $days[$d],
                                                'startTime' => $startTime,
                                                'endTime' => $endTime,
                                                'subject_id' => $data['subject_id'],
                                                'faculty_id' => $data['faculty_id'],
                                                'classroom_id' => $data['classroom_id']]);
                    $classschedule = DB::table('class_schedules')
                                        ->where('id', $id2)
                                        ->update(['day' => $days[$d],
                                                'startTime' => $startTime,
                                                'endTime' => $endTime,
                                                'subject_id' => $data['subject_id'],
                                                'faculty_id' => $data['faculty_id'],
                                                'classroom_id' => $data['classroom_id']]);
                    $d = 6;
                    break;
                }
                else{
                    //add startTime and endTime
                    $add = strtotime($startTime);
                    $add = date("i", $add);
                    if($add >= 30)
                    $add = 60 - $add;
                    else
                    $add = 30 - $add;
                    if($add == 60)
                    $add = 30;
                    $startTime = strtotime("+$add minutes", strtotime($startTime));
                    $startTime = date('H:i', $startTime);
                    $endTime = strtotime("+$hrs hour +$mins minutes", strtotime($startTime));
                    $endTime = date('H:i', $endTime);
                }
            }
            if($maxTime == '12:00')
                $maxTime = '21:00';
            else{
                $maxTime = '12:00';
                $d++;
            }
        }
    }
});