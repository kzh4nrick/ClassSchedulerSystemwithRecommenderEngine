<template>
        <header class="bg-white shadow">
            <div
                class="max-w-7xl mx-auto py-3 px-4 text-center sm:px-6 lg:px-8"
            >
                <h1 v-if="userType == 'reg'" class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-sky-900">
                    UPDATE & MANAGING OF DEGREE PROGRAMS OF THE {{ college.College_Name }}
                </h1>
                <h1 v-else class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-sky-900">
                    UPDATE & MANAGING OF DEGREE PROGRAMS OF THE {{ college.Department_Name }}
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 mt-5 md:mt-0">
                        <form v-if="userType == 'reg'" class="form" @submit.prevent="AddNewCourse()">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="course_code"
                                                class="block text-sm font-medium text-gray-700"
                                                >Degree Program Code</label
                                            >
                                            <input
                                                v-if="courseId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Course_Code.$model"
                                                @keyup="v$.Course_Code.$touch()"
                                                @blur="v$.Course_Code.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Course_Code"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Course_Code.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Course_Code.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="course_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Degree Program Name</label
                                            >
                                            <input
                                                v-if="courseId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Course_Name.$model"
                                                @keyup="v$.Course_Name.$touch()"
                                                @blur="v$.Course_Name.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Course_Name"
                                                autocomplete="Course_name"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Course_Name.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Course_Name.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="college"
                                                class="block text-sm font-medium text-gray-700"
                                                >College Departments</label
                                            >
                                            <select
                                                v-if="courseId == false"
                                                required
                                                v-model="v$.department_id.$model"
                                                @keyup="v$.department_id.$touch()"
                                                @blur="v$.department_id.$touch()"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                            >
                                                <option disabled value="">Lists of Departments</option>
                                                <option v-for="lists in departmentList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        >
                                                        {{ lists.Department_Name }}
                                                </option>
                                            </select>
                                            <select
                                                v-else
                                                disabled
                                                v-model="model.department_id"
                                                class="cursor-not-allowed mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                            >
                                                <option disabled value="">Lists of Departments</option>
                                                <option v-for="lists in departmentList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        >
                                                        {{ lists.Department_Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        v-if="v$.$invalid || courseId != false"
                                        disabled
                                        type="submit" :value="'Save'"
                                        class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                    <input
                                        v-else
                                        @click="postAjaxForm"
                                        type="submit" :value="'Save'"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                </div>
                            </div>
                        </form>
                        <form v-else class="form" @submit.prevent="AddNewCourse()">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="course_code"
                                                class="block text-sm font-medium text-gray-700"
                                                >Degree Program Code</label
                                            >
                                            <input
                                                v-if="courseId == false"
                                                type="text"
                                                required
                                                v-model.trim="z$.Course_Code.$model"
                                                @keyup="z$.Course_Code.$touch()"
                                                @blur="z$.Course_Code.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Course_Code"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="z$.Course_Code.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ z$.Course_Code.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="course_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Degree Program Name</label
                                            >
                                            <input
                                                v-if="courseId == false"
                                                type="text"
                                                required
                                                v-model.trim="z$.Course_Name.$model"
                                                @keyup="z$.Course_Name.$touch()"
                                                @blur="z$.Course_Name.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Course_Name"
                                                autocomplete="Course_name"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="z$.Course_Name.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ z$.Course_Name.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        v-if="z$.$invalid || courseId != false"
                                        disabled
                                        type="submit" :value="'Save'"
                                        class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                    <input
                                        v-else
                                        @click="postAjaxForm"
                                        type="submit" :value="'Save'"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-span-2">
                        <div class="flex flex-col">
                            <div
                                class="-my-2 overflow-x-auto h-96 sm:-mx-6 lg:-mx-8"
                            >
                                <div
                                    class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
                                >
                                    <div
                                        class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                                    >
                                        <table
                                            class="min-w-full divide-y divide-gray-200"
                                        >
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th v-if="userType =='reg'"
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Department Name
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Degree Program Code
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Degree Program Name
                                                    </th>
                                                    <th
                                                    >
                                                        <span class="sr-only"
                                                            >Edit</span
                                                        >
                                                    </th>
                                                    <th
                                                    >
                                                    <div class="relative right-16 w-20">
                                                        <input type="text" class="bg-gray-50 border border-gray-300 text-left text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-36 h-8" placeholder="Search..." v-model="searchQuery" />
                                                        <div class="flex absolute inset-y-0 left-24 items-center pl-5 pointer-events-none">
                                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                        </div>
                                                    </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-gray-200"
                                            >
                                                <tr
                                                    v-for="list in searchedCourses"
                                                    :key="list.id"
                                                    v-if="courseList.length > 0"
                                                    class="hover:bg-gray-100"
                                                >
                                                    <td v-if="userType == 'reg'">
                                                        <td v-if="courseId == list.id" class="bg-gray-100 pl-3 w-fit">
                                                            <select
                                                                v-model="model2.department_id"
                                                                :id="'course-edit-${list.id}'"
                                                                class="border-0 py-1.5 text-sm font-medium text-sky-600 bg-gray-100 w-full"
                                                            >
                                                                <option disabled value="">Lists of Departments</option>
                                                                <option v-for="lists in departmentList"
                                                                        v-bind="lists.id"
                                                                        :value="lists.id"
                                                                        >
                                                                        {{  lists.Department_Name }}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td v-else @click="setToEditing(list.id)"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >           
                                                                <span v-html="highlightMatches(list.Department_Name)"></span>
                                                        </td>
                                                    </td>
                                                    <!-- <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            {{
                                                                list.college.College_Name
                                                            }}
                                                    </td> -->
                                                    <td v-if="courseId == list.id" class="bg-gray-100 pl-3 w-1/6">
                                                        <input type="text" v-model.trim="model2.Course_Code"
                                                            :id="'course-edit-${list.id}'"
                                                            @keydown.enter="updateCourse()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Course_Code)"></span>
                                                    </td>
                                                    <td v-if="courseId == list.id" class="bg-gray-100 pl-3 py-2.5 w-fit">
                                                        <input type="text" v-model.trim="model2.Course_Name"
                                                            :id="'course-edit-${list.id}'"
                                                            @keydown.enter="updateCourse()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Course_Name)"></span>
                                                    </td>
                                                    <td v-if="courseId == list.id" class="bg-gray-100 w-1/12">
                                                        <button
                                                            class="px-3 py-4 text-sm font-medium text-sky-600 hover:text-sky-900"
                                                            @click="updateCourse()"
                                                            >Update</button>
                                                    </td>
                                                    <td v-else
                                                        class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium"
                                                    >
                                                        <a
                                                            href="#" @click="() => setToEditing(list.id)"
                                                            class="text-green-500 hover:text-green-900"
                                                            >Edit</a
                                                        >
                                                        <!-- <button 
                                                            @click="setToEditing(list.id)"
                                                            class="text-green-500 hover:text-green-900"
                                                            >Edit
                                                        </button> -->
                                                    </td>
                                                    <td v-if="courseId == list.id" class="bg-gray-100 w-1/12">
                                                        <a
                                                            href="#" @click="() => cancelEdit()"
                                                            class="pl-5 py-4 whitespace-nowrap text-sm font-medium text-red-600 hover:text-red-900"
                                                            >Cancel</a>
                                                    </td>
                                                    <td v-else
                                                        class="px-3 py-4 whitespace-nowrap indent-2 text-left text-sm font-medium"
                                                    >
                                                        <a
                                                            href="#" @click="() => deleteCourse(list.id)"
                                                            class="text-red-600 hover:text-red-900"
                                                            >Delete</a
                                                        >
                                                    </td>
                                                </tr>
                                                <tr class="EmptyCourses" v-else>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">No Records of Degree Programs.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /End replace -->
            </div>
        </main>
</template>

<script>
import { ref, computed } from 'vue'
import { useVuelidate } from '@vuelidate/core';
import { required } from '@vuelidate/validators';

import APIController from '../controllers/api'

export default {
    name: 'Courses',
    setup () {
        const initialState = {
            Course_Code: '',
            Course_Name: '',
            department_id: '',
        }
        const model = ref({...initialState});

        const rules = computed(() => {
            return {
                Course_Code: { required },
                Course_Name: { required },
                department_id: { required }
            }
        })

        const v$ = useVuelidate(rules, model)

        const initialStatez = {
            Course_Code: '',
            Course_Name: ''
        }
        const modelz = ref({...initialStatez});

        const rulesz = computed(() => {
            return {
                Course_Code: { required },
                Course_Name: { required }
            }
        })

        const z$ = useVuelidate(rulesz, modelz)

        const model2 = ref([]);
        const searchQuery = ref("");

        const courseList = ref({});
        const courseId = ref(false);

        const college = ref([]);
        const departmentList = ref({});

        const userType = localStorage.getItem('userType');

         const reset = () => {
            model.value = {...initialState};
            modelz.value = {...initialStatez};
        }
        const fetchCourses = async () => {
            courseList.value = await APIController.FetchCoursesByCollege(college.value.id);
        }
        const fetchCoursesByDept = async () => {
            courseList.value = await APIController.FetchCoursesByDept(college.value.id);
        }
        const fetchDepartments = async () => {
            departmentList.value = await APIController.FetchDepartments(college.value.id);
        }
        
        const GetCourse = async () => {
            model2.value = await APIController.FetchCourse(courseId.value);
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            
            fetchDepartments();
            if(userType == 'reg'){
                fetchCourses();
                college.value.College_Name = college.value.College_Name.toUpperCase();
            } else {
                fetchCoursesByDept();
                college.value.Department_Name = college.value.Department_Name.toUpperCase();
            }
            
        }

        const AddNewCourse = async () => {
            if(userType == 'reg'){
                let tempCourse = await APIController.CreateCourse(model.value.Course_Code, model.value.Course_Name, model.value.department_id);
                if (tempCourse) {
                    fetchCourses();
                    reset();
                }
            } else {
                let tempCourse = await APIController.CreateCourse(modelz.value.Course_Code, modelz.value.Course_Name, college.value.id);
                if (tempCourse) {
                    fetchCoursesByDept();
                    reset();
                }
            }
        }

        const updateCourse = async () => {
            if(userType == 'reg'){
                let tempCourse = await APIController.UpdateCourse(model2.value.Course_Code, model2.value.Course_Name, model2.value.department_id, courseId.value);
                if (tempCourse) {
                    fetchCourses();
                    GetCourse();
                    courseId.value = false;
                }
            } else {
                let tempCourse = await APIController.UpdateCourseByDept(model2.value.Course_Code, model2.value.Course_Name, courseId.value);
                if (tempCourse) {
                    fetchCoursesByDept();
                    GetCourse();
                    courseId.value = false;
                }
            }
        }

        const deleteCourse = async id => {
            const success = await APIController.DeleteCourse(id);

            if (success) {
                if(userType == 'reg'){
                    fetchCourses();
                } else {
                    fetchCoursesByDept();
                }
            }
        }

        const searchedCourses = computed(() => {
            return courseList.value.filter((list) => {
                if(userType == 'reg'){
                    return (
                        list.Course_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Course_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Department_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1
                    )
                } else {
                    return (
                        list.Course_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Course_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1
                    )
                }
                
            });
        });

         function highlightMatches(text) {
            text = text.toString();
            const matchExists = text.toLowerCase().includes(searchQuery.value.toLowerCase());
            if (!matchExists) return text;

            const re = new RegExp(searchQuery.value, 'ig');
            return text.replace(re, matchedText => `<strong style="color:#F28B07">${matchedText}</strong>`);
        }

        function setToEditing(list) {
            courseId.value = list;
            GetCourse();
            // this.$refs.search.focus();
            setTimeout(()=> {
                document.getElementById('course-edit-${list.id}').focus()
            }, 1);
        }
        
        function cancelEdit(){
            courseId.value = false;
        }

        return {
            model,
            model2,
            courseList,
            fetchCourses,
            AddNewCourse,
            deleteCourse,
            GetCourse,
            updateCourse,
            // toggleCourse,
            courseId,
            // collegeList,
            // fetchColleges,
            reset,
            searchedCourses,
            searchQuery,
            highlightMatches,
            setToEditing,
            cancelEdit,
            v$,
            fetchDepartments,
            GetCollege,
            college,
            departmentList,
            fetchCoursesByDept,
            userType,
            modelz,
            z$
        }
    },
    mounted () {
        if (this.courseId != false){
            this.GetCourse();
        }
        this.GetCollege();
    },
    methods: {
        postAjaxForm () {
            this.v$.$reset();
            this.z$.$reset();
        }
    },
}
</script>
