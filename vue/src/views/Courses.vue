<template>
    <header class="bg-white shadow">
        <div
            class="max-w-screen mx-auto py-3 text-center"
        >
            <h1 v-if="userType == 'reg'" class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-[#253B80]">
                UPDATE & MANAGING OF DEGREE PROGRAMS OF THE {{ college.College_Name }}
            </h1>
            <h1 v-else class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-[#253B80]">
                UPDATE & MANAGING OF DEGREE PROGRAMS OF THE {{ college.Department_Name }}
            </h1>
        </div>
    </header>
    <main>
        <div class="max-w-[90vw] mx-auto py-2">
            <!-- Replace with your content -->
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <form v-if="userType == 'reg' && isBlocks == false" class="form" @submit.prevent="AddNewCourse()">
                        <div class="shadow overflow-hidden rounded-md">
                            <div class="bg-white p-5">
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
                    <form v-else-if="userType == 'dept' && isBlocks == false" class="form" @submit.prevent="AddNewCourse()">
                        <div class="shadow overflow-hidden rounded-md">
                            <div class="bg-white p-5">
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
                    <form v-else-if="isBlocks == true" class="form" @submit.prevent="isEditBlocks ? updateCourseBlock() : AddNewCourseBlock()">
                        <div class="shadow overflow-hidden rounded-md">
                            <div class="bg-white p-4">
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-6">
                                        <select
                                            required
                                            v-model="b$.selectedDegreeProgram.$model"
                                            @change="toggleBlock()"
                                            class="w-full px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 text-sky-600 font-medium bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 text-sm"
                                        >
                                            <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                                            <option v-for="lists in courseList"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ "[ "+lists.Course_Code+" ] &nbsp;"+lists.Course_Name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 flex justify-between gap-2">
                                        <select v-model="b$.selectedAcademicYear.$model"
                                                @change="toggleBlock()"
                                                required class="w-full px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 text-sky-600 font-medium bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 text-sm">
                                            <option disabled value="" class="text-center">-- Academic Year --</option>
                                            <option value="A.Y. 2022 - 2023" class="text-sky-600 text-justify">A.Y. 2022 - 2023</option>
                                            <option value="A.Y. 2023 - 2024" class="text-sky-600 text-justify">A.Y. 2023 - 2024</option>
                                        </select>
                                        <select v-model="b$.selectedSemester.$model"
                                                @change="toggleBlock()"
                                                required class="w-full px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 text-sky-600 font-medium bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 text-sm">
                                            <option disabled value="" class="text-center">-- Semester --</option>
                                            <option value="First Semester" class="text-sky-600 text-justify">First Semester</option>
                                            <option value="Second Semester" class="text-sky-600 text-justify">Second Semester</option>
                                            <option value="Mid Semester | Summer" class="text-sky-600 text-justify">Mid Semester / Summer</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6">
                                        <div class="flex justify-between text-center mb-1 border-b">
                                            <label for="college"
                                                class="w-full text-sm font-medium text-gray-700"
                                                >Year Level</label>
                                            <label for="college"
                                                class="w-full text-sm font-medium text-gray-700"
                                                >No. of Blocks</label> 
                                        </div>
                                        <div class="flex justify-between items-center pb-1">
                                            <span class="text-center w-[50%] bg-white text-sm font-roboto text-[#253B80]"
                                            >First Year
                                            </span>
                                            <div class="flex justify-center w-[50%]">
                                                <input v-if="enableEdit == false"
                                                    v-model="b$.first.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    required
                                                    class="w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                                <input v-else
                                                    v-model="b$.first.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    disabled
                                                    class="cursor-not-allowed w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center pb-1">
                                            <span class="text-center w-[50%] bg-white text-sm font-roboto text-[#253B80]"
                                            >Second Year
                                            </span>
                                            <div class="flex justify-center w-[50%]">
                                                <input v-if="!enableEdit" 
                                                    v-model="b$.second.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    required
                                                    class="w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                                <input v-else
                                                    v-model="b$.second.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    disabled
                                                    class="cursor-not-allowed w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center pb-1">
                                            <span class="text-center w-[50%] bg-white text-sm font-roboto text-[#253B80]"
                                            >Third Year
                                            </span>
                                            <div class="flex justify-center w-[50%]">
                                                <input v-if="!enableEdit"
                                                    v-model="b$.third.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    required
                                                    class="w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                                <input v-else
                                                    v-model="b$.third.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    disabled
                                                    class="cursor-not-allowed w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-center w-[50%] bg-white text-sm font-roboto text-[#253B80]"
                                            >Fourth Year
                                            </span>
                                            <div class="flex justify-center w-[50%]">
                                                <input v-if="!enableEdit"
                                                    v-model="b$.fourth.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    required
                                                    class="w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                                <input v-else
                                                    v-model="b$.fourth.$model"
                                                    type="number"
                                                    min="1" max="4"
                                                    disabled
                                                    class="cursor-not-allowed w-[40%] h-7 text-center font-roboto text-sky-600 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="py-3 bg-gray-50 text-center px-6"
                            >
                                <button
                                    v-if="b$.$invalid && isEditBlocks == false"
                                    disabled
                                    class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-roboto tracking-widest rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                >SAVE</button>
                                <input
                                    v-else-if="!b$.$invalid && isEditBlocks == false"
                                    @click="postAjaxForm"
                                    type="submit" :value="'SAVE'"
                                    class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-roboto tracking-widest rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                />
                                <button
                                    v-else-if="isEditBlocks == true && enableEdit == true"
                                    @click="enableEdit=false"
                                    class="button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-roboto tracking-widest rounded-md text-white bg-sky-400 hover:bg-sky-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                >EDIT</button>
                                <button
                                    v-if="b$.$invalid && isEditBlocks == true &&  enableEdit == false"
                                    disabled
                                    class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-roboto tracking-widest rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                >UPDATE</button>
                                <input
                                    v-else-if="!b$.$invalid && isEditBlocks == true &&  enableEdit == false"
                                    type="submit" :value="'UPDATE'"
                                    class="button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-roboto tracking-widest rounded-md text-white bg-sky-500 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                />
                            </div>
                        </div>
                    </form>
                    <div class="flex justify-center p-4 px-6">
                        <button v-if="courseId != false" class="cursor-not-allowed uppercase w-full py-2 border border-transparent w-full shadow-sm text-sm font-roboto tracking-wider rounded-md text-white bg-sky-300">
                            Set the No. of Blocks per Degree Programs
                        </button>
                        <button v-else-if="isBlocks == false" @click="isBlocks=true" class="cursor-pointer uppercase w-full py-2 border border-transparent w-full shadow-sm text-sm font-roboto tracking-wider rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            Set the No. of Blocks per Degree Programs
                        </button>
                        <button v-else @click="isBlocks=false, resetBlock()" class="cursor-pointer uppercase w-full py-2 border border-transparent w-full shadow-sm text-sm font-roboto tracking-wider rounded-md text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300">
                            Go back to the Input Form
                        </button>
                    </div>
                    
                </div>
                <div class="col-span-2">
                    <div class="flex flex-col">
                        <div
                            class="overflow-auto h-auto max-h-[80vh]"
                        >
                            <div
                                class="align-middle inline-block min-w-full p-px"
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
                                                            class="border-0 py-4 text-sm font-medium text-sky-600 bg-gray-100 w-[75%]"
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
                                                <td v-if="courseId == list.id" class="bg-gray-100 pl-3 w-fit">
                                                    <input type="text" v-model.trim="model2.Course_Code"
                                                        :id="'course-edit-${list.id}'"
                                                        @keydown.enter="updateCourse()"
                                                    class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-[85%]"/>
                                                </td>
                                                <td v-else @click="setToEditing(list.id)"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[22%]"
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
                                        </tbody>
                                    </table>
                                    <div class="EmptyCourses p-2" v-if="courseList.length <= 0">
                                        <span class="pl-4 whitespace-nowrap text-sm font-roboto tracking-wide text-[#253B80]">No Records of Degree Programs.</span>
                                    </div>
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
import { required, minValue, maxValue } from '@vuelidate/validators';

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

        const initialBlock = {
            selectedDegreeProgram: '',
            selectedAcademicYear: '',
            selectedSemester: '',
            first: 0,
            second: 0,
            third: 0,
            fourth: 0,
        }
        const modelBlock = ref({...initialBlock});
        const rulesBlock = computed(() => {
            return {
                selectedDegreeProgram: { required },
                selectedAcademicYear: { required },
                selectedSemester: { required },
                first: { required, minValue: minValue(1), maxValue: maxValue(4) },
                second: { required, minValue: minValue(1), maxValue: maxValue(4) },
                third: { required, minValue: minValue(1), maxValue: maxValue(4) },
                fourth: { required, minValue: minValue(1), maxValue: maxValue(4) }
            }
        });
        const b$ = useVuelidate(rulesBlock, modelBlock)
        const isBlocks = ref(false);
        const isEditBlocks = ref(false);
        const enableEdit = ref(false);

        const toggleBlock = async () => {
            let tempblock;
            if(!b$.value.selectedDegreeProgram.$invalid && !b$.value.selectedAcademicYear.$invalid && !b$.value.selectedSemester.$invalid){
                tempblock = await APIController.FetchCourseBlock(modelBlock.value.selectedDegreeProgram, modelBlock.value.selectedAcademicYear, modelBlock.value.selectedSemester);
            }
            if(tempblock){
                modelBlock.value.first = tempblock.first;
                modelBlock.value.second = tempblock.second;
                modelBlock.value.third = tempblock.third;
                modelBlock.value.fourth = tempblock.fourth;
                isEditBlocks.value = true;
                enableEdit.value = true;
            }
            else {
                modelBlock.value.first = 0;
                modelBlock.value.second = 0;
                modelBlock.value.third = 0;
                modelBlock.value.fourth = 0;
                isEditBlocks.value = false;
                enableEdit.value = false;
            }
        }

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
        const resetBlock = () => {
            modelBlock.value = {...initialBlock};
            isEditBlocks.value = false;
            enableEdit.value = false;
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
            if(userType == 'reg') {
                fetchCourses();
                college.value.College_Name = college.value.College_Name.toUpperCase();
            }
            else {
                fetchCoursesByDept();
                college.value.Department_Name = college.value.Department_Name.toUpperCase();
            }
        }

        const AddNewCourseBlock = async () => {
            let tempBlock = await APIController.CreateCourseBlock(modelBlock.value.selectedDegreeProgram, modelBlock.value.selectedAcademicYear, modelBlock.value.selectedSemester, modelBlock.value.first, modelBlock.value.second, modelBlock.value.third, modelBlock.value.fourth);
                if (tempBlock)
                    resetBlock();
        }

        const updateCourseBlock = async () => {
            let tempBlock = await APIController.UpdateCourseBlock(modelBlock.value.selectedDegreeProgram, modelBlock.value.selectedAcademicYear, modelBlock.value.selectedSemester, modelBlock.value.first, modelBlock.value.second, modelBlock.value.third, modelBlock.value.fourth);
                if (tempBlock)
                    resetBlock();
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
            isBlocks.value = false;
            resetBlock();
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
            z$,
            modelBlock,
            b$,
            isBlocks,
            resetBlock,
            toggleBlock,
            AddNewCourseBlock,
            isEditBlocks,
            enableEdit,
            updateCourseBlock
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
            this.b$.$reset();
        }
    },
}
</script>
