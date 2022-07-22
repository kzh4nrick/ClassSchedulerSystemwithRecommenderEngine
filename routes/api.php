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

// GET ALL MAJOR/MINOR SUBJECTS BY COURSE
Route::get("/subjects/major/course/{id}/{m}", function ($id, $m) {
    $subjects = DB::table('subjects')
                ->where('course_id', $id)
                ->where('Subject_Type', $m)
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

// GET ALL MINOR SUBJECTS BY COLLEGE
Route::get("/subjects/minor/college/{id}", function ($id) {
    $subjects = DB::table('subjects')
                ->join('courses', 'subjects.course_id', '=', 'courses.id')
                ->join('departments', 'courses.department_id', '=', 'departments.id')
                ->where('departments.college_id', $id)
                ->where('subjects.Subject_Type', '=','minor')
                ->select('subjects.*')
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

    if(!Subject::where('Subject_Code','=', $data['Subject_Code'])->exists()) {

        $subject = Subject::create([
            "Subject_Code" => $data["Subject_Code"],
            "Subject_Name" => $data["Subject_Name"],
            "Subject_Type" => $data["Subject_Type"],
            "course_id" => $data["course_id"]
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

//GET ALL FACULTIES BY DEPARTMENT
Route::get("/faculties/department/{id}", function ($id) {
    $faculties = DB::table('faculties')
        ->where('department_id', $id)
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

//GET ALL FACULTIES BY SUBJECT-DEPARTMENT
Route::get("/faculties/department-subject/{subj}", function ($subj) {
    $faculties = DB::table('faculties')
        ->join('departments', 'faculties.department_id', '=', 'departments.id')
        ->join('courses', 'departments.id', '=', 'courses.department_id')
        ->join('subjects', 'courses.id', '=', 'subjects.course_id')
        ->where('subjects.id', '=', $subj)
        ->select('faculties.*')
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
    $classrooms = Classroom::where('college_id', $id)->get();

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

    if(!Curriculum::where('subject_id','=', $data['subject_id'])->where('course_id', '=', $data['course_id'])->exists()) {
        $curriculum = Curriculum::create([
            "course_id" => $data["course_id"],
            "yearLevel" => $data["yearLevel"],
            "subject_id" => $data["subject_id"],
            "Units" => $data["Units"],
            "lec" => $data["lec"],
            "lab" => $data["lab"],
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
                "error" => "Invalid! Subject is already added."
            ]
        ];
    }

});

// GET ALL CURRICULA BY COURSE
Route::get("/curricula/{id}/{user}", function ($id, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name')
        ->where('curricula.course_id', $id)
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

// GET ALL CURRICULA BY COURSE AND YEAR LEVEL
Route::get("/curricula/YL/{id}/{user}/{yL}", function ($id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('curricula.yearLevel', $yL)
        ->where('curricula.course_id', $id)
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

// GET ALL CURRICULA LIST BY COLLEGE
Route::get("/curricula/{user}", function ($user) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
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

// GET COLLEGE LIST BY CURRICULA OF USER
Route::get("/curricula-college/{user}", function ($user) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
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
Route::get("/curricula-course/{user}/{c}", function ($user, $c) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
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
Route::get("/curricula-yl/{user}/{c}", function ($user, $c) {
    $curricula = DB::table('curricula')
        ->join('courses', 'curricula.course_id', '=', 'courses.id')
        ->join('departments', 'courses.department_id', '=', 'departments.id')
        ->join('colleges', 'departments.college_id', '=', 'colleges.id')
        ->where('user_id', $user)
        ->where('courses.id', $c)
        ->distinct()
        ->get(['curricula.yearLevel']);

    return [
        "success" => true,
        "response" => [
            "curricula" => $curricula
        ]
    ];
});

// GET ALL CURRICULA BY COURSE & YEAR LEVEL
Route::get("/curriculayl/{id}/{yL}", function ($id, $yL) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.*', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
        ->where('curricula.course_id', $id)
        ->where('curricula.yearLevel', $yL)
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

// GET ALL SUBJECTS BY COURSE & YEAR LEVEL & USER ON CURRICULA
Route::get("/curriculayl/{id}/{yL}/{user}", function ($id, $yL, $user) {
    $curricula = DB::table('curricula')
        ->join('subjects', 'curricula.subject_id', '=', 'subjects.id')
        ->select('curricula.subject_id', 'subjects.Subject_Code', 'subjects.Subject_Name', 'subjects.Subject_Type')
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

//GET A SINGLE CURRICULUM
Route::get("/curriculum/{id}", function (Request $request, $id) {
    $curriculum = Curriculum::find($id);

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
Route::put('/curricula/update/{cid}/{id}', function (Request $request, $cid, $id) {
    $data = $request->all();

    if(!Curriculum::where('subject_id','=', $data['subject_id'])
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

//CREATE RECORD FOR SCHEDULE
Route::post('/schedules/create', function (Request $request) {
    $data = $request->all();

    if(!Schedule::where('course_id','=', $data['course_id'])->where('yearLevel','=', $data['yearLevel'])->where('block','=', $data['block'])->exists()) {

        $schedule = Schedule::create([
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
Route::get("/schedules/{id}/{yL}/{b}", function (Request $request, $id, $yL, $b) {
    $schedule = Schedule::where('course_id', $id)
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
Route::post('/classschedules/create', function (Request $request) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;

    // $schedules = ClassSchedule::chunkById(100, function($schedules) use ($data, $i, $count, $noConflict){
    if ($data['startTime'] < $data['endTime']) {
        foreach (ClassSchedule::cursor() as $schedule) {
            if( ($schedule['day'] == $data['day']) AND 
                ((($schedule['startTime'] <= $data['startTime']) AND
                ($schedule['endTime'] > $data['startTime'])) OR
                (($schedule['startTime'] < $data['endTime']) AND
                ($schedule['endTime'] >= $data['endTime'])))
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
                            "error" => "This Schedule is not available for this Class."
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
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "Invalid Time! The Start Time must be less than to End Time."
            ]
        ];
    }

    if($noConflict == false)
    {
        $classschedule = ClassSchedule::create([
            "schedule_id" => $data["schedule_id"],
            "day" => $data["day"],
            "startTime" => $data["startTime"],
            "endTime" => $data["endTime"],
            "subject_id" => $data["subject_id"],
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
                    "error" => "This Schedule is not available for this Class."
                ]
            ];
        }
    }
});

//GET CLASS SCHEDULES FOR MERGING (MAJOR)
Route::get("/classschedules/merging/{s_id}/{c_id}/{yL}/{id}", function ($s_id, $c_id, $yL, $id) {
    $classschedules = DB::table('class_schedules')
                        ->where('class_schedules.subject_id', $s_id)
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        // ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('schedules.course_id', $c_id)
                        ->where('schedules.yearLevel', $yL)
                        ->where('class_schedules.schedule_id', '!=',$id)
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
Route::get("/classschedules/merging/{s_id}/{id}", function ($s_id, $id) {
    $classschedules = DB::table('class_schedules')
                        ->where('class_schedules.subject_id', $s_id)
                        ->join('schedules', 'class_schedules.schedule_id', '=', 'schedules.id')
                        ->join('courses', 'schedules.course_id', '=', 'courses.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('class_schedules.schedule_id', '!=',$id)
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

//GET CLASS SCHEDULES BY CLASS
Route::get("/classschedules/class/{id}", function (Request $request, $id) {
    $classschedules = DB::table('class_schedules')
                        ->where('schedule_id', $id)
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
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

//GET CLASS SCHEDULES BY FACULTY
Route::get("/classschedules/faculty/{id}", function (Request $request, $id) {
    $classschedules = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('faculties.id', '=', $id)
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

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

//GET CLASS SCHEDULES BY CLASSROOM
Route::get("/classschedules/classroom/{id}", function (Request $request, $id) {
    $classschedules = DB::table('class_schedules')
                        ->join('subjects', 'class_schedules.subject_id', '=', 'subjects.id')
                        ->join('faculties', 'class_schedules.faculty_id', '=', 'faculties.id')
                        ->join('classrooms', 'class_schedules.classroom_id', '=', 'classrooms.id')
                        ->where('classrooms.id', '=', $id)
                        ->select('class_schedules.*', 'subjects.Subject_Name', 'subjects.Subject_Type', 'faculties.Faculty_Name', 'faculties.Faculty_ID', 'classrooms.Building_No', 'classrooms.Classroom_No')
                        ->get();

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
                    ->select('class_schedules.startTime', 'class_schedules.endTime', 'class_schedules.day', 'subjects.Subject_Type', 'class_schedules.subject_id', 'class_schedules.faculty_id', 'class_schedules.classroom_id')
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

//GET A SINGLE CLASSSCHEDULE FOR MERGING
Route::get("/classschedules-m/{id}", function (Request $request, $id) {
    $classschedule = DB::table('class_schedules')
                    ->where('class_schedules.id', $id)
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
Route::put('/classschedules/update/{id}', function (Request $request, $id) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;

    // $schedules = ClassSchedule::chunkById(100, function($schedules) use ($data, $i, $count, $noConflict){

        foreach (ClassSchedule::cursor() as $schedule) {
            if($schedule['id'] <> $id){
                if( ($schedule['day'] == $data['day']) AND 
                    ((($schedule['startTime'] <= $data['startTime']) AND
                    ($schedule['endTime'] > $data['startTime'])) OR
                    (($schedule['startTime'] < $data['endTime']) AND
                    ($schedule['endTime'] >= $data['endTime'])))
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
                                "error" => "This Schedule is not available for this Class."
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

//MERGING OF CLASS SCHEDULES
Route::put('/classschedules/merge/{id}/{id2}', function (Request $request, $id, $id2) {
    $data = $request->all();
    $noConflict = null;
    $conflict = null;

    // $schedules = ClassSchedule::chunkById(100, function($schedules) use ($data, $i, $count, $noConflict){

        foreach (ClassSchedule::cursor() as $schedule) {
            if($schedule['id'] <> $id && $schedule['id'] <> $id2){
                if( ($schedule['day'] == $data['day2']) AND 
                    ((($schedule['startTime'] <= $data['startTime2']) AND
                    ($schedule['endTime'] > $data['startTime2'])) OR
                    (($schedule['startTime'] < $data['endTime2']) AND
                    ($schedule['endTime'] >= $data['endTime2'])))
                )
                {
                    if(($schedule['faculty_id'] == $data['faculty_id2'])) {
                        $noConflict=true;
                        $conflict = 1;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "Selected Faculty is not available on that time."
                        //     ]
                        // ];
                        return false;
                            
                    }
                    else if(($schedule['classroom_id'] == $data['classroom_id2'])) {
                        $noConflict=true;
                        $conflict = 2;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "Selected Classroom is already occupied."
                        //     ]
                        // ];
                        return false;
                    }
                    else if(($schedule['schedule_id'] == $data['schedule_id2']) AND
                            ($schedule['faculty_id'] != $data['faculty_id2']) AND
                            ($schedule['classroom_id'] != $data['classroom_id2'])){
                        $noConflict=true;
                        $conflict = 3;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "This Schedule is not available for this Class."
                        //     ]
                        // ];
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

    if($noConflict == false)
    {
        // $classschedule = ClassSchedule::find($id);

        // foreach ($data as $key => $value) {
        //     $classschedule->{$key} = $value;
        // }

        // $result = $classschedule->save();

        // return ["success" => $result, "response" => ["classschedule" => $classschedule]];

        $classschedule = DB::table('class_schedules')
                            ->where('id', $id)
                            ->update(['day' => $data['day2'],
                                      'startTime' => $data['startTime2'],
                                      'endTime' => $data['endTime2'],
                                      'subject_id' => $data['subject_id2'],
                                      'faculty_id' => $data['faculty_id2'],
                                      'classroom_id' => $data['classroom_id2']]);

        return ["success" => true, "response" => ["classschedule" => $classschedule]];
        
    } else {
        foreach (ClassSchedule::cursor() as $schedule) {
            if($schedule['id'] <> $id && $schedule['id'] <> $id2){
                if( ($schedule['day'] == $data['day']) AND 
                    ((($schedule['startTime'] <= $data['startTime']) AND
                    ($schedule['endTime'] > $data['startTime'])) OR
                    (($schedule['startTime'] < $data['endTime']) AND
                    ($schedule['endTime'] >= $data['endTime'])))
                )
                {
                    if(($schedule['faculty_id'] == $data['faculty_id'])) {
                        $noConflict=true;
                        $conflict = 1;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "Selected Faculty is not available on that time."
                        //     ]
                        // ];
                        return false;
                            
                    }
                    else if(($schedule['classroom_id'] == $data['classroom_id'])) {
                        $noConflict=true;
                        $conflict = 2;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "Selected Classroom is already occupied."
                        //     ]
                        // ];
                        return false;
                    }
                    else if(($schedule['schedule_id'] == $data['schedule_id']) AND
                            ($schedule['faculty_id'] != $data['faculty_id']) AND
                            ($schedule['classroom_id'] != $data['classroom_id'])){
                        $noConflict=true;
                        $conflict = 3;
                        // return [
                        //     "success" => false,
                        //     "response" => [
                        //         "error" => "This Schedule is not available for this Class."
                        //     ]
                        // ];
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
        if($noConflict == false)
        {
            $classschedule = DB::table('class_schedules')
                                ->where('id', $id2)
                                ->update(['day' => $data['day'],
                                        'startTime' => $data['startTime'],
                                        'endTime' => $data['endTime'],
                                        'subject_id' => $data['subject_id'],
                                        'faculty_id' => $data['faculty_id'],
                                        'classroom_id' => $data['classroom_id']]);

            return ["success" => true, "response" => ["classschedule" => $classschedule]];
        }
        else {
            if($conflict == 1) {
                return [
                    "success" => false,
                    "response" => [
                        "error" => "Merging can't be done. The Faculty of the selected Class is not available for this schedule."
                    ]
                ];
            }
            else if ($conflict == 2) {
                return [
                    "success" => false,
                    "response" => [
                        "error" => "Merging can't be done. The Classroom of the selected Class is not available for this schedule."
                    ]
                ];
            }
            else if ($conflict == 3) {
                return [
                    "success" => false,
                    "response" => [
                        "error" => "Merging can't be done. This Schedule is not available on the selected Class Schedule."
                    ]
                ];
            }
        }
    }
});