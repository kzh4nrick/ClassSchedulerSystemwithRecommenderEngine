<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-gray-100">
    <body class="h-full">
    ```
  -->

    <header class="bg-white shadow">
        <div class="flex items-center flex-row py-2">
            <h1 class="w-3/5 lg:text-xl md:text-lg text-right uppercase lg:font-extrabold md:font-bold text-[#253B80]">
                Update and Managing of Curriculum
            </h1>
            <div class="flex justify-center w-2/5">
                <select v-model="h$.selectedAcademicYear.$model"
                        @change="toggleAYSem(), resetModel(), curr = false"
                        required class="invalid:border-orange-600 invalid:text-orange-600 focus:invalid:border-orange-600 focus:invalid:ring-orange-600 h-7.5 w-max text-sky-800 border border-sky-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-600 focus:border-sky-600 text-xs">
                    <option disabled value="" class="text-center">-- Academic Year --</option>
                    <option value="A.Y. 2022 - 2023" class="text-sky-600 text-justify">A.Y. 2022 - 2023</option>
                    <option value="A.Y. 2023 - 2024" class="text-sky-600 text-justify">A.Y. 2023 - 2024</option> -->
                </select>
                <select v-model="h$.selectedSemester.$model"
                        @change="toggleAYSem(), resetModel(), curr = false"
                        required class="ml-2 invalid:border-orange-600 invalid:text-orange-600 focus:invalid:border-orange-600 focus:invalid:ring-orange-600 h-7.5 w-max text-sky-800 border border-sky-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-600 focus:border-sky-600 text-xs">
                    <option disabled value="" class="text-center">-- Semester --</option>
                    <option value="First Semester" class="text-sky-600 text-justify">First Semester</option>
                    <option value="Second Semester" class="text-sky-600 text-justify">Second Semester</option> -->
                    <option value="Mid Semester | Summer" class="text-sky-600 text-justify">Mid Semester / Summer</option> -->
                </select>
            </div>
        </div>
    </header>
    <main>
        <div class="2xl:max-w-[90vw] xl:max-w-[92vw] lg:max-w-[96vw] mx-auto py-1.5">
            <form v-if="userType == 'reg'" class="form" @submit.prevent="">
            <div class="grid grid-cols-11 gap-4">
                <div class="col-span-4">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCollege.$model"
                        @keyup="v$.selectedCollege.$touch()"
                        @blur="v$.selectedCollege.$touch()"
                        required
                        @change="fetchCourses(), viewcurr1 = true, viewcurr2 = false, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                        <option v-for="list in collegeList"
                                v-bind="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.College_Name + " - " + list.Campus }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                    </select>
                </div>
                <div class="col-span-5">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCourse.$model"
                        @keyup="v$.selectedCourse.$touch()"
                        @blur="v$.selectedCourse.$touch()"
                        required
                        @change="curr = false, viewcurr2 = true, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                        <option v-for="list in courseList"
                                :key="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.Course_Name }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <button
                        v-if="(v$.$invalid || viewcurr2 == false)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else-if="!(viewcurr1 == true || viewcurr2 == true)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else
                        type="submit"
                        @click="curr = true, viewcurr1 = false, viewcurr2 = false, curriculumId = false, reset(), fetchCurricula(), fetchGECSubjectsByCollege()"
                        class="h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Curriculum
                    </button>
                </div>
            </div>
            </form>
            <form v-else class="form" @submit.prevent="">
            <div class="grid grid-cols-10 gap-4" v-if="SubjectTypeDept2 == ''">
                <div class="col-span-2">
                    <select
                        v-if="!h$.$invalid"
                        v-model="SubjectTypeDept"
                        required
                        @change="(curr = false, reset2(SubjectTypeDept), toggleType(SubjectTypeDept))"
                        class="cursor-pointer invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select the Course Type --</option>
                        <option value="Major" class="text-sky-600 text-center">Major / Elective</option>
                        <option value="Core" class="text-sky-600 text-center">Core / Core Elective</option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select the Course Type --</option>
                    </select>
                </div>
                <div class="col-span-4" v-if="SubjectTypeDept == 'Major'">
                    <select
                        v-if="!h$.$invalid"
                        v-model="z$.selectedCourse.$model"
                        @keyup="z$.selectedCourse.$touch()"
                        @blur="z$.selectedCourse.$touch()"
                        required
                        @change="viewcurr2 = true, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                        <option v-for="list in courseList"
                                :key="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.Course_Name }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                    </select>
                </div>
                <div class="col-span-3" v-else-if="SubjectTypeDept == 'Core'">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCollege.$model"
                        @keyup="v$.selectedCollege.$touch()"
                        @blur="v$.selectedCollege.$touch()"
                        required
                        @change="fetchCourses(), viewcurr1 = true, viewcurr2 = false, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                        <option v-for="list in collegeList"
                                v-bind="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.College_Name + " - " + list.Campus }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                    </select>
                </div>
                <div class="col-span-3" v-else>
                    <select
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 focus:border-orange-500 focus:ring-orange-500 h-max w-full border-2 bg-white rounded-lg shadow-sm focus:outline-none sm:text-sm"
                    >
                    </select>
                </div>
                <div class="col-span-2" v-if="SubjectTypeDept == 'Major'">
                    <select
                        v-if="!h$.$invalid"
                        required
                        v-model="z$.selectedyearLevel.$model"
                        @keyup="z$.selectedyearLevel.$touch()"
                        @blur="z$.selectedyearLevel.$touch()"
                        @change="viewcurr1 = true, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Year Level --</option>
                        <option value="First Year" class="text-sky-600 text-justify">First Year</option>
                        <option value="Second Year" class="text-sky-600 text-justify">Second Year</option>
                        <option value="Third Year" class="text-sky-600 text-justify">Third Year</option>
                        <option value="Fourth Year" class="text-sky-600 text-justify">Fourth Year</option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Year Level --</option>
                    </select>
                </div>
                <div class="col-span-3" v-else-if="SubjectTypeDept == 'Core'">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCourse.$model"
                        @keyup="v$.selectedCourse.$touch()"
                        @blur="v$.selectedCourse.$touch()"
                        required
                        @change="viewcurr2 = true, curr = false, reset"
                        
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                        <option v-for="list in courseList"
                                :key="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.Course_Name }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                    </select>
                </div>
                <div class="col-span-3" v-else>
                    <select
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 focus:border-orange-500 focus:ring-orange-500 h-max w-full border-2 bg-white rounded-lg shadow-sm focus:outline-none sm:text-sm"
                    >
                    </select>
                </div>
                <div class="col-span-2" v-if="SubjectTypeDept == 'Major'">
                    <button
                        v-if="z$.$invalid"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else-if="!(viewcurr2 == true || viewcurr1 == true)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else
                        type="submit"
                        @click="curr = true, viewcurr2 = false, viewcurr1 = false, curriculumId = false, reset(); fetchCurriculaD()"
                        class="h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Curriculum
                    </button>
                </div>
                <div class="col-span-2" v-else-if="SubjectTypeDept == 'Core'">
                    <button
                        v-if="(v$.$invalid || viewcurr2 == false)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else-if="!(viewcurr2 == true || viewcurr1 == true)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else
                        type="submit"
                        @click="curr = true, viewcurr2 = false, viewcurr1 = false, curriculumId = false, reset(); fetchCurriculaCore(); fetchCoreSubjectsByDepartment()"
                        class="h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Curriculum
                    </button>
                </div>
                <div class="col-span-2" v-else>
                    <button
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-11 gap-4" v-else-if="SubjectTypeDept2 == 'Major'">
                <div class="col-span-6">
                    <select
                        v-if="!h$.$invalid"
                        v-model="z$.selectedCourse.$model"
                        @keyup="z$.selectedCourse.$touch()"
                        @blur="z$.selectedCourse.$touch()"
                        required
                        @change="viewcurr2 = true, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                        <option v-for="list in courseList"
                                :key="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.Course_Name }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                    </select>
                </div>
                <div class="col-span-3">
                    <select
                        v-if="!h$.$invalid"
                        required
                        v-model="z$.selectedyearLevel.$model"
                        @keyup="z$.selectedyearLevel.$touch()"
                        @blur="z$.selectedyearLevel.$touch()"
                        @change="viewcurr1 = true, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Year Level --</option>
                        <option value="First Year" class="text-sky-600 text-justify">First Year</option>
                        <option value="Second Year" class="text-sky-600 text-justify">Second Year</option>
                        <option value="Third Year" class="text-sky-600 text-justify">Third Year</option>
                        <option value="Fourth Year" class="text-sky-600 text-justify">Fourth Year</option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Year Level --</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <button
                        v-if="z$.$invalid"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else-if="!(viewcurr2 == true || viewcurr1 == true)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else
                        type="submit"
                        @click="curr = true, viewcurr2 = false, viewcurr1 = false, curriculumId = false, reset(); fetchCurriculaD()"
                        class="h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Curriculum
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-11 gap-4" v-else-if="SubjectTypeDept2 == 'Core'">
                <div class="col-span-4">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCollege.$model"
                        @keyup="v$.selectedCollege.$touch()"
                        @blur="v$.selectedCollege.$touch()"
                        required
                        @change="fetchCourses(), viewcurr1 = true, viewcurr2 = false, curr = false, reset"
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                        <option v-for="list in collegeList"
                                v-bind="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.College_Name + " - " + list.Campus }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a College Department --</option>
                    </select>
                </div>
                <div class="col-span-5">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCourse.$model"
                        @keyup="v$.selectedCourse.$touch()"
                        @blur="v$.selectedCourse.$touch()"
                        required
                        @change="viewcurr2 = true, curr = false, reset"
                        
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                        <option v-for="list in courseList"
                                :key="list.id"
                                :value="list.id"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.Course_Name }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Select a Degree Program --</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <button
                        v-if="(v$.$invalid || viewcurr2 == false)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm uppercase font-extrabold rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else-if="!(viewcurr2 == true || viewcurr1 == true)"
                        disabled
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm uppercase font-extrabold rounded-lg text-white bg-orange-400">
                        Manage Curriculum
                    </button>
                    <button
                        v-else
                        type="submit"
                        @click="curr = true, viewcurr2 = false, viewcurr1 = false, curriculumId = false, reset(); fetchCurriculaCore(); fetchCoreSubjectsByDepartment()"
                        class="h-full border border-transparent w-full shadow-sm text-sm uppercase font-extrabold rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Curriculum
                    </button>
                </div>
            </div>
            </form>
        </div>

        <div class="max-w-[95vw] mx-auto py-px">
                <!-- Replace with your content -->
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1">
                    <form v-if="curr == true && (userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core')))" class="form" @submit.prevent="AddNewCurriculum()">
                        <div class="shadow rounded-md">
                            <div class="overflow-x-auto h-auto max-h-[69vh] bg-white p-4">
                                <div class="grid grid-cols-4 gap-6">
                                    <div class="col-span-6">
                                        <label
                                            for="yearLevel"
                                            class="block text-sm font-medium text-gray-700"
                                            >Year Level
                                        </label>
                                        <select
                                            v-if="curriculumId == false"
                                            required
                                            v-model="y$.yearLevel.$model"
                                            @keyup="y$.yearLevel.$touch()"
                                            @blur="y$.yearLevel.$touch()"
                                            class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Year Level</option>
                                            <option value="First Year" class="text-sky-600 text-justify">First Year</option>
                                            <option value="Second Year" class="text-sky-600 text-justify">Second Year</option>
                                            <option value="Third Year" class="text-sky-600 text-justify">Third Year</option>
                                            <option value="Fourth Year" class="text-sky-600 text-justify">Fourth Year</option>
                                        </select>
                                        <select
                                            v-else
                                            disabled
                                            required
                                            v-model="model2.yearLevel"
                                            class="cursor-not-allowed mt-1 block w-full py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Year Level</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6">
                                        <label
                                            for="subject"
                                            class="block text-sm font-medium text-gray-700"
                                            >Course
                                        </label>
                                        <!-- <div v-if="userType == 'dept'">
                                            <input 
                                                v-model="MESubjType"
                                                @change="fetchMESubjectsByCourse()"
                                                class="md:ml-1 lg:ml-3 xl:ml-3 2xl:ml-5 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="major"/>
                                            <label class="inline-block ml-1 xs:text-xs sm:text-xs md:text-xs lg:text-sm text-sm font-medium text-gray-700" for="Major">Major</label>
                                            <input
                                                v-model="MESubjType"
                                                @change="fetchMESubjectsByCourse()"
                                                class="md:ml-1.5 lg:ml-2 xl:ml-3 ml-3 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="minor"/>
                                            <label class="inline ml-1 xs:text-xs sm:text-xs md:text-xs lg:text-sm text-sm font-medium text-gray-700" for="Elective">Elective</label>
                                        </div> -->
                                        <select
                                            v-if="curriculumId == false"
                                            required
                                            v-model="y$.subject_id.$model"
                                            @keyup="y$.subject_id.$touch()"
                                            @blur="y$.subject_id.$touch()"
                                            @change="FetchFaculties(onChangeSubject($event)), FetchClassrooms(onChangeSubject($event))"
                                            class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Course</option>
                                            <option v-for="lists in subjectList"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Subject_Code + " - " + lists.Subject_Name }}
                                            </option>
                                        </select>
                                        <select
                                            v-else
                                            disabled
                                            required
                                            v-model="model2.subject_id"
                                            class="cursor-not-allowed mt-1 block w-full py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Course</option>
                                            <option v-for="lists in subjectList"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Subject_Code + " - " + lists.Subject_Name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-span-6">
                                        <label
                                            for="Units"
                                            class="block text-sm font-medium text-gray-700"
                                            >Units
                                        </label>
                                        <label for="lec"
                                            class="md:ml-1 lg:ml-1 xl:ml-2 2xl:ml-4 text-sm font-medium text-gray-700"
                                            >Lec
                                        </label>
                                        <input
                                            v-if="curriculumId == false"
                                            v-model="y$.lec.$model"
                                            @keyup="y$.lec.$touch()"
                                            @blur="y$.lec.$touch()"
                                            type="number"
                                            min="1" max="15"
                                            required
                                            class="mt-1 w-4/12 text-center invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 xs:h-8 sm:h-8 md:h-8 h-2/4 text-sky-600 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                        <input
                                            v-else
                                            disabled
                                            required
                                            v-model="model2.lec"
                                            type="number"
                                            min="1" max="15"
                                            class="cursor-not-allowed mt-1 w-4/12 text-center xs:h-8 sm:h-8 md:h-8 h-2/4 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        />
                                        <label for="lab"
                                            class="md:ml-1 lg:ml-1 xl:ml-2 2xl:ml-2 text-sm font-medium text-gray-700"
                                            >Lab
                                        </label>
                                        <input
                                            v-if="curriculumId == false"
                                            v-model="y$.lab.$model"
                                            @keyup="y$.lab.$touch()"
                                            @blur="y$.lab.$touch()"
                                            type="number"
                                            min="0" max="15"
                                            required
                                            class="w-4/12 text-center invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 xs:h-8 sm:h-8 md:h-8 h-2/4 text-sky-600 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                        <input
                                            v-else
                                            disabled
                                            required
                                            v-model="model2.lab"
                                            type="number"
                                            min="0" max="15"
                                            class="cursor-not-allowed w-4/12 text-center xs:h-8 sm:h-8 md:h-8 h-2/4 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        />
                                    </div>

                                    <div class="col-span-6">
                                        <div class="relative">
                                            <label
                                                for="faculty"
                                                class="text-sm font-medium text-gray-700"
                                                >Faculty
                                            </label>
                                            <button v-if="curriculumId == false" @click.prevent="addFaculty()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700">
                                                    <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                                                </svg>
                                            </button>
                                            <button v-else disabled class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                    <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="relative" v-for="(fact, index) in faculties" :key="index">
                                            <select
                                                v-if="curriculumId == false"
                                                required
                                                v-model="fact.faculty"
                                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Faculty</option>
                                                <option v-for="lists in facultyList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Faculty_ID + " - " + lists.Faculty_Name }}
                                                </option>
                                            </select>
                                            <select
                                                v-else
                                                disabled
                                                v-model="fact.faculty"
                                                class="cursor-not-allowed mt-1 w-5/6 py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Faculty</option>
                                                <option v-for="lists in facultyList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Faculty_ID + " - " + lists.Faculty_Name }}
                                                </option>
                                            </select>
                                            <button v-if="(curriculumId == false && faculties.length > 1)" @click.prevent="removeFaculty(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700">
                                                <path d="M10.375 2.25a4.125 4.125 0 100 8.25 4.125 4.125 0 000-8.25zM10.375 12a7.125 7.125 0 00-7.124 7.247.75.75 0 00.363.63 13.067 13.067 0 006.761 1.873c2.472 0 4.786-.684 6.76-1.873a.75.75 0 00.364-.63l.001-.12v-.002A7.125 7.125 0 0010.375 12zM16 9.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" />
                                                </svg>
                                            </button>
                                            <button v-else disabled v-if="faculties.length > 1" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                <path d="M10.375 2.25a4.125 4.125 0 100 8.25 4.125 4.125 0 000-8.25zM10.375 12a7.125 7.125 0 00-7.124 7.247.75.75 0 00.363.63 13.067 13.067 0 006.761 1.873c2.472 0 4.786-.684 6.76-1.873a.75.75 0 00.364-.63l.001-.12v-.002A7.125 7.125 0 0010.375 12zM16 9.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-6">
                                        <div class="relative">
                                        <label
                                            for="classroom"
                                            class="text-sm font-medium text-gray-700"
                                            >Classroom
                                        </label>
                                        <button v-if="curriculumId == false" @click.prevent="addClassroom()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700" viewBox="0 0 20 20">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                            </svg>
                                        </button>
                                        <button v-else disabled class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                            </svg>
                                        </button>
                                        </div>
                                        <div class="relative" v-for="(classs, index) in classrooms" :key="index">
                                            <select
                                                v-if="curriculumId == false"
                                                required
                                                v-model="classs.classroom"
                                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Classroom</option>
                                                <option v-for="lists in classroomList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Building_No + " - " + lists.Classroom_No + " [" + lists.Classroom_Type + "]" }}
                                                </option>
                                            </select>
                                            <select
                                                v-else
                                                disabled
                                                v-model="classs.classroom"
                                                class="cursor-not-allowed mt-1 w-5/6 py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Classroom</option>
                                                <option v-for="lists in classroomList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Building_No + " - " + lists.Classroom_No + " [" + lists.Classroom_Type + "]" }}
                                                </option>
                                            </select>
                                            <button v-if="(curriculumId == false && classrooms.length > 1)" @click.prevent="removeClassroom(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700" viewBox="0 0 20 20">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                                </svg>
                                            </button>
                                            <button v-else disabled v-if="classrooms.length > 1" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div
                                class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                            >
                                <input
                                    v-if="y$.$invalid || curriculumId != false"
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
                    <form v-else-if="curr == true && userType =='dept'" class="form" @submit.prevent="AddNewCurriculum()">
                        <div class="shadow rounded-md">
                            <div class="overflow-x-auto h-auto max-h-[69vh] bg-white p-4">
                                <div class="grid grid-cols-4 gap-6">
                                    <div class="col-span-6">
                                        <label
                                            for="subject"
                                            class="block text-sm font-medium text-gray-700"
                                            >Course
                                        </label>
                                        <div v-if="userType == 'dept'">
                                            <input
                                                v-if="curriculumId == false"
                                                v-model="MESubjType"
                                                @change="fetchMESubjectsByCourse()"
                                                class="md:ml-1 lg:ml-3 xl:ml-3 2xl:ml-5 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Major"/>
                                            <input
                                                v-else
                                                @change="fetchMESubjectsByCourse()"
                                                class="cursor-not-allowed md:ml-1 lg:ml-3 xl:ml-3 2xl:ml-5 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Major"/>
                                            <label class="inline-block ml-1 xs:text-xs sm:text-xs md:text-xs lg:text-sm text-sm font-medium text-gray-700" for="Major">Major</label>
                                            <input
                                                v-if="curriculumId == false"
                                                v-model="MESubjType"
                                                @change="fetchMESubjectsByCourse()"
                                                class="md:ml-1.5 lg:ml-2 xl:ml-3 ml-3 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Elective"/>
                                            <input
                                                v-else
                                                @change="fetchMESubjectsByCourse()"
                                                class="cursor-not-allowed md:ml-1.5 lg:ml-2 xl:ml-3 ml-3 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Elective"/>
                                            <label class="inline ml-1 xs:text-xs sm:text-xs md:text-xs lg:text-sm text-sm font-medium text-gray-700" for="Elective">Elective</label>
                                        </div>
                                        <select
                                            v-if="curriculumId == false"
                                            required
                                            v-model="w$.subject_id.$model"
                                            @keyup="w$.subject_id.$touch()"
                                            @blur="w$.subject_id.$touch()"
                                            @change="FetchFaculties(onChangeSubject($event)), FetchClassrooms(onChangeSubject($event))"
                                            class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                            <option disabled value="" class="text-center">Select a Course</option>
                                            <option v-for="lists in subjectList"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Subject_Code + " - " + lists.Subject_Name }}
                                            </option>
                                        </select>
                                        <select
                                            v-else
                                            disabled
                                            required
                                            v-model="model2a.subject_id"
                                            class="cursor-not-allowed mt-1 block w-full py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        >
                                            <option disabled value="">Lists of Subjects</option>
                                            <option v-for="lists in subjectList"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Subject_Code + " - " + lists.Subject_Name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-span-6">
                                        <label
                                            for="Units"
                                            class="block text-sm font-medium text-gray-700"
                                            >Units
                                        </label>
                                        <label for="lec"
                                            class="md:ml-1 lg:ml-1 xl:ml-2 2xl:ml-4 text-sm font-medium text-gray-700"
                                            >Lec
                                        </label>
                                        <input
                                            v-if="curriculumId == false"
                                            v-model="w$.lec.$model"
                                            @keyup="w$.lec.$touch()"
                                            @blur="w$.lec.$touch()"
                                            type="number"
                                            min="1" max="15"
                                            required
                                            class="mt-1 w-4/12 text-center invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 xs:h-8 sm:h-8 md:h-8 h-2/4 text-sky-600 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                        <input
                                            v-else
                                            disabled
                                            required
                                            v-model="model2a.lec"
                                            type="number"
                                            min="1" max="15"
                                            class="cursor-not-allowed mt-1 w-4/12 text-center xs:h-8 sm:h-8 md:h-8 h-2/4 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        />
                                        <label for="lab"
                                            class="md:ml-1 lg:ml-1 xl:ml-2 2xl:ml-2 text-sm font-medium text-gray-700"
                                            >Lab
                                        </label>
                                        <input
                                            v-if="curriculumId == false"
                                            v-model="w$.lab.$model"
                                            @keyup="w$.lab.$touch()"
                                            @blur="w$.lab.$touch()"
                                            type="number"
                                            min="0" max="15"
                                            required
                                            class="w-4/12 text-center invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 xs:h-8 sm:h-8 md:h-8 h-2/4 text-sky-600 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                        <input
                                            v-else
                                            disabled
                                            required
                                            v-model="model2a.lab"
                                            type="number"
                                            min="0" max="15"
                                            class="cursor-not-allowed w-4/12 text-center xs:h-8 sm:h-8 md:h-8 h-2/4 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                        />
                                    </div>

                                    <div class="col-span-6">
                                        <div class="relative">
                                        <label
                                            for="faculty"
                                            class="text-sm font-medium text-gray-700"
                                            >Faculty
                                        </label>
                                        <button v-if="curriculumId == false" @click.prevent="addFaculty()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700">
                                                <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                                            </svg>
                                        </button>
                                        <button v-else disabled class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                                            </svg>
                                        </button>
                                        </div>
                                        <div class="relative" v-for="(fact, index) in faculties" :key="index">
                                            <select
                                                v-if="curriculumId == false"
                                                required
                                                v-model="fact.faculty"
                                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Faculty</option>
                                                <option v-for="lists in facultyList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Faculty_ID + " - " + lists.Faculty_Name }}
                                                </option>
                                            </select>
                                            <select
                                                v-else
                                                disabled
                                                v-model="fact.faculty"
                                                class="cursor-not-allowed mt-1 w-5/6 py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Faculty</option>
                                                <option v-for="lists in facultyList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Faculty_ID + " - " + lists.Faculty_Name }}
                                                </option>
                                            </select>
                                            <button v-if="(curriculumId == false && faculties.length > 1)" @click.prevent="removeFaculty(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700">
                                                <path d="M10.375 2.25a4.125 4.125 0 100 8.25 4.125 4.125 0 000-8.25zM10.375 12a7.125 7.125 0 00-7.124 7.247.75.75 0 00.363.63 13.067 13.067 0 006.761 1.873c2.472 0 4.786-.684 6.76-1.873a.75.75 0 00.364-.63l.001-.12v-.002A7.125 7.125 0 0010.375 12zM16 9.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" />
                                                </svg>
                                            </button>
                                            <button v-else disabled v-if="faculties.length > 1" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                <path d="M10.375 2.25a4.125 4.125 0 100 8.25 4.125 4.125 0 000-8.25zM10.375 12a7.125 7.125 0 00-7.124 7.247.75.75 0 00.363.63 13.067 13.067 0 006.761 1.873c2.472 0 4.786-.684 6.76-1.873a.75.75 0 00.364-.63l.001-.12v-.002A7.125 7.125 0 0010.375 12zM16 9.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-span-6">
                                        <div class="relative">
                                        <label
                                            for="classroom"
                                            class="text-sm font-medium text-gray-700"
                                            >Classroom
                                        </label>
                                        <button v-if="curriculumId == false" @click.prevent="addClassroom()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                            </svg>
                                        </button>
                                        <button v-else disabled class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                            </svg>
                                        </button>
                                        </div>
                                        <div class="relative" v-for="(classs, index) in classrooms" :key="index">
                                            <select
                                                v-if="curriculumId == false"
                                                required
                                                v-model="classs.classroom"
                                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Classroom</option>
                                                <option v-for="lists in classroomList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Building_No + " - " + lists.Classroom_No + " [" + lists.Classroom_Type + "]" }}
                                                </option>
                                            </select>
                                            <select
                                                v-else
                                                disabled
                                                v-model="classs.classroom"
                                                class="cursor-not-allowed mt-1 w-5/6 py-2 px-3 border-2 bg-white rounded-md shadow-sm focus:outline-none sm:text-sm"
                                            >
                                                <option disabled class="text-center" value="">Select a Classroom</option>
                                                <option v-for="lists in classroomList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        class="text-sky-600 text-justify"
                                                        >
                                                        {{ lists.Building_No + " - " + lists.Classroom_No + " [" + lists.Classroom_Type + "]" }}
                                                </option>
                                            </select>
                                            <button v-if="(curriculumId == false && classrooms.length > 1)" @click.prevent="removeClassroom(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                                </svg>
                                            </button>
                                            <button v-else disabled v-if="classrooms.length > 1" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="cursor-not-allowed w-5 h-5 fill-gray-500">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div
                                class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                            >
                                <input
                                    v-if="w$.$invalid || curriculumId != false"
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
                <div :class="(userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core'))) ? 'col-span-2' : 'col-span-3'">
                    <div v-if="curr == true" class="flex flex-col">
                        <div
                            class="overflow-auto h-auto max-h-[67vh]"
                        >
                            <div
                                class="align-middle inline-block min-w-full p-px pb-1 pr-1"
                            >
                                <div
                                    class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-200"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th v-if="(userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core')))"
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Year Level
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Course Code
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Course Name
                                                </th>
                                                <!-- <th v-if="userType == 'dept'"
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Subject Type
                                                </th> -->
                                                <th
                                                    scope="col"
                                                    class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Units
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Lec
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Lab
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Faculty
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Classroom
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
                                                v-for="list in searchedCurricula"
                                                :key="list.id"
                                                v-if="curriculumList.length > 0"
                                                class="hover:bg-gray-100"
                                            >
                                                <td v-if="(userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core')))">
                                                    <td v-if="curriculumId == list.id" class="bg-gray-100 pl-3 w-3/6">
                                                        <select
                                                            v-model="model3.yearLevel"
                                                            :id="'curriculum-edit-${list.id}'"
                                                            class="border-0 py-4 text-sm font-medium text-sky-600 bg-gray-100 w-full"
                                                        >
                                                            <option disabled value="">Select a Year Level</option>
                                                            <option value="First Year" class="text-sky-600 text-justify">First Year</option>
                                                            <option value="Second Year" class="text-sky-600 text-justify">Second Year</option>
                                                            <option value="Third Year" class="text-sky-600 text-justify">Third Year</option>
                                                            <option value="Fourth Year" class="text-sky-600 text-justify">Fourth Year</option>
                                                        </select>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                        <span v-html="highlightMatches(list.yearLevel)"></span>
                                                    </td>
                                                </td>
                                                <td v-if="curriculumId == list.id"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-100 w-1/6"
                                                >
                                                        <span v-html="highlightMatches(list.Subject_Code)"></span>
                                                </td>
                                                <td v-else
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                        <span v-html="highlightMatches(list.Subject_Code)"></span>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100 pl-3">
                                                    <select
                                                        v-model="model3.subject_id"
                                                        :id="'curriculum-edit-${list.id}'"
                                                        class="border-0 py-1.5 text-sm font-medium text-sky-600 bg-gray-100 w-72"
                                                    >
                                                        <option disabled value="">Lists of Courses</option>
                                                        <option v-for="lists in subjectList"
                                                                v-bind="lists.id"
                                                                :value="lists.id"
                                                                >
                                                                {{  lists.Subject_Code + " - " + lists.Subject_Name }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td v-else @click="setToEditing(list.id)"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                    <span v-html="highlightMatches(list.Subject_Name)"></span>
                                                </td>
                                                <!-- <td v-if="userType == 'dept'">
                                                    <td v-if="curriculumId == list.id" class="bg-gray-100 pl-3">
                                                        <select
                                                            v-model="model3.Subject_Type"
                                                            :id="'curriculum-edit-${list.id}'"
                                                            @change="fetchMESubjectsByCourse2()"
                                                            class="border-0 py-1.5 text-sm text-sky-600 font-medium bg-gray-100 w-fit"
                                                        >
                                                            <option value="major">major</option>
                                                            <option value="minor">minor</option>
                                                        </select>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                        <span v-html="highlightMatches(list.Subject_Type)"></span>
                                                    </td>
                                                </td> -->
                                                <td v-if="curriculumId == list.id"
                                                    class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-100 w-1/6"
                                                >
                                                        <span v-html="highlightMatches(list.Units)"></span>
                                                </td>
                                                <td v-else
                                                    class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                        <span v-html="highlightMatches(list.Units)"></span>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100 pl-3 w-1/6">
                                                    <input type="number" min="1" max="15" required v-model="model3.lec"
                                                        :id="'curriculum-edit-${list.id}'"
                                                        @keydown.enter="updateCurriculum()"
                                                    class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                </td>
                                                <td v-else @click="setToEditing(list.id)"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                        <span v-html="highlightMatches(list.lec)"></span>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100 pl-3 w-1/6">
                                                    <input type="number" min="0" max="15" required v-model="model3.lab"
                                                        :id="'curriculum-edit-${list.id}'"
                                                        @keydown.enter="updateCurriculum()"
                                                    class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                </td>
                                                <td v-else @click="setToEditing(list.id)"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                        <span v-html="highlightMatches(list.lab)"></span>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100">
                                                    <button v-on:click="toggleModal()" class="text-sm font-medium text-orange-600 hover:text-orange-900">
                                                        View Faculty[{{ list.faculties_id.length }}]</button>
                                                </td>
                                                <td v-else>
                                                    <button  @click="() => setToEditing(list.id)" class="text-sm font-medium text-gray-500">
                                                        View Faculty[{{ list.faculties_id.length }}]</button>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100">
                                                    <button v-on:click="toggleModalC()" class="text-sm font-medium text-orange-600 hover:text-orange-900">
                                                        View Classroom[{{ list.classrooms_id.length }}]</button>
                                                </td>
                                                <td v-else>
                                                    <button  @click="() => setToEditing(list.id)" class="text-sm font-medium text-gray-500">
                                                        View Classroom[{{ list.classrooms_id.length }}]</button>
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100 w-1/12">
                                                    <button
                                                        class="px-3 py-4 text-sm font-medium text-sky-600 hover:text-sky-900"
                                                        @click="updateCurriculum()"
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
                                                </td>
                                                <td v-if="curriculumId == list.id" class="bg-gray-100 w-1/12">
                                                    <a
                                                        href="#" @click="() => cancelEdit()"
                                                        class="pl-5 py-4 whitespace-nowrap text-sm font-medium text-red-600 hover:text-red-900"
                                                        >Cancel</a>
                                                </td>
                                                <td v-else
                                                    class="px-3 py-4 whitespace-nowrap indent-2 text-left text-sm font-medium"
                                                >
                                                    <a
                                                        href="#" @click="() => deleteCurriculum(list.id)"
                                                        class="text-red-600 hover:text-red-900"
                                                        >Delete</a
                                                    >
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="EmptyCurricula p-2" v-if="curriculumList.length <= 0">
                                        <span class="pl-4 whitespace-nowrap text-sm font-roboto tracking-wide text-[#253B80]">No Records for this Curriculum.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="(currList == true && (userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core'))))" class="col-span-1">
                    <div class="shadow overflow-auto h-auto max-h-[77vh] border-b border-gray-200 rounded-lg">
                        <table
                            class="table-auto min-w-full divide-y divide-gray-200"
                        >
                            <caption class="bg-gray-50 py-3 text-center text-xs font-medium text-gray-500 uppercase">Saved Curriculums</caption>
                            <thead class="bg-gray-50">
                                
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-gray-200"
                            >
                                <tr
                                    v-for="list in curriculaList"
                                    :key="list.Course_Name"
                                    v-if="curriculaList.length > 0"
                                    class="hover:bg-gray-100"
                                >
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ list.College_Code }}
                                    </td>
                                    <td class="py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ list.Course_Name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="EmptyCurricula p-2 text-center" v-if="curriculaList.length <= 0">
                            <span class="whitespace-nowrap text-sm font-roboto tracking-wide text-[#253B80]">No Records of Curriculum for this Degree Program.</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /End replace -->
            <!-- FacultyModal -->
            <div v-if="showModal" class="fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                <div class="relative w-auto my-6 mx-auto min-w-[30vw] max-w-[80vw]">
                    <!--content-->
                    <div class="border-0 rounded-lg drop-shadow-2xl relative opacity-90 flex flex-col w-full bg-white outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-start justify-between py-2 px-4 border-b border-solid border-slate-200 rounded-t">
                        <span v-if = "(userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core')))"
                            class="font-sans text-sm font-bold">
                            {{ "["+model3.Subject_Code + "] " + model3.Subject_Name }} <br> {{( model3.yearLevel + " (Lec: " + model3.lec +" Lab: " + model3.lab +")")}}
                        </span>
                        <span v-else
                            class="font-sans text-sm font-bold">
                            {{ "["+model3.Subject_Code + "] " + model3.Subject_Name }} <br> {{( " [ Lec: " + model3.lec +" Lab: " + model3.lab +" ]")}}
                        </span>
                        <button class="p-1 ml-auto border-0 text-black float-right leading-none font-semibold outline-none focus:outline-none" v-on:click="toggleModal()">
                        <span class="text-gray-500 hover:text-gray-700 h-6 w-6 text-2xl block outline-none focus:outline-none">
                            ×
                        </span>
                        </button>
                    </div>
                    <!--body-->
                    <div class="overflow-auto h-auto max-h-[50vh] relative px-5 pb-2 flex-auto">
                        <div class="relative">
                            <label
                                for="faculty"
                                class="text-sm font-semibold text-gray-800"
                                >Saved Faculty
                            </label>
                            <button @click.prevent="addFacultyModal()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700">
                                    <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative" v-for="(fact, index) in facultiesModal" :key="index">
                            <select
                                required
                                v-model="fact.faculty"
                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                            >
                                <option disabled class="text-center" value="">Select a Faculty</option>
                                <option v-for="lists in facultyList"
                                        v-bind="lists.id"
                                        :value="lists.id"
                                        class="text-sky-600 text-justify"
                                        >
                                        {{ lists.Faculty_ID + " - " + lists.Faculty_Name }}
                                </option>
                            </select>
                            <button v-if="(facultiesModal.length > 1)" @click.prevent="removeFacultyModal(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700">
                                <path d="M10.375 2.25a4.125 4.125 0 100 8.25 4.125 4.125 0 000-8.25zM10.375 12a7.125 7.125 0 00-7.124 7.247.75.75 0 00.363.63 13.067 13.067 0 006.761 1.873c2.472 0 4.786-.684 6.76-1.873a.75.75 0 00.364-.63l.001-.12v-.002A7.125 7.125 0 0010.375 12zM16 9.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!--footer-->
                    <div class="flex items-center justify-center py-1 border-t border-solid border-slate-200 rounded-b">
                        <button class="text-sky-500 px-3 bg-transparent border border-solid border-2 border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold p-1 uppercase text-xs rounded outline-none focus:outline-none ease-linear transition-all duration-150" type="button" v-on:click="toggleModal()">
                        SAVE
                        </button>
                    </div>
                    </div>
                </div>
            </div>
            <div v-if="showModal" class="opacity-25 fixed inset-0 z-40 bg-slate-600"></div>
            <!-- ClassroomModal -->
            <div v-if="showModalC" class="fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                <div class="relative w-auto my-6 mx-auto min-w-[30vw] max-w-[80vw]">
                    <!--content-->
                    <div class="border-0 rounded-lg drop-shadow-2xl relative opacity-90 flex flex-col w-full bg-white outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-start justify-between py-2 px-4 border-b border-solid border-slate-200 rounded-t">
                        <span v-if = "(userType == 'reg' || (userType == 'dept' && (SubjectTypeDept2 == 'Core' || SubjectTypeDept == 'Core')))"
                            class="font-sans text-sm font-bold">
                            {{ "["+model3.Subject_Code + "] " + model3.Subject_Name }} <br> {{( model3.yearLevel + " (Lec: " + model3.lec +" Lab: " + model3.lab +")")}}
                        </span>
                        <span v-else
                            class="font-sans text-sm font-bold">
                            {{ "["+model3.Subject_Code + "] " + model3.Subject_Name }} <br> {{( " [ Lec: " + model3.lec +" Lab: " + model3.lab +" ]")}}
                        </span>
                        <button class="p-1 ml-auto border-0 text-black float-right leading-none font-semibold outline-none focus:outline-none" v-on:click="toggleModalC()">
                        <span class="text-gray-500 hover:text-gray-700 h-6 w-6 text-2xl block outline-none focus:outline-none">
                            ×
                        </span>
                        </button>
                    </div>
                    <!--body-->
                    <div class="overflow-y-auto h-auto max-h-[50vh] relative px-5 pb-2 flex-auto">
                        <div class="relative">
                            <label
                                for="faculty"
                                class="text-sm font-semibold text-gray-800"
                                >Saved Classroom
                            </label>
                            <button @click.prevent="addClassroomModal()" class="absolute top-1 lg:right-3 md:right-2 xl:right-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-sky-500 hover:fill-sky-700">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="relative" v-for="(classs, index) in classroomsModal" :key="index">
                            <select
                                required
                                v-model="classs.classroom"
                                class="mt-1 w-5/6 py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                            >
                                <option disabled class="text-center" value="">Select a Classroom</option>
                                <option v-for="lists in classroomList"
                                        v-bind="lists.id"
                                        :value="lists.id"
                                        class="text-sky-600 text-justify"
                                        >
                                        {{ lists.Building_No + " - " + lists.Classroom_No + " [" + lists.Classroom_Type + "]" }}
                                </option>
                            </select>
                            <button v-if="(classroomsModal.length > 1)" @click.prevent="removeClassroomModal(index)" class="absolute top-3 lg:right-3 md:right-2 xl:right-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-orange-500 hover:fill-orange-700">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!--footer-->
                    <div class="flex items-center justify-center py-1 border-t border-solid border-slate-200 rounded-b">
                        <button class="text-sky-500 px-3 bg-transparent border border-solid border-2 border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold p-1 uppercase text-xs rounded outline-none focus:outline-none ease-linear transition-all duration-150" type="button" v-on:click="toggleModalC()">
                        SAVE
                        </button>
                    </div>
                    </div>
                </div>
            </div>
            <div v-if="showModalC" class="opacity-25 fixed inset-0 z-40 bg-slate-600"></div>

        </div>
    </main>
</template>

<script>
import { ref, computed } from 'vue'
import { useVuelidate } from '@vuelidate/core';
import { required, minValue, maxValue, integer, numeric } from '@vuelidate/validators';

import APIController from '../controllers/api'

export default {
    name: 'Curriculum',
    setup() {
        const curr = ref(false);
        const currList = ref(false);

        const viewcurr1 = ref(false);
        const viewcurr2 = ref(false);

        const collegeList = ref([]);
        const courseList = ref([]);

        const subjectList = ref({});
        const facultyList = ref({});
        const classroomList = ref({});

        const initialHeader = {
            selectedAcademicYear: '',
            selectedSemester: '',
        }
        const modelHeader = ref({...initialHeader});
        const rulesHeader = computed(() => {
            return {
                selectedAcademicYear: { required },
                selectedSemester: { required },
            }
        });
        const h$ = useVuelidate(rulesHeader, modelHeader)
        const resetModel = () => {
            model.value = {...initialState};
            modelz.value = {...initialStatez};
        }
        const toggleAYSem = () => {
            if(!h$.value.$invalid){
                if(userType == 'reg'){
                    courseList.value = [];
                    fetchCurriculaList();
                }
                else
                    fetchCurriculaListCore();
                currList.value = true;
            } 
        }
        
        const initialState = {
            selectedCollege: '',
            selectedCourse: '',
        }
        const model = ref({...initialState});
        const rules = computed(() => {
            return {
                selectedCourse: { required },
                selectedCollege: { required },
            }
        })
        const v$ = useVuelidate(rules, model)

        const initialStatez = {
            selectedyearLevel: '',
            selectedCourse: '',
        }
        const modelz = ref({...initialStatez});
        const rulesz = computed(() => {
            return {
                selectedCourse: { required },
                selectedyearLevel: { required }
            }
        })
        const z$ = useVuelidate(rulesz, modelz)

        const initialState2 = {
            yearLevel: '',
            subject_id: '',
            lec: '',
            lab: ''
        }
        const model2 = ref({...initialState2});
        const rules2 = computed(() => {
            return {
                yearLevel: { required },
                subject_id: { required },
                lec: { required, numeric, integer, minValue: minValue(1), maxValue: maxValue(15) },
                lab: { required, numeric, integer, minValue: minValue(0), maxValue: maxValue(15) }
            }
        })
        const y$ = useVuelidate(rules2, model2)

        const initialState2a = {
            subject_id: '',
            lec: '',
            lab: ''
        }
        const model2a = ref({...initialState2a});
        const rules2a = computed(() => {
            return {
                subject_id: { required },
                lec: { required, numeric, integer, minValue: minValue(1), maxValue: maxValue(15) },
                lab: { required, numeric, integer, minValue: minValue(0), maxValue: maxValue(15) }
            }
        })
        const w$ = useVuelidate(rules2a, model2a)

        const reset = () => {
            model2.value = {...initialState2};
            model2a.value = {...initialState2a};
            MESubjType.value = '';
            subjectList.value = {};
            facultyList.value = {};
            faculties.value = [{faculty: ''}];
            facultiesModal.value = [{faculty: ''}];
            classroomList.value = {};
            classrooms.value = [{classroom: ''}];
            classroomsModal.value = [{classroom: ''}];
        }
        const reset2 = (val) => {
            if(val == 'Major')
                model.value = {...initialState};
            else
                modelz.value = {...initialStatez};
        }

        const toggleType = (val) => {
            if(val == 'Major'){
                fetchCoursesByDept();
                currList.value = false;
            }
            else {
                fetchColleges();
                fetchCurriculaListCore();
                currList.value = true;
            }
        }

        const model3 = ref([]);
        const searchQuery = ref("");

        const curriculumList = ref({});
        const curriculaList = ref({});
        const curriculumId = ref(false);

        const college = ref([]);
        const deptID = ref(null);

        const MESubjType = ref('');

        const countOf = ref([]);
        const SubjectTypeDept = ref('');
        const SubjectTypeDept2 = ref('');

        const faculties = ref([{faculty: ''}]);
        const faculties_id = ref([]);
        const facultiesModal = ref([{faculty: ''}]);
        const faculties_idModal = ref([]);

        const classrooms = ref([{classroom: ''}]);
        const classrooms_id = ref([]);
        const classroomsModal = ref([{classroom: ''}]);
        const classrooms_idModal = ref([]);

        const userType = localStorage.getItem('userType');

        const fetchColleges = async () => {
            collegeList.value = await APIController.FetchColleges();
        }

        const fetchCourses = async () => {
            courseList.value = await APIController.FetchCoursesCollege(model.value.selectedCollege);
        }

        const fetchCoursesByDept = async () => {
            courseList.value = await APIController.FetchCoursesByDept(deptID.value);
        }
        // const fetchSubjects = async () => {
        //     subjectList.value = await APIController.FetchSubjects();
        // }
        
        const fetchMESubjectsByCourseEDIT = async (id) => {
            subjectList.value = await APIController.FetchMESubjectsByCourseEDIT(modelz.value.selectedCourse, id);
        }
        const fetchMESubjectsByCourse = async () => {
            subjectList.value = await APIController.FetchMESubjectsByCourse(modelz.value.selectedCourse, MESubjType.value);
        }
        const fetchCoreSubjectsByDepartment = async () => {
            subjectList.value = await APIController.FetchCoreSubjectsByDepartment(deptID.value);
        }

        const fetchGECSubjectsByCollege = async () => {
            subjectList.value = await APIController.FetchGECSubjectsByCollege(localStorage.getItem('collegeId'));
        }

        const fetchCurricula = async () => {
            curriculumList.value = await APIController.FetchCurricula(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, localStorage.getItem('userID'));
        }
        const fetchCurriculaCore = async () => {
            curriculumList.value = await APIController.FetchCurriculaCore(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, localStorage.getItem('userID'));
        }
        const fetchCurriculaD = async () => {
            curriculumList.value = await APIController.FetchCurriculaYL(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, modelz.value.selectedCourse, modelz.value.selectedyearLevel, localStorage.getItem('userID'));
        }
        const fetchCurriculaList = async () => {
            curriculaList.value = await APIController.FetchCurriculaList(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
        }
        const fetchCurriculaListCore = async () => {
            curriculaList.value = await APIController.FetchCurriculaListCore(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
        }

        const GetCurriculum = async () => {
            model3.value = await APIController.FetchCurriculum(curriculumId.value);
            for(let i = 1; i < model3.value.faculties_id.length; i++){
                facultiesModal.value.push({faculty: ''});
            }
            for(let i = 0; i < model3.value.faculties_id.length; i++){
                facultiesModal.value[i].faculty = model3.value.faculties_id[i];
                faculties_idModal.value[i] = model3.value.faculties_id[i];
            }
            for(let i = 1; i < model3.value.classrooms_id.length; i++){
                classroomsModal.value.push({classroom: ''});
            }
            for(let i = 0; i < model3.value.classrooms_id.length; i++){
                classroomsModal.value[i].classroom = model3.value.classrooms_id[i];
                classrooms_idModal.value[i] = model3.value.classrooms_id[i];
            }
        }

        const FetchFaculties = async (id) => {
            facultyList.value = await APIController.FetchFacultiesByDepartmentSubject(id);
        }
        const FetchFacultiesModal = async (id) => {
            facultyList.value = await APIController.FetchFacultiesByDepartmentSubject(id);
        }

        const FetchClassrooms = async (id) => {
            if(localStorage.getItem('userType') == "reg")
                classroomList.value = await APIController.FetchClassroomsPerCollegeBySubject(id, "Lecture");
            else
                classroomList.value = await APIController.FetchClassroomsPerCollegeBySubject(id, "Both");
        }
        const FetchClassroomsModal = async (id) => {
            if(localStorage.getItem('userType') == "reg")
                classroomList.value = await APIController.FetchClassroomsPerCollegeBySubject(id, "Lecture");
            else
                classroomList.value = await APIController.FetchClassroomsPerCollegeBySubject(id, "Both");
        }

        const GetCountSubjects = async (id) => {
            countOf.value = await APIController.GetNumberofSubjectsByDepartment(id);
            if (countOf.value.core >= 1 && countOf.value.major >= 1)
                SubjectTypeDept2.value = '';
            else if (countOf.value.core < 1 && countOf.value.major >= 1)
                SubjectTypeDept2.value = 'Major';
            else if (countOf.value.core >= 1 && countOf.value.major < 1){
                SubjectTypeDept2.value = 'Core';
                fetchColleges();
                // fetchCurriculaListCore();
            }
            
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            // college.value.College_Name = college.value.College_Name.toUpperCase();
            if(userType == 'dept'){
                deptID.value = college.value.id;
                fetchCoursesByDept();
                GetCountSubjects(college.value.id);
            } else {
                fetchColleges();
                // fetchCurriculaList();
            }
            
        }

        const AddNewCurriculum = async () => {
            let toMap = {};
            let resultDuplicate = false;
            for (let i = 0; i < Object.keys(faculties.value).length; i++){
                faculties_id.value[i] = faculties.value[i].faculty;

                if (toMap[faculties_id.value[i]]){
                    resultDuplicate = true;
                }
                toMap[faculties_id.value[i]] = true;
            }
            let toMapC = {};
            let resultDuplicateC = false;
            for (let i = 0; i < Object.keys(classrooms.value).length; i++){
                classrooms_id.value[i] = classrooms.value[i].classroom;

                if (toMapC[classrooms_id.value[i]]){
                    resultDuplicateC = true;
                }
                toMapC[classrooms_id.value[i]] = true;
            }
            if(resultDuplicate)
                alert("Some of the Selected Faculties are the same.");
            else if(resultDuplicateC)
                alert("Some of the Selected Classrooms are the same.");
            else{
                if(userType == 'reg'){
                    let tempCurriculum = await APIController.CreateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model2.value.yearLevel, model2.value.subject_id, model2.value.lec+model2.value.lab, model2.value.lec, model2.value.lab, faculties_id.value, classrooms_id.value, localStorage.getItem('userID'));
                    if(tempCurriculum){
                        faculties.value = [{faculty: ''}],
                        classrooms.value = [{classroom: ''}],
                        faculties_id.value = [],
                        classrooms_id.value = [],
                        fetchCurricula(),
                        fetchGECSubjectsByCollege(),
                        reset();
                        fetchCurriculaList();
                    }
                } else{
                    if(SubjectTypeDept2.value == 'Core' || SubjectTypeDept.value == 'Core'){
                        let tempCurriculum = await APIController.CreateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model2.value.yearLevel, model2.value.subject_id, model2.value.lec+model2.value.lab, model2.value.lec, model2.value.lab, faculties_id.value, classrooms_id.value, localStorage.getItem('userID'));
                        if(tempCurriculum){
                            faculties.value = [{faculty: ''}],
                            classrooms.value = [{classroom: ''}],
                            faculties_id.value = [],
                            classrooms_id.value = [],
                            fetchCurriculaCore(),
                            fetchCoreSubjectsByDepartment();
                            fetchCurriculaListCore();
                            reset();
                        }
                    }
                    else {
                        let tempCurriculum = await APIController.CreateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, modelz.value.selectedCourse, modelz.value.selectedyearLevel, model2a.value.subject_id, model2a.value.lec+model2a.value.lab, model2a.value.lec, model2a.value.lab, faculties_id.value, classrooms_id.value, localStorage.getItem('userID'));
                        if(tempCurriculum){
                            faculties.value = [{faculty: ''}],
                            classrooms.value = [{classroom: ''}],
                            faculties_id.value = [],
                            classrooms_id.value = [],
                            fetchCurriculaD(),
                            reset();
                        }
                    }
                }
            }
        }

        const updateCurriculum = async () => {
            if(userType == 'reg'){
                let tempCurriculum = await APIController.UpdateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model3.value.yearLevel, model3.value.subject_id, model3.value.lec+model3.value.lab, model3.value.lec, model3.value.lab, model.value.selectedCourse, faculties_idModal.value, classrooms_idModal.value, curriculumId.value);
                if (tempCurriculum) {
                    fetchCurricula(),
                    fetchGECSubjectsByCollege(),
                    fetchCurriculaList();
                    curriculumId.value = false;
                    facultiesModal.value = [{faculty: ''}];
                    classroomsModal.value = [{classroom: ''}];
                    faculties_idModal.value = [];
                    classrooms_idModal.value = [];
                }
            } else {
                if(SubjectTypeDept2.value == 'Core' || SubjectTypeDept.value == 'Core'){
                    let tempCurriculum = await APIController.UpdateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model3.value.yearLevel, model3.value.subject_id, model3.value.lec+model3.value.lab, model3.value.lec, model3.value.lab, model.value.selectedCourse, faculties_idModal.value, classrooms_idModal.value, curriculumId.value);
                    if(tempCurriculum){
                        fetchCurriculaCore(),
                        fetchCoreSubjectsByDepartment();
                        fetchCurriculaListCore();
                        curriculumId.value = false;
                        facultiesModal.value = [{faculty: ''}];
                        classroomsModal.value = [{classroom: ''}];
                        faculties_idModal.value = [];
                        classrooms_idModal.value = [];
                    }
                }
                else {
                    let tempCurriculum = await APIController.UpdateCurriculum(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, modelz.value.selectedyearLevel, model3.value.subject_id, model3.value.lec+model3.value.lab, model3.value.lec, model3.value.lab, modelz.value.selectedCourse, faculties_idModal.value, classrooms_idModal.value, curriculumId.value);
                    if (tempCurriculum) {
                        fetchCurriculaD();
                        curriculumId.value = false;
                        facultiesModal.value = [{faculty: ''}];
                        classroomsModal.value = [{classroom: ''}];
                        faculties_idModal.value = [];
                        classrooms_idModal.value = [];
                    }
                }
            }
            
        }

        const deleteCurriculum = async id => {
            const success = await APIController.DeleteCurriculum(id);
            if (success) {
                if(userType == 'reg'){
                    fetchCurricula();
                    fetchCurriculaList();
                } else {
                    if(SubjectTypeDept2.value == 'Core' || SubjectTypeDept.value == 'Core')
                    {   fetchCurriculaCore();
                        fetchCurriculaListCore();
                    }
                    else
                        fetchCurriculaD();
                }
                
            }
        }

        const searchedCurricula = computed(() => {
            return curriculumList.value.filter((list) => {
                if(userType == 'reg'){
                    return (
                        list.yearLevel
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Units
                            .toString()
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.lec
                            .toString()
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.lab
                            .toString()
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1
                    )
                } else {
                    return (
                        list.Subject_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Type
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Units
                            .toString()
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.lec
                            .toString()
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.lab
                            .toString()
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
            if(SubjectTypeDept2.value == 'Major' || SubjectTypeDept.value == 'Major')
                fetchMESubjectsByCourseEDIT(list);
            curriculumId.value = list;
            facultiesModal.value = [{faculty: ''}];
            classroomsModal.value = [{classroom: ''}];

            GetCurriculum();
            // this.$refs.search.focus();
            setTimeout(()=> {
                document.getElementById('curriculum-edit-${list.id}').focus()
            }, 1);
        }
        
        function cancelEdit(){
            curriculumId.value = false;
            if(SubjectTypeDept2.value == 'Major' || SubjectTypeDept.value == 'Major')
            {   subjectList.value = {};
                MESubjType.value = '';
            }
            facultiesModal.value = [{faculty: ''}];
            classroomsModal.value = [{classroom: ''}];
        }

        function addFaculty(){
            faculties.value.push({faculty: ''});
        }
        function removeFaculty(index){
            faculties.value.splice(index, 1);
        }

        function addClassroom(){
            classrooms.value.push({classroom: ''});
        }
        function removeClassroom(index){
            classrooms.value.splice(index, 1);
        }

        const showModal = ref(false);
        const showModalC = ref(false);

        const toggleModal = () => {
            FetchFacultiesModal(model3.value.subject_id);
            let toMap = {};
            let resultDuplicate = false;
            let resultNone = false;
            faculties_idModal.value = [];
            for (let j = 0; j < Object.keys(facultiesModal.value).length; j++){
                if (facultiesModal.value[j].faculty != ''){
                    faculties_idModal.value[j] = facultiesModal.value[j].faculty;
                    if (toMap[faculties_idModal.value[j]])
                        resultDuplicate = true;
                } else{
                    resultNone = true;
                }
                toMap[faculties_idModal.value[j]] = true;
            }
            if(resultDuplicate){
                alert("Some of the Selected Faculties are the same.");
            } else if (resultNone){
                alert("Please select a Faculty to proceed.");
            } else {
                showModal.value = !showModal.value;
            }
        }
        const toggleModalC = () => {
            FetchClassroomsModal(model3.value.subject_id);
            let toMap = {};
            let resultDuplicate = false;
            let resultNone = false;
            classrooms_idModal.value = [];
            for (let j = 0; j < Object.keys(classroomsModal.value).length; j++){
                if (classroomsModal.value[j].classroom != ''){
                    classrooms_idModal.value[j] = classroomsModal.value[j].classroom;
                    if (toMap[classrooms_idModal.value[j]])
                        resultDuplicate = true;
                } else{
                    resultNone = true;
                }
                toMap[classrooms_idModal.value[j]] = true;
            }
            if(resultDuplicate){
                alert("Some of the Selected Classrooms are the same.");
            } else if (resultNone){
                alert("Please select a Classroom to proceed.");
            } else {
                showModalC.value = !showModalC.value;
            }
        }
        function addFacultyModal(){
            facultiesModal.value.push({faculty: ''});
        }
        function removeFacultyModal(index){
            facultiesModal.value.splice(index, 1);
        }
        function addClassroomModal(){
            classroomsModal.value.push({classroom: ''});
        }
        function removeClassroomModal(index){
            classroomsModal.value.splice(index, 1);
        }

        return {
            collegeList,
            courseList,
            model,
            v$,
            model2,
            y$,
            fetchColleges,
            fetchCourses,
            subjectList,
            // fetchSubjects,
            curr,
            AddNewCurriculum,
            reset,
            model3,
            curriculumList,
            fetchCurricula,
            curriculumId,
            searchedCurricula,
            searchQuery,
            highlightMatches,
            setToEditing,
            cancelEdit,
            updateCurriculum,
            deleteCurriculum,
            MESubjType,
            fetchMESubjectsByCourse,
            fetchMESubjectsByCourseEDIT,
            fetchCoreSubjectsByDepartment,
            fetchGECSubjectsByCollege,
            modelz,
            z$,
            userType,
            deptID,
            college,
            GetCollege,
            fetchCurriculaD,
            fetchCurriculaList,
            curriculaList,
            w$,
            model2a,
            countOf,
            GetCountSubjects,
            SubjectTypeDept,
            SubjectTypeDept2,
            fetchCurriculaCore,
            toggleType,
            reset2,
            fetchCoursesByDept,
            fetchCurriculaListCore,
            addFaculty,
            removeFaculty,
            FetchFaculties,
            facultyList,
            faculties_id,
            faculties,
            showModal,
            toggleModal,
            facultiesModal,
            addFacultyModal,
            removeFacultyModal,
            FetchFacultiesModal,
            addClassroom,
            removeClassroom,
            FetchClassrooms,
            classroomList,
            classrooms_id,
            classrooms,
            showModalC,
            toggleModalC,
            classroomsModal,
            addClassroomModal,
            removeClassroomModal,
            FetchClassroomsModal,
            modelHeader,
            h$,
            resetModel,
            toggleAYSem,
            currList,
            viewcurr1,
            viewcurr2
        }
    },
    mounted () {
        // if (this.curriculumId != false){
        //     this.GetCurriculum();
        // }
        this.GetCollege();
        // this.fetchSubjects();
    },
    methods: {
        onChangeSubject(e){
            return e.target.value;
        },
        postAjaxForm () {
            this.y$.$reset();
        }
    },
}
</script>
