// const API_BASE = "https://calm-lake-70586.herokuapp.com/api";
const API_BASE = "http://127.0.0.1:8000/api";

export default {
    UpdateUser: (name = "", username = "", userN = "") => {
        if (
            name == "" ||
            username == "" ||
            userN == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/user/update/" + userN, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ name, username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.user;
            } else {
                // alert("Username is already taken.");
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchUser: user => {
        return fetch(API_BASE + "/user/" + user)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.user;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    DeleteUser: id => {
        if (id != null) {
            return fetch(API_BASE + "/users/delete/" + id, {
                method: "DELETE"
            });
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success){
                //         alert("User removed Successfully.");
                //         return true;
                //     } else {
                //         throw data.response.error;
                //     }
                // })
                // .catch(err => {
                //     alert(err);
                // });
        }
    },
    FetchSubjects: () => {
        return fetch(API_BASE + "/subjects/all")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchSubjectsByDepartment: id => {
        return fetch(API_BASE + "/subjects/departments/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    GetNumberofSubjectsByDepartment: id => {
        return fetch(API_BASE + "/num-subjects/departments/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.countOf;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchSubjectsByCollege: id => {
        return fetch(API_BASE + "/subjects/colleges/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchMESubjectsByCourseEDIT: (id, s_id) => {
        return fetch(API_BASE + "/subjects/ME/courseEDIT/" + id + "/" + s_id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchMESubjectsByCourse: (id, me) => {
        return fetch(API_BASE + "/subjects/ME/course/" + id + "/" + me)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchCoreSubjectsByDepartment: (id) => {
        return fetch(API_BASE + "/subjects/Core/department/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchGECSubjectsByCollege: id => {
        return fetch(API_BASE + "/subjects/GEC/college/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },

    FetchSubject: id => {
        if (id != null) {
            return fetch(API_BASE + "/subjects/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.subject;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },

    CreateSubject: (Subject_Code = "", Subject_Name = "", Subject_Type = "", course_id = "", department_id = "") => {
        if (
            Subject_Code == "" || 
            Subject_Name == "" || 
            Subject_Type == "" ||
            department_id == ""

        ) {
            return false;
        }
       
        return fetch(API_BASE + "/subjects/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Subject_Code, Subject_Name, Subject_Type, course_id, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.subject;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    DeleteSubject: id => {
        if (id != null) {
            return fetch(API_BASE + "/subjects/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Subject removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    UpdateSubject: (Subject_Code = "", Subject_Name = "", Subject_Type = "", course_id = "", id = "", department_id) => {
        if (
            Subject_Code == "" ||
            Subject_Name == "" ||
            Subject_Type == "" ||
            id == "" ||
            department_id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/subjects/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Subject_Code, Subject_Name, Subject_Type, course_id, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.subject;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },

    FetchColleges: () => {
        return fetch(API_BASE + "/colleges/all")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.colleges;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },

    FetchCollege: id => {
        return fetch(API_BASE + "/colleges/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.college;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },

    FetchDepartments: id => {
        return fetch(API_BASE + "/departments/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.departments;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },

    FetchDepartment: id => {
        return fetch(API_BASE + "/departments/user/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.department;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },

    CreateDepartment: (college_id = "", Department_Name = "", Department_Chair = "", username = "") => {
        if (
            college_id == "" || 
            Department_Name == "" ||
            Department_Chair == "" ||
            username == ""

        ) {
            return false;
        }

        return fetch(API_BASE + "/departments/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ college_id, Department_Name, Department_Chair, username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.department;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },

    UpdateDepartment: (Department_Name = "", Department_Chair = "", username = "", id = "") => {
        if (
            Department_Name == "" ||
            Department_Chair == "" ||
            username == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/departments/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Department_Name, Department_Chair, username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.department;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },

    DeleteDepartment: id => {
        if (id != null) {
            return fetch(API_BASE + "/departments/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Department removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },

    FetchCourses: () => {
        return fetch(API_BASE + "/courses/all")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.courses;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchCoursesWithColleges: () => {
        return fetch(API_BASE + '/courses/colleges/all')
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    return data.response.courses
                } else {
                    throw data.response.error
                }
            })
            .catch((err) => {
                alert(err)
            })
    },
    FetchCoursesByCollege: id => {
        return fetch(API_BASE + '/courses/departments/' + id)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    return data.response.courses
                } else {
                    throw data.response.error
                }
            })
            .catch((err) => {
                alert(err)
            })
    },
    FetchCoursesByDept: id => {
        return fetch(API_BASE + '/courses/department/' + id)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    return data.response.courses
                } else {
                    throw data.response.error
                }
            })
            .catch((err) => {
                alert(err)
            })
    },
    FetchCourse: id => {
        if (id != null) {
            return fetch(API_BASE + "/courses/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.course;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    CreateCourse: (Course_Code = "", Course_Name = "", department_id = "") => {
        if (
            Course_Code == "" || 
            Course_Name == "" ||
            department_id == ""

        ) {
            return false;
        }

        return fetch(API_BASE + "/courses/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Course_Code, Course_Name, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.course;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    DeleteCourse: id => {
        if (id != null) {
            return fetch(API_BASE + "/courses/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Course removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    UpdateCourse: (Course_Code = "", Course_Name = "", department_id = "", id = "") => {
        if (
            Course_Code == "" ||
            Course_Name == "" ||
            department_id == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/courses/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Course_Code, Course_Name, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.course;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    UpdateCourseByDept: (Course_Code = "", Course_Name = "", id = "") => {
        if (
            Course_Code == "" ||
            Course_Name == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/courses/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Course_Code, Course_Name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.course;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchFaculties: () => {
        return fetch(API_BASE + "/faculties/all")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.faculties;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchFacultiesByCollege: id => {
        return fetch(API_BASE + "/faculties/college/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.faculties;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchFacultiesByDepartment: id => {
        return fetch(API_BASE + "/faculties/department/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.faculties;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchFacultiesByDepartmentSubject: subj => {
        return fetch(API_BASE + "/faculties/department-subject/" + subj)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.faculties;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchFacultiesWithColleges: () => {
        return fetch(API_BASE + '/faculties/colleges/all')
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    return data.response.faculties
                } else {
                    throw data.response.error
                }
            })
            .catch((err) => {
                alert(err)
            })
    },

    FetchFaculty: id => {
        if (id != null) {
            return fetch(API_BASE + "/faculties/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.faculty;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchFacultyCurriculum: id => {
        if (id != null) {
            return fetch(API_BASE + "/faculties-curriculum/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.faculty;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchFacultiesBySubject: id => {
        if (id != null) {
            return fetch(API_BASE + "/faculties-subject/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.faculty;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },

    CreateFaculty: (Faculty_ID = "", Faculty_Name = "", department_id = "") => {
        if (
            Faculty_ID == "" || 
            Faculty_Name == "" ||
            department_id == ""

        ) {
            return false;
        }

        return fetch(API_BASE + "/faculties/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Faculty_ID, Faculty_Name, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.faculty;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    DeleteFaculty: id => {
        if (id != null) {
            return fetch(API_BASE + "/faculties/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Faculty removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    UpdateFaculty: (Faculty_ID = "", Faculty_Name = "", department_id = "", id = "") => {
        if (
            Faculty_ID == "" ||
            Faculty_Name == "" ||
            department_id == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/faculties/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Faculty_ID, Faculty_Name, department_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.faculty;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    UpdateFacultyDept: (Faculty_ID = "", Faculty_Name = "", id = "") => {
        if (
            Faculty_ID == "" ||
            Faculty_Name == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/faculties/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Faculty_ID, Faculty_Name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.faculty;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchClassrooms: () => {
        return fetch(API_BASE + "/classrooms/all")
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.classrooms;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchClassroomsByCollege: id => {
        return fetch(API_BASE + "/classrooms/college/" + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.classrooms;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchClassroomsPerCollegeBySubject: s_id => {
        return fetch(API_BASE + "/classrooms/college-subject/" + s_id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.classrooms;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchClassroomsPerCurriculumBySubject: s_id => {
        return fetch(API_BASE + "/classrooms/curriculum-subject/" + s_id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                return data.response.classrooms;
            } else {
                throw data.response.error;
            }

        })
        .catch(err => {
            alert(err);
        }); 
    },
    FetchClassroomsWithColleges: () => {
        return fetch(API_BASE + '/classrooms/colleges/all')
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    return data.response.classrooms
                } else {
                    throw data.response.error
                }
            })
            .catch((err) => {
                alert(err)
            })
    },

    FetchClassroom: id => {
        if (id != null) {
            return fetch(API_BASE + "/classrooms/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classroom;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassroomCurriculum: id => {
        if (id != null) {
            return fetch(API_BASE + "/classrooms-curriculum/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classroom;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassroomCurriculumLab: id => {
        if (id != null) {
            return fetch(API_BASE + "/classrooms-curriculum/" + id + "/lab")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classroom;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassroomsBySubject: (AY, sem, id) => {
        if (id != null) {
            return fetch(API_BASE + "/classrooms-subject/" + AY + "/" + sem + "/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classroom;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },

    CreateClassroom: (Building_No = "", Classroom_No = "", Classroom_Type = "", college_id = "") => {
        if (
            Building_No == "" ||
            Classroom_No == "" || 
            Classroom_Type == "" ||
            college_id == ""

        ) {
            return false;
        }

        return fetch(API_BASE + "/classrooms/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Building_No, Classroom_No, Classroom_Type, college_id})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.classroom;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    DeleteClassroom: id => {
        if (id != null) {
            return fetch(API_BASE + "/classrooms/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Classroom removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    UpdateClassroom: (Building_No = "", Classroom_No = "", Classroom_Type = "", id = "") => {
        if (
            Building_No == "" ||
            Classroom_No == "" ||
            Classroom_Type == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/classrooms/update/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ Building_No, Classroom_No, Classroom_Type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.classroom;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    CreateCurriculum: (academicYear = "", semester = "", course_id = "", yearLevel = "", subject_id = "", Units = "", lec = "", lab = "", faculties_id = "", classrooms_id = "", user_id = "") => {
        if (
            academicYear == "" ||
            semester == "" ||
            course_id == "" || 
            yearLevel == "" ||
            subject_id == "" ||
            Units == "" ||
            lec == "" ||
            faculties_id == "" ||
            classrooms_id == "" ||
            user_id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/curricula/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ academicYear, semester, course_id, yearLevel, subject_id, Units, lec, lab, faculties_id, classrooms_id, user_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New record has been saved.");
                return data.response.curriculum;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchCurricula: (AY, sem, id, user) => {
        return fetch(API_BASE + "/curricula/" + AY + "/" + sem + "/" + id + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaCore: (AY, sem, id, user) => {
        return fetch(API_BASE + "/curricula/core/" + AY + "/" + sem + "/" + id + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaYL: (AY, sem, id, yL, user) => {
        return fetch(API_BASE + "/curricula/YL/" + AY + "/" + sem + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaList: (AY, sem, user) => {
        return fetch(API_BASE + "/curricula/" + AY + "/" + sem + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaListCore: (AY, sem, user) => {
        return fetch(API_BASE + "/curriculaCore/" + AY + "/" + sem + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaListCollege: (AY, sem, user) => {
        return fetch(API_BASE + "/curricula-college/" + AY + "/" + sem + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaListCourse: (AY, sem, user, c) => {
        return fetch(API_BASE + "/curricula-course/" + AY + "/" + sem + "/" + user + "/" + c)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaListYearLevel: (AY, sem, user, c) => {
        return fetch(API_BASE + "/curricula-yl/" + AY + "/" + sem + "/" + user + "/" + c)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaYearLevel: (AY, sem, id, yL, user) => {
        
        return fetch(API_BASE + "/curriculayl/" + AY + "/" + sem + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaNotSaved: (AY, sem, sched, id, yL, user) => {
        
        return fetch(API_BASE + "/curricula-notsaved/" + AY + "/" + sem + "/" + sched + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaStatus: (AY, sem, sched, id, yL, user) => {
        
        return fetch(API_BASE + "/curricula-status/" + AY + "/" + sem + "/" + sched + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curriculaStatus;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchSubjectsCurriculaStatus: (AY, sem, sched, id, yL, user) => {
        
        return fetch(API_BASE + "/subjects/curricula-status/" + AY + "/" + sem + "/" + sched + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curriculaStatus;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculaStatusOther: (AY, sem, sched, id, yL, user) => {
        
        return fetch(API_BASE + "/curricula-status-other/" + AY + "/" + sem + "/" + sched + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curriculaStatus;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchSubjectsCurriculaYearLevel: (AY, sem, id, yL, user) => {
        
        return fetch(API_BASE + "/curriculayl-subjects/" + AY + "/" + sem + "/" + id + "/" + yL + "/" + user)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.curricula;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchCurriculum: id => {
        if (id != null) {
            return fetch(API_BASE + "/curriculum/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.curriculum;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    DeleteCurriculum: id => {
        if (id != null) {
            return fetch(API_BASE + "/curricula/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Curriculum removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    UpdateCurriculum: (AY = "", sem = "", yearLevel = "", subject_id = "", Units = "", lec = "", lab = "", cid = "", faculties_id, classrooms_id, id = "") => {
        if (
            yearLevel == "" ||
            subject_id == "" ||
            Units == "" ||
            lec == "" ||
            AY == "" ||
            sem == "" ||
            cid == "" ||
            faculties_id == "" ||
            classrooms_id == "" ||
            id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/curricula/update/" + AY + "/" + sem + "/" + cid + "/" + id, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ yearLevel, subject_id, Units, lec, lab, faculties_id, classrooms_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.curriculum;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchCoursesCollege: id => {
        
            return fetch(API_BASE + "/courses/college/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.courses;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        
    },
    CreateSchedule: (academicYear = "", semester = "", course_id = "", yearLevel = "", block = "") => {
        if (
            academicYear == "" ||
            semester == "" ||
            course_id == "" ||
            yearLevel == "" || 
            block == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/schedules/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ academicYear, semester, course_id, yearLevel, block})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // alert("New record has been saved.");
                return data.response.schedule;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            // alert(err);
        });
    },
    FetchSchedule: (AY, sem, id, yL, b) => {
        if (id != null) {
            return fetch(API_BASE + "/schedules/" + AY + "/" + sem + "/" + id + "/" + yL + "/" + b)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.schedule;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchScheduleInfo: id => {
        if (id != null) {
            return fetch(API_BASE + "/schedules/info/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.schedule;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchSchedulesByCollege: (id, AY, sem) => {
        if (id != null && AY != null && sem != null) {
            return fetch(API_BASE + "/schedules/view/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.schedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchMajorSubjects: (m, id) => {
        return fetch(API_BASE + "/subjects/major/" + m + "/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchNotMajorSubjects: id => {
        return fetch(API_BASE + "/subjects/notmajor/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.subjects;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchMajorFaculties: id => {
        return fetch(API_BASE + "/faculties/major/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.faculties;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    FetchMajorClassrooms: id => {
        return fetch(API_BASE + "/classrooms/major/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.response.classrooms;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err)
        });
    
    },
    CreateClassSchedule: (AY = "", sem = "", schedule_id = "", day = "", startTime = "", endTime = "", subject_id = "", faculty_id = "", classroom_id = "", user_id = "") => {
        if (
            AY == "" ||
            sem == "" ||
            schedule_id == "" ||
            day == "" || 
            startTime == "" ||
            endTime == "" ||
            subject_id == "" ||
            faculty_id == "" ||
            classroom_id == "" ||
            user_id == ""

        ) {
            return false;
        }

        return fetch(API_BASE + "/classschedules/create/" + AY + "/" + sem, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ schedule_id, day, startTime, endTime, subject_id, faculty_id, classroom_id, user_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("New Class Schedule has been saved.");
                return data.response.classschedule;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    GenerateClassSchedule: (AY = "", sem = "", schedule_id = "", StartTimeM = "", StartTimeA = "", subjects = "", lec = "", lab = "", split_lec = "", split_lab = "", prefDays = "", time_period = "", faculties = "", classrooms_lec = "", classrooms_lab = "", user_id = "") => {
        if (
            AY == "" ||
            sem == "" ||
            schedule_id == "" ||
            StartTimeM == "" ||
            StartTimeA == "" ||
            subjects == "" ||
            lec == "" ||
            split_lec == "" ||
            split_lab == "" ||
            prefDays == "" ||
            time_period == "" ||
            faculties == "" ||
            classrooms_lec == "" ||
            classrooms_lab == "" ||
            user_id == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/classschedules/generate/" + AY + "/" + sem, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ schedule_id, StartTimeM, StartTimeA, subjects, lec, lab, split_lec, split_lab, prefDays, time_period, faculties, classrooms_lec, classrooms_lab, user_id })
        })
        // .then(async response => {
        //     try {
        //      const data = await response.json()
        //      console.log('response data?', data)
        //    } catch(error) {
        //      console.log('Error happened here!')
        //      console.error(error)
        //    }
        //   })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Generating of Schedules completed.");
            } else {
                return data.response.scheduleserror;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    FetchClassSchedulesMergingMajor: (s_id, c_id, yL, id) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/merging/" + s_id + "/" + c_id + "/" + yL + "/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassSchedulesMergingMinor: (s_id, id) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/merging/" + s_id + "/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassSchedules: id => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/class/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                // alert(err)
            });
        }
    },
    FetchClassSchedulesInfo: (id, AY, sem) => {
        if (id != null && AY != null && sem != null) {
            return fetch(API_BASE + "/classschedules/class-info/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                // alert(err)
            });
        }
    },
    FetchClassSchedulesInfoUser: (id, AY, sem, user) => {
        if (id != null && AY != null && sem != null && user != null) {
            return fetch(API_BASE + "/classschedules/class-info-user/" + id + "/" + AY + "/" + sem + "/" + user)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                // alert(err)
            });
        }
    },
    FetchFacultySchedules: (id, AY, sem) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/faculty/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchFacultySchedulesInfo: (id, AY, sem) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/faculty-info/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                // alert(err)
            });
        }
    },
    FetchClassroomSchedules: (id, AY, sem) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/classroom/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassroomSchedulesInfo: (id, AY, sem) => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/classroom-info/" + id + "/" + AY + "/" + sem)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedules;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                // alert(err)
            });
        }
    },
    FetchClassSchedule: id => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedule;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassScheduleViewing: id => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules-viewing/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedule;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    FetchClassScheduleM: id => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules-m/" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return data.response.classschedule;
                } else {
                    throw data.response.error;
                }
            })
            .catch(err => {
                alert(err)
            });
        }
    },
    UpdateClassSchedule: (schedule_id = "", day = "", startTime = "", endTime = "", subject_id = "", faculty_id = "", classroom_id = "", id = "", AY = "", sem = "") => {
        if (
            schedule_id == "" ||
            day == "" || 
            startTime == "" ||
            endTime == "" ||
            subject_id == "" ||
            faculty_id == "" ||
            classroom_id == "" ||
            id == "" ||
            AY == "" ||
            sem == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/classschedules/update/" + id + "/" + AY + "/" + sem, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ schedule_id, day, startTime, endTime, subject_id, faculty_id, classroom_id})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Updated Successfully.");
                return data.response.classschedule;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },
    DeleteClassSchedule: id => {
        if (id != null) {
            return fetch(API_BASE + "/classschedules/delete/" + id, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("Class Schedule removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    DeleteClassSchedulesReset: (sched, user) => {
        if (sched != null) {
            return fetch(API_BASE + "/classschedules/delete-all/" + sched + "/" + user, {
                method: "DELETE"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        alert("All of the Schedules on this Class are removed Successfully.");
                        return true;
                    } else {
                        throw data.response.error;
                    }
                })
                .catch(err => {
                    alert(err);
                });
        }
    },
    MergeClassSchedule: (schedule_id = "", day = "", startTime = "", endTime = "", subject_id = "", faculty_id = "", classroom_id = "", id = "", schedule_id2 = "", day2 = "", startTime2 = "", endTime2 = "", subject_id2 = "", faculty_id2 = "", classroom_id2 = "", id2 = "") => {
        if (
            schedule_id == "" ||
            day == "" || 
            startTime == "" ||
            endTime == "" ||
            subject_id == "" ||
            faculty_id == "" ||
            classroom_id == "" ||
            id == "" ||
            schedule_id2 == "" ||
            day2 == "" || 
            startTime2 == "" ||
            endTime2 == "" ||
            subject_id2 == "" ||
            faculty_id2 == "" ||
            classroom_id2 == "" ||
            id2 == ""
        ) {
            return false;
        }

        return fetch(API_BASE + "/classschedules/merge/" + id + "/" + id2, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ schedule_id, day, startTime, endTime, subject_id, faculty_id, classroom_id, schedule_id2, day2, startTime2, endTime2, subject_id2, faculty_id2, classroom_id2 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Merged Successfully.");
                return data.response.classschedule;
            } else {
                throw data.response.error;
            }
        })
        .catch(err => {
            alert(err);
        });
    },

}