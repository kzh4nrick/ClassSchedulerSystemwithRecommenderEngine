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
            <h1 class="w-3/5 lg:text-xl md:text-lg text-right uppercase lg:font-extrabold md:font-bold text-sky-900">
                Class Scheduler System with Recommender Engine
            </h1>
            <div class="flex justify-center w-2/5">
                <select v-model="h$.selectedAcademicYear.$model"
                        @change="toggleAYSem(), resetModel(), sched = false"
                        required class="invalid:border-orange-600 invalid:text-orange-600 focus:invalid:border-orange-600 focus:invalid:ring-orange-600 h-7.5 w-max text-sky-800 border border-sky-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-600 focus:border-sky-600 text-xs">
                    <option disabled value="" class="text-center">-- Academic Year --</option>
                    <option value="A.Y. 2022 - 2023" class="text-sky-600 text-justify">A.Y. 2022 - 2023</option>
                    <option value="A.Y. 2023 - 2024" class="text-sky-600 text-justify">A.Y. 2023 - 2024</option> -->
                </select>
                <select v-model="h$.selectedSemester.$model"
                        @change="toggleAYSem(), resetModel(), sched = false"
                        required class="ml-2 invalid:border-orange-600 invalid:text-orange-600 focus:invalid:border-orange-600 focus:invalid:ring-orange-600 h-7.5 w-max text-sky-800 border border-sky-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-600 focus:border-sky-600 text-xs">
                    <option disabled value="" class="text-center">-- Semester --</option>
                    <option value="First Semester" class="text-sky-600 text-justify">First Semester</option>
                    <option value="Second Semester" class="text-sky-600 text-justify">Second Semester</option> -->
                    <option value="Mid Semester / Summer" class="text-sky-600 text-justify">Mid Semester / Summer</option> -->
                </select>
            </div>
        </div>
    </header>
    <main>
        <div class="max-w-full py-2.5 md:px-4 sm:px-2 2xl:px-12 xl:px-9 lg:px-6">
            <form class="form" @submit.prevent="AddSchedule()">
            <div class="grid grid-cols-11 gap-2">
                <div class="col-span-4">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCollege.$model"
                        @keyup="v$.selectedCollege.$touch()"
                        @blur="v$.selectedCollege.$touch()"
                        required
                        @change="fetchCourses(), viewsched1 = true, sched = false"
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
                <div class="col-span-3">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCourse.$model"
                        @keyup="v$.selectedCourse.$touch()"
                        @blur="v$.selectedCourse.$touch()"
                        @change="fetchYearLevel(), viewsched2 = true, sched = false"
                        required
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
                <div class="col-span-1">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedYearLevel.$model"
                        @keyup="v$.selectedYearLevel.$touch()"
                        @blur="v$.selectedYearLevel.$touch()"
                        @change="viewsched3 = true, sched = false"
                        required
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-Year Level-</option>
                        <option v-for="list in yearLevelList"
                                :key="list.yearLevel"
                                :value="list.yearLevel"
                                class="text-sky-600 text-justify"
                                >
                                {{  list.yearLevel }}
                        </option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-- Year Level --</option>
                    </select>
                </div>
                <div class="col-span-1">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedBlock.$model"
                        @keyup="v$.selectedBlock.$touch()"
                        @blur="v$.selectedBlock.$touch()"
                        @change="viewsched4 = true, sched = false"
                        required
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">--Block--</option>
                        <option value="A" class="text-sky-600 text-center">A</option>
                        <option value="B" class="text-sky-600 text-center">B</option>
                        <option value="C" class="text-sky-600 text-center">C</option>
                    </select>
                    <select
                        v-else
                        disabled
                        class="cursor-not-allowed border-orange-500 text-orange-600 border-2 ring-orange-500 h-max w-full bg-white rounded-lg shadow-sm sm:text-sm"
                    >
                        <option disabled value="" class="text-center">--Block--</option>
                    </select>
                </div>
                <div class="ml-1 col-span-2">
                    <button
                        v-if="v$.$invalid"
                        disabled
                        type="submit"
                        class="cursor-not-allowed h-full border border-transparent 2xl:w-9/12 xl:w-10/12 lg:w-11/12 shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Schedule
                    </button>
                    <button
                        v-else-if="!(viewsched1 == true || viewsched2 == true || viewsched3 == true || viewsched4 == true)"
                        disabled
                        type="submit"
                        class="cursor-not-allowed h-full border border-transparent 2xl:w-9/12 xl:w-10/12 lg:w-11/12 shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Schedule
                    </button>
                    <button
                        v-else
                        @click="cancel(), viewsched1 = false, viewsched2 = false, viewsched3 = false, viewsched4 = false, viewSchedule = 'class'"
                        type="submit"
                        class="h-full border border-transparent 2xl:w-9/12 xl:w-10/12 lg:w-11/12 shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Schedule
                    </button>
                </div>
            </div>
            </form>
        </div>

        <div v-if="sched == false" class="max-w-full py-10 sm:px-24">
          <div class="text-center text-2xl font-mono font-black text-zinc-600 pt-40 border-4 border-dashed border-gray-200 rounded-lg h-96">
              All of the save records on the Curriculum will be shown here in the Class Scheduler.
          </div>
        </div>
        <div v-else class="max-w-full mx-auto pb-6 xs:px-px sm:px-1 lg:px-6 grid grid-cols-4">
            <!-- Replace with your content -->
            <!-- :time-step="5"
                timeCellHeight=20 -->
                <div v-if="loading1" class="loader"></div>
            <div v-if="(viewSchedule == 'class' && viewSchedule != 'faculty' && viewSchedule != 'classroom')" class="col-span-3 rounded-lg shadow-lg">
                <vue-cal class="rounded-lg"
                    selected-date="2022-08-01"
                    :time-from="7 * 60"
                    :time-to="21 * 60"
                    twelveHour="true"
                    hideTitleBar="true"
                    hideViewSelector="true"
                    disableViews="[day, month, year, years]"
                    :events= "events"
                    :on-event-click="onEventClick"
                    >
                    <template v-slot:event="{ event }">
                        <div class="font-sans 2xl:text-sm xl:text-sm lg:text-xs md:text-xs sm:text-xs sm:font-normal md:font-medium lg:font-semibold xl:font-bold 2xl:font-bold vuecal__event-title" v-html="event.title"/>
                        <!-- Or if your events are editable: -->
                        <div class="font-sans text-xs sm:font-light md:font-light lg:font-normal xl:font-medium 2xl:font-medium vuecal__event-time">
                            <span>{{ event.start.formatTime("hh:mm {am}") + " - " + event.end.formatTime("hh:mm {am}") }}</span>
                        <!-- Using Vue Cal injected Date prototypes -->
                        <br><span class="2xl:text-sm xl:text-sm lg:text-xs md:text-xs sm:text-xs sm:font-light md:font-normal lg:font-medium xl:font-semibold 2xl:font-semibold font-sans" v-html="event.content"></span>
                        </div>
                        
                    </template>
                </vue-cal>
            </div>

            <div v-else-if="showModalFaculty" class="fixed inset-x-10 inset-y-28 z-50 outline-none focus:outline-none">
                    <div class="w-full mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg bg-white outline-none focus:outline-none">
                            <div class="flex items-center justify-end rounded-t -mb-5">
                                <button class="pr-1.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalFaculty(), viewSchedule = 'class'">
                                <span class="bg-transparent text-gray-300 hover:text-gray-400 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <div class="grid grid-cols-12 gap-x-2 mb-2">
                                <div class="col-span-1"></div>
                                <select
                                    v-model="vf$.faculty.$model"
                                    required
                                    @change="vfs=true, printFact = false, infoF = false, resetVF()"
                                    class="col-span-3 cursor-pointer invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-auto text-sky-600 border-1 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                >
                                    <option disabled value="" class="text-center">Select a Faculty</option>
                                    <option v-for="list in facultiesList"
                                                :key="list.id"
                                                :value="list.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{  list.Faculty_Name }}
                                    </option>
                                </select>
                                <button
                                    v-if="vf$.$invalid"
                                    disabled
                                    class="cursor-not-allowed col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Faculty
                                </button>
                                <button
                                    v-else-if="vfs == false"
                                    disabled
                                    class="cursor-not-allowed col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Faculty
                                </button>
                                <button
                                    v-else
                                    @click="fetchFacultySchedules(), vfs = false, printFact = true, infoF= true, viewsched4 = true"
                                    class="col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-80 hover:bg-sky-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Faculty
                                </button>
                                
                                <div v-if="infoF == true" class="col-span-3 flex flex-col items-center justify-center text-sky-800 text-xs uppercase font-bold">
                                    <span>No. of Classes = {{ factInfo.count }}</span>
                                    <span>Units = {{ factInfo.lecU+factInfo.labU }} :: {{ factInfo.lecU+factInfo.labU*3 }} hrs &nbsp; Lec =  {{ factInfo.lecU  }} :: {{ factInfo.lecU }} hrs &nbsp; Lab = {{ factInfo.labU }} :: {{ factInfo.labU*3 }} hrs</span>
                                </div>
                                <div v-else class="col-span-3"></div>

                                <div class="col-span-2">
                                    <button v-if="printFact == true && facultyscheduleList.length > 0" @click="printFacultySchedule()"
                                        class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                    <button v-else disabled
                                        class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 opacity-60">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="rounded-lg shadow-lg">
                                <vue-cal class="rounded-lg"
                                    selected-date="2022-08-01"
                                    :time-from="7 * 60"
                                    :time-to="21 * 60"
                                    twelveHour="true"
                                    hideTitleBar="true"
                                    hideViewSelector="true"
                                    disableViews="[day, month, year, years]"
                                    :events= "eventss"
                                    >
                                    <template v-slot:event="{ event }">
                                        <div class="font-sans 2xl:text-sm xl:text-sm lg:text-xs md:text-xs sm:text-xs sm:font-normal md:font-medium lg:font-semibold xl:font-bold 2xl:font-bold vuecal__event-title" v-html="event.title"/>
                                        <!-- Or if your events are editable: -->
                                        <div class="font-sans text-xs sm:font-light md:font-light lg:font-normal xl:font-medium 2xl:font-medium vuecal__event-time">
                                            <span>{{ event.start.formatTime("hh:mm {am}") + " - " + event.end.formatTime("hh:mm {am}") }}</span>
                                        <!-- Using Vue Cal injected Date prototypes -->
                                        <br><span class="text-xs font-bold font-sans text-center" v-html="event.content"></span>
                                        </div>
                                        
                                    </template>
                                </vue-cal>
                            </div>
                        </div>
                    </div>
            </div>
            <div v-if="showModalFaculty" class="opacity-25 fixed inset-0 z-40 bg-slate-700"></div>

            <div v-else-if="showModalClassroom" class="fixed inset-x-10 inset-y-28 z-50 outline-none focus:outline-none">
                    <div class="w-full mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg bg-white outline-none focus:outline-none">
                            <div class="flex items-center justify-end rounded-t -mb-5">
                                <button class="pr-1.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalClassroom(), viewSchedule = 'class'">
                                <span class="bg-transparent text-gray-300 hover:text-gray-400 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <div class="grid grid-cols-12 gap-x-2 mb-2">
                                <select
                                    v-model="vc$.classroom.$model"
                                    required
                                    @change="printRoom = false, vcs=true, infoC = false, resetVC()"
                                    class="ml-8 col-span-3 cursor-pointer invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-auto text-sky-600 border-1 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                >
                                    <option disabled value="" class="text-center">Select a Classroom</option>
                                    <option v-for="list in classroomsList"
                                                :key="list.id"
                                                :value="list.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{  list.Building_No +" - "+ list.Classroom_No+" ( "+list.Classroom_Type+" )" }}
                                    </option>
                                </select>
                                <button
                                    v-if="vc$.$invalid"
                                    disabled
                                    class="cursor-not-allowed col-span-3 w-10/12 text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedules of this Classroom
                                </button>
                                <button
                                    v-else-if="vcs == false"
                                    disabled
                                    class="cursor-not-allowed col-span-3 w-10/12 text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedules of this Classroom
                                </button>
                                <button
                                    v-else
                                    @click="fetchClassroomSchedules(), printRoom = true, vcs = false, infoC = true, viewsched4 = true"
                                    class="col-span-3 w-10/12 text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-80 hover:bg-sky-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedules of this Classroom
                                </button>

                                <div v-if="infoC == true" class="col-span-3 flex flex-col items-center justify-center text-sky-800 text-xs uppercase font-bold">
                                    <span>No. of Classes = {{ roomInfo.count }}</span>
                                    <span>Units = {{ roomInfo.lecU+roomInfo.labU }} :: {{ roomInfo.lecU+roomInfo.labU*3 }} hrs &nbsp; Lec =  {{ roomInfo.lecU  }} :: {{ roomInfo.lecU }} hrs &nbsp; Lab = {{ roomInfo.labU }} :: {{ roomInfo.labU*3 }} hrs</span>
                                </div>
                                <div v-else class="col-span-3"></div>

                                <div class="col-span-2">
                                    <button v-if="printRoom == true && classroomscheduleList.length > 0" @click="printClassroomSchedule()"
                                        class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                    <button v-else disabled
                                        class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 opacity-60">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="rounded-lg shadow-lg">
                                <vue-cal class="rounded-lg"
                                    selected-date="2022-08-01"
                                    :time-from="7 * 60"
                                    :time-to="21 * 60"
                                    twelveHour="true"
                                    hideTitleBar="true"
                                    hideViewSelector="true"
                                    disableViews="[day, month, year, years]"
                                    :events= "eventsss"
                                    >
                                    <template v-slot:event="{ event }">
                                        <div class="font-sans 2xl:text-sm xl:text-sm lg:text-xs md:text-xs sm:text-xs sm:font-normal md:font-medium lg:font-semibold xl:font-bold 2xl:font-bold vuecal__event-title" v-html="event.title"/>
                                        <!-- Or if your events are editable: -->
                                        <div class="font-sans text-xs sm:font-light md:font-light lg:font-normal xl:font-medium 2xl:font-medium vuecal__event-time">
                                            <span>{{ event.start.formatTime("hh:mm {am}") + " - " + event.end.formatTime("hh:mm {am}") }}</span>
                                        <!-- Using Vue Cal injected Date prototypes -->
                                        <br><span class="text-xs font-bold font-sans" v-html="event.content"></span>
                                        </div>
                                        
                                    </template>
                                </vue-cal>
                            </div>
                        </div>
                    </div>
            </div>
            <div v-if="showModalClassroom" class="opacity-25 fixed inset-0 z-40 bg-slate-700"></div>

            <div v-else-if="showModalClassScheduleREG" class="fixed inset-x-10 inset-y-28 z-50 outline-none focus:outline-none">
                    <div class="w-full mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg bg-white outline-none focus:outline-none">
                            <div class="flex items-center justify-end rounded-t -mb-5">
                                <button class="pr-1.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalClassScheduleREG(), viewSchedule = 'class'">
                                <span class="bg-transparent text-gray-300 hover:text-gray-400 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <div class="grid grid-cols-12 gap-x-2 mb-2">
                                <div class="col-span-1"></div>
                                <select
                                    v-model="vr$.registrar.$model"
                                    required
                                    @change="vrs=true, printFact = false, infoR = false, resetVR()"
                                    class="col-span-3 cursor-pointer invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-auto text-sky-600 border-1 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                >
                                    <option disabled value="" class="text-center">Select a Class</option>
                                    <option v-for="list in schedulesList"
                                                :key="list.id"
                                                :value="list.id"
                                                class="text-sky-600 text-center"
                                                >
                                                {{ list.Course_Code +"\t"+ list.yearLevel + "-"+list.block }}
                                    </option>
                                </select>
                                <button
                                    v-if="vr$.$invalid"
                                    disabled
                                    class="cursor-not-allowed col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Class
                                </button>
                                <button
                                    v-else-if="vrs == false"
                                    disabled
                                    class="cursor-not-allowed col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-60 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Class
                                </button>
                                <button
                                    v-else
                                    @click="fetchClassSchedulesREG(), vrs = false, printSched = true, infoR= true, viewsched4 = true"
                                    class="col-span-2 w-full text-sm border border-transparent shadow-sm uppercase font-semibold font-mono rounded-lg text-white bg-[#0ea5e9] opacity-80 hover:bg-sky-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                    View Schedule of this Class
                                </button>
                                
                                <div v-if="infoR == true" class="col-span-3 flex flex-col items-center justify-center text-sky-800 text-xs uppercase font-bold">
                                    <span>No. of Subjects = {{ classInfo.count }}</span>
                                    <span>Units = {{ classInfo.lecU+classInfo.labU }} :: {{ classInfo.lecU+classInfo.labU*3 }} hrs &nbsp; Lec =  {{ classInfo.lecU  }} :: {{ classInfo.lecU }} hrs &nbsp; Lab = {{ classInfo.labU }} :: {{ classInfo.labU*3 }} hrs</span>
                                </div>
                                <div v-else class="col-span-3"></div>

                                <div class="col-span-2">
                                    <button v-if="printSched == true && classscheduleListREG.length > 0" @click="printAll = true, printClassSchedule()"
                                        class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                    <button v-else disabled
                                        class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-cyan-500 opacity-60">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="rounded-lg shadow-lg">
                                <vue-cal class="rounded-lg"
                                    selected-date="2022-08-01"
                                    :time-from="7 * 60"
                                    :time-to="21 * 60"
                                    twelveHour="true"
                                    hideTitleBar="true"
                                    hideViewSelector="true"
                                    disableViews="[day, month, year, years]"
                                    :events= "eventsREG"
                                    >
                                    <template v-slot:event="{ event }">
                                        <div class="font-sans 2xl:text-sm xl:text-sm lg:text-xs md:text-xs sm:text-xs sm:font-normal md:font-medium lg:font-semibold xl:font-bold 2xl:font-bold vuecal__event-title" v-html="event.title"/>
                                        <!-- Or if your events are editable: -->
                                        <div class="font-sans text-xs sm:font-light md:font-light lg:font-normal xl:font-medium 2xl:font-medium vuecal__event-time">
                                            <marquee scrollamount="1" direction="up" onmouseenter="this.stop()" onmouseleave="this.start()" class="flex justify-center" style="height:50%;"><span>{{ event.start.formatTime("hh:mm {am}") + " - " + event.end.formatTime("hh:mm {am}") }}</span>
                                        <!-- Using Vue Cal injected Date prototypes -->
                                        <br><span class="text-xs font-bold font-sans text-center" v-html="event.content"></span></marquee>
                                        </div>
                                        
                                    </template>
                                </vue-cal>
                            </div>
                        </div>
                    </div>
            </div>
            <div v-if="showModalClassScheduleREG" class="opacity-25 fixed inset-0 z-40 bg-slate-700"></div>

            <div class="ml-2 mt-1.5 h-min grid grid-cols-4">
                <div class="col-span-4 flex justify-end w-10/12 gap-x-2.5">
                    <button v-if="(curriculumListNotSave.length > 0)" @click="toggleModal()"
                        class="h-auto py-1.5 w-3/5 outline outline-offset-2 outline-1 outline-sky-400 hover:outline-sky-600 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
                        Generate Schedules
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-3/5 outline outline-offset-2 outline-1 outline-sky-400 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
                        Generate Schedules
                    </button>
                    <button v-if="(classscheduleList.length > 0)" @click="toggleModalDeleteSchedules()"
                        class="h-auto py-1.5 w-8 outline outline-offset-2 outline-1 outline-rose-600 hover:outline-rose-700 text-sm font-bold uppercase rounded-lg text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="hover:stroke-rose-400" width="31" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"> <path d="M2.5 2v6h6M21.5 22v-6h-6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-8 outline outline-offset-2 outline-1 outline-rose-600 text-sm font-bold uppercase rounded-lg text-white bg-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"> <path d="M2.5 2v6h6M21.5 22v-6h-6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
                    </button>
                </div>
                <div v-if="showModalAdd" class="mt-2 2xl:ml-8 static col-span-4 flex justify-end items-center z-50 outline-none focus:outline-none">
                    <div class="w-screen mx-auto max-w-screen">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg bg-white w-max outline-none focus:outline-none">
                            <div class="flex items-center justify-end rounded-t -mb-6">
                                <button class="pr-1.5 -mt-1.5 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalAdd()">
                                <span class="bg-transparent text-gray-300 hover:text-gray-400 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <form class="form" @submit.prevent="AddClassSchedule()"> 
                                <div class="h-full grid grid-cols-4 gap-2 shadow-lg overflow-hidden sm:rounded-lg bg-white sm:px-4 sm:py-3">
                                <!-- <form class="form" @submit.prevent="AddSchedule()"> -->
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm text-center font-medium text-gray-700"
                                        >Start Time
                                        </label>
                                        <input
                                                v-model="x$.StartTime.$model"
                                                @keyup="x$.StartTime.$touch()"
                                                @blur="x$.StartTime.$touch()"
                                                type="time"
                                                min="07:00" max="20:00"
                                                required
                                                class="invalid:border-orange-500 w-full invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                    </div>
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm text-center font-medium text-gray-700"
                                        >End Time
                                        </label>
                                        <input
                                                v-model="x$.EndTime.$model"
                                                @keyup="x$.EndTime.$touch()"
                                                @blur="x$.EndTime.$touch()"
                                                type="time"
                                                min="08:00" max="21:00"
                                                required
                                                class="invalid:border-orange-500 w-full invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="x$.Day.$model"
                                                @keyup="x$.Day.$touch()"
                                                @blur="x$.Day.$touch()"
                                                required
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Day</option>
                                            <option value="2022-08-01" class="text-sky-600 text-center">Monday</option>
                                            <option value="2022-08-02" class="text-sky-600 text-center">Tuesday</option>
                                            <option value="2022-08-03" class="text-sky-600 text-center">Wednesday</option>
                                            <option value="2022-08-04" class="text-sky-600 text-center">Thursday</option>
                                            <option value="2022-08-05" class="text-sky-600 text-center">Friday</option>
                                            <option value="2022-08-06" class="text-sky-600 text-center">Saturday</option>
                                            <option value="2022-08-07" class="text-sky-600 text-center">Sunday</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="x$.Subject.$model"
                                                @keyup="x$.Subject.$touch()"
                                                @blur="x$.Subject.$touch()"
                                                @change="fetchFaculties(onChangeSubject($event)), fetchClassrooms(onChangeSubject($event))"
                                                required
                                                class="mt-0.5 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Course</option>
                                        <option v-for="list in subjectList"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Subject_Code+" - "+list.Subject_Name }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="x$.Faculty.$model"
                                                @keyup="x$.Faculty.$touch()"
                                                @blur="x$.Faculty.$touch()"
                                                required
                                                
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Faculty</option>
                                        <option v-for="list in facultyList"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Faculty_Name }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="x$.Classroom.$model"
                                                @keyup="x$.Classroom.$touch()"
                                                @blur="x$.Classroom.$touch()"
                                                required
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Classroom</option>
                                        <option v-for="list in classroomList"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Building_No +" - "+ list.Classroom_No+" ( "+list.Classroom_Type+" )" }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4 grid grid-cols-4">
                                        <button
                                                v-if="x$.$invalid"
                                                disabled
                                                :class="(resetB == true) ? 'col-span-3' : 'col-span-4'"
                                                class="col-span-3 cursor-not-allowed h-auto py-1.5 w-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase tracking-widest rounded-lg text-white bg-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2">
                                                Save Schedule
                                        </button>
                                        <button
                                                v-else
                                                type="submit"
                                                @click="resetB = false"
                                                :class="(resetB == true || !x$.$invalid) ? 'col-span-3' : 'col-span-4'"
                                                class="col-span-3 h-auto py-1.5 w-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase tracking-widest rounded-lg text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                                                Save this Schedule
                                        </button>
                                        <button
                                                v-if="resetB == true || !x$.$invalid"
                                                @click="cancel(), resetB = false"
                                                class="col-span-1 h-auto py-1.5 ml-1.5 w-11/12 border border-transparent w-full shadow-sm text-sm font-extrabold uppercase tracking-widest rounded-lg text-white bg-slate-500 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                                Reset
                                        </button>
                                    </div>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                </div>
                <div v-if="showModalAdd" class="opacity-25 fixed inset-0 z-40 bg-slate-500"></div>
               
                <div v-if="(classscheduleID != false)" class="mt-2 2xl:ml-5 xl:ml-4 lg:ml-3 md:ml-2 static col-span-4 flex justify-end items-center z-50 outline-none focus:outline-none">
                    <div class="w-screen mx-auto max-w-screen">
                        <div class="w-full border-0 rounded-lg shadow-lg bg-white outline-none focus:outline-none">
                            <div class="flex items-center justify-between rounded-t -mb-6">
                                <button v-if="!showModalEditMode" class="ml-2.5 mt-2" @click="toggleModalEditMode()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="hover:stroke-sky-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3a6fb0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon></svg>
                                </button>
                                <button v-else class="ml-2.5 mt-2" @click="toggleModalEditMode()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="fill-sky-800 hover:fill-sky-600" viewBox="0 0 16 16">
                                        <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
                                    </svg>                                
                                </button>
                                <button class="pr-1.5 -mt-1.5 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="(classscheduleID = false, showModalEditMode = false)">
                                    <span class="bg-transparent text-gray-300 hover:text-gray-400 text-3xl outline-none focus:outline-none">
                                        ×
                                    </span>
                                </button>
                            </div>
                            <form v-if="showModalEditMode" class="form" @submit.prevent="updateClassSchedule()">
                                    <div v-if="loading" class="loader"></div>
                                <div class="h-full w-full grid grid-cols-4 gap-2 shadow-md overflow-hidden sm:rounded-lg bg-white sm:px-4 sm:py-2">
                                <!-- <form class="form" @submit.prevent="AddSchedule()"> -->
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm text-center font-medium text-gray-700"
                                        >Start Time
                                        </label>
                                        <input
                                                v-model="model3Edit.startTime"
                                                type="time"
                                                min="07:00" max="20:00"
                                                required
                                                class="invalid:border-orange-500 w-full invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                    </div>
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm text-center font-medium text-gray-700"
                                        >End Time
                                        </label>
                                        <input
                                                v-model="model3Edit.endTime"
                                                type="time"
                                                min="08:00" max="21:00"
                                                required
                                                class="invalid:border-orange-500 w-full invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        />
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="model3Edit.day"
                                                required
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Day</option>
                                            <option value="2022-08-01" class="text-sky-600 text-center">Monday</option>
                                            <option value="2022-08-02" class="text-sky-600 text-center">Tuesday</option>
                                            <option value="2022-08-03" class="text-sky-600 text-center">Wednesday</option>
                                            <option value="2022-08-04" class="text-sky-600 text-center">Thursday</option>
                                            <option value="2022-08-05" class="text-sky-600 text-center">Friday</option>
                                            <option value="2022-08-06" class="text-sky-600 text-center">Saturday</option>
                                            <option value="2022-08-07" class="text-sky-600 text-center">Sunday</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="model3Edit.subject_id"
                                                @change="fetchFaculties(onChangeSubject($event)), fetchClassrooms(onChangeSubject($event))"
                                                required
                                                class="mt-0.5 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Course</option>
                                        <option v-for="list in subjectListUpdate"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Subject_Code+" - "+list.Subject_Name }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="model3Edit.faculty_id"
                                                required
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Faculty</option>
                                        <option v-for="list in facultyList"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Faculty_Name }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4">
                                        <select
                                                v-model="model3Edit.classroom_id"
                                                required
                                                class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Select a Classroom</option>
                                        <option v-for="list in classroomList"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Building_No +" - "+ list.Classroom_No+" ( "+list.Classroom_Type+" )" }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-span-4 grid grid-cols-4">
                                        <button
                                                type="submit"
                                                class="col-span-3 h-max py-2 w-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase tracking-widest rounded-lg text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                                            Save Changes
                                        </button>
                                        <button
                                            type = "button"
                                            @click="cancel, showModalEditMode = false"
                                            class="col-span-1 h-max py-2 ml-1.5 w-11/12 border border-transparent w-full shadow-sm text-sm font-extrabold uppercase tracking-wider rounded-lg text-white bg-slate-500 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div v-else class="h-full w-full grid grid-cols-4 gap-2 shadow-md overflow-hidden sm:rounded-lg bg-white sm:px-4 sm:py-3">
                                <!-- <form class="form" @submit.prevent="AddSchedule()"> -->
                                <div class="col-span-2 flex flex-col w-full h-full">
                                    <label
                                        class="text-sm text-center font-medium text-gray-700"
                                    >Start Time
                                    </label>
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span class="text-sky-800 font-medium text-sm"
                                        >{{ model3.startTime }}</span>
                                    </div>
                                </div>
                                <div class="col-span-2 flex flex-col w-full h-full">
                                    <label
                                        class="text-sm text-center font-medium text-gray-700"
                                    >End Time
                                    </label>
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span class="text-sky-800 font-medium text-sm"
                                        >{{ model3.endTime }}</span>
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span
                                            class="text-sky-800 font-medium sm:text-sm"
                                    >{{ model3.day }}</span>
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span
                                            class="text-sky-800 font-medium sm:text-sm"
                                    >[ {{ model3.Subject_Code }} ] {{ model3.Subject_Name }}</span>
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span
                                            class="text-sky-800 font-medium sm:text-sm"
                                    >{{ model3.Faculty_Name }} [ {{ model3.Faculty_ID }} ]</span>
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <div class="flex items-center justify-center w-full h-9 cursor-text border-2 border-gray-500 rounded-lg">
                                        <span
                                            class="text-sky-800 font-medium sm:text-sm"
                                    >[ {{ model3.Building_No }} ] {{ model3.Classroom_No }} - {{ model3.Classroom_Type }}</span>
                                    </div>
                                </div>
                                <div class="col-span-4">
                                        <button
                                            @click="toggleModalDeleteSched()"
                                            class="h-max py-2 w-full border border-transparent shadow-sm text-sm font-extrabold uppercase tracking-wider rounded-lg text-white opacity-90 bg-red-700 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Delete this Schedule
                                        </button>
                                </div>
                                
                                <div v-if="clickSubj == 'GEC'"
                                    class="w-full border-t-2 pt-2 mr-1.5 col-span-4 grid grid-cols-6 w-auto">
                                    <button
                                        v-if="mcs$.$invalid"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-full text-xs border border-transparent shadow-sm font-semibold uppercase tracking-wider rounded-lg text-white bg-[#f97316] opacity-80 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                        Merge Class
                                    </button>
                                    <button
                                        v-else-if="mcs == false"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-full text-xs border border-transparent shadow-sm font-semibold uppercase tracking-wider rounded-lg text-white bg-[#f97316] opacity-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge This Class To:
                                    </button>
                                    <button
                                        v-else
                                        @click="mergeClassSchedule(), mcs = false"
                                        class="cursor-pointer col-span-2 w-full text-xs border border-transparent shadow-sm font-semibold uppercase tracking-wider rounded-lg text-white bg-[#f97316] opacity-80 hover:bg-orange-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge Class to
                                    </button>
                                    <select
                                                v-model="mcs$.mergeClass.$model"
                                                required
                                                @change="mcs=true"
                                                class="cursor-pointer col-span-4 ml-2 w-full px-1 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-auto text-sky-600 border-1 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    >
                                        <option disabled value="" class="text-center">Select a Class Schedule</option>
            
                                        <option v-for="list in classScheduleMinor"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  list.Course_Code +" - "+ list.yearLevel+" ( "+list.block+" ) || ( "+list.day+" ) " +list.startTime +" - "+ list.endTime + " || " + list.Faculty_Name + " || " + list.Building_No + "-" +list.Classroom_No }}
                                        </option>
                                    </select>
                                </div>
                                <div v-else
                                    class="w-full border-t-2 pt-2 mr-1.5 col-span-4 grid grid-cols-6 w-auto">
                                    <button
                                        v-if="mcs$.$invalid"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-full text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-80 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                        Merge Class
                                    </button>
                                    <button
                                        v-else-if="mcs == false"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-full text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge This Class To:
                                    </button>
                                    <button
                                        v-else
                                        @click="mergeClassSchedule(), mcs = false"
                                        class="cursor-pointer col-span-2 w-full text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-80 hover:bg-orange-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge Class to
                                    </button>
                                    <select
                                                v-model="mcs$.mergeClass.$model"
                                                required
                                                @change="mcs=true"
                                                class="cursor-pointer col-span-4 ml-2 w-full px-1 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-auto text-sky-600 border-1 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    >
                                        <option disabled value="" class="text-center">Select a Class Schedule</option>
            
                                        <option v-for="list in classScheduleMajor"
                                                    :key="list.id"
                                                    :value="list.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{  "Block - "+list.block+" || ( "+list.day+" ) " +list.startTime +" - "+ list.endTime + " || " + list.Faculty_Name + " || " + list.Building_No + "-" +list.Classroom_No}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="(classscheduleID != false)" class="opacity-25 fixed inset-0 z-40 bg-slate-500"></div>
                <div v-if="showModalDeleteSched" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                    <div class="relative w-auto mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                            <!--header-->
                            <div class="flex items-center justify-between pl-5 border-b border-solid rounded-t">
                                <span class="text-sm font-semibold uppercase">
                                !! Deletion of Schedule
                                </span>
                                <button class="pr-1 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalDeleteSched()">
                                <span class="bg-transparent text-gray-400 hover:text-gray-600 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <div class="my-2 flex items-center justify-center w-auto text-sm text-black font-medium">
                                <span class="px-10 text-center">This Schedule will be Deleted.</span>
                            </div>
                            <div class="mb-1 flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                                <button class="mt-1 text-red-500 bg-transparent border border-solid border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="deleteClassSchedule()">
                                Confirm
                                </button>
                                <button class="mt-1 ml-2 text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="toggleModalDeleteSched()">
                                Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="showModalDeleteSched" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>
                <!-- <div class="col-span-4">
                    <span class="sm:ml-6 lg:ml-10 text-xs font-medium font-sans text-zinc-700">Subjects: {{ details.noS1 }} / {{ details.noS }} || Units: {{ details.totU1 }} / {{ details.totU }} || Hrs/Wk: {{ details.totH1 }} / {{ details.totH }} </span>
                </div> -->

                <div class="col-span-4 border border-1 mt-3 shadow-md overflow-hidden sm:rounded-lg bg-white sm:px-3">
                    <div class="flex flex-col items-start h-auto gap-y-1">
                        <span class="border-b-2 -mb-px mt-0.5 w-full text-center text-xs uppercase text-sky-800 font-bold">Schedules' Status</span>
                        <div v-for="(stat, index) in curriculumStatus" :key="index"
                            class="flex items-center gap-x-1 w-max h-max text-xs text-sky-900 font-medium">
                            <svg v-if="stat.status == 'Complete'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55bc7c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <svg v-else-if="stat.status == 'Incomplete'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8f55bc" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            <svg v-else-if="stat.status == 'Not Saved'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bf071e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <svg v-else-if="stat.status == 'Exceeded'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#da7807" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <div class="textBox">
                                <span>[ {{ stat.Subject_Code }} ] {{ stat.Subject_Name }}</span>
                            </div>
                            <div class="textBox">
                                <span class="text-sky-600">|| Lec {{ stat.lec }} | Lab {{ stat.lab }} | Units: {{ stat.lab+stat.lec }} = Hours: {{ stat.reqHrs }} || Saved: {{ stat.mins/60 }} hrs</span>
                            </div>
                        </div>
                        <div v-if="(curriculumStatus.length <= 0)" class="uppercase flex items-center justify-center gap-x-1 w-full h-max text-xs text-red-700 font-medium">
                            <span>
                                No records of Schedules for this class.
                            </span>
                        </div>
                        <div v-if="statOther" v-for="(stat, index) in curriculumStatusOther" :key="index"
                            class="flex items-center gap-x-1 w-max h-max text-xs text-sky-900 font-medium">
                            <svg v-if="stat.status == 'Complete'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55bc7c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <svg v-else-if="stat.status == 'Incomplete'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8f55bc" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            <svg v-else-if="stat.status == 'Not Saved'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bf071e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <svg v-else-if="stat.status == 'Exceeded'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#da7807" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <div class="textBox">
                                <span>[ {{ stat.Subject_Code }} ] {{ stat.Subject_Name }}</span>
                            </div>
                            <div class="textBox">
                                <span class="text-sky-600">|| Lec {{ stat.lec }} | Lab {{ stat.lab }} | Units: {{ stat.lab+stat.lec }} = Hours: {{ stat.reqHrs }} || Saved: {{ stat.mins/60 }} hrs</span>
                            </div>
                        </div>
                        <div v-if="(statOther && curriculumStatusOther.length <= 0)" class="flex items-center gap-x-1 w-max h-max text-xs text-red-700 font-medium">
                            <span>
                                No records of Schedules for this class from the other user/s.
                            </span>
                        </div>
                        <div class="textBox2 flex items-center gap-x-1 text-xs text-gray-500 font-bold">
                            <span v-if="!statOther">
                                TOTALS: Courses: {{CSI1.count}}&nbsp; Credit Units = {{CSI1.lecU+CSI1.labU}} :: {{ CSI1.lecU+CSI1.labU*3 }} hrs &nbsp; Lecture Units = {{ CSI1.lecU }} :: {{ CSI1.lecU }} hrs &nbsp; Lab Units = {{ CSI1.labU }} :: {{ CSI1.labU*3 }} hrs
                            </span>
                            <span v-else>
                                TOTALS: Courses: {{CSI2.count}}&nbsp; Credit Units = {{CSI2.lecU+CSI2.labU}} :: {{ CSI2.lecU+CSI2.labU*3 }} hrs &nbsp; Lecture Units = {{ CSI2.lecU }} :: {{ CSI2.lecU }} hrs &nbsp; Lab Units = {{ CSI2.labU }} :: {{ CSI2.labU*3 }} hrs
                            </span>
                        </div>
                    </div>
                    <div class="mt-1.5 flex flex-row items-center gap-x-1 border-t-2 w-full h-max text-xs text-sky-800 font-medium">
                        <input type="checkbox" v-model="statOther" class="ml-0.5 mt-0.5 w-3 h-3 cursor-pointer border-1 border-sky-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500 rounded-sm"/>
                        <span class="mt-px">Show Schedules' Status from the other User/s.</span>
                    </div>
                    <div class="flex flex-col items-center h-auto gap-y-1">
                        <div class="mt-1 flex items-center gap-x-1 w-max h-max text-xs text-sky-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#55bc7c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span>Saved Completely</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-sky-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8f55bc" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            <span>Insufficient no. of Hours per Total Units</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-sky-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#bf071e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <span>Not Saved</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-sky-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#da7807" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>Exceeds no. of Hours per Total Units</span>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-sky-800 font-medium text-center">
                        <div class="mt-1 mr-1 w-2/12 h-6 border border-1 bg-[#45c2fc] border-[#095181] opacity-80 rounded-md mb-0.5"></div>
                        <span>Saved Schedule</span>
                    </div>
                    <div class="mb-1 flex items-center text-xs text-sky-800 font-medium text-center">
                        <div class="mr-1 w-2/12 h-6 border border-1 bg-[#6dd8db] border-[#025d6d] opacity-80 rounded-md pt-px"></div>
                        <span>Saved Schedules by the Other User/s</span>
                    </div>
                </div>

                <div class="col-span-4 flex justify-center mt-4">
                    <button v-if="(subjectList.length > 0)" @click="toggleModalAdd()"
                        class="h-auto py-1.5 w-3/5 outline outline-offset-2 outline-1 outline-sky-400 hover:outline-sky-600 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
                        Add Schedule
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-3/5 outline outline-offset-2 outline-1 outline-sky-400 text-sm font-bold uppercase rounded-lg text-white bg-sky-400">
                        Add Schedule
                    </button>
                </div>

                <div class="col-span-4 flex justify-center mt-4">
                    <button @click="toggleModalFaculty(), viewSchedule = 'faculty'"
                        class="h-auto py-1.5 w-4/6 outline outline-offset-2 outline-1 outline-cyan-400 hover:outline-cyan-600 text-sm font-bold uppercase rounded-lg text-white bg-cyan-400 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400">
                        View Faculty Schedule
                    </button>
                </div>
                <div class="col-span-4 flex justify-center mt-4">
                    <button @click="toggleModalClassroom(), viewSchedule = 'classroom'"
                        class="h-auto py-1.5 w-4/6 outline outline-offset-2 outline-1 outline-cyan-400 hover:outline-cyan-600 text-sm font-bold uppercase rounded-lg text-white bg-cyan-400 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400">
                        View Classroom Schedule
                    </button>
                </div>

                <div class="col-span-4 flex justify-center mt-4">
                    <button v-if="classscheduleList.length > 0 && userType == 'dept'" @click="toggleModalPrintClass()"
                        class="h-auto py-1.5 w-8/12 flex items-center justify-center outline outline-offset-2 outline-1 outline-teal-400 hover:outline-teal-600 text-sm hover:text-sky-300 font-bold uppercase rounded-lg text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-400">
                        Print This Class Schedule
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg>
                    </button>
                    <button v-else-if="classscheduleList.length > 0 && userType == 'reg'" @click="fetchSchedulesCollege(); toggleModalClassScheduleREG()"
                        class="h-auto py-1.5 w-8/12 flex items-center justify-center outline outline-offset-2 outline-1 outline-teal-400 hover:outline-teal-600 text-sm hover:text-sky-300 font-bold uppercase rounded-lg text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-400">
                        View Class Schedules
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg> -->
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-8/12 flex items-center justify-center outline outline-offset-2 outline-1 outline-teal-400 text-sm font-bold uppercase rounded-lg text-white bg-teal-500">
                        Print This Class Schedule
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- /End replace -->
        </div>

        <div v-if="showModal" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-auto mx-auto max-w-screen-xl">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-center justify-between pl-5 border-b border-solid rounded-t">
                        <span class="text-lg font-bold uppercase">
                        Generate Class Schedule
                        </span>
                        <button class="pr-2 -mt-1 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModal()">
                        <span class="bg-transparent text-gray-400 hover:text-gray-600 text-3xl outline-none focus:outline-none">
                            ×
                        </span>
                        </button>
                    </div>
                    <form class="form" @submit.prevent="generateClassSchedule()">
                        <!--body-->
                        <div class="px-5 pb-1 grid grid-cols-12 h-64 overflow-y-auto">
                            <div class="border-b col-span-12 py-1 grid grid-cols-12 gap-x-5">
                                <div class="col-span-1"></div>
                                <div class="col-span-5 flex items-center">
                                    <label
                                        for="start-time"
                                        class="w-full text-sm font-medium text-black"
                                        >Select Starting Time for Morning Classes<br>[ Choose within 07:00 am to 10:00 am ]
                                    </label>
                                    <input 
                                        v-model="chooseStartTimeM"
                                        type="time"
                                        min="07:00" max="10:00"
                                        required
                                        class="invalid:border-orange-500 w-2/5 mr-20 h-9 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    />
                                    <!-- <input v-if="amTP"
                                        v-else
                                        type="time"
                                        min="07:00" max="10:00"
                                        disabled
                                        class="w-2/5 mr-20 h-9 text-gray-500 border-2 border-gray-500 bg-white rounded-lg shadow-sm sm:text-sm"
                                    /> -->
                                </div>
                                <div class="col-span-5 flex items-center">
                                    <label
                                        for="start-time"
                                        class="w-full text-sm font-medium text-black"
                                        >Select Starting Time for Afternoon Classes<br>[ Choose within 01:00 pm to 04:00 pm ]
                                    </label>
                                    <input 
                                        v-model="chooseStartTimeA"
                                        type="time"
                                        min="13:00" max="16:00"
                                        required
                                        class="invalid:border-orange-500 w-2/5 mr-16 h-9 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    />
                                    <!-- <input v-if="pmTP"
                                        v-else
                                        type="time"
                                        min="13:00" max="16:00"
                                        disabled
                                        class="w-2/5 mr-20 h-9 text-gray-600 border-2 border-gray-500 bg-white rounded-lg shadow-sm sm:text-sm"
                                    /> -->
                                </div>
                            </div>
                            <div class="col-span-12 grid grid-cols-12 my-2">
                                <div class="col-span-3 flex justify-center items-center border-r">
                                    <label
                                        for="subject"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Subject
                                    </label>
                                </div>
                                <div class="col-span-2 flex justify-center items-center border-l border-r">
                                    <label
                                        for="split-session"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Split Session Into
                                    </label>
                                </div>
                                <div class="col-span-2 flex justify-center items-center border-l border-r">
                                    <label
                                        for="days"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Choose Preferred Days
                                    </label>
                                </div>
                                <div class="col-span-1 flex justify-center items-center border-l border-r">
                                    <label
                                        for="day"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Time Period
                                    </label>
                                </div>
                                <div class="col-span-2 flex justify-center items-center border-l border-r">
                                    <label
                                        for="faculty"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Faculty
                                    </label>
                                </div>
                                <div class="col-span-2 flex justify-center items-center border-l">
                                    <label
                                        for="classroom"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Classroom
                                    </label>
                                </div>
                            </div>
                            <div class="col-span-12 grid grid-cols-12 flex items-center border-t-2" v-for="(item, index) in preferredSchedule" :key="index">
                                <div class="col-span-3 pr-1 border-r">
                                    <span type="text" class="mt-1 block w-full py-2 px-3 border-2 border-gray-500 bg-white font-semibold rounded-md shadow-sm focus:outline-none sm:text-sm">
                                    {{ "[ " + item.Subject_Code + " ] " + item.Subject_Name }}
                                    </span>
                                </div>
                                <div class="col-span-1 flex flex-col items-center justify-center px-1 border-l border-r">
                                    <div>
                                        <label
                                            for="split-session-lec"
                                            class="text-sm font-medium text-gray-700"
                                            >Lec {{ " Units [" + item.lec +"]"}}
                                        </label>
                                    </div>
                                    <div class="-mt-1.5">
                                        <label for="one" class="text-sm font-medium text-gray-700">1</label>
                                        <input v-if="(item.lec >= 1)" v-model="item.split_lec" value=1 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="two" class="text-sm font-medium text-gray-700">2</label>
                                        <input v-if="(item.lec >= 2)" v-model="item.split_lec" value=2 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="three" class="text-sm font-medium text-gray-700">3</label>
                                        <input v-if="(item.lec >= 3)" v-model="item.split_lec" value=3 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col items-center justify-center px-1 border-l border-r">
                                    <div>
                                        <label
                                            for="split-session-lec"
                                            class="text-sm font-medium text-gray-700"
                                            >Lab {{ " Units [" + item.lab +"]"}}
                                        </label>
                                    </div>
                                    <div class="-mt-1.5">
                                        <label for="one" class="text-sm font-medium text-gray-700">1</label>
                                        <input v-if="(item.lab >= 1)" v-model="item.split_lab" value=1 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="two" class="text-sm font-medium text-gray-700">2</label>
                                        <input v-if="(item.lab >= 1)" v-model="item.split_lab" value=2 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="three" class="text-sm font-medium text-gray-700">3</label>
                                        <input v-if="(item.lab >= 1)" v-model="item.split_lab" value=3 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                    </div>
                                </div>
                                <div class="col-span-2 flex items-center justify-center border-l border-r gap-x-1.5">
                                    <div v-for="(items, indexx) in preferredDays">
                                        <input type="checkbox" :value="indexx" v-model="item.prefDays" :disabled="(item.prefDays.length >= 3 && item.prefDays.indexOf(indexx) == -1)"
                                                class="cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500 rounded-sm">
                                        <label for="preferred_days" class="ml-px text-sm font-medium text-gray-700">{{ items }}</label>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col px-1 border-l border-r">
                                    <div>
                                        <input v-model="item.time_period" value="Morning" type="radio" required class="cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <label for="morning" class="ml-1 text-sm font-medium text-gray-700">Morning</label>
                                    </div>
                                    <div>
                                        <input v-model="item.time_period" value="Afternoon" type="radio" required class="cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <label for="afternoon" class="ml-1 text-sm font-medium text-gray-700">Afternoon</label>
                                    </div>
                                </div>
                                <div class="col-span-2 px-1 border-l border-r">
                                    <select
                                        v-model="item.f_id"
                                        required
                                        class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    >
                                        <option disabled class="text-center" value="">Select a Faculty</option>
                                        <option v-for="lists in item.faculty_id"
                                                v-bind="lists.id"
                                                :value="lists.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{ "[" + lists.Faculty_ID + "] " + lists.Faculty_Name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-span-2 pl-1 border-l">
                                    <select
                                        v-model="item.c_id"
                                        required
                                        class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                    >
                                        <option disabled class="text-center" value="">Select a Classroom</option>
                                        <option v-for="lists in item.classroom_id"
                                                v-bind="lists.id"
                                                :value="lists.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{ lists.Building_No + " - " + lists.Classroom_No + " [ " + lists.Classroom_Type + " ]" }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--footer-->
                        <div class="flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                            <button v-if="(curriculumListNotSave.length > 0)" type="submit" class="text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-4 py-2 rounded outline-none focus:outline-none my-2 ease-linear transition-all duration-150">
                            Generate Schedules
                            </button>
                            <button v-else type="submit" disabled class="cursor-not-allowed text-sky-500 bg-transparent border border-solid border-sky-500 font-bold uppercase text-sm px-4 py-2 rounded outline-none focus:outline-none my-2 ease-linear transition-all duration-150">
                            Generate Schedules
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="opacity-25 fixed inset-0 z-40 bg-slate-600"></div>

        <div v-if="showModalDeleteSchedules" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-auto mx-auto">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-center justify-between pl-5 border-b border-solid rounded-t">
                        <span class="text-sm font-semibold uppercase">
                        Warning!
                        </span>
                        <button class="pr-1 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalDeleteSchedules()">
                        <span class="bg-transparent text-gray-400 hover:text-gray-600 text-3xl outline-none focus:outline-none">
                            ×
                        </span>
                        </button>
                    </div>
                    <div class="my-2 flex items-center justify-center w-auto text-sm text-black font-medium">
                        <span class="px-10 text-center">All of the saved Schedules on this Class by this user will be deleted. <br> Do you want to continue?</span>
                    </div>
                    <div class="mb-1 flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                        <button class="mt-1 text-red-500 bg-transparent border border-solid border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="deleteClassSchedulesReset()">
                        Yes
                        </button>
                        <button class="mt-1 ml-2 text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="toggleModalDeleteSchedules()">
                        No
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModalDeleteSchedules" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>

        <div v-if="showModalPrintClass" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-auto mx-auto">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-center justify-between pl-5 border-b border-solid rounded-t">
                        <span class="text-sm font-semibold uppercase">
                        Printing Options
                        </span>
                        <button class="pr-1 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalPrintClass()">
                        <span class="bg-transparent text-gray-400 hover:text-gray-600 text-3xl outline-none focus:outline-none">
                            ×
                        </span>
                        </button>
                    </div>
                    <div class="my-2 flex items-center justify-center w-auto text-sm text-black font-medium">
                        <p class="text-center">Do you want to include the saved Schedules<br>for this class from the other User/s to be printed?</p>
                    </div>
                    <div class="py-1 mb-1 mx-2 flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                        <button class="mt-1 text-red-500 bg-transparent border border-solid border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="printAll = true; printClassSchedule()">
                        Yes
                        </button>
                        <button class="mt-1 ml-2 text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="printAll = false; printClassSchedule()">
                        No, Only the Saved Schedules from this account.
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModalPrintClass" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>
    </main>
