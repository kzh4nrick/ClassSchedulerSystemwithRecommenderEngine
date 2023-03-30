<template>
        <header class="bg-white shadow">
            <div
                class="max-w-screen mx-auto py-3 text-center"
            >
                <h1 v-if="userType == 'reg'" class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-[#253B80]">
                    VIEWING OF COURSES OF {{ college.College_Name }}
                </h1>
                <h1 v-else class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-[#253B80]">
                    UPDATE & MANAGING OF COURSES OF {{ college.Department_Name }}
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-[90vw] mx-auto py-2">
                <!-- Replace with your content -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <form v-if="userType == 'reg'" class="form">
                            <div class="shadow overflow-hidden rounded-md">
                                <div class="bg-white p-5">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="subject_code"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Code</label
                                            >
                                            <input
                                                disabled
                                                type="text"
                                                v-model.trim="model.Subject_Code"
                                                placeholder="XXX-000"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="subject_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Name</label
                                            >
                                            <input
                                                disabled
                                                type="text"
                                                name="subject_name"
                                                id="subject_name"
                                                v-model.trim="model.Subject_Name"
                                                autocomplete="subject_name"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="subject_type"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Type</label
                                            >
                                            <input
                                                disabled
                                                class="cursor-not-allowed mt-1 xl:ml-2 lg:ml-1 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Major"/>
                                            <label class="inline-block ml-1 text-sm font-medium text-gray-700" for="Major">Major</label>
                                            <input
                                                disabled
                                                class="cursor-not-allowed mt-1 ml-1 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Elective"/>
                                            <label class="inline ml-1 text-sm font-medium text-gray-700" for="Elective">Elective</label>
                                            <input
                                                disabled
                                                class="cursor-not-allowed mt-1 ml-1 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Core/Core Elec"/>
                                            <label class="inline-block ml-1 text-sm font-medium text-gray-700" for="Core/Core Elec">Core/Core Elec</label>
                                            <input
                                                disabled
                                                class="cursor-not-allowed mt-1 ml-1 border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="GEC"/>
                                            <label class="inline ml-1 text-sm font-medium text-gray-700" for="GEC">GEC</label>
                                            <!-- <select
                                                v-if="subjectId == false && major == false"
                                                
                                                v-model="model.course_id"
                                                class="mt-1.5 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            >
                                                <option disabled value="">Lists of Courses</option>
                                                <option v-for="lists in courseList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        >
                                                        {{ lists.Course_Name }}
                                                </option>
                                            </select>
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.course_id"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            /> -->
                                        </div>

                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        disabled
                                        type="submit" :value="'Save'"
                                        class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                </div>
                            </div>
                        </form>
                        <form v-else class="form" @submit.prevent="AddNewSubject()">
                            <div class="shadow overflow-hidden rounded-md">
                                <div class="bg-white p-5">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="subject_code"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Code</label
                                            >
                                            <input
                                                v-if="subjectId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Subject_Code.$model"
                                                placeholder="XXX-000"
                                                @keyup="v$.Subject_Code.$touch()"
                                                @blur="v$.Subject_Code.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Subject_Code"
                                                placeholder="XXX-000"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Subject_Code.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Subject_Code.$errors[0].$message }}
                                                </span>
                                            </div>
                                            <!-- <template v-if="v$.Subject_Code.$dirty">
                                            <div v-for="error of v$.Subject_Code.$silentErrors" :key="error.$message">
                                                {{ error.$message }}
                                            </div>
                                            </template> -->
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="subject_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Name</label
                                            >
                                            <input
                                                v-if="subjectId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Subject_Name.$model"
                                                @keyup="v$.Subject_Name.$touch()"
                                                @blur="v$.Subject_Name.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                name="subject_name"
                                                id="subject_name"
                                                v-model="model.Subject_Name"
                                                autocomplete="subject_name"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Subject_Name.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Subject_Name.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="subject_type"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Type</label
                                            >
                                            <input
                                                v-if="subjectId == false"
                                                v-model="v$.Subject_Type.$model"
                                                class="mt-1 xl:ml-3 lg:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Major"/>
                                            <input 
                                                v-else
                                                disabled
                                                v-model="model.Subject_Type"
                                                class="cursor-not-allowed mt-1 xl:ml-3 lg:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Major"/>
                                            <label class="inline-block xl:ml-1 text-sm font-medium text-gray-700" for="major">Major</label>
                                            <input
                                                v-if="subjectId == false"
                                                v-model="v$.Subject_Type.$model"
                                                class="mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Elective"/>
                                            <input 
                                                v-else
                                                disabled
                                                v-model="model.Subject_Type"
                                                class="cursor-not-allowed mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Elective"/>
                                            <label class="inline-block xl:ml-1 text-sm font-medium text-gray-700" for="elective">Elective</label>
                                            <input
                                                v-if="subjectId == false"
                                                v-model="v$.Subject_Type.$model"
                                                @change="course_id = ''"
                                                class="mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Core"/>
                                            <input
                                                v-else
                                                disabled
                                                v-model="model.Subject_Type"
                                                class="cursor-not-allowed mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="Core"/>
                                            <label class="inline xl:ml-1 text-sm font-medium text-gray-700" for="Core">Core/Core Elec</label>
                                            <input
                                                v-if="subjectId == false"
                                                v-model="v$.Subject_Type.$model"
                                                @change="course_id = ''"
                                                class="mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="GEC"/>
                                            <input
                                                v-else
                                                disabled
                                                v-model="model.Subject_Type"
                                                class="cursor-not-allowed mt-1 xl:ml-2 lg:ml-1 md:ml-px border-2 border-sky-600 focus:border-sky-500 inline-block focus:ring-sky-500" type="radio" required value="GEC"/>
                                            <label class="inline xl:ml-1 text-sm font-medium text-gray-700" for="GEC">GEC</label>
                                            
                                            <select
                                                v-if="subjectId == false && (model.Subject_Type == 'Major' || model.Subject_Type == 'Elective')"
                                                v-model="course_id"
                                                required
                                                class="mt-1.5 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            >
                                                <option disabled class="text-center" value="">-- Select a Degree Program for this Subject --</option>
                                                <option v-for="lists in courseList"
                                                        v-bind="lists.id"
                                                        :value="lists.id"
                                                        >
                                                        {{ lists.Course_Code + ' - ' + lists.Course_Name }}
                                                </option>
                                            </select>
                                            <input
                                                v-else-if="model.Subject_Type == 'Core' || model.Subject_Type == 'GEC'"
                                                disabled
                                                type="text"
                                                placeholder="-- This Course will be open to all Degree Programs. --"
                                                class="text-center text-gray-600 cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                value=""
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Subject_Type.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Subject_Type.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        v-if="v$.$invalid || subjectId != false"
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
                                class="overflow-auto h-auto max-h-[82vh]"
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
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Program
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Course Name
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
                                                        Course Type
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
                                                    v-for="list in searchedSubjects"
                                                    :key="list.id"
                                                    v-if="subjectList.length > 0"
                                                    class="hover:bg-gray-100"
                                                >   
                                                    <!-- <td v-if="userType == 'reg'"
                                                        :class="(subjectId == list.id) ? 'bg-gray-100' : ''" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Course_Name)"></span>
                                                    </td> -->
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 pl-3 w-fit">
                                                        <select
                                                            v-if="(model2.Subject_Type == 'Major' || model2.Subject_Type == 'Elective')"
                                                            v-model="model2.course_id"
                                                            :id="'subject-edit-${list.id}'"
                                                            class="border-0 py-1.5 text-sm font-medium text-sky-600 bg-gray-100 w-full"
                                                        >
                                                            <option disabled value="">-- Select a Program --</option>
                                                            <option v-for="lists in courseList"
                                                                    v-bind="lists.id"
                                                                    :value="lists.id"
                                                                    >
                                                                    {{  lists.Course_Code }}
                                                            </option>
                                                        </select>
                                                        <td v-else
                                                            class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >           
                                                                <span v-html="highlightMatches('Open to All')"></span>
                                                        </td>
                                                    </td>
                                                    <td v-else>
                                                        <td v-if="userType == 'reg'"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >           
                                                                <span v-html="highlightMatches(list.Course_Code)"></span>
                                                        </td>
                                                        <td v-else @click="setToEditing(list.id)"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >           
                                                                <span v-html="highlightMatches(list.Course_Code)"></span>
                                                        </td>
                                                    </td>
                                                    
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 pl-3 py-2.5 w-1/4">
                                                        <input type="text" v-model.trim="model2.Subject_Name"
                                                            :id="'subject-edit-${list.id}'"
                                                            @keydown.enter="updateSubject()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else>
                                                        <td v-if="userType == 'reg'"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Name)"></span>
                                                        </td>
                                                        <td v-else @click="setToEditing(list.id)"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Name)"></span>
                                                        </td>
                                                    </td>
                                                    
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 pl-3 w-1/6">
                                                        <input type="text" v-model.trim="model2.Subject_Code"
                                                            :id="'subject-edit-${list.id}'"
                                                            @keydown.enter="updateSubject()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else>
                                                        <td v-if="userType == 'reg'"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Code)"></span>
                                                        </td>
                                                        <td v-else @click="setToEditing(list.id)"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Code)"></span>
                                                        </td>
                                                    </td>
                                                        
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 pl-3 w-1/6">
                                                        <select v-model.trim="model2.Subject_Type"
                                                            :id="'subject-edit-${list.id}'"
                                                            class="border-0 py-1.5 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 w-fit"
                                                        >
                                                            <option value="Major">Major</option>
                                                            <option value="Elective">Elective</option>
                                                            <option value="Core">Core</option>
                                                            <option value="GEC">GEC</option>
                                                        </select>
                                                    </td>
                                                    <td v-else>
                                                        <td v-if="userType == 'reg'"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Type)"></span>
                                                        </td>
                                                        <td v-else @click="setToEditing(list.id)"
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                        >
                                                                <span v-html="highlightMatches(list.Subject_Type)"></span>
                                                        </td>
                                                    </td>
                                                    
                                                    <!-- <td v-if="subjectId == list.id" class="bg-gray-100 pl-3 w-1/12">
                                                        <input type="text" v-model="model2.Units"
                                                            :id="'subject-edit-${list.id}'"
                                                            @keydown.enter="updateSubject()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-8/12"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Units)"></span>
                                                    </td> -->
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 w-1/12">
                                                        <button
                                                            class="px-3 py-4 text-sm font-medium text-sky-600 hover:text-sky-900"
                                                            @click="updateSubject()"
                                                            >Update</button>
                                                    </td>
                                                    <td v-else
                                                        class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium"
                                                    >
                                                        <button  v-if="userType == 'reg'"
                                                            disabled
                                                            class="cursor-not-allowed font-medium text-green-500"
                                                            >Edit
                                                        </button>
                                                        <a v-else
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
                                                    <td v-if="subjectId == list.id" class="bg-gray-100 w-1/12">
                                                        <a
                                                            href="#" @click="() => cancelEdit()"
                                                            class="pl-5 py-4 whitespace-nowrap text-sm font-medium text-red-600 hover:text-red-900"
                                                            >Cancel</a>
                                                    </td>
                                                    <td v-else
                                                        class="px-3 py-4 whitespace-nowrap indent-2 text-left text-sm font-medium"
                                                    >
                                                        <button  v-if="userType == 'reg'"
                                                            disabled
                                                            class="cursor-not-allowed font-medium text-red-600"
                                                            >Delete
                                                        </button>
                                                        <a v-else
                                                            href="#" @click="() => deleteSubject(list.id)"
                                                            class="text-red-600 hover:text-red-900"
                                                            >Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="EmptySubjects p-2" v-if="subjectList.length <= 0">
                                            <span class="pl-4 whitespace-nowrap text-sm font-roboto tracking-wide text-[#253B80]">No Records of Courses.</span>
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
import { ref, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required } from '@vuelidate/validators';

