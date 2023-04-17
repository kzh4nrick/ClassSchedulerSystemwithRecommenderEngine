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
            <h1 class="w-3/5 lg2:text-xl md:text-lg tracking-wider text-right uppercase font-raleway text-[#253B80]">
                Class Scheduler System with Recommender Engine
            </h1>
            <div class="flex justify-center w-2/5">
                <select v-model="h$.selectedAcademicYear.$model"
                        @change="toggleAYSem(), resetModel(), sched = false"
                        required class="invalid:border-orange-500 invalid:text-orange-500 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-7.5 w-max text-sky-500 border border-sky-500 bg-transparent rounded-lg shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 text-xs">
                    <option disabled value="" class="text-center">-- Academic Year --</option>
                    <option value="A.Y. 2022 - 2023" class="text-sky-600 text-justify">A.Y. 2022 - 2023</option>
                    <option value="A.Y. 2023 - 2024" class="text-sky-600 text-justify">A.Y. 2023 - 2024</option> -->
                </select>
                <select v-model="h$.selectedSemester.$model"
                        @change="toggleAYSem(), resetModel(), sched = false"
                        required class="ml-2 invalid:border-orange-500 invalid:text-orange-500 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-7.5 w-max text-sky-500 border border-sky-500 bg-transparent rounded-lg shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 text-xs">
                    <option disabled value="" class="text-center">-- Semester --</option>
                    <option value="First Semester" class="text-sky-600 text-justify">First Semester</option>
                    <option value="Second Semester" class="text-sky-600 text-justify">Second Semester</option> -->
                    <option value="Mid Semester_Summer" class="text-sky-600 text-justify">Mid Semester / Summer</option> -->
                </select>
            </div>
        </div>
    </header>
    <main>
        <div class="xl2:max-w-[90vw] lg:max-w-[95vw] mx-auto py-2.5">
            <form class="form" @submit.prevent="AddSchedule()">
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-4">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCollege.$model"
                        @keyup="v$.selectedCollege.$touch()"
                        @blur="v$.selectedCollege.$touch()"
                        required
                        @change="fetchCourses(), showChart = false, model.selectedCourse = '', model.selectedYearLevel = '', model.selectedBlock = '', viewsched1 = true, sched = false"
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
                <div class="col-span-4">
                    <select
                        v-if="!h$.$invalid"
                        v-model="v$.selectedCourse.$model"
                        @keyup="v$.selectedCourse.$touch()"
                        @blur="v$.selectedCourse.$touch()"
                        @change="fetchYearLevel(), showChart = false, model.selectedYearLevel = '', model.selectedBlock = '', viewsched2 = true, sched = false"
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
                        @change="fetchBlock(onChangeYearLevel($event)), showChart = false, model.selectedBlock = '', viewsched3 = true, sched = false"
                        required
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">-Year Level-</option>
                        <option v-for="list in yearLevelList"
                                :key="list"
                                :value="list"
                                class="text-sky-600 text-justify"
                                >
                                {{  list }}
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
                        @change="showChart = false, viewsched4 = true, sched = false"
                        required
                        class="invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                    >
                        <option disabled value="" class="text-center">--Block--</option>
                        <option v-if="block >= 1" value="A" class="text-sky-600 text-center">A</option>
                        <option v-if="block >= 2" value="B" class="text-sky-600 text-center">B</option>
                        <option v-if="block >= 3" value="C" class="text-sky-600 text-center">C</option>
                        <option v-if="block >= 4" value="D" class="text-sky-600 text-center">D</option>
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
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Schedule
                    </button>
                    <button
                        v-else-if="!(viewsched1 == true || viewsched2 == true || viewsched3 == true || viewsched4 == true)"
                        disabled
                        type="submit"
                        class="cursor-not-allowed h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white bg-orange-400">
                        Manage Schedule
                    </button>
                    <button
                        v-else
                        @click="resetStartTime(), cancel(), viewsched1 = false, viewsched2 = false, viewsched3 = false, viewsched4 = false, viewSchedule = 'class', events = []"
                        type="submit"
                        class="h-full border border-transparent w-full shadow-sm text-sm font-extrabold uppercase rounded-lg text-white hover:text-cyan-200 bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
                        Manage Schedule
                    </button>
                </div>
            </div>
            </form>
        </div>

        <div v-if="sched == false && loading1 == false" class="xl2:max-w-[90vw] lg:max-w-[95vw] mx-auto my-[3vh] grid grid-cols-6 gap-x-5">
            <div v-if="userType == 'reg'" class="col-span-3 border border-sky-400/[15%] flex justify-center shadow-xl shadow-sky-400/[15%] rounded-md h-[70vh]">
                <div v-if="loading2" class="top-1/3 lds-facebook"><div></div><div></div><div></div></div>
                <canvas v-else-if="showChart" id="myChartALL"></canvas>
                <div v-if="showClassesALL" class="fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                    <div class="relative w-auto mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                            <!--header-->
                            <div class="flex flex-row items-center justify-center py-2 px-4 border-b border-solid rounded-t">
                                <span class="text-sm text-[#253B80] font-roboto uppercase">LIST OF CLASSES FOR THE ENTIRE {{ legendCollege.College_Code }} {{ legendLabel }} &nbsp; [ {{ legendCount }} ]
                                </span>
                                <!-- <span class="text-gray-400 hover:text-gray-600 font-roboto text-lg mb-1">
                                    <button class="bg-transparent" v-on:click="showClasses = false">x</button>
                                </span> -->
                            </div>
                            <div class="overflow-auto w-auto max-w-[80vw] h-auto max-h-[60vh] my-2 mx-4 flex flex-col text-sm font-semibold text-[#2C4899]">
                                <div v-for="(cL, index) in classListsALL" :key="index" class="py-0.5">
                                    <span>[ {{ cL.class_code }} ] &nbsp; {{ cL.program_name }} &nbsp;|&nbsp; {{ cL.yearLevel }} &nbsp;|&nbsp; {{ cL.block }}</span>
                                </div>
                            </div>
                            <div class="mb-1 flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                                <button class="mt-1 text-[#253B80] bg-transparent border border-solid border-[#253B80] hover:bg-[#253B80] hover:text-white active:bg-[#253B80] font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="showClassesALL = false">
                                Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="showClassesALL" class="opacity-25 fixed inset-0 z-40 bg-slate-400"></div>
            </div>
            <div v-if="userType != null" :class="userType == 'dept' ? 'col-span-6':'col-span-3'" class="border border-sky-400/[15%] flex justify-center shadow-xl shadow-sky-400/[15%] rounded-md h-[70vh]">
                <div v-if="loading2" class="top-1/3 lds-facebook"><div></div><div></div><div></div></div>
                <canvas v-else-if="showChart" id="myChart"></canvas>
                <div v-if="showClasses" class="fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                    <div class="relative w-auto mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                            <!--header-->
                            <div class="flex flex-row items-center justify-center py-2 px-4 border-b border-solid rounded-t">
                                <span class="text-sm text-[#253B80] font-roboto uppercase">LIST OF CLASSES {{ legendLabel }} &nbsp; [ {{ legendCount }} ]
                                </span>
                                <!-- <span class="text-gray-400 hover:text-gray-600 font-roboto text-lg mb-1">
                                    <button class="bg-transparent" v-on:click="showClasses = false">x</button>
                                </span> -->
                            </div>
                            <div class="overflow-auto w-auto max-w-[80vw] h-auto max-h-[60vh] my-2 mx-4 flex flex-col text-sm font-semibold text-[#2C4899]">
                                <div v-for="(cL, index) in classLists" :key="index" class="py-0.5">
                                    <span>[ {{ cL.class_code }} ] &nbsp; {{ cL.program_name }} &nbsp;|&nbsp; {{ cL.yearLevel }} &nbsp;|&nbsp; {{ cL.block }}</span>
                                </div>
                            </div>
                            <div class="mb-1 flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                                <button class="mt-1 text-[#253B80] bg-transparent border border-solid border-[#253B80] hover:bg-[#253B80] hover:text-white active:bg-[#253B80] font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="showClasses = false">
                                Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="showClasses" class="opacity-25 fixed inset-0 z-40 bg-slate-400"></div>
            </div>
            <!-- <div :class="userType == 'dept' ? 'col-span-4':'col-span-2'" class="border border-[#436BE5]/[15%] shadow-xl shadow-[#436BE5]/[15%] rounded-md h-[70vh]">
            </div> -->
        </div>                
        <div v-if="loading1" class="relative inset-x-2/4 inset-y-48 lds-ring"><div></div><div></div><div></div><div></div></div>
        <div v-else-if="sched != false && loading1 == false" class="max-w-[97vw] mx-auto grid grid-cols-4">
            <!-- Replace with your content -->
            <!-- :time-step="5"
                timeCellHeight=20 -->
            <div v-if="(viewSchedule == 'class' && viewSchedule != 'faculty' && viewSchedule != 'classroom')" class="col-span-3 rounded-lg shadow-lg">
                <vue-cal class="rounded-xl"
                    selected-date="2022-08-01"
                    :time-from="7 * 60"
                    :time-to="21 * 60"
                    twelve-hour
                    hide-title-bar
                    hide-view-selector
                    :disable-views="['day', 'month', 'year', 'years']"
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

            <div v-else-if="showModalFaculty" class="fixed inset-x-10 inset-y-28 z-30 outline-none focus:outline-none">
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
                                    <option v-for="list in facultiesListReport"
                                                :key="list.id"
                                                :value="list.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{  "[  "+list.Faculty_ID+" ]&nbsp; "+list.Faculty_Name }}
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
                                        class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] hover:bg-[#2C4899] focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#3453B3]">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                    <button v-else disabled
                                        class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] opacity-60">
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
                                    twelve-hour
                                    hide-title-bar
                                    hide-view-selector
                                    :disable-views="['day', 'month', 'year', 'years']"
                                    :events= "eventss"
                                    :on-event-click="onEventClickFaculty"
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
            <div v-if="showModalFaculty" class="opacity-25 fixed inset-0 z-20 bg-slate-700"></div>

        <div v-if="showModalFacultyEvent" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-1/5 mx-auto">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white w-full outline-none focus:outline-none">
                    <button class="absolute right-0 top-0 pr-0.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="showModalFacultyEvent = false">
                    <span class="bg-transparent text-gray-400 hover:text-gray-600 text-2xl outline-none focus:outline-none">
                        ×
                    </span>
                    </button>
                    <div class="py-1.5 pr-1.5 flex flex-col items-center text-center justify-center w-auto text-sm text-sky-900 font-semibold">
                        <span class="font-bold">{{ facultyevent.subject }}</span>
                        <span class="font-bold">[ {{ facultyevent.subjectcode }} ]</span>
                        <span>{{ facultyevent.course }} &nbsp;||&nbsp; {{ facultyevent.classroom }}</span>
                        <span></span>
                        <span class="text-xs font-medium">{{ facultyevent.units }}</span>
                        <span class="text-xs font-medium">{{ facultyevent.dayy }} &nbsp;[ {{ facultyevent.timee }} ]</span>
                        <span class="text-xs font-medium">[ {{ facultyevent.session }} ]</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModalFacultyEvent" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>

            
            <div v-else-if="showModalClassroom" class="fixed inset-x-10 inset-y-28 z-30 outline-none focus:outline-none">
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
                                    <option v-for="list in classroomsListReport"
                                                :key="list.id"
                                                :value="list.id"
                                                class="text-sky-600 text-justify"
                                                >
                                                {{  list.Building_No +" - "+ list.Classroom_No+"&nbsp; [ "+list.Classroom_Type+" ]" }}
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
                                        class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] hover:bg-[#2C4899] focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#3453B3]">
                                        Print this Schedule
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                        </svg>
                                    </button>
                                    <button v-else disabled
                                        class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] opacity-60">
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
                                    twelve-hour
                                    hide-title-bar
                                    hide-view-selector
                                    :disable-views="['day', 'month', 'year', 'years']"
                                    :events= "eventsss"
                                    :on-event-click="onEventClickClassroom"
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
            <div v-if="showModalClassroom" class="opacity-25 fixed inset-0 z-20 bg-slate-700"></div>

        <div v-if="showModalClassroomEvent" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-1/5 mx-auto">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white w-full outline-none focus:outline-none">
                    <button class="absolute right-0 top-0 pr-0.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="showModalClassroomEvent = false">
                    <span class="bg-transparent text-gray-400 hover:text-gray-600 text-2xl outline-none focus:outline-none">
                        ×
                    </span>
                    </button>
                    <div class="py-1.5 pr-1.5 flex flex-col items-center text-center justify-center w-auto text-sm text-sky-900 font-semibold">
                        <span class="font-bold">{{ classroomevent.subject }}</span>
                        <span class="font-bold">[ {{ classroomevent.subjectcode }} ]</span>
                        <span>{{ classroomevent.faculty }}</span>
                        <span>{{ classroomevent.course }}</span>
                        <span class="text-xs font-medium">{{ classroomevent.units }}</span>
                        <span class="text-xs font-medium">{{ classroomevent.dayy }} &nbsp;[ {{ classroomevent.timee }} ]</span>
                        <span class="text-xs font-medium">[ {{ classroomevent.session }} ]</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModalClassroomEvent" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>

            <div v-else-if="showModalClassScheduleREG" class="fixed inset-x-10 inset-y-28 z-30 outline-none focus:outline-none">
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
                                    class="w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] hover:bg-[#2C4899] focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#3453B3]">
                                    Print this Schedule
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                                    <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                    </svg>
                                </button>
                                <button v-else disabled
                                    class="cursor-not-allowed w-full h-full flex items-center justify-center text-sm border border-transparent shadow-sm uppercase font-extrabold font-mono rounded-lg text-white bg-[#3453B3] opacity-60">
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
                                twelve-hour
                                hide-title-bar
                                hide-view-selector
                                :disable-views="['day', 'month', 'year', 'years']"
                                :events= "eventsREG"
                                :on-event-click= "onEventClickREG"
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
            <div v-if="showModalClassScheduleREG" class="opacity-25 fixed inset-0 z-20 bg-slate-700"></div>

        <div v-if="showModalClassREGEvent" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-1/5 mx-auto">
                <!--content-->
                <div class="border-0 rounded-lg shadow-lg relative bg-white w-full outline-none focus:outline-none">
                    <button class="absolute right-0 top-0 pr-0.5 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="showModalClassREGEvent = false">
                    <span class="bg-transparent text-gray-400 hover:text-gray-600 text-2xl outline-none focus:outline-none">
                        ×
                    </span>
                    </button>
                    <div class="py-1.5 pr-1.5 flex flex-col items-center text-center justify-center w-auto text-sm text-sky-900 font-semibold">
                        <span class="font-bold">{{ classregevent.subject }}</span>
                        <span class="font-bold">[ {{ classregevent.subjectcode }} ]</span>
                        <span>{{ classregevent.faculty }}</span>
                        <span>{{ classregevent.classroom }}</span>
                        <span class="text-xs font-medium">{{ classregevent.units }}</span>
                        <span class="text-xs font-medium">{{ classregevent.dayy }} &nbsp;[ {{ classregevent.timee }} ]</span>
                        <span class="text-xs font-medium">[ {{ classregevent.session }} ]</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModalClassREGEvent" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>

            <div class="ml-2 mt-1.5 h-min overflow-x-auto h-auto max-h-[76vh] grid grid-cols-4">
                <div class="mt-1 col-span-4 flex justify-end w-10/12 gap-x-2.5">
                    <button v-if="(curriculumListNotSave.length > 0)" @click="toggleModal()"
                        class="h-auto py-1.5 w-[70%] outline outline-offset-2 outline-1 outline-sky-400 hover:outline-sky-600 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
                        Generate Schedules
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-[70%] outline outline-offset-2 outline-1 outline-sky-400 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
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
                <div v-if="showModalAdd" class="mt-2 fixed right-14 top-1/4 col-span-4 flex justify-end items-center z-50 outline-none focus:outline-none">
                    <div class="w-auto mx-auto max-w-full">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg bg-white w-max outline-none focus:outline-none">
                            <div class="flex items-center justify-end rounded-t -mb-6">
                                <button class="pr-1.5 -mt-1.5 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalAdd(), reset()">
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
                                                @change="resetSession(), fetchSubjectUnits(onChangeSubject($event)), fetchFaculties(onChangeSubject($event))"
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
                                                v-model="x$.Session.$model"
                                                @keyup="x$.Session.$touch()"
                                                @blur="x$.Session.$touch()"
                                                @change="fetchClassrooms(onChangeSession($event))"
                                                required
                                                class="mt-0.5 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-sky-600 border-2 border-sky-500 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                        <option disabled value="" class="text-center">Session Type</option>
                                        <option v-if="subjectUnits != ''" value="Lecture" class="text-sky-600 text-justify">{{ "Lecture [ "+subjectUnits.lec+" units = "+subjectUnits.lec+" hours ]" }}</option>
                                        <option v-if="subjectUnits != '' && subjectUnits.lab > 0" value="Laboratory" class="text-sky-600 text-justify">{{ "Laboratory [ "+subjectUnits.lab+" units = "+subjectUnits.lab*3+" hours ]" }}</option>
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
               
                <div v-if="(classscheduleID != false)" class="mt-2 fixed right-14 top-1/4 col-span-4 flex justify-end items-center z-40 outline-none focus:outline-none">
                    <div class="w-auto mx-auto max-w-max">
                        <div class="w-max border-0 rounded-lg shadow-lg bg-white outline-none focus:outline-none">
                            <div class="flex items-center justify-between rounded-t -mb-6">
                                <button v-if="!showModalEditMode" class="ml-2.5 mt-2" @click="toggleModalEditMode()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="hover:stroke-sky-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3a6fb0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon></svg>
                                </button>
                                <button v-else class="ml-2.5 mt-2" @click="toggleModalEditMode()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="fill-sky-800 hover:fill-sky-600" viewBox="0 0 16 16">
                                        <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
                                    </svg>                                
                                </button>
                                <button class="pr-1.5 -mt-1.5 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="(classscheduleID = false, showModalEditMode = false, resetM())">
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
                                                disabled
                                                v-bind:class="`${model3Edit.session == 'Lecture' ? 'bg-[#45c2fc]/[0.85]' : 'bg-[#97deff]/[0.50]'}`"
                                                class="mt-0.5 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 h-max w-full text-[#16244D] font-bold border-2 border-sky-600 rounded-lg shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
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
                            <div v-else class="h-full w-auto grid grid-cols-4 gap-2 shadow-md overflow-hidden sm:rounded-lg bg-white sm:px-4 sm:py-3">
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
                                    >{{ model3.day }}&nbsp;&nbsp; [ {{ model3.session }} Session ]</span>
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
                                        @click="toggleModalMerge(), mcs = false"
                                        class="cursor-pointer col-span-2 w-full text-xs border border-transparent shadow-sm font-semibold uppercase tracking-wider rounded-lg text-white bg-[#f97316] opacity-80 hover:bg-orange-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge Class to
                                    </button>
                                    <select
                                                v-model="mcs$.mergeClass.$model"
                                                required
                                                @change="fetchFacultyByClassSchedule(onChangeFacultyMerge($event)), mcs=true"
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
                                    class="w-auto border-t-2 pt-2 mr-1.5 col-span-4 grid grid-cols-6">
                                    <button
                                        v-if="mcs$.$invalid"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-auto text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-80 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#6ccffd]">
                                        Merge Class
                                    </button>
                                    <button
                                        v-else-if="mcs == false"
                                        disabled
                                        class="cursor-not-allowed col-span-2 w-auto text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge This Class To:
                                    </button>
                                    <button
                                        v-else
                                        @click="toggleModalMerge(), mcs = false"
                                        class="cursor-pointer col-span-2 w-auto text-xs border border-transparent shadow-sm font-extrabold tracking-wider uppercase rounded-lg text-white bg-[#f97316] opacity-80 hover:bg-orange-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#f8bb82]">
                                        Merge Class to
                                    </button>
                                    <select
                                                v-model="mcs$.mergeClass.$model"
                                                required
                                                @change="fetchFacultyByClassSchedule(onChangeFacultyMerge($event)), mcs=true"
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
                <div v-if="(classscheduleID != false)" class="opacity-25 fixed inset-0 z-30 bg-slate-500"></div>
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
                <div v-if="showModalMerge" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
                    <div class="relative w-auto mx-auto">
                        <!--content-->
                        <div class="border-0 rounded-lg shadow-lg relative bg-white flex flex-col w-full outline-none focus:outline-none">
                            <!--header-->
                            <div class="flex items-center justify-between pl-2 border-b border-solid border-slate-200 rounded-t">
                                <span class="text-sm font-roboto text-[#253B80] uppercase">
                                Merging of Schedules
                                </span>
                                <button class="pr-1 -mt-2 bg-transparent font-semibold outline-none focus:outline-none" v-on:click="toggleModalMerge(), mcs = true">
                                <span class="bg-transparent text-gray-400 hover:text-gray-600 text-3xl outline-none focus:outline-none">
                                    ×
                                </span>
                                </button>
                            </div>
                            <div class="my-2 flex items-center justify-center w-auto text-sm text-[#253B80] font-medium">
                                <span class="px-10 text-center">Select a Faculty.</span>
                            </div>
                            <div class="mb-1 px-2 flex items-center justify-center gap-x-2 border-t border-solid border-slate-200 rounded-b">
                                <button class="mt-1 text-[#253B80] bg-transparent border border-solid border-[#253B80] hover:bg-[#253B80] hover:text-white active:bg-[#253B80] font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="mergeClassSchedule(model3.faculty_id, 'changed')">
                                {{ model3.Faculty_Name }}
                                </button>
                                <button class="mt-1 text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-2 py-1 rounded outline-none focus:outline-none ease-linear transition-all duration-150" v-on:click="mergeClassSchedule(mergeFact.faculty_id, 'not')">
                                {{ mergeFact.Faculty_Name }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="showModalMerge" class="opacity-25 fixed inset-0 z-40 bg-slate-800"></div>

                <div class="col-span-4 border border-1 mt-3 shadow-md overflow-hidden sm:rounded-lg bg-white sm:px-3">
                    <div class="flex flex-col items-start h-auto gap-y-1">
                        <span class="border-b-2 -mb-px mt-0.5 w-full text-center text-xs uppercase text-[#253B80] font-bold">Schedules' Status</span>
                        <div v-for="(stat, index) in curriculumStatus" :key="index"
                            class="flex items-center gap-x-1 w-max h-max text-xs text-[#1E3066] font-medium">
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
                        <div v-if="(statOther && curriculumStatusOther.length > 0)" class="border-t border-sky-200 w-full">
                        </div>
                        <div v-if="statOther" v-for="(stat, index) in curriculumStatusOther" :key="index"
                            class="flex items-center gap-x-1 w-max h-max text-xs text-[#1E3066] font-medium">
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
                                TOTALS&nbsp; :: &nbsp;Courses = {{CSI1.count}} / {{CSI1.total}}&nbsp; :: &nbsp;Credit Units [{{CSI1.lecU+CSI1.labU}}] = {{ CSI1.hours }} / {{ CSI1.lecU+CSI1.labU*3 }} hrs &nbsp; :: &nbsp;Lecture Units [{{ CSI1.lecU }}] =  {{ CSI1.hourslec }} / {{ CSI1.lecU }} hrs &nbsp; :: &nbsp;Lab Units [{{ CSI1.labU }}] = {{ CSI1.hourslab }} / {{ CSI1.labU*3 }} hrs
                            </span>
                            <span v-else>
                                TOTALS&nbsp; :: &nbsp;Courses = {{CSI2.count}} / {{CSI2.total}}&nbsp; :: &nbsp;Credit Units [{{CSI2.lecU+CSI2.labU}}] = {{ CSI2.hours }} / {{ CSI2.lecU+CSI2.labU*3 }} hrs &nbsp; :: &nbsp;Lecture Units [{{ CSI2.lecU }}] = {{ CSI2.hourslec }} / {{ CSI2.lecU }} hrs &nbsp; :: &nbsp;Lab Units [{{ CSI2.labU }}] = {{ CSI2.hourslab }} / {{ CSI2.labU*3 }} hrs
                            </span>
                        </div>
                    </div>
                    <div class="mt-1.5 flex flex-row items-center gap-x-1 border-t-2 w-full h-max text-xs text-[#253B80] font-medium">
                        <input type="checkbox" v-model="statOther" class="ml-0.5 mt-0.5 w-3 h-3 cursor-pointer border-1 border-sky-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500 rounded-sm"/>
                        <span class="mt-px">Show Schedules' Status from the other User/s.</span>
                    </div>
                    <div class="flex flex-col items-center h-auto gap-y-1">
                        <div class="mt-1 flex items-center gap-x-1 w-max h-max text-xs text-[#253B80] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#55bc7c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span>Saved Completely</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-[#253B80] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8f55bc" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            <span>Insufficient no. of Hours per Total Units</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-[#253B80] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#bf071e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <span>Not Saved</span>
                        </div>
                        <div class="flex items-center gap-x-1 w-max h-max text-xs text-[#253B80] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#da7807" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>Exceeds no. of Hours per Total Units</span>
                        </div>
                    </div>
                    <div class="-ml-1 mt-1 mb-0.5 flex items-center text-xs text-[#253B80] font-medium text-center">
                        <div class="mr-0.5 w-1/5 h-6 border border-1 bg-[#45c2fc] border-[#253B80] rounded-md"><span class="align-middle text-xs text-[#253B80]">Lecture</span></div>
                        <div class="mr-0.5 w-1/5 h-6 border border-1 bg-[#97DDFF] border-[#253B80] rounded-md"><span class="align-middle text-xs text-[#253B80]">Laboratory</span></div>
                        <span>Saved Schedules</span>
                    </div>
                    <div class="-ml-1 mb-1 flex items-center text-xs text-[#253B80] font-medium">
                        <div class="mr-0.5 w-1/5 h-6 border border-1 bg-[#6dd8db] border-[#253B80] rounded-md text-center"><span class="align-middle text-xs text-[#253B80]">Lecture</span></div>
                        <div class="mr-0.5 w-1/5 h-6 border border-1 bg-[#a5f0f3] border-[#253B80] rounded-md text-center"><span class="align-middle text-xs text-[#253B80]">Laboratory</span></div>
                        <span>Saved Schedules by the Other User/s</span>
                    </div>
                </div>

                <div class="col-span-4 flex justify-center mt-4">
                    <button v-if="(subjectList.length > 0)" @click="reset(), toggleModalAdd()"
                        class="h-auto py-1.5 w-[70%] outline outline-offset-2 outline-1 outline-sky-400 hover:outline-sky-600 text-sm font-bold uppercase rounded-lg text-white bg-sky-400 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">
                        Add Schedule
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-[70%] outline outline-offset-2 outline-1 outline-sky-400 text-sm font-bold uppercase rounded-lg text-white bg-sky-400">
                        Add Schedule
                    </button>
                </div>

                <div class="col-span-4 flex justify-center mt-4">
                    <button @click="toggleModalFaculty(), fetchFacultiesReports(), viewSchedule = 'faculty'"
                        class="h-auto py-1.5 w-[80%] outline outline-offset-2 outline-1 outline-cyan-400 hover:outline-cyan-600 text-sm font-bold uppercase rounded-lg text-white bg-cyan-400 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400">
                        View Faculty Schedule
                    </button>
                </div>
                <div class="col-span-4 flex justify-center mt-4">
                    <button @click="toggleModalClassroom(), fetchClassroomsCollegeReports(), viewSchedule = 'classroom'"
                        class="h-auto py-1.5 w-[80%] outline outline-offset-2 outline-1 outline-cyan-400 hover:outline-cyan-600 text-sm font-bold uppercase rounded-lg text-white bg-cyan-400 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400">
                        View Classroom Schedule
                    </button>
                </div>

                <div class="mb-1 col-span-4 flex justify-center mt-4">
                    <button v-if="classscheduleList.length > 0 && userType == 'dept'" @click="toggleModalPrintClass()"
                        class="h-auto py-1.5 w-[80%] flex items-center justify-center outline outline-offset-2 outline-1 outline-[#2C4899] hover:outline-[#253B80] text-sm hover:text-slate-300 font-bold uppercase rounded-lg text-white bg-[#253B80] hover:bg-[#2C4899] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2C4899]">
                        Print This Class Schedule
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg>
                    </button>
                    <button v-else-if="classscheduleList.length > 0 && userType == 'reg'" @click="fetchSchedulesCollege(); toggleModalClassScheduleREG()"
                        class="h-auto py-1.5 w-[80%] flex items-center justify-center outline outline-offset-2 outline-1 outline-[#2C4899] hover:outline-[#253B80] text-sm hover:text-slate-300 font-bold uppercase rounded-lg text-white bg-[#253B80] hover:bg-[#2C4899] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2C4899]">
                        View Class Schedules
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down ml-1.5" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg> -->
                    </button>
                    <button v-else disabled
                        class="cursor-not-allowed h-auto py-1.5 w-8/12 flex items-center justify-center outline outline-offset-2 outline-1 outline-[#2C4899] text-sm font-bold uppercase rounded-lg text-white bg-[#253B80]">
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

        <div v-if="showModal" class="overflow-x-hidden fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div v-if="loading" class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <div v-else class="relative overflow-x-auto w-auto mx-auto max-w-[90vw]">
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
                        <div class="px-5 pb-1 grid grid-cols-12 overflow-y-auto h-auto max-h-[85vh]">
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
                                <div class="col-span-2 flex justify-center items-center border-r">
                                    <label
                                        for="subject"
                                        class="uppercase text-base font-mono font-semibold text-gray-700"
                                        >Course
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
                                <div class="col-span-3 flex flex-col justify-center border-l">
                                    <label
                                        for="classroom"
                                        class="uppercase text-base text-center font-mono font-semibold text-gray-700"
                                        >Classroom
                                    </label>
                                    <div class="flex justify-around">
                                        <div>
                                            <label
                                                for="Lec"
                                                class="uppercase text-base font-mono font-semibold text-gray-700"
                                                >Lecture
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                for="Lab"
                                                class="uppercase text-base font-mono font-semibold text-gray-700"
                                                >Laboratory
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 grid grid-cols-12 flex items-center border-t-2" v-for="(item, index) in preferredSchedule" :key="index">
                                <div class="col-span-2 pr-1 border-r">
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
                                        <input v-if="(item.lec >= 1)" @change="getErrorS(index, item.lecerror, item.laberror, 'lec')" v-model="item.split_lec" value=1 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="two" class="text-sm font-medium text-gray-700">2</label>
                                        <input v-if="(item.lec >= 2)" @change="getErrorS(index, item.lecerror, item.laberror, 'lec')" v-model="item.split_lec" value=2 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="three" class="text-sm font-medium text-gray-700">3</label>
                                        <input v-if="(item.lec >= 3)" @change="getErrorS(index, item.lecerror, item.laberror, 'lec')" v-model="item.split_lec" value=3 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
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
                                        <input v-if="(item.lab >= 1)" @change="getErrorS(index, item.lecerror, item.laberror, 'lab')" v-model="item.split_lab" value=1 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="two" class="text-sm font-medium text-gray-700">2</label>
                                        <input v-if="(item.lab >= 1)" @change="getErrorS(index, item.lecerror, item.laberror, 'lab')" v-model="item.split_lab" value=2 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                        <label for="three" class="text-sm font-medium text-gray-700">3</label>
                                        <input v-if="(item.lab >= 1)" @change="getErrorS(index, item.lecerror, item.laberror, 'lab')" v-model="item.split_lab" value=3 type="radio" required class="ml-px mr-1.5 cursor-pointer border-2 border-orange-500 checked:bg-sky-500 focus:ring-sky-500 focus:ring-1 focus:ring-offset-1 focus:checked:bg-sky-500"/>
                                        <input v-else type="radio" value=0 disabled class="ml-px mr-1.5 cursor-not-allowed place-self-center border-2 border-gray-500"/>
                                    </div>
                                </div>
                                <div class="col-span-2 flex items-center justify-center border-l border-r gap-x-1.5">
                                    <div v-for="(items, indexx) in preferredDays">
                                        <input type="checkbox" :value="indexx" v-model="item.prefDays" :disabled="(item.prefDays.length >= 5 && item.prefDays.indexOf(indexx) == -1)"
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
                                        @change="getErrorF(index, item.lecerror, item.laberror)"
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
                                <div class="col-span-3 flex flex-row pl-1 border-l gap-x-1">
                                    <div>
                                        <select
                                            v-model="item.c_id_lec"
                                            required
                                            @change="getError(index, item.lecerror, 'lec'), item.lecerror = ''"
                                            class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Classroom</option>
                                            <option v-for="lists in item.classroom_id_lec"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Building_No + " - " + lists.Classroom_No + " [ " + lists.Classroom_Type + " ]" }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <select
                                            v-if="item.lab > 0"
                                            v-model="item.c_id_lab"
                                            required
                                            @change="getError(index, item.laberror, 'lab'), item.laberror = ''"
                                            class="mt-1 block w-full py-2 px-3 invalid:border-orange-500 invalid:text-orange-600 focus:invalid:border-orange-500 focus:invalid:ring-orange-500 border-2 border-sky-500 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-400 focus:border-sky-400 sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Classroom</option>
                                            <option v-for="lists in item.classroom_id_lab"
                                                    v-bind="lists.id"
                                                    :value="lists.id"
                                                    class="text-sky-600 text-justify"
                                                    >
                                                    {{ lists.Building_No + " - " + lists.Classroom_No + " [ " + lists.Classroom_Type + " ]" }}
                                            </option>
                                        </select>
                                        <select
                                            v-else
                                            disabled
                                            class="cursor-not-allowed mt-1 block w-full py-2 px-3 border-2 border-gray-500 bg-white rounded-md shadow-sm sm:text-sm"
                                        >
                                            <option disabled class="text-center" value="">Select a Classroom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-6 flex justify-center">
                                    <span class="font-medium text-xs text-red-500">{{ item.lecerror }}</span>
                                </div>
                                <div class="col-span-6 flex justify-center">
                                    <span class="font-medium text-xs text-red-500">{{ item.laberror }}</span>
                                </div>
                            </div>
                        </div>
                        <!--footer-->
                        <div class="flex items-center justify-center border-t border-solid border-slate-200 rounded-b">
                            <button v-if="(curriculumListNotSave.length > 0 && countError <= 0)" type="submit" class="text-sky-500 bg-transparent border border-solid border-sky-500 hover:bg-sky-500 hover:text-white active:bg-sky-600 font-bold uppercase text-sm px-4 py-2 rounded outline-none focus:outline-none my-2 ease-linear transition-all duration-150">
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
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

export default {
    name: 'Class Scheduler',
    components: { VueCal },
    setup () {
        const myChart = ref(null);
        const myChartALL = ref(null);
        const showChart = ref(false);
        const totalCurr = ref(0);
        const totalClasses = ref([0,0]);
        const completeCount = ref([0,0]);
        const incompleteCount = ref([0,0]);
        const withoutCount = ref([0,0]);
        const classLists = ref([]);
        const completeList = ref([]);
        const incompleteList = ref([]);
        const withoutList = ref([]);
        const classListsALL = ref([]);
        const completeListALL = ref([]);
        const incompleteListALL = ref([]);
        const withoutListALL = ref([]);

        const data = ref({});
        const config = ref({});
        const dataALL = ref({});
        const configALL = ref({});
        const showClasses = ref(false);
        const showClassesALL = ref(false);
        const legendCollege = ref([]);
        const legendLabel = ref("");
        const legendCount = ref(0);

        function newClickHandler(e, legendItem, legend) {
            legendLabel.value = legendItem.text;
            legendCount.value = legend.chart.data.datasets[0].data[legendItem.index];
            classLists.value = [];
            switch (legendItem.text) {
                case "With Complete Saved Schedules":
                    classLists.value = classLists.value.concat(completeList.value);
                    break;
                case "With Incomplete Saved Schedules":
                    classLists.value = classLists.value.concat(incompleteList.value);
                    break;
                case "Without Saved Schedules":
                    classLists.value = classLists.value.concat(withoutList.value);
                    break;
            }
            if(legend.chart.data.datasets[0].data[legendItem.index] != 0)
                showClasses.value = true;
        }
        function newClickHandlerALL(e, legendItem, legend) {
            legendLabel.value = legendItem.text;
            legendCount.value = legend.chart.data.datasets[0].data[legendItem.index];
            classListsALL.value = [];
            switch (legendItem.text) {
                case "With Complete Saved Schedules":
                    classListsALL.value = classListsALL.value.concat(completeListALL.value);
                    break;
                case "With Incomplete Saved Schedules":
                    classListsALL.value = classListsALL.value.concat(incompleteListALL.value);
                    break;
                case "Without Saved Schedules":
                    classListsALL.value = classListsALL.value.concat(withoutListALL.value);
                    break;
            }
            if(legend.chart.data.datasets[0].data[legendItem.index] != 0)
                showClassesALL.value = true;
        }

        function handleHover(evt, item, legend) {
            legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
                colors[index] = index === item.index || color.length === 9 ? color : color + '4D';
            });
            legend.chart.update();
        }
        function handleLeave(evt, item, legend) {
            legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
                colors[index] = color.length === 9 ? color.slice(0, -2) : color;
            });
            legend.chart.update();
        }

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

        const showModalMerge = ref(false);
        const toggleModalMerge = () => {
            if(model3.value.faculty_id != mergeFact.value.faculty_id)
                showModalMerge.value = !showModalMerge.value;
            else
                mergeClassSchedule(mergeFact.value.faculty_id, 'not');
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
                head: [['Code', 'Course', 'Credit', 'Lec', 'Lab*', 'Days', 'Time', 'Room', 'Faculty']],
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
                head: [['Class', 'Code', 'Course', 'Credit', 'Lec', 'Lab *', 'Days', 'Time', 'Room']],
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
                head: [['Class', 'Code', 'Course', 'Credit', 'Lec', 'Lab *', 'Days', 'Time', 'Faculty']],
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

        const chooseStartTimeM = ref('08:00');
        const chooseStartTimeA = ref('13:00');

        const onefaculty = ref({});
        const oneclassroom_lec = ref({});
        const oneclassroom_lab = ref({});

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

        const mergeFact = ref({});

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
            collegeList.value = [];
            courseList.value = [];
            yearLevelList.value = [];
            completeList.value = [];
            incompleteList.value = [];
            withoutList.value = [];
            completeListALL.value = [];
            incompleteListALL.value = [];
            withoutListALL.value = [];
            totalClasses.value = [0,0];
            totalCurr.value = 0;
            completeCount.value = [0,0];
            incompleteCount.value = [0,0];
            withoutCount.value = [0,0];
            block.value = 0;
            if(!h$.value.$invalid){
                userType.value = localStorage.getItem('userType');
                loading2.value = true;
                showChart.value = true;
                fetchColleges(),
                fetchTotalClasses().then(()=>{
                    myChart.value = new Chart(document.getElementById('myChart'), config.value);
                    document.getElementById("myChart").onclick = clickHandler;
                    if(localStorage.getItem('userType') == 'reg'){
                        myChartALL.value = new Chart(document.getElementById('myChartALL'), configALL.value);
                        document.getElementById("myChartALL").onclick = clickHandlerALL;
                    }
                });
            }
        }

        const collegeList = ref([]);
        const courseList = ref([]);

        const events = ref([]); //class schedules
        const eventss = ref([]); //faculty schedules
        const eventsss = ref([]); //classroom schedules
        const eventsREG = ref([]);//view schedules for registrar

        const facultyevent = ref({});
        const classroomevent = ref({});
        const classregevent = ref({});

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
            total: 0,
            count: 0,
            lecU: 0,
            labU: 0,
            hours: 0,
            hourslec: 0,
            hourslab: 0,
        });
        const CSI2 = ref({
            total: 0,
            count: 0,
            lecU: 0,
            labU: 0,
            hours: 0,
            hourslec: 0,
            hourslab: 0,
        });

        const initialState = {
            selectedCollege: '',
            selectedCourse: '',
            selectedYearLevel: '',
            selectedBlock: '',
        }
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
        const subjectUnits = ref([]);
        const facultyList = ref([]);
        const classroomList = ref([]);
        const classScheduleMajor = ref([]);
        const classScheduleMinor = ref([]);

        const clickSubj = ref(null);

        const college = ref([]);
        const facultiesListReport = ref([]);
        const classroomsListReport = ref([]);
        const schedulesList = ref([]);

        // const major = ref('');

        const initialState2 = {
            StartTime: '',
            EndTime: '',
            Day: '',
            Subject: '',
            Session: '',
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
                Session: { required },
                Faculty: { required },
                Classroom: { required },
            }
        })

        const reset = () => {
            model2.value = {...initialState2};
            subjectUnits.value = [];
            facultyList.value = [];
            classroomList.value = [];
            // major.value = '';
        }
        const resetSession = () => {
            model2.value.Session = '';
            model2.value.Classroom = '';
            classroomList.value = [];
        }
        const resetPref = () => {
            preferredSchedule.value = [];
        }
        const resetStartTime = () => {
            chooseStartTimeA.value = '13:00';
            chooseStartTimeM.value = '08:00';
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
            CSI1.value = { total: 0, count: 0, lecU: 0, labU: 0, hours: 0, hourslec: 0, hourslab: 0};
            CSI2.value = { total: 0, count: 0, lecU: 0, labU: 0, hours: 0, hourslec: 0, hourslab: 0};
        }
        const resetM = () => {
            mergeClass.value = {...initialMCS};
        }

        const x$ = useVuelidate(rules2, model2);

        const classscheduleID = ref(false);
        const loading = ref(false);
        const loading1 = ref(false);
        const loading2 = ref(false);
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
        const block = ref(0);

        const countError = ref(0);
        const errorTypeLec = ref([]);
        const errorTypeLab = ref([]);

        const getError = (index, err, type) => {
            if(type == "lec" && errorTypeLec.value[index] == "assroom.")
                if(!(err == null || err == ""))
                    countError.value -= 1;
            if(type == "lab" && errorTypeLab.value[index] == "assroom.")
                if(!(err == null || err == ""))
                    countError.value -= 1;
        }
        const getErrorF = (index, errLec, errLab) => {
            if(errorTypeLec.value[index] == "Faculty.")
                if(!(errLec == null || errLec == "")){
                    countError.value -= 1;
                    preferredSchedule.value[index].lecerror = '';
                }
            if(errorTypeLab.value[index] == "Faculty.")
                if(!(errLab == null || errLab == "")){
                    countError.value -= 1;
                    preferredSchedule.value[index].laberror = '';
                }
        }
        const getErrorS = (index, errLec, errLab, type) => {
            if(type == "lec" && errorTypeLec.value[index] == "session.")
                if(!(errLec == null || errLec == "")){
                    countError.value -= 1;
                    preferredSchedule.value[index].lecerror = '';
                }
            if(type == "lab" && errorTypeLab.value[index] == "session.")
                if(!(errLab == null || errLab == "")){
                    countError.value -= 1;
                    preferredSchedule.value[index].laberror = '';
                }
        }

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

        const fetchTotalClasses = async () => {
            if(localStorage.getItem('userType') == 'reg') {
                legendCollege.value = await APIController.FetchCollege(localStorage.getItem('username'));
                let totalCALL = await APIController.FetchCurriculaTotalsCollege(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('username'));
                let totalCListALL = await APIController.FetchCurriculaTotalsListCollege(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('username'));
            
                if(totalCALL.length != 0) {
                    totalClasses.value[0] = totalCListALL.length;
                    for (let i = 0; i < totalCALL.length; i++) {
                        let statusC = await APIController.FetchCurriculaStatusCollege(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, totalCALL[i].sched_id, totalCALL[i].course_id, totalCALL[i].yearLevel);
                        var countStatS = 0;var countStatN = 0;
                        for (let j = 0; j < statusC.length; j++) {
                            if(statusC[j].status == "Complete")
                                countStatS++;
                            else if(statusC[j].status == "Not Saved")
                                countStatN++;
                        }
                        if(countStatS == statusC.length){
                            completeCount.value[0] += 1;
                            completeListALL.value.push({
                                program_name: totalCALL[i].program_name,
                                yearLevel: totalCALL[i].yearLevel,
                                block: totalCALL[i].block,
                                class_code: totalCALL[i].class_code
                            });
                        }
                        else if(countStatN != statusC.length){
                            incompleteCount.value[0] += 1;
                            incompleteListALL.value.push({
                                program_name: totalCALL[i].program_name,
                                yearLevel: totalCALL[i].yearLevel,
                                block: totalCALL[i].block,
                                class_code: totalCALL[i].class_code
                            });
                        }
                    }
                    withoutListALL.value = withoutListALL.value.concat(totalCListALL);
                    completeListALL.value.forEach(element => {
                        withoutListALL.value = withoutListALL.value.filter(data => data.class_code != element.class_code);
                    });
                    incompleteListALL.value.forEach(element => {
                        withoutListALL.value = withoutListALL.value.filter(data => data.class_code != element.class_code);
                    });
                    withoutCount.value[0] = totalClasses.value[0] - (completeCount.value[0] + incompleteCount.value[0]);
                }

                dataALL.value = {
                        labels: [
                            'With Incomplete Saved Schedules',
                            'Without Saved Schedules',
                            'With Complete Saved Schedules'
                        ],
                        datasets: [{
                            label: 'No. of Classes',
                            data: [incompleteCount.value[0], withoutCount.value[0], completeCount.value[0]],
                            backgroundColor: [//'#78D1F7','#FAA662','#3453B3'
                                '#38bdf8',
                                '#FB923C',
                                '#253B80'
                            ],
                            hoverOffset: 4,
                            cutout: '40%',
                            radius: '100%'
                        }]
                    };
                configALL.value = {
                        type: 'doughnut',
                        data: dataALL.value,
                        options: {
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: '#253B80',
                                    },
                                    onHover: handleHover,
                                    onLeave: handleLeave,
                                    onClick: newClickHandlerALL
                                },
                                title: {
                                    display: true,
                                    color: '#253B80',
                                    text: 'TOTAL NO. OF CLASSES IN '+legendCollege.value.College_Code+'  [ '+totalClasses.value[0]+' ]',
                                    font: {
                                        weight: '800',
                                        size: 14,
                                    },
                                },
                                tooltip: {
                                    position: 'nearest',
                                    bodyFont: {
                                        weight: '600'
                                    }
                                },
                                subtitle: {
                                    display: false,
                                    text: 'Based on the Saved Curriculums by this user.',
                                    color: '#253B80',
                                    font: {
                                        style: 'italic',
                                    },
                                    padding: {
                                        top: -5,
                                        bottom: -10
                                    }
                                },
                                datalabels: {
                                    formatter: (value, context) => {
                                        const datapoints = context.chart.data.datasets[0].data;
                                        function totalSum(total, datapoint){
                                            return total + datapoint;
                                        }
                                        const totalValue = datapoints.reduce(totalSum, 0);
                                        var percentageValue = (value / totalValue * 100).toFixed(1);
                                        if(percentageValue - Math.floor(percentageValue) == 0)
                                            percentageValue = Math.trunc(percentageValue);
                                        var display = [`${percentageValue}%`];
                                        if(percentageValue == 0)
                                            display = "";
                                        return display;
                                    },
                                    color: 'white',
                                    font: {
                                        weight: '800',
                                        size: 14
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    };
            }

            let totalC = await APIController.FetchCurriculaTotals(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
            let totalCList = await APIController.FetchCurriculaTotalsList(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID')); 
            
            if(totalC.length != 0) {
                totalClasses.value[1] = totalCList.length;
                totalCurr.value = totalC[totalC.length-1].curr;
                for (let i = 0; i < totalC.length; i++) {
                    let statusC = await APIController.FetchCurriculaStatus(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, totalC[i].sched_id, totalC[i].course_id, totalC[i].yearLevel, localStorage.getItem('userID'));
                    var countStatS = 0;var countStatN = 0;
                    for (let j = 0; j < statusC.length; j++) {
                        if(statusC[j].status == "Complete")
                            countStatS++;
                        else if(statusC[j].status == "Not Saved")
                            countStatN++;
                    }
                    if(countStatS == statusC.length){
                        completeCount.value[1] += 1;
                        completeList.value.push({
                            program_name: totalC[i].program_name,
                            yearLevel: totalC[i].yearLevel,
                            block: totalC[i].block,
                            class_code: totalC[i].class_code
                        });
                    }
                    else if(countStatN != statusC.length){
                        incompleteCount.value[1] += 1;
                        incompleteList.value.push({
                            program_name: totalC[i].program_name,
                            yearLevel: totalC[i].yearLevel,
                            block: totalC[i].block,
                            class_code: totalC[i].class_code
                        });
                    }
                }
                withoutList.value = withoutList.value.concat(totalCList);
                completeList.value.forEach(element => {
                    withoutList.value = withoutList.value.filter(data => data.class_code != element.class_code);
                });
                incompleteList.value.forEach(element => {
                    withoutList.value = withoutList.value.filter(data => data.class_code != element.class_code);
                });
                withoutCount.value[1] = totalClasses.value[1] - (completeCount.value[1] + incompleteCount.value[1]);
            }
            data.value = {
                            labels: [
                                'With Incomplete Saved Schedules',
                                'Without Saved Schedules',
                                'With Complete Saved Schedules'
                            ],
                            datasets: [{
                                label: 'No. of Classes',
                                data: [incompleteCount.value[1], withoutCount.value[1], completeCount.value[1]],
                                backgroundColor: [
                                '#38bdf8',
                                '#FB923C',
                                '#253B80'
                                ],
                                hoverOffset: 4,
                                cutout: '60%',
                                radius: '90%'
                            }]
                        };
            config.value = {
                                type: 'doughnut',
                                data: data.value,
                                options: {
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                color: '#253B80',
                                            },
                                            onHover: handleHover,
                                            onLeave: handleLeave,
                                            onClick: newClickHandler
                                        },
                                        title: {
                                            display: true,
                                            color: '#253B80',
                                            text: 'TOTAL NO. OF CLASSES  [ '+totalClasses.value[1]+' ]',
                                            font: {
                                                weight: '800',
                                                size: 13,
                                            },
                                        },
                                        tooltip: {
                                            position: 'nearest',
                                            bodyFont: {
                                                weight: '600'
                                            }
                                        },
                                        subtitle: {
                                            display: true,
                                            text: 'Based on the Saved Curriculums [ '+totalCurr.value+' ]  by this user.',
                                            color: '#253B80',
                                            font: {
                                                style: 'italic',
                                            },
                                            padding: {
                                                top: -5,
                                                bottom: -10
                                            }
                                        },
                                        datalabels: {
                                            formatter: (value, context) => {
                                                const datapoints = context.chart.data.datasets[0].data;
                                                function totalSum(total, datapoint){
                                                    return total + datapoint;
                                                }
                                                const totalValue = datapoints.reduce(totalSum, 0);
                                                var percentageValue = (value / totalValue * 100).toFixed(1);
                                                if(percentageValue - Math.floor(percentageValue) == 0)
                                                    percentageValue = Math.trunc(percentageValue);
                                                var display = [`${percentageValue}%`];
                                                if(percentageValue == 0)
                                                    display = "";
                                                return display;
                                            },
                                            color: 'white',
                                            font: {
                                                weight: '800',
                                                size: 13
                                            }
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels]
                            };
            loading2.value = false;
        }

        function clickHandler(evt){
            var points = myChart.value.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length) {
                var firstPoint = points[0];
                var label = myChart.value.data.labels[firstPoint.index];
                var value = myChart.value.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                classLists.value = [];
                switch (label) {
                    case "With Complete Saved Schedules":
                        classLists.value = classLists.value.concat(completeList.value);
                        break;
                    case "With Incomplete Saved Schedules":
                        classLists.value = classLists.value.concat(incompleteList.value);
                        break;
                    case "Without Saved Schedules":
                        classLists.value = classLists.value.concat(withoutList.value);
                        break;
                }
                legendLabel.value = label;
                legendCount.value = value;
                showClasses.value = true;
            }
        }
        function clickHandlerALL(evt){
            var points = myChartALL.value.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length) {
                var firstPoint = points[0];
                var label = myChartALL.value.data.labels[firstPoint.index];
                var value = myChartALL.value.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                classListsALL.value = [];
                switch (label) {
                    case "With Complete Saved Schedules":
                        classListsALL.value = classListsALL.value.concat(completeListALL.value);
                        break;
                    case "With Incomplete Saved Schedules":
                        classListsALL.value = classListsALL.value.concat(incompleteListALL.value);
                        break;
                    case "Without Saved Schedules":
                        classListsALL.value = classListsALL.value.concat(withoutListALL.value);
                        break;
                }
                legendLabel.value = label;
                legendCount.value = value;
                showClassesALL.value = true;
            }
        }

        const fetchColleges = async () => {
            courseList.value = [];
            yearLevelList.value = [];
            block.value = 0;
            collegeList.value = await APIController.FetchCurriculaListCollege(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
        }

        const fetchCourses = async () => {
            yearLevelList.value = [];
            block.value = 0;
            courseList.value = await APIController.FetchCurriculaListCourse(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'), model.value.selectedCollege);
        }

        const fetchYearLevel = async () => {
            block.value = 0;
            yearLevelList.value = await APIController.FetchCurriculaListYearLevel(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'), model.value.selectedCourse);
        }

        const fetchBlock = async (id) => {
            let tempblock = await APIController.FetchCourseBlock(model.value.selectedCourse, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            if (tempblock){
                switch (id) {
                    case "First Year":
                        block.value = tempblock.first;
                        break;
                    case "Second Year":
                        block.value = tempblock.second;
                        break;
                    case "Third Year":
                        block.value = tempblock.third;
                        break;
                    case "Fourth Year":
                        block.value = tempblock.fourth;
                        break;
                }
            }
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
            countError.value = 0;
            errorTypeLec.value = [];
            errorTypeLab.value = [];
            loading.value = true;
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
                                                classroom_id_lec: {},
                                                classroom_id_lab: {},
                                                f_id: '',
                                                c_id_lec: '',
                                                c_id_lab: '',
                                                lecerror: '',
                                                laberror: '',
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
            
                oneclassroom_lec.value = await APIController.FetchClassroomCurriculum(curriculumListNotSave.value[i].classrooms_id);

                preferredSchedule.value[i].classroom_id_lec = oneclassroom_lec.value.map(function (element){
                    let properties = {
                        "id": element.id,
                        "Building_No": element.Building_No,
                        "Classroom_No": element.Classroom_No,
                        "Classroom_Type": element.Classroom_Type
                    };
                    return properties;
                });

                oneclassroom_lab.value = await APIController.FetchClassroomCurriculumLab(curriculumListNotSave.value[i].classrooms_id);

                if(oneclassroom_lab.value.length>0)
                preferredSchedule.value[i].classroom_id_lab = oneclassroom_lab.value.map(function (element){
                    let properties = {
                        "id": element.id,
                        "Building_No": element.Building_No,
                        "Classroom_No": element.Classroom_No,
                        "Classroom_Type": element.Classroom_Type
                    };
                    return properties;
                });
            }
            loading.value = false;
        }

        // const FetchClassroomsModal = async (id) => {
        //     classroomList.value = await APIController.FetchClassroomsPerCurriculumBySubject(id);
        // }

        const AddSchedule = async () => {
            showChart.value = true;
            loading1.value = true;
            // userType.value = localStorage.getItem('userType');
            tempSchedule = await APIController.CreateSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, model.value.selectedBlock);
            if(tempSchedule){
                schedule.value = tempSchedule.id;
                fetchCurricula();
                fetchCurriculaStatus();
                fetchClassSchedules();
                sched.value = true;
            }
            else {
                schedule.value = await APIController.FetchSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, model.value.selectedBlock);
                sched.value = true;
                fetchCurricula();
                fetchCurriculaStatus();
                fetchClassSchedules();
            }
            loading1.value = false;
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
            const classrooms_lec = preferredSchedule.value.map(function (element) {
                return element.c_id_lec;
            });
            const classrooms_lab = preferredSchedule.value.map(function (element) {
                return element.c_id_lab;
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
                let gensched = await APIController.GenerateClassSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, chooseStartTimeM.value, chooseStartTimeA.value, JSON.stringify(subjects), JSON.stringify(lec), JSON.stringify(lab), JSON.stringify(split_lec), JSON.stringify(split_lab), JSON.stringify(prefDays), JSON.stringify(time_period), JSON.stringify(faculties), JSON.stringify(classrooms_lec), JSON.stringify(classrooms_lab), localStorage.getItem('userID'));
                if(gensched){
                    let arr = [];
                    arr = Object.keys(gensched);
                    for(let i = 0; i < arr.length; i++){
                        if(!(gensched[arr[i]].lecerror == null || gensched[arr[i]].lecerror == "")){
                            countError.value += 1;
                            errorTypeLec.value[arr[i]] = gensched[arr[i]].lecerror.slice(-8);
                        }
                        if(!(gensched[arr[i]].laberror == null || gensched[arr[i]].laberror == "")){
                            countError.value += 1;
                            errorTypeLab.value[arr[i]] = gensched[arr[i]].laberror.slice(-8);
                        }
                        preferredSchedule.value[parseInt(arr[i])].lecerror = gensched[arr[i]].lecerror;
                        preferredSchedule.value[parseInt(arr[i])].laberror = gensched[arr[i]].laberror;
                    }
                } else {
                    fetchCurricula();
                    fetchCurriculaStatus();
                    fetchClassSchedules();
                    toggleModal();
                    resetPref();
                    resetStartTime();
                }
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
            else if(model2.value.Session == "Lecture" && minET-minST > subjectUnits.value.lec * 60)
                alert("The selected time range exceeded the equivalent hours per lecture unit.");
            else if(model2.value.Session == "Laboratory" && minET-minST > subjectUnits.value.lab * 3 * 60)
                alert("The selected time range exceeded the equivalent hours per laboratory unit.");
            else{
                console.log(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model2.value.Day, model2.value.StartTime, model2.value.EndTime, model2.value.Subject, model2.value.Session, model2.value.Faculty, model2.value.Classroom, localStorage.getItem('userID'), " ");
                let tempClassSchedule = await APIController.CreateClassSchedule(modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, schedule.value, model2.value.Day, model2.value.StartTime, model2.value.EndTime, model2.value.Subject, model2.value.Session, model2.value.Faculty, model2.value.Classroom, localStorage.getItem('userID'));
                console.log(tempClassSchedule);
                if(tempClassSchedule){
                    fetchCurricula();
                    fetchCurriculaStatus(),
                    fetchClassSchedules();
                    reset();
                    toggleModalAdd();
                }
            }
        }

        const fetchClassSchedulesREG = async () => {
            classscheduleListREG.value = await APIController.FetchClassSchedules(registrar.value.registrar);
            let crCS = await APIController.FetchClassSchedulesInfo(registrar.value.registrar, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            classInfo.value.count = crCS.length;
            if(crCS){
                crCS.forEach(e => {
                    classInfo.value.lecU = classInfo.value.lecU + e.lec;
                    classInfo.value.labU = classInfo.value.labU + e.lab;
                });
            }

            eventsREG.value = classscheduleListREG.value.map(function (element){
                let properties = {};
                if(element.user_id == localStorage.getItem('userID')){
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                        "class": "saved1classREG"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "faculty": element.Faculty_Name,
                        "classroom": element.Building_No+"-"+element.Classroom_No,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                } else {
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                        "class": "saved2classREG"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "faculty": element.Faculty_Name,
                        "classroom": element.Building_No+"-"+element.Classroom_No,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                }
                return properties;
            });

            for (let q = 0; q < days.value.length; q++) {
                eventsREG.value.push({
                    start: days.value[q]+' 12:00',
                    end: days.value[q]+' 13:00',
                    title: 'LUNCH',
                    class: 'lunch',
                    background: true
                });
            }
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
                let properties = {};
                if(element.user_id == localStorage.getItem('userID')){
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" || "+element.Building_No+" "+element.Classroom_No+"<br>[ Lec: "+element.lec+" - Lab: "+element.lab+" ]",
                        "class": "saved1faculty"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "classroom": element.Building_No+"-"+element.Classroom_No,
                        "course": element.Course_Code+" - "+element.yearLevel+" "+element.block,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                } else {
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" || "+element.Building_No+" "+element.Classroom_No+"<br>[ Lec: "+element.lec+" - Lab: "+element.lab+" ]",
                        "class": "saved2faculty"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "classroom": element.Building_No+"-"+element.Classroom_No,
                        "course": element.Course_Code+" - "+element.yearLevel+" "+element.block,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                }
                return properties;
            });

            for (let q = 0; q < days.value.length; q++) {
                eventss.value.push({
                    start: days.value[q]+' 12:00',
                    end: days.value[q]+' 13:00',
                    title: 'LUNCH',
                    class: 'lunch',
                    background: true
                });
            }
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
                let properties = {};
                if(element.user_id == localStorage.getItem('userID')){
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" [ Lec: "+element.lec+" - Lab: "+element.lab+" ] <br>"+element.Faculty_Name,
                        "class": "saved1classroom"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "faculty": element.Faculty_Name,
                        "course": element.Course_Code+" - "+element.yearLevel+" "+element.block,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                } else {
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Course_Code+" "+element.yearLevel+"-"+element.block+" [ Lec: "+element.lec+" - Lab: "+element.lab+" ] <br>"+element.Faculty_Name,
                        "class": "saved2classroom"+element.session,
                        "session": element.session,
                        "units": "Units: "+(element.lec+element.lab)+" | Lec: "+element.lec+" | Lab: "+element.lab,
                        "dayy": element.dayy,
                        "subject": element.Subject_Name,
                        "subjectcode": element.Subject_Code,
                        "faculty": element.Faculty_Name,
                        "course": element.Course_Code+" - "+element.yearLevel+" "+element.block,
                        "timee": element.startTime.slice(0, -3)+" - "+element.endTime.slice(0, -3)
                    }
                }
                return properties;
            });

            for (let q = 0; q < days.value.length; q++) {
                eventsss.value.push({
                    start: days.value[q]+' 12:00',
                    end: days.value[q]+' 13:00',
                    title: 'LUNCH',
                    class: 'lunch',
                    background: true
                });
            }
        }

        const fetchClassSchedules = async () => {
            resetCSI();
            let csi1_ = await APIController.FetchClassSchedulesInfoUser(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, localStorage.getItem('userID'));
            let csi2_ = await APIController.FetchClassSchedulesInfo(schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            
            CSI1.value.total = curriculumStatus.value.length;
            CSI2.value.total = curriculumStatus.value.length + curriculumStatusOther.value.length;

            curriculumStatus.value.forEach(e => {
                CSI1.value.lecU = CSI1.value.lecU + e.lec;
                CSI1.value.labU = CSI1.value.labU + e.lab;
            });
            CSI2.value.lecU = CSI1.value.lecU;
            CSI2.value.labU = CSI1.value.labU;
            curriculumStatusOther.value.forEach(e => {
                CSI2.value.lecU = CSI2.value.lecU + e.lec;
                CSI2.value.labU = CSI2.value.labU + e.lab;
            });

            if(csi1_){    
                CSI1.value.count = csi1_.length;
                csi1_.forEach(e => {
                    CSI1.value.hours = CSI1.value.hours + e.Hours;
                    CSI1.value.hourslec = CSI1.value.hourslec + e.HoursLec;
                    CSI1.value.hourslab = CSI1.value.hourslab + e.HoursLab;
                });
            }
            if(csi2_){
                CSI2.value.count = csi2_.length;
                csi2_.forEach(e => {
                    CSI2.value.hours = CSI2.value.hours + e.Hours;
                    CSI2.value.hourslec = CSI2.value.hourslec + e.HoursLec;
                    CSI2.value.hourslab = CSI2.value.hourslab + e.HoursLab;
                });
            }

            classscheduleList.value = await APIController.FetchClassSchedules(schedule.value);

            events.value = classscheduleList.value.map(function (element){
                let properties = {};
                if(element.user_id == localStorage.getItem('userID')){
                    properties = {
                        "start": element.day.concat(" "+element.startTime),
                        "end": element.day.concat(" "+element.endTime),
                        "title": element.Subject_Name,
                        "content": element.Faculty_Name+"<br>"+element.Building_No+" "+element.Classroom_No,
                        "class": "saved1"+element.session,
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
                        "class": "saved2"+element.session,
                        "s_id": element.subject_id,
                        "id": element.id,
                        "userID": element.user_id
                    }
                }
                return properties;
            });

            for (let q = 0; q < days.value.length; q++) {
                events.value.push({
                    start: days.value[q]+' 12:00',
                    end: days.value[q]+' 13:00',
                    title: 'LUNCH',
                    class: 'lunch',
                    background: true
                });
            }
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
                    fetchCurricula();
                    fetchCurriculaStatus();
                    fetchClassSchedules();
                    classscheduleID.value = false;
                    toggleModalUpdate();
                    cancel();
                    showModalEditMode.value = false;
                }
            }
        }

        const fetchFacultyByClassSchedule = async (cs_id) => {
            mergeFact.value = await APIController.FetchFacultyByClassSchedule(cs_id);
        }

        const mergeClassSchedule = async (faculty, changed) => {
            merge1.value = await APIController.FetchClassScheduleM(classscheduleID.value);
            merge2.value = await APIController.FetchClassScheduleM(mergeClass.value.mergeClass);
            let tempClassSchedule = await APIController.MergeClassSchedule(merge1.value.schedule_id, merge2.value.schedule_id, merge2.value.day, merge2.value.startTime, merge2.value.endTime, merge2.value.subject_id, faculty, merge2.value.classroom_id, classscheduleID.value, mergeClass.value.mergeClass, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, changed);
                if(tempClassSchedule){
                    fetchCurricula();
                    fetchCurriculaStatus();
                    fetchClassSchedules();
                    classscheduleID.value = false;
                    showModalMerge.value = false;
                    cancel();
                    resetM();
                }
        }

        const deleteClassSchedule = async () => {
            const success = await APIController.DeleteClassSchedule(classscheduleID.value);
            if (success) {
                cancel(),
                fetchCurricula();
                fetchCurriculaStatus();
                fetchClassSchedules();
                showModalEditMode.value = false;
                showModalDeleteSched.value = false;
            }
        }

        const deleteClassSchedulesReset = async () => {
            const success = await APIController.DeleteClassSchedulesReset(schedule.value, localStorage.getItem('userID'));
            if (success) {
                fetchCurricula();
                fetchCurriculaStatus();
                fetchClassSchedules();
                toggleModalDeleteSchedules();
            }
        }

        // const fetchMajorFaculty = async () => {
        //     facultyList.value = await APIController.FetchMajorFaculties(model.value.selectedCollege);
        //     // console.log(model.value.selectedCollege);
        // }

        const fetchSubjectUnits = async (id) => {
            subjectUnits.value = await APIController.FetchSubjectUnits(id, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel);
        }

        const fetchFaculties = async (id) => {
            facultyList.value = await APIController.FetchFacultiesBySubject(id, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel);
        }

        const fetchSchedulesCollege = async () => {
            schedulesList.value = await APIController.FetchSchedulesByCollege(localStorage.getItem('collegeId'), modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
        }

        // const fetchClassroomsCollege = async () => {
        //     classroomsList.value = await APIController.FetchClassroomsByCollege(localStorage.getItem('collegeId'));
        // }

        const fetchClassroomsCollegeReports = async () => {
            if(localStorage.getItem('userType') == 'reg')
            classroomsListReport.value = await APIController.FetchClassroomsByCollegeReports(localStorage.getItem('collegeId'), modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            else
            classroomsListReport.value = await APIController.FetchClassroomsByDepartmentReports(localStorage.getItem('userID'), modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
        }

        // const fetchFacultiesCollege = async () => {
        //     facultiesList.value = await APIController.FetchFacultiesByCollege(localStorage.getItem('collegeId'));
        // }

        const fetchFacultiesReports = async () => {
            if(localStorage.getItem('userType') == 'reg')
            facultiesListReport.value = await APIController.FetchFacultiesByCollegeReports(localStorage.getItem('collegeId'), modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
            else
            facultiesListReport.value = await APIController.FetchFacultiesByDepartmentReports(college.value.id, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
        }

        // const fetchFacultiesDepartment = async () => {
        //     facultiesList.value = await APIController.FetchFacultiesByDepartment(college.value.id);
        // }

        // const fetchFacultiesDepartmentReports = async () => {
        //     facultiesListReport.value = await APIController.FetchFacultiesByDepartmentReports(college.value.id, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester);
        // }

        // const fetchMajorClassrooms = async () => {
        //     classroomList.value = await APIController.FetchMajorClassrooms(model.value.selectedCollege);
        //     // console.log(model.value.selectedCollege);
        // }

        const fetchClassrooms = async (type) => {
            classroomList.value = await APIController.FetchClassroomsBySubject(subjectUnits.value.id, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, model.value.selectedCourse, model.value.selectedYearLevel, type);
        }

        const fetchClassScheduleMajor = async (type) => { // parameters are college id, subject id, year level and schedule id(?)
            // console.log(model3.value.subject_id, model.value.selectedCourse, model.value.selectedYearLevel, classscheduleID.value)
            classScheduleMajor.value = await APIController.FetchClassSchedulesMergingMajor(model3.value.subject_id, model.value.selectedCourse, model.value.selectedYearLevel, schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, type);
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

        const fetchClassScheduleMinor = async (type) => {// parameters are subject id and schedule id(?)
            classScheduleMinor.value = await APIController.FetchClassSchedulesMergingMinor(model3.value.subject_id, schedule.value, modelHeader.value.selectedAcademicYear, modelHeader.value.selectedSemester, type);
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
            fetchSubjectUnits(event.s_id);
            fetchFaculties(event.s_id);
            if((event.class == "saved1Lecture" || event.class == "saved1Laboratory") && event.userID == localStorage.getItem('userID'))
            {
                loading.value = true;
                classscheduleID.value = event.id;
                GetClassSchedule().then(()=>{
                    localStorage.setItem('clickSubj', model3.value.Subject_Type);
                    fetchClassrooms(model3.value.session);
                    if (model3.value.Subject_Type === "Major" || model3.value.Subject_Type === "Elective"){
                        clickSubj.value = localStorage.getItem('clickSubj');
                        fetchClassScheduleMajor(model3.value.session);
                    }else{
                        // localStorage.getItem('clickSubj');
                        clickSubj.value = localStorage.getItem('clickSubj');
                        fetchClassScheduleMinor(model3.value.session);
                    }
                });
                
                
                // console.log(model3.value)
            } else if(event.class == 'lunch'){

            } else if(event.userID != localStorage.getItem('userID')) {

            } else if(event.class == "saved2Lecture" || event.class == "saved2Laboratory"){

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

        const showModalFacultyEvent = ref(false);

        function onEventClickFaculty(event){
            if(event.class == 'lunch'){
            }else{
                facultyevent.value = event;
                showModalFacultyEvent.value = true; 
            }
        }

        const showModalClassroomEvent = ref(false);

        function onEventClickClassroom(event){
            if(event.class == 'lunch'){
            }else{
                classroomevent.value = event;
                showModalClassroomEvent.value = true;  
            }
        }

        const showModalClassREGEvent = ref(false);

        function onEventClickREG(event){
            if(event.class == 'lunch'){
            }else{
                classregevent.value = event;
                showModalClassREGEvent.value = true;  
            }
        }

        function cancel () {
            classscheduleID.value = false;
            reset();
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
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
            fetchSubjectUnits,
            subjectUnits,
            fetchFaculties,
            facultyList,
            classroomList,
            fetchClassrooms,
            // fetchMajorClassrooms,
            onEventClick,
            onEventClickFaculty,
            onEventClickClassroom,
            onEventClickREG,
            facultyevent,
            classroomevent,
            classregevent,
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
            loading2,
            cancel,
            deleteClassSchedule,
            fetchCurricula,
            curriculumList,
            delete_recommend,
            fetchYearLevel,
            fetchBlock,
            yearLevelList,
            block,
            subjectList,
            subjectListUpdate,
            deleteCS,
            resetB,
            classroomsListReport,
            fetchClassroomsCollegeReports,
            facultiesListReport,
            fetchFacultiesReports,
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
            mergeFact,
            fetchFacultyByClassSchedule,
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
            oneclassroom_lec,
            oneclassroom_lab,
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
            resetSession,
            resetPref,
            resetStartTime,
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
            showModalMerge,
            toggleModalMerge,
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
            printClassroomSchedule,

            showModalFacultyEvent,
            showModalClassroomEvent,
            showModalClassREGEvent,

            countError,
            getError,
            errorTypeLec,
            errorTypeLab,
            getErrorF,
            getErrorS,

            data,
            config,
            dataALL,
            configALL,
            newClickHandler,
            newClickHandlerALL,
            handleHover,
            handleLeave,
            showClasses,
            showClassesALL,
            legendLabel,
            legendCount,
            myChart,
            myChartALL,
            showChart,
            fetchTotalClasses,
            totalCurr,
            totalClasses,
            completeCount,
            incompleteCount,
            withoutCount,
            classLists,
            completeList,
            incompleteList,
            withoutList,
            clickHandler,
            classListsALL,
            completeListALL,
            incompleteListALL,
            withoutListALL,
            clickHandlerALL,
            legendCollege
        }
    },
    methods: {
        getUserType(){
            this.userType = localStorage.getItem('userType');
        },
        onChangeSubject(e){
            return e.target.value;
        },
        onChangeSession(e){
            return e.target.value;
        },
        onChangeYearLevel(e){
            return e.target.value;
        },
        onChangeFacultyMerge(e){
            return e.target.value;
        },
    },
    mounted() {
        if (this.classscheduleID != false){
            this.GetClassSchedule();
        }
        // this.GetClassSchedule();
        this.GetCollege();
        this.getUserType();
    }
}
</script>

<style>
.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #b5e3f7;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #38bdf8 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.lds-facebook {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-facebook div {
  display: inline-block;
  position: absolute;
  left: 8px;
  width: 16px;
  background: #253B80;
  animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
}
.lds-facebook div:nth-child(1) {
  left: 8px;
  animation-delay: -0.24s;
}
.lds-facebook div:nth-child(2) {
  left: 32px;
  animation-delay: -0.12s;
}
.lds-facebook div:nth-child(3) {
  left: 56px;
  animation-delay: 0;
}
@keyframes lds-facebook {
  0% {
    top: 8px;
    height: 64px;
  }
  50%, 100% {
    top: 24px;
    height: 32px;
  }
}

.loader {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  animation: spin 3s linear infinite;
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
  transition: 8s;
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
  transition: 12s;
}
.textBox2:hover span {
  transform: translateX(calc(150px - 80%));
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

.vuecal__event.saved1Lecture { background-color: rgba(98, 205, 255, 0.85);border: 1px solid #2C4899;border-radius: 12px;color: #253B80;}
.vuecal__event.saved1Laboratory { background-color: rgba(151, 222, 255, 0.70);border: 1px solid #2C4899;border-radius: 12px;color: #253B80;}
.vuecal__event.saved2Lecture {cursor: not-allowed; background-color: rgba(109, 216, 219, 0.85);border: 1px solid rgb(2, 93, 109);border-radius: 12px;color: rgb(2, 93, 109);}
.vuecal__event.saved2Laboratory {cursor: not-allowed; background-color: rgba(165, 240, 243, 0.7);border: 1px solid rgb(2, 93, 109);border-radius: 12px;color: rgb(2, 93, 109);}

.vuecal__event.saved1classroomLecture {background-color: rgba(14, 165, 233, 0.65);border: 1px solid rgb(144, 214, 247);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved1classroomLaboratory {background-color: rgba(116, 203, 243, 0.65);border: 1px solid rgb(144, 214, 247);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved2classroomLecture {background-color: rgba(109, 216, 219, 0.85);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}
.vuecal__event.saved2classroomLaboratory {background-color: rgba(163, 237, 240, 0.70);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}
.vuecal__event.saved1facultyLecture {background-color: rgb(14, 165, 233, 0.65);border: 1px solid rgb(108, 207, 253);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved1facultyLaboratory {background-color: rgba(116, 203, 243, 0.65);border: 1px solid rgb(108, 207, 253);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved2facultyLecture {background-color: rgba(109, 216, 219, 0.85);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}
.vuecal__event.saved2facultyLaboratory {background-color: rgba(163, 237, 240, 0.70);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}
.vuecal__event.saved1classREGLecture {background-color: rgb(14, 165, 233, 0.65);border: 1px solid rgb(108, 207, 253);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved1classREGLaboratory {background-color: rgba(116, 203, 243, 0.65);border: 1px solid rgb(108, 207, 253);border-radius: 12px;color: #1E3066;}
.vuecal__event.saved2classREGLecture {background-color: rgba(109, 216, 219, 0.85);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}
.vuecal__event.saved2classREGLaboratory {background-color: rgba(163, 237, 240, 0.70);border: 1px solid rgb(157, 228, 231);border-radius: 12px;color: rgb(12 74 110);}

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