</template>

<script>
import { ref, computed } from 'vue'
import { useVuelidate } from '@vuelidate/core';
import { required, minValue, maxValue, integer } from '@vuelidate/validators';
import { jsPDF } from "jspdf";
import { autoTable } from 'jspdf-autotable'

import APIController from '../controllers/api'

import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import { reactive } from '@vue/reactivity';

export default {
    name: 'Class Scheduler',
    components: { VueCal },
    setup () {
        const userType = ref(null);
        
        const showModal = ref(false);
        
        const showModalAdd = ref(false);
        const toggleModalAdd = () => {
            showModalAdd.value = !showModalAdd.value;
        }
        const showModalUpdate = ref(false);
        const toggleModalUpdate = () => {
            showModalUpdate.value = !showModalUpdate.value;
        }
        const showModalFaculty = ref(false);
        const toggleModalFaculty = () => {
            showModalFaculty.value = !showModalFaculty.value;
            faculty.value = {...initialVF};
            resetVF();
            infoF.value = false;
            printFact.value = null;
        }
        const showModalClassroom = ref(false);
        const toggleModalClassroom = () => {
            showModalClassroom.value = !showModalClassroom.value;
            classroom.value = {...initialVC};
            resetVC();
            infoC.value = false;
            printRoom.value = null;
        }
        const showModalClassScheduleREG = ref(false);
        const toggleModalClassScheduleREG = () => {
            showModalClassScheduleREG.value = !showModalClassScheduleREG.value;
            registrar.value = {...initialVR};
            resetVR();
            infoR.value = false;
            printSched.value = null;
        }

        const showModalDeleteSchedules = ref(false);
        const toggleModalDeleteSchedules = () => {
            showModalDeleteSchedules.value = !showModalDeleteSchedules.value;
        }

        const showModalDeleteSched = ref(false);
        const toggleModalDeleteSched = () => {
            showModalDeleteSched.value = !showModalDeleteSched.value;
        }

        const showModalEditMode = ref(false);
        const toggleModalEditMode = () => {
            showModalEditMode.value = !showModalEditMode.value;
        }

        const printAll = ref(null);
        const showModalPrintClass = ref(false);
        const toggleModalPrintClass = () => {
            showModalPrintClass.value = !showModalPrintClass.value;
        }

        const printClassSchedule = async () => {
            let temp;
            let cs;
            if(printAll.value == false){
                temp = await APIController.FetchScheduleInfo(schedule.value);
                cs = await APIController.FetchClassSchedulesInfoUser(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
            }
            else if(printAll.value == true){
                if(userType.value == 'dept'){                    
                    temp = await APIController.FetchScheduleInfo(schedule.value);
                    cs = await APIController.FetchClassSchedulesInfo(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
                }
                else{
                    temp = await APIController.FetchScheduleInfo(registrar.value.registrar);
                    cs = await APIController.FetchClassSchedulesInfo(registrar.value.registrar, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
                }
            }
                
            const doc = new jsPDF();
            let subject = cs.length;
            let lecU = 0;
            let labU = 0;
            var rowBody = [];

            cs.forEach(e => {
                lecU = lecU + e.lec;
                labU = labU + e.lab;
                var temp = [e.Subject_Code, e.Subject_Name, e.lec+e.lab+'.0', e.lec+'.0', e.lab+'.0', e.Day, e.Time, e.Room, e.Faculty];
                rowBody.push(temp);
            });
            
            let credit = lecU + labU;
            let labHrs = labU * 3;
            let creditHrs = lecU + labHrs;
            
            // Header
            doc.setFontSize(9);
            doc.text("Republic of the Philippines", 105, 25, {align: 'center'})
            doc.text("Bicol University", 105, 28, {align: 'center'})
            doc.text("("+temp.Campus+")", 105, 32, {align: 'center'})
            doc.addImage("src/assets/buLogo.png", "PNG", 71, 21.5, 12, 12);

            doc.setFontSize(9);
            doc.text(temp.College_Name, 17, 45);
            doc.text(temp.academicYear, 178, 45, {align: 'center'});
            doc.text(temp.semester, 178, 49, {align: 'center'});
            doc.text(temp.Course_Name, 17, 50);
            doc.text("Year Level: "+temp.yearLevel+"   Block: "+temp.block, 17, 55);

            doc.setFontSize(7);
            doc.text("TOTALS:   Courses: "+subject+"   Credit Units = "+credit+".00 :: "+creditHrs+" hrs     Lecture Units = "+lecU+".00 :: "+lecU+" hrs     Lab Units = "+labU+".00 :: "+labHrs+" hrs", 15, 65);

            doc.autoTable({
                theme: 'plain',
                startY: 68,
                headStyles: { fillColor: [14, 165, 233],
                              textColor: [255, 255, 255],
                              
                            },
                bodyStyles: { 
                              fontSize: 9,
                              cellPadding: {top: 3, bottom: 3, left: 1.5, right: 1}
                            },
                head: [['Code', 'Course', 'Credit', 'Lec', 'Lab', 'Days', 'Time', 'Room', 'Faculty']],
                body:  rowBody
  
            })

            let YL = 0;
            switch(temp.yearLevel){
                case 'First Year':
                    YL = 1;
                    break;
                case 'Second Year':
                    YL = 2;
                    break;
                case 'Third Year':
                    YL = 3;
                    break;
                case 'Fourth Year':
                    YL = 4;
                    break;
            }
            
            if(printAll.value == false){
                doc.save(temp.College_Code+'_'+temp.Course_Code+'_'+YL+'-'+temp.block+' ['+temp.academicYear+'_'+temp.semester+'].pdf');
                printAll.value = null;
            }
            else if(printAll.value== true){
                doc.save(temp.College_Code+'_'+temp.Course_Code+'_'+YL+'-'+temp.block+'[ALL] ['+temp.academicYear+'_'+temp.semester+'].pdf');
                printAll.value = null;
            }
            // toggleModalPrintClass();
            showModalPrintClass.value = false;
            showModalClassScheduleREG.value = false;
        }

        const printFacultySchedule = async () => {
            let fact = await APIController.FetchFaculty(faculty.value.faculty);
            let temp = await APIController.FetchScheduleInfo(schedule.value);
            let cs = await APIController.FetchFacultySchedulesInfo(faculty.value.faculty, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
 
            const doc = new jsPDF();
            let class_ = cs.length;
            let lecU = 0;
            let labU = 0;
            var rowBody = [];

            cs.forEach(e => {
                lecU = lecU + e.lec;
                labU = labU + e.lab;
                var tempcs = [e.Class, e.Subject_Code, e.Subject_Name, e.lec+e.lab+'.0', e.lec+'.0', e.lab+'.0', e.Day, e.Time, e.Room];
                rowBody.push(tempcs);
            });
            
            let credit = lecU + labU;
            let labHrs = labU * 3;
            let creditHrs = lecU + labHrs;
            
            // Header
            doc.setFontSize(9);
            doc.text("Republic of the Philippines", 105, 25, {align: 'center'})
            doc.text("Bicol University", 105, 28, {align: 'center'})
            doc.text("("+temp.Campus+")", 105, 32, {align: 'center'})
            doc.addImage("src/assets/buLogo.png", "PNG", 71, 21.5, 12, 12);

            doc.setFontSize(9);
            doc.text(temp.College_Name, 17, 45);
            doc.text(temp.Department_Name, 17, 50);
            doc.text(temp.academicYear, 178, 45, {align: 'center'});
            doc.text(temp.semester, 178, 49, {align: 'center'});
            doc.text(fact.Faculty_Name+"     ID: "+fact.Faculty_ID, 17, 55);
            // doc.text("Faculty ID: "+fact.Faculty_ID, 17, 60);

            doc.setFontSize(7);
            doc.text("TOTALS:   Class: "+class_+"   Credit Units = "+credit+".00 :: "+creditHrs+" hrs     Lecture Units = "+lecU+".00 :: "+lecU+" hrs     Lab Units = "+labU+".00 :: "+labHrs+" hrs", 15, 65);

            doc.autoTable({
                theme: 'plain',
                startY: 68,
                headStyles: { fillColor: [14, 165, 233],
                              textColor: [255, 255, 255],
                              
                            },
                bodyStyles: { 
                              fontSize: 9,
                              cellPadding: {top: 3, bottom: 3, left: 1.5, right: 1}
                            },
                head: [['Class', 'Code', 'Course', 'Credit', 'Lec', 'Lab', 'Days', 'Time', 'Room']],
                body:  rowBody
  
            })
            
            doc.save(temp.College_Code+'_'+fact.Faculty_Name+'_Schedule ['+temp.academicYear+'_'+temp.semester+'].pdf');
        }

        const printClassroomSchedule = async () => {
            let room = await APIController.FetchClassroom(classroom.value.classroom);
            let temp = await APIController.FetchScheduleInfo(schedule.value);
            let cs = await APIController.FetchClassroomSchedulesInfo(classroom.value.classroom, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
 
            const doc = new jsPDF();
            let class_ = cs.length;
            let lecU = 0;
            let labU = 0;
            var rowBody = [];

            cs.forEach(e => {
                lecU = lecU + e.lec;
                labU = labU + e.lab;
                var tempcs = [e.Class, e.Subject_Code, e.Subject_Name, e.lec+e.lab+'.0', e.lec+'.0', e.lab+'.0', e.Day, e.Time, e.Faculty];
                rowBody.push(tempcs);
            });
            
            let credit = lecU + labU;
            let labHrs = labU * 3;
            let creditHrs = lecU + labHrs;
            // Header
            doc.setFontSize(9);
            doc.text("Republic of the Philippines", 105, 25, {align: 'center'})
            doc.text("Bicol University", 105, 28, {align: 'center'})
            doc.text("("+temp.Campus+")", 105, 32, {align: 'center'})
            doc.addImage("src/assets/buLogo.png", "PNG", 71, 21.5, 12, 12);

            doc.setFontSize(9);
            doc.text(temp.College_Name, 17, 45);
            doc.text(temp.academicYear, 178, 45, {align: 'center'});
            doc.text(temp.semester, 178, 49, {align: 'center'});
            doc.text("Building No.: "+room.Building_No+"     Room No.: "+room.Classroom_No, 17, 50);
            doc.text("Classroom Type: "+room.Classroom_Type, 17, 55);
            // doc.text("Faculty ID: "+fact.Faculty_ID, 17, 60);

            doc.setFontSize(7);
            doc.text("TOTALS:   Class: "+class_+"   Credit Units = "+credit+".00 :: "+creditHrs+" hrs     Lecture Units = "+lecU+".00 :: "+lecU+" hrs     Lab Units = "+labU+".00 :: "+labHrs+" hrs", 15, 65);

            doc.autoTable({
                theme: 'plain',
                startY: 68,
                headStyles: { fillColor: [14, 165, 233],
                              textColor: [255, 255, 255],
                              
                            },
                bodyStyles: { 
                              fontSize: 9,
                              cellPadding: {top: 3, bottom: 3, left: 1.5, right: 1}
                            },
                head: [['Class', 'Code', 'Course', 'Credit', 'Lec', 'Lab', 'Days', 'Time', 'Faculty']],
                body:  rowBody
  
            })
            
            doc.save(temp.College_Code+'_'+room.Building_No+'-'+room.Classroom_No+'_Schedule ['+temp.academicYear+'_'+temp.semester+'].pdf');
        }

        const amTP = ref(false);
        const pmTP = ref(false);
        const toggleTimePeriod = () => {
            amTP.value = false;
            pmTP.value = false;
            for(let i = 0; i < preferredSchedule[i].value.time_period.length; i++)
                if(preferredSchedule[i].value.time_period == "Morning")
                    amTP.value = true;
                else
                    pmTP.value = true;
        }

        const preferredDays = ['M', 'T', 'W', 'Th', 'F', 'S'];

        const preferredSchedule = ref([
            // subject_id: '',
            // Subject_Name: '',
            // Subject_Code: '',
            // split_lec: 0,
            // split_lab: 0,
            // lec: '',
            // lab: '',
            // prefDays: [],
            // time_period: '',
            // faculty_id: {},
            // classroom_id: {},
            // f_id: '',
            // c_id: ''
        ]);

        // const showPref = () => {
        //     const subjects = preferredSchedule.value.map(function (element) {
        //         return element.subject_id;
        //     });
        //     const lec = preferredSchedule.value.map(function (element) {
        //         return element.lec;
        //     });
        //     const lab = preferredSchedule.value.map(function (element) {
        //         return element.lab;
        //     });
        //     const prefDays = preferredSchedule.value.map(function (element) {
        //         let x = JSON.stringify(element.prefDays.toString());
        //         let xx = JSON.parse(x);
        //         return xx;
        //     });
        //     const split_lec = preferredSchedule.value.map(function (element) {
        //         return JSON.parse(element.split_lec);
        //     });
        //     const split_lab = preferredSchedule.value.map(function (element) {
        //         return JSON.parse(element.split_lab);
        //     });
        //     const time_period = preferredSchedule.value.map(function (element) {
        //         return element.time_period;
        //     });
        //     const faculties = preferredSchedule.value.map(function (element) {
        //         return element.f_id;
        //     });
        //     const classrooms = preferredSchedule.value.map(function (element) {
        //         return element.c_id;
        //     });
        //     // const prefDayss = JSON.stringify(prefDays);
        //     let alertPD = false;
        //     let alertTP = false;
        //     for(let i =0; i < prefDays.length; i++)
        //         if(prefDays[i].length == 0)
        //             alertPD = true;
        //     for(let i =0; i < time_period.length; i++)
        //         if(time_period[i] == '')
        //             alertTP = true;

        //     if(alertPD)
        //         alert("Please select atleast one for the Preferred Days on each row.");
        //     else if(alertTP)
        //         alert("Please select a Time Period on each row.");

        //     console.log(schedule.value, chooseStartTimeM.value, chooseStartTimeA.value, subjects, lec, lab, split_lec, split_lab, JSON.stringify(prefDays), time_period, faculties, classrooms);
        // };

        const chooseStartTimeM = ref(null);
        const chooseStartTimeA = ref(null);

        const onefaculty = ref({});
        const oneclassroom = ref({});

        const sched = ref(false);
        let tempSchedule = ref([]);

        const schedule = ref([]);

        const viewsched1 = ref(false);
        const viewsched2 = ref(false);
        const viewsched3 = ref(false);
        const viewsched4 = ref(false);

        const infoF = ref(false);
        const infoC = ref(false);
        const infoR = ref(false);
        const printSched = ref(false);
        const printFact = ref(false);
        const printRoom = ref(false);
        const vfs = ref(false);
        const vcs = ref(false);
        const vrs = ref(false);
        const viewSchedule = ref('');

        const initialVF = {
            faculty: '',
        }

        const faculty = ref({...initialVF});
        const rulesVF = computed(() => {
            return {
                faculty: { required }
            }
        })

        const vf$ = useVuelidate(rulesVF, faculty);

        const initialVC = {
            classroom: '',
        }

        const classroom = ref({...initialVC});
        const rulesVC = computed(() => {
            return {
                classroom: { required }
            }
        })

        const vc$ = useVuelidate(rulesVC, classroom);

        const initialVR = {
            registrar: '',
        }

        const registrar = ref({...initialVR});
        const rulesVR = computed(() => {
            return {
                registrar: { required }
            }
        })

        const vr$ = useVuelidate(rulesVR, registrar);

        //<--Merging
        const mcs = ref(false);

        const initialMCS = {
            mergeClass: '',
        }

        const mergeClass = ref({...initialMCS});
        const rulesMCS = computed(() => {
            return {
                mergeClass: { required }
            }
        })

        const mcs$ = useVuelidate(rulesMCS, mergeClass);
        //-->Merging

        //header for A.Y. and Sem
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
        }
        const toggleAYSem = () => {
            if(!h$.value.$invalid)
                fetchColleges();
        }

        const initialState = {
            selectedCollege: '',
            selectedCourse: '',
            selectedYearLevel: '',
            selectedBlock: '',
        }

        const collegeList = ref([]);
        const courseList = ref([]);

        const events = ref([]); //class schedules
        const eventss = ref([]); //faculty schedules
        const eventsss = ref([]); //classroom schedules
        const eventsREG = ref([]);//view schedules for registrar

        const classscheduleList = ref([]);
        const facultyscheduleList = ref([]);
        const classroomscheduleList = ref([]);
        const classscheduleListREG = ref([]);

        const factInfo = ref({
            count: 0,
            lecU: 0,
            labU: 0
        });
        const roomInfo = ref({
            count: 0,
            lecU: 0,
            labU: 0
        });
        const classInfo = ref({
            count: 0,
            lecU: 0,
            labU: 0
        });
        const CSI1 = ref({
            count: 0,
            lecU: 0,
            labU: 0
        });
        const CSI2 = ref({
            count: 0,
            lecU: 0,
            labU: 0
        });

        const model = ref({...initialState});

        const rules = computed(() => {
            return {
                selectedCourse: { required },
                selectedCollege: { required },
                selectedYearLevel: { required },
                selectedBlock: { required },
            }
        })

        const v$ = useVuelidate(rules, model)

        const subjectList = ref([]);
        const subjectListUpdate = ref([]);
        const facultyList = ref([]);
        const classroomList = ref([]);
        const classScheduleMajor = ref([]);
        const classScheduleMinor = ref([]);

        const clickSubj = ref(null);

        const college = ref([]);
        const facultiesList = ref([]);
        const classroomsList = ref([]);
        const schedulesList = ref([]);

        // const major = ref('');

        const initialState2 = {
            StartTime: '',
            EndTime: '',
            Day: '',
            Subject: '',
            Faculty: '',
            Classroom: '',
        }
        const model2 = ref({...initialState2});
        const rules2 = computed(() => {
            return {
                Day: { required },
                StartTime: { required },
                EndTime: { required },
                Subject: { required },
                Faculty: { required },
                Classroom: { required },
            }
        })

        const reset = () => {
            model2.value = {...initialState2};
            // major.value = '';
        }
        const resetPref = () => {
            preferredSchedule.value = [],
            chooseStartTimeA.value = null;
            chooseStartTimeM.value = null;
        }
        const resetVF = () => {
            factInfo.value = { count: 0, lecU: 0, labU: 0 };
            facultyscheduleList.value = [];
            eventss.value = [];
        }
        const resetVC = () => {
            roomInfo.value = { count: 0, lecU: 0, labU: 0};
            classroomscheduleList.value = [];
            eventsss.value = [];
        }
        const resetVR = () => {
            classInfo.value = { count: 0, lecU: 0, labU: 0};
            classscheduleListREG.value = [];
            eventsREG.value = [];
        }
        const resetCSI = () => {
            CSI1.value = { count: 0, lecU: 0, labU: 0};
            CSI2.value = { count: 0, lecU: 0, labU: 0};
        }
        const resetM = () => {
            mergeClass.value = {...initialMCS};
        }

        const x$ = useVuelidate(rules2, model2);

        const classscheduleID = ref(false);
        const loading = ref(false);
        const loading1 = ref(false);
        const model3 = ref({});
        const model3Edit = ref({});

        const merge1 = ref({});
        const merge2 = ref({});

        const curriculumList = ref([]);
        const curriculumListNotSave = ref([]);
        const curriculumStatus = ref([]);
        const curriculumStatusOther = ref([]);

        const statOther = ref(false);

        const delete_recommend = ref("");

        const days = ref([
            "2022-08-01",
            "2022-08-02",
            "2022-08-03",
            "2022-08-04",
            "2022-08-05",
            "2022-08-06",
            "2022-08-07"
        ]);

        // const time = ref([
        //    "07:00:00", "07:30:00",
        //    "08:00:00", "08:30:00",
        //    "09:00:00", "09:30:00",
        //    "10:00:00", "10:30:00",
        //    "11:00:00", "11:30:00",
        //    "12:00:00", "12:30:00",
        //    "13:00:00", "13:30:00",
        //    "14:00:00", "14:30:00",
        //    "15:00:00", "15:30:00",
        //    "16:00:00", "16:30:00",
        //    "17:00:00", "17:30:00",
        //    "18:00:00", "18:30:00",
        //    "19:00:00", "19:30:00",
        //    "20:00:00", "20:30:00",
        //    "21:00:00"
        // ]);

        // const dayR = ref(null);
        // const timeR = ref(null);

        const yearLevelList = ref([]);

        const deleteCS = ref(false);
        const resetB = ref(false);

        // const zxc = {
        //     noS: 0,
        //     noS1: 0,
        //     totU: 0,
        //     totU1: 0,
        //     totH1: 0,
        //     totH: 0,
        // }
        // const details = ref({...zxc});

        const fetchColleges = async () => {
            collegeList.value = await APIController.FetchCurriculaListCollege(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
        }

        const fetchCourses = async () => {
            courseList.value = await APIController.FetchCurriculaListCourse(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'), model.value.selectedCollege);
            // courseList.value = await APIController.FetchCoursesCollege(model.value.selectedCollege);
        }

        const fetchYearLevel = async () => {
            yearLevelList.value = await APIController.FetchCurriculaListYearLevel(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'), model.value.selectedCourse);
        }

        const fetchCurricula = async () => {
            curriculumList.value = await APIController.FetchCurriculaYearLevel(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
            subjectListUpdate.value = await APIController.FetchSubjectsCurriculaYearLevel(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
            subjectList.value = await APIController.FetchSubjectsCurriculaStatus(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
            curriculumListNotSave.value = await APIController.FetchCurriculaNotSaved(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
        }
        const fetchCurriculaStatus = async () => {
            curriculumStatus.value = await APIController.FetchCurriculaStatus(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
            curriculumStatusOther.value = await APIController.FetchCurriculaStatusOther(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
        }

        const toggleModal = async () => {
            curriculumListNotSave.value = await APIController.FetchCurriculaNotSaved(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model.value.selectedCourse, model.value.selectedYearLevel, localStorage.getItem('userID'));
            showModal.value = !showModal.value;
            preferredSchedule.value = new Array(curriculumListNotSave.value.length);
            for (let i = 0; i < preferredSchedule.value.length; i++) {
                let lab = 1;
                if(curriculumListNotSave.value[i].lab <= 0)
                    lab = 0;
                preferredSchedule.value[i] = {   subject_id: '',
                                                Subject_Name: '',
                                                Subject_Code: '',
                                                split_lec: 1,
                                                split_lab: lab,
                                                lec: '',
                                                lab: '',
                                                prefDays: [],
                                                time_period: '',
                                                faculty_id: {},
                                                classroom_id: {},
                                                f_id: '',
                                                c_id: ''
                                            };
            }

            for(let i = 0; i < curriculumListNotSave.value.length; i++){
                preferredSchedule.value[i].subject_id = curriculumListNotSave.value[i].subject_id;
                preferredSchedule.value[i].Subject_Name = curriculumListNotSave.value[i].Subject_Name;
                preferredSchedule.value[i].Subject_Code = curriculumListNotSave.value[i].Subject_Code;
                preferredSchedule.value[i].lec = curriculumListNotSave.value[i].lec;
                preferredSchedule.value[i].lab = curriculumListNotSave.value[i].lab;

                onefaculty.value = await APIController.FetchFacultyCurriculum(curriculumListNotSave.value[i].faculties_id);

                preferredSchedule.value[i].faculty_id = onefaculty.value.map(function (element){
                    let properties = {
                        "id": element.id,
                        "Faculty_Name": element.Faculty_Name,
                        "Faculty_ID": element.Faculty_ID
                    };
                    return properties;
                });
            
                oneclassroom.value = await APIController.FetchClassroomCurriculum(curriculumListNotSave.value[i].classrooms_id);

                preferredSchedule.value[i].classroom_id = oneclassroom.value.map(function (element){
                    let properties = {
                        "id": element.id,
                        "Building_No": element.Building_No,
                        "Classroom_No": element.Classroom_No,
                        "Classroom_Type": element.Classroom_Type
                    };
                    return properties;
                });
            }
            
        }

        // const FetchClassroomsModal = async (id) => {
        //     classroomList.value = await APIController.FetchClassroomsPerCurriculumBySubject(id);
        // }

        const AddSchedule = async () => {
            tempSchedule = await APIController.CreateSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, model.value.selectedBlock);
            if(tempSchedule){
                loading1.value = true;
                try{
                schedule.value = tempSchedule.id;
                fetchClassSchedules();
                fetchCurricula();
                fetchCurriculaStatus();
                sched.value = true;
                loading1.value = false;
                }catch (error) {
                console.log(error);
                loading1.value = false;
                }
            }
            else {
                loading1.value = true;
                try{
                schedule.value = await APIController.FetchSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, model.value.selectedBlock);
                sched.value = true;
                fetchCurricula();
                fetchCurriculaStatus();
                fetchClassSchedules();
                loading1.value = false;
                }catch (error) {
                console.log(error);
                loading1.value = false;
                }
            }
        }

        const generateClassSchedule = async () => {
            const subjects = preferredSchedule.value.map(function (element) {
                return element.subject_id;
            });
            const lec = preferredSchedule.value.map(function (element) {
                return element.lec;
            });
            const lab = preferredSchedule.value.map(function (element) {
                return element.lab;
            });
            const prefDays = preferredSchedule.value.map(function (element) {
                let x = JSON.stringify(element.prefDays.toString());
                let xx = JSON.parse(x);
                return xx;
            });
            const split_lec = preferredSchedule.value.map(function (element) {
                return parseInt(element.split_lec);
            });
            const split_lab = preferredSchedule.value.map(function (element) {
                return parseInt(element.split_lab);
            });
            const time_period = preferredSchedule.value.map(function (element) {
                return element.time_period;
            });
            const faculties = preferredSchedule.value.map(function (element) {
                return element.f_id;
            });
            const classrooms = preferredSchedule.value.map(function (element) {
                return element.c_id;
            });
            // const prefDayss = JSON.stringify(prefDays);
            let alertPD = false;
            let alertTP = false;
            for(let i =0; i < prefDays.length; i++)
                if(prefDays[i].length == 0)
                    alertPD = true;
            for(let i =0; i < time_period.length; i++)
                if(time_period[i] == '')
                    alertTP = true;

            if(alertPD)
                alert("Please select atleast one for the Preferred Days on each row.");
            else if(alertTP)
                alert("Please select a Time Period on each row.");
            else {
                                              console.log(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, chooseStartTimeM.value, chooseStartTimeA.value, JSON.stringify(subjects), JSON.stringify(lec), JSON.stringify(lab), JSON.stringify(split_lec), JSON.stringify(split_lab), JSON.stringify(prefDays), JSON.stringify(time_period), JSON.stringify(faculties), JSON.stringify(classrooms), parseInt(localStorage.getItem('userID')));
                await APIController.GenerateClassSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, chooseStartTimeM.value, chooseStartTimeA.value, JSON.stringify(subjects), JSON.stringify(lec), JSON.stringify(lab), JSON.stringify(split_lec), JSON.stringify(split_lab), JSON.stringify(prefDays), JSON.stringify(time_period), JSON.stringify(faculties), JSON.stringify(classrooms), parseInt(localStorage.getItem('userID')));
                fetchClassSchedules();
                toggleModal();
                fetchCurriculaStatus();
                resetPref();
            }
        }

        const AddClassSchedule = async () => {
            var minST = model2.value.StartTime.split(":");
            var minET = model2.value.EndTime.split(":");
            minST = (parseInt(minST[0])*60) + parseInt(minST[1]);
            minET = (parseInt(minET[0])*60) + parseInt(minET[1]);
            if(model2.value.StartTime > model2.value.EndTime)
                alert("Invalid Time! The Start Time must be less than the End Time.");
            else if(model2.value.StartTime == model2.value.EndTime)
                alert("Invalid Time! The Start Time and End Time must not be the same.");
            else if(minET - minST < 60)
                alert("Time range must be atleast within an hour.");
            else if((model2.value.StartTime <= '12:00' && model2.value.EndTime >= '13:00') || (model2.value.StartTime <= '12:00' && model2.value.EndTime <= '13:00' && model2.value.EndTime > '12:00') || (model2.value.StartTime >= '12:00' && model2.value.StartTime < '13:00' && model2.value.EndTime >= '13:00'))
                alert("A Schedule cannot be save within the Lunch Time Period.");
            // console.log(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model2.value.Day, model2.value.StartTime, model2.value.EndTime, model2.value.Subject, model2.value.Faculty, model2.value.Classroom, localStorage.getItem('userID'));
            else{
                let tempClassSchedule = await APIController.CreateClassSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model2.value.Day, model2.value.StartTime, model2.value.EndTime, model2.value.Subject, model2.value.Faculty, model2.value.Classroom, localStorage.getItem('userID'));
                // console.log("saasfs"+tempClassSchedule.day);
                if(tempClassSchedule){
                    fetchClassSchedules(),
                    fetchCurriculaStatus();
                    fetchCurricula();
                    reset();
                    toggleModalAdd();
                }
            }
        }

        const fetchClassSchedulesREG = async () => {
            classscheduleListREG.value = await APIController.FetchClassSchedules(registrar.value.registrar, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            let crCS = await APIController.FetchClassSchedulesInfo(registrar.value.registrar, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            classInfo.value.count = crCS.length;
            if(crCS){
                crCS.forEach(e => {
                    classInfo.value.lecU = classInfo.value.lecU + e.lec;
                    classInfo.value.labU = classInfo.value.labU + e.lab;
                });
            }
            

            eventsREG.value = classscheduleListREG.value.map(function (element){
                let properties = {
                    "start": element.day.concat(" "+element.startTime),
                    "end": element.day.concat(" "+element.endTime),
                    "title": element.Subject_Name,
                    "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                    "class": "faculty",
                    // "s_id": element.subject_id,
                    // "id": element.id,
                    // "userID": element.user_id
                };
                return properties;
            });
        }

        const fetchFacultySchedules = async () => {
            facultyscheduleList.value = await APIController.FetchFacultySchedules(faculty.value.faculty, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            let fCS = await APIController.FetchFacultySchedulesInfo(faculty.value.faculty, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            
            if(fCS){
                factInfo.value.count = fCS.length;
                fCS.forEach(e => {
                    factInfo.value.lecU = factInfo.value.lecU + e.lec;
                    factInfo.value.labU = factInfo.value.labU + e.lab;
                });
            }
            

            eventss.value = facultyscheduleList.value.map(function (element){
                let properties = {
                    "start": element.day.concat(" "+element.startTime),
                    "end": element.day.concat(" "+element.endTime),
                    "title": element.Subject_Name,
                    "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" || "+element.Building_No+" "+element.Classroom_No+"<br>[ Lec: "+element.lec+" - Lab: "+element.lab+" ]",
                    "class": "faculty",
                    // "s_id": element.subject_id,
                    // "id": element.id,
                    // "userID": element.user_id
                };
                return properties;
            });
        }

        const fetchClassroomSchedules = async () => {
            classroomscheduleList.value = await APIController.FetchClassroomSchedules(classroom.value.classroom, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            let cCS = await APIController.FetchClassroomSchedulesInfo(classroom.value.classroom, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            
            if(cCS){
                roomInfo.value.count = cCS.length;
                cCS.forEach(e => {
                    roomInfo.value.lecU = roomInfo.value.lecU + e.lec;
                    roomInfo.value.labU = roomInfo.value.labU + e.lab;
                });
            }

            eventsss.value = classroomscheduleList.value.map(function (element){
                let properties = {
                    "start": element.day.concat(" "+element.startTime),
                    "end": element.day.concat(" "+element.endTime),
                    "title": element.Subject_Name,
                    "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" [ Lec: "+element.lec+" - Lab: "+element.lab+" ] <br>"+element.Faculty_Name,
                    "class": "classroom",
                    // "s_id": element.subject_id,
                    // "id": element.id,
                    // "userID": element.user_id
                };
                return properties;
            });
        }

        const fetchClassSchedules = async () => {
            resetCSI();
            let csi1_ = await APIController.FetchClassSchedulesInfoUser(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
            let csi2_ = await APIController.FetchClassSchedulesInfo(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            
            if(csi1_){    
                CSI1.value.count = csi1_.length;
                csi1_.forEach(e => {
                    CSI1.value.lecU = CSI1.value.lecU + e.lec;
                    CSI1.value.labU = CSI1.value.labU + e.lab;
                });
            }
            if(csi2_){
                CSI2.value.count = csi2_.length;
                csi2_.forEach(e => {
                    CSI2.value.lecU = CSI2.value.lecU + e.lec;
                    CSI2.value.labU = CSI2.value.labU + e.lab;
                });
            }

            classscheduleList.value = await APIController.FetchClassSchedules(schedule.value);

            let temp = '';
            for (let i = 0; i < Object.keys(classscheduleList.value).length; i++) {
                console.log(classscheduleList.value[i].Subject_Name);
                if(temp != classscheduleList.value[i].Subject_Name){
                    temp = classscheduleList.value[i].Subject_Name;
                }
            }

            events.value = classscheduleList.value.map(function (element){
                let properties = {};
                if(element.user_id == localStorage.getItem('userID')){
                        properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                        "class": "saved1",
                        "s_id": element.subject_id,
                        "id": element.id,
                        "userID": element.user_id
                    }
                } else {
                        properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                        "class": "saved2",
                        "s_id": element.subject_id,
                        "id": element.id,
                        "userID": element.user_id
                    }
                }
                
                return properties;
            });

            for (let z = 0; z < Object.keys(events.value).length; z++) {
                var startttMm = new Date(events.value[z].start.split(' '));
                startttMm = (startttMm.getHours() * 60) + startttMm.getMinutes();
                var endddMm = new Date(events.value[z].end.split(' '));
                endddMm = (endddMm.getHours() * 60) + endddMm.getMinutes();
                var tt = (endddMm - startttMm) / 60;

            }

            for (let q = 0; q < days.value.length; q++) {
                events.value.push({
                    start: days.value[q]+' 12:00',
                    end: days.value[q]+' 13:00',
                    title: 'LUNCH',
                    class: 'lunch',
                    background: true
                });
            }
            //recommender engine on word
        }

        const GetClassSchedule = async () => {
            loading.value = true;
            try {
            model3.value = await APIController.FetchClassScheduleViewing(classscheduleID.value);
            model3Edit.value = await APIController.FetchClassSchedule(classscheduleID.value);
            loading.value = false;
            }catch (error) {
                console.log(error);
                loading.value = false;
            }
        }

        const updateClassSchedule = async () => {
            var minST = model3Edit.value.startTime.split(":");
            var minET = model3Edit.value.endTime.split(":");
            minST = (parseInt(minST[0])*60) + parseInt(minST[1]);
            minET = (parseInt(minET[0])*60) + parseInt(minET[1]);
            if(model3Edit.value.startTime >= model3Edit.value.endTime)
                alert("Invalid Time! The Start Time must be less than the End Time.");
            else if(model3Edit.value.startTime == model3Edit.value.endTime)
                alert("Invalid Time! The Start Time and End Time must not be the same.");
            else if(minET - minST < 60)
                alert("Time range must be atleast within an hour.");
            else if((model3Edit.value.startTime <= '12:00' && model3Edit.value.endTime >= '13:00') || (model3Edit.value.startTime <= '12:00' && model3Edit.value.endTime <= '13:00' && model3Edit.value.endTime > '12:00') || (model3Edit.value.startTime >= '12:00' && model3Edit.value.startTime < '13:00' && model3Edit.value.endTime >= '13:00'))
                alert("A Schedule cannot be save within the Lunch Time Period.");
            else{
                let tempClassSchedule = await APIController.UpdateClassSchedule(schedule.value, model3Edit.value.day, model3Edit.value.startTime, model3Edit.value.endTime, model3Edit.value.subject_id, model3Edit.value.faculty_id, model3Edit.value.classroom_id, classscheduleID.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
                if(tempClassSchedule){
                    fetchClassSchedules();
                    fetchCurriculaStatus();
                    fetchCurricula();
                    classscheduleID.value = false;
                    toggleModalUpdate();
                    cancel();
                    showModalEditMode.value = false;
                }
            }
            // console.log(tempClassSchedule);
        }

        const mergeClassSchedule = async () => {
            merge1.value = await APIController.FetchClassScheduleM(classscheduleID.value);
            merge2.value = await APIController.FetchClassScheduleM(mergeClass.value.mergeClass);

            let tempClassSchedule = await APIController.MergeClassSchedule(merge1.value.schedule_id, merge1.value.day, merge1.value.startTime, merge1.value.endTime, merge1.value.subject_id, merge1.value.faculty_id, merge1.value.classroom_id, classscheduleID.value, merge2.value.schedule_id, merge2.value.day, merge2.value.startTime, merge2.value.endTime, merge2.value.subject_id, merge2.value.faculty_id, merge2.value.classroom_id, mergeClass.value.mergeClass);
                if(tempClassSchedule){
                    fetchClassSchedules();
                    classscheduleID.value = false;
                    cancel();
                    resetM();
                }
        }

        const deleteClassSchedule = async () => {
            const success = await APIController.DeleteClassSchedule(classscheduleID.value);
            if (success) {
                cancel(),
                fetchClassSchedules();
                fetchCurriculaStatus();
                fetchCurricula();
                showModalEditMode.value = false;
                showModalDeleteSched.value = false;
            }
        }

        const deleteClassSchedulesReset = async () => {
            const success = await APIController.DeleteClassSchedulesReset(schedule.value, localStorage.getItem('userID'));
            if (success) {
                fetchClassSchedules();
                fetchCurriculaStatus();
                fetchCurricula();
                toggleModalDeleteSchedules();
            }
        }

        // const fetchMajorFaculty = async () => {
        //     facultyList.value = await APIController.FetchMajorFaculties(model.value.selectedCollege);
        //     // console.log(model.value.selectedCollege);
        // }

        const fetchFaculties = async (id) => {
            facultyList.value = await APIController.FetchFacultiesBySubject(id);
        }

        const fetchSchedulesCollege = async () => {
            schedulesList.value = await APIController.FetchSchedulesByCollege(localStorage.getItem('collegeId'), modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
        }

        const fetchClassroomsCollege = async () => {
            classroomsList.value = await APIController.FetchClassroomsByCollege(localStorage.getItem('collegeId'));
        }

        const fetchFacultiesCollege = async () => {
            facultiesList.value = await APIController.FetchFacultiesByCollege(localStorage.getItem('collegeId'));
        }

        const fetchFacultiesDepartment = async () => {
            facultiesList.value = await APIController.FetchFacultiesByDepartment(college.value.id);
        }

        // const fetchMajorClassrooms = async () => {
        //     classroomList.value = await APIController.FetchMajorClassrooms(model.value.selectedCollege);
        //     // console.log(model.value.selectedCollege);
        // }

        const fetchClassrooms = async (id) => {
            classroomList.value = await APIController.FetchClassroomsBySubject(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, id);
        }

        const fetchClassScheduleMajor = async () => { // parameters are college id, subject id, year level and schedule id(?)
            // console.log(model3.value.subject_id, model.value.selectedCourse, model.value.selectedYearLevel, classscheduleID.value)
            classScheduleMajor.value = await APIController.FetchClassSchedulesMergingMajor(model3.value.subject_id, model.value.selectedCourse, model.value.selectedYearLevel, schedule.value);
            for(let i = 0; i < classScheduleMajor.value.length; i++){
                switch (classScheduleMajor.value[i].day){
                    case '2022-08-01':
                        classScheduleMajor.value[i].day = 'MON';
                        break;
                    case '2022-08-02':
                        classScheduleMajor.value[i].day = 'TUE';
                        break;
                    case '2022-08-03':
                        classScheduleMajor.value[i].day = 'WED';
                        break;
                    case '2022-08-04':
                        classScheduleMajor.value[i].day = 'THU';
                        break;
                    case '2022-08-05':
                        classScheduleMajor.value[i].day = 'FRI';
                        break;
                    case '2022-08-06':
                        classScheduleMajor.value[i].day = 'SAT';
                        break;
                    case '2022-08-07':
                        classScheduleMajor.value[i].day = 'SUN';
                        break;
                }
            }
            // console.log(model3.value.subject_id, model.value.selectedCourse, model.value.selectedYearLevel, schedule.value)
        }   

        const fetchClassScheduleMinor = async () => {// parameters are subject id and schedule id(?)
            classScheduleMinor.value = await APIController.FetchClassSchedulesMergingMinor(model3.value.subject_id, schedule.value);
            for(let i = 0; i < classScheduleMinor.value.length; i++){
                switch (classScheduleMinor.value[i].day){
                    case '2022-08-01':
                        classScheduleMinor.value[i].day = 'MON';
                        break;
                    case '2022-08-02':
                        classScheduleMinor.value[i].day = 'TUE';
                        break;
                    case '2022-08-03':
                        classScheduleMinor.value[i].day = 'WED';
                        break;
                    case '2022-08-04':
                        classScheduleMinor.value[i].day = 'THU';
                        break;
                    case '2022-08-05':
                        classScheduleMinor.value[i].day = 'FRI';
                        break;
                    case '2022-08-06':
                        classScheduleMinor.value[i].day = 'SAT';
                        break;
                    case '2022-08-07':
                        classScheduleMinor.value[i].day = 'SUN';
                        break;
                }
            }
            // console.log(model3.value.subject_id, schedule.value)
            // console.log("minor");
            // console.log(classScheduleMinor.value)
       }


        // function major (){
        //     for(let i = 0; i < Object.keys(curriculumList.value).length; i++){
        //         if(curriculumList.value[i].subject_id == model2.value.Subject){
        //             if(curriculumList.value[i].Subject_Type == 'major'){
        //                 fetchMajorFaculty();
        //                 fetchMajorClassrooms();
        //             } else {
        //                 fetchFaculties();
        //                 fetchClassrooms();
        //             }
        //             break;
        //         }
        //     }
        // }

        function onEventClick(event) {
            fetchFaculties(event.s_id);
            fetchClassrooms(event.s_id);
            if(event.class == "saved1" && event.userID == localStorage.getItem('userID'))
            {
                loading.value = true;
                classscheduleID.value = event.id;
                GetClassSchedule().then(()=>{
                    localStorage.setItem('clickSubj', model3.value.Subject_Type);
                    if (model3.value.Subject_Type === "Major"){
                        clickSubj.value = localStorage.getItem('clickSubj');
                        fetchClassScheduleMajor();
                    }else{
                        // localStorage.getItem('clickSubj');
                        clickSubj.value = localStorage.getItem('clickSubj');
                        fetchClassScheduleMinor();
                    }
                });
                // console.log(model3.value)
            } else if(event.class == 'lunch'){

            } else if(event.userID != localStorage.getItem('userID')) {

            } else if(event.class == "saved2"){

            }
            else {
                loading.value = true;
                // classscheduleID.value = true;
                var start = event.start.toString().split(' ');
                var end = event.end.toString().split(' ');
                var date = new Date(event.start);
                var month = date.getMonth()+1;
                date = date.getFullYear()+"-0"+month+"-0"+date.getDate();
                // model3.value.push({startTime: start[4], endTime: end[4], day: date, subject_id: event.id});
                // console.log(model3.value[0]);
                cancel();
                resetB.value = true;
                model2.value.StartTime = start[4];
                model2.value.EndTime = end[4];
                model2.value.Day = date;
                model2.value.Subject = event.s_id;
                delete_recommend.value = event.id;
                // major();
            }
            
        }

        function cancel () {
            classscheduleID.value = false;
            reset();
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            if(localStorage.getItem('userType') == 'reg'){
                fetchFacultiesCollege();
            } else {
                fetchFacultiesDepartment();
            }
            userType.value = localStorage.getItem('userType');
            fetchClassroomsCollege();
            
        }

        return {
            collegeList,
            courseList,
            classscheduleList,
            fetchColleges,
            fetchCourses,
            v$,
            model,
            sched,
            AddSchedule,
            AddClassSchedule,
            tempSchedule,
            schedule,
            // major,
            // fetchMajorFaculty,
            fetchFaculties,
            facultyList,
            classroomList,
            fetchClassrooms,
            // fetchMajorClassrooms,
            onEventClick,
            x$,
            model2,
            reset,
            events,
            // y$,
            model3,
            model3Edit,
            GetClassSchedule,
            classscheduleID,
            updateClassSchedule,
            loading,
            loading1,
            cancel,
            deleteClassSchedule,
            fetchCurricula,
            curriculumList,
            delete_recommend,
            fetchYearLevel,
            yearLevelList,
            subjectList,
            subjectListUpdate,
            deleteCS,
            resetB,
            classroomsList,
            fetchClassroomsCollege,
            facultiesList,
            fetchFacultiesCollege,
            fetchFacultiesDepartment,
            GetCollege,
            viewSchedule,
            eventss,
            faculty,
            facultyscheduleList,
            fetchFacultySchedules,
            vfs,
            classroom,
            vc$,
            vf$,
            vcs,
            classroomscheduleList,
            fetchClassroomSchedules,
            eventsss,
            resetVC,
            resetVF,
            // details,
            fetchClassScheduleMajor,
            fetchClassScheduleMinor,
            classScheduleMinor,
            classScheduleMajor,
            clickSubj,
            mcs,
            mcs$,
            mergeClass,
            mergeClassSchedule,
            merge1,
            merge2,
            resetM,
            days,
            // dayR,
            // timeR,
            showModal,
            toggleModal,
            // FetchClassroomsModal,
            preferredDays,
            preferredSchedule,
            onefaculty,
            oneclassroom,
            showModalAdd,
            toggleModalAdd,
            toggleModalUpdate,
            showModalUpdate,
            showModalFaculty,
            toggleModalFaculty,
            showModalClassroom,
            toggleModalClassroom,
            chooseStartTimeM,
            chooseStartTimeA,
            // showPref,
            generateClassSchedule,
            resetPref,
            curriculumListNotSave,
            fetchCurriculaStatus,
            curriculumStatus,
            curriculumStatusOther,
            statOther,
            deleteClassSchedulesReset,
            showModalDeleteSchedules,
            toggleModalDeleteSchedules,
            toggleModalEditMode,
            showModalEditMode,
            amTP,
            pmTP,
            toggleTimePeriod,
            modelHeader,
            h$,
            resetModel,
            toggleAYSem,
            showModalDeleteSched,
            toggleModalDeleteSched,

            showModalPrintClass,
            toggleModalPrintClass,
            showModalClassScheduleREG,
            toggleModalClassScheduleREG,
            classInfo,
            factInfo,
            roomInfo,
            infoR,
            infoF,
            infoC,
            printAll,
            printSched,
            printFact,
            printRoom,
            userType,
            classscheduleListREG,
            eventsREG,
            registrar,
            vr$,
            vrs,
            schedulesList,
            resetVR,
            fetchClassSchedulesREG,
            fetchSchedulesCollege,
            CSI1,
            CSI2,
            resetCSI,

            printClassSchedule,
            printFacultySchedule,
            printClassroomSchedule
        }
    },
    mounted () {
        if (this.classscheduleID != false){
            this.GetClassSchedule();
        }
        // this.GetClassSchedule();
        this.GetCollege();
        // this.fetchClassScheduleMajor();
    },
    methods: {
        onChangeSubject(e){
            return e.target.value;
        },
    }
}
</script>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  animation: spin 5s linear infinite;
}

.textBox {
  width: 200px;
  height: 20px;
  overflow: hidden;
  padding: 2px;
  position: relative;
}
.textBox span {
  position: absolute;
  white-space: nowrap;
  transform: translateX(0);
  transition: 4s;
}
.textBox:hover span {
  transform: translateX(calc(90px - 100%));
}

.textBox2 {
  width: 100%;
  height: 20px;
  overflow: hidden;
  padding: 2px;
  position: relative;
}
.textBox2 span {
  position: absolute;
  white-space: nowrap;
  transform: translateX(0);
  transition: 8s;
}
.textBox2:hover span {
  transform: translateX(calc(200px - 100%));
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(90deg);
  }
  50% {
    transform: rotate(180deg);
  }
  75% {
    transform: rotate(270deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.vuecal__event {cursor: pointer; vertical-align: middle;}
.vuecal__cell--selected:before {border-color: rgba(132, 205, 236);}

.vuecal__event.saved1 { background-color: rgba(69, 194, 252, 0.65);border: 1px solid rgb(9, 81, 129);border-radius: 12px;color: rgb(9, 81, 129);}
.vuecal__event.saved2 {cursor: not-allowed; background-color: rgba(109, 216, 219, 0.65);border: 1px solid rgb(2, 93, 109);border-radius: 12px;color: rgb(2, 93, 109);}
.vuecal__event.recommend1 {background-color: rgba(133, 215, 253, 0.3);border: 1px solid rgb(133, 204, 236);border-radius: 12px;color: #3f3f46;}
.vuecal__event.recommend2 {cursor: not-allowed; background-color: rgba(252, 145, 69, 0.3);border: 1px solid rgb(233, 179, 129);border-radius: 12px;color: #3f3f46;}

.vuecal__event.classroom {cursor: text; background-color: rgba(80, 195, 248, 0.65);border: 1px solid rgb(144, 214, 247);border-radius: 12px;color: #565658;}
.vuecal__event.faculty {cursor: text; background-color: rgb(14, 165, 233, 0.65);border: 1px solid rgb(108, 207, 253);border-radius: 12px;color: #3f3f46;}

.vuecal__event.lunch {
  cursor: not-allowed;
  background: repeating-linear-gradient(45deg, transparent, transparent 10px, #f2f2f2 10px, #f2f2f2 20px);/* IE 10+ */
  color: #999;
  display: flex;
  justify-content: center;
  align-items: center;
}
.vuecal__event.lunch .vuecal__event-time {display: none;align-items: center;}
</style>