import APIController from '../controllers/api'

export default {
    name: 'Subjects',
    setup () {
        const userType = localStorage.getItem('userType');

        const course_id = ref("");
        const initialState = {
            Subject_Code: '',
            Subject_Name: '',
            Subject_Type: '',
        }
        const model = ref({...initialState});

        const rules = computed(() => {
            return {
                Subject_Code: { required },
                Subject_Name: { required },
                Subject_Type: { required },
            }
        })

        const v$ = useVuelidate(rules, model)

        const model2 = ref([]);
        const searchQuery = ref("");

        const subjectList = ref({});
        const subjectId = ref(false);

        const courseList = ref({});
        const college = ref({});

        const major = ref(false);

        const reset = () => {
            model.value = {...initialState};
            course_id.value = ''
        }
        const fetchSubjects = async () => {
            subjectList.value = await APIController.FetchSubjectsByCollege(college.value.id);
        }
        const fetchSubjectsDept = async () => {
            subjectList.value = await APIController.FetchSubjectsByDepartment(college.value.id);
        }

        const fetchCourses = async () => {
            courseList.value = await APIController.FetchCoursesByDept(college.value.id);
        }
        
        const GetSubject = async () => {
            model2.value = await APIController.FetchSubject(subjectId.value);
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            if(localStorage.getItem('userType') == 'reg'){
                fetchSubjects();
                college.value.College_Name = college.value.College_Name.toUpperCase();
            } else {
                fetchSubjectsDept(),
                fetchCourses();
                college.value.Department_Name = college.value.Department_Name.toUpperCase();
            }
        }

        const AddNewSubject = async () => {
            let tempSubject = await APIController.CreateSubject(model.value.Subject_Code, model.value.Subject_Name, model.value.Subject_Type, course_id.value, college.value.id);
            if (tempSubject) {
                if(userType == 'reg'){
                    fetchSubjects();
                } else {
                    fetchSubjectsDept();
                }
                reset();
            }
        }

        const updateSubject = async () => {
            if(model2.value.Subject_Type == 'Core' || model2.value.Subject_Type == 'GEC')
                model2.value.course_id = null;
            if(((model2.value.Subject_Type == 'Core' || model2.value.Subject_Type == 'GEC') && model2.value.course_id == null) || ((model2.value.Subject_Type == 'Major' || model2.value.Subject_Type == 'Elective') && model2.value.course_id != null)){
                
                let tempSubject = await APIController.UpdateSubject(model2.value.Subject_Code, model2.value.Subject_Name, model2.value.Subject_Type, model2.value.course_id, subjectId.value, college.value.id);
                if (tempSubject) {
                    if(userType == 'reg'){
                        fetchSubjects();
                    } else {
                        fetchSubjectsDept();
                    }
                    GetSubject();
                    subjectId.value = false;
                }
            }
        }

        const deleteSubject = async id => {
            const success = await APIController.DeleteSubject(id);

            if (success) {
                if(userType == 'reg'){
                    fetchSubjects();
                } else {
                    fetchSubjectsDept();
                }
            }
        }

        const searchedSubjects = computed(() => {
            return subjectList.value.filter((list) => {
                    return (
                        list.Course_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Name
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Code
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                        list.Subject_Type
                            .toLowerCase()
                            .indexOf(searchQuery.value.toLowerCase()) != -1
                    )
            });
        });

        function highlightMatches(text) {
            text = text.toString();
            const matchExists = text.toLowerCase().includes(searchQuery.value.toLowerCase());
            if (!matchExists) return text;

            const re = new RegExp(searchQuery.value, 'ig');
            return text.replace(re, matchedText => `<strong style="color:#009bde">${matchedText}</strong>`);
        }

        function setToEditing(list) {
            subjectId.value = list;
            GetSubject();
            // this.$refs.search.focus();
            setTimeout(()=> {
                document.getElementById('subject-edit-${list.id}').focus()
            }, 1);
        }
        
        function cancelEdit(){
            subjectId.value = false;
        }

        return {
            model,
            model2,
            subjectList,
            fetchSubjects,
            AddNewSubject,
            deleteSubject,
            GetSubject,
            updateSubject,
            // toggleSubject,
            subjectId,
            reset,
            searchedSubjects,
            searchQuery,
            highlightMatches,
            setToEditing,
            cancelEdit,
            v$,
            // fetchCourses,
            major,
            college,
            GetCollege,
            userType,
            fetchSubjectsDept,
            courseList,
            fetchCourses,
            course_id
            // Subject_Code,
            // Subject_Name,
            // Subject_Type,
            // Units,
            // rules
        }
    },
    mounted () {
        if (this.subjectId != false){
            this.GetSubject();
        }
        this.GetCollege();
        // this.v$.$validate();
    },
    methods: {
        postAjaxForm () {
            this.v$.$reset();
        }
    },
    
}
</script>
