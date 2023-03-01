<template>
        <header class="bg-white shadow">
            <div
                class="max-w-7xl mx-auto py-3 px-4 text-center sm:px-6 lg:px-8"
            >
                <h1 class="w-full lg:text-xl md:text-lg text-center uppercase lg:font-extrabold md:font-bold text-sky-900">
                    UPDATE & MANAGING OF CLASSROOMS ON {{ college.College_Name }}
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 mt-5 md:mt-0">
                        <form class="form" @submit.prevent="AddNewClassroom()">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-4 gap-6">
                                        
                                        <div class="col-span-6">
                                            <label
                                                for="building_no"
                                                class="block text-sm font-medium text-gray-700"
                                                >Building No.</label
                                            >
                                            <input
                                                v-if="classroomId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Building_No.$model"
                                                @keyup="v$.Building_No.$touch()"
                                                @blur="v$.Building_No.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Building_No"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Building_No.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Building_No.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="classroom_no"
                                                class="block text-sm font-medium text-gray-700"
                                                >Classroom No.</label
                                            >
                                            <input
                                                v-if="classroomId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Classroom_No.$model"
                                                @keyup="v$.Classroom_No.$touch()"
                                                @blur="v$.Classroom_No.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Classroom_No"
                                                autocomplete="Classroom_No"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Classroom_No.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Classroom_No.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="classroom_no"
                                                class="block text-sm font-medium text-gray-700"
                                                >Classroom Type.</label
                                            >
                                            <input
                                                v-if="classroomId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Classroom_Type.$model"
                                                @keyup="v$.Classroom_Type.$touch()"
                                                @blur="v$.Classroom_Type.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                disabled
                                                type="text"
                                                v-model="model.Classroom_Type"
                                                autocomplete="Classroom_No"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Classroom_Type.$error">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Classroom_Type.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        v-if="v$.$invalid || classroomId != false"
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
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Building No.
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Classroom No.
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Classroom Type
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
                                                    v-for="list in searchedClassrooms"
                                                    :key="list.id"
                                                    v-if="classroomList.length > 0"
                                                    class="hover:bg-gray-100"
                                                >
                                                    <td v-if="classroomId == list.id" class="bg-gray-100 pl-3 w-fit">
                                                        <input type="text" v-model.trim="model2.Building_No"
                                                            :id="'classroom-edit-${list.id}'"
                                                            @keydown.enter="updateClassroom()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Building_No)"></span>
                                                    </td>
                                                    <td v-if="classroomId == list.id" class="bg-gray-100 pl-3 py-2.5 w-fit">
                                                        <input type="text" v-model.trim="model2.Classroom_No"
                                                            :id="'classroom-edit-${list.id}'"
                                                            @keydown.enter="updateClassroom()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Classroom_No)"></span>
                                                    </td>
                                                    <td v-if="classroomId == list.id" class="bg-gray-100 pl-3 py-2.5 w-fit">
                                                        <input type="text" v-model.trim="model2.Classroom_Type"
                                                            :id="'classroom-edit-${list.id}'"
                                                            @keydown.enter="updateClassroom()"
                                                        class="border-0 py-4 whitespace-nowrap text-sm text-sky-600 font-medium text-gray-900 bg-gray-100 h-px w-full"/>
                                                    </td>
                                                    <td v-else @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.Classroom_Type)"></span>
                                                    </td>
                                                    <td v-if="classroomId == list.id" class="bg-gray-100 w-1/12">
                                                        <button
                                                            class="px-3 py-4 text-sm font-medium text-sky-600 hover:text-sky-900"
                                                            @click="updateClassroom()"
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
                                                    <td v-if="classroomId == list.id" class="bg-gray-100 w-1/12">
                                                        <a
                                                            href="#" @click="() => cancelEdit()"
                                                            class="pl-5 py-4 whitespace-nowrap text-sm font-medium text-red-600 hover:text-red-900"
                                                            >Cancel</a>
                                                    </td>
                                                    <td v-else
                                                        class="px-3 py-4 whitespace-nowrap indent-2 text-left text-sm font-medium"
                                                    >
                                                        <a
                                                            href="#" @click="() => deleteClassroom(list.id)"
                                                            class="text-red-600 hover:text-red-900"
                                                            >Delete</a
                                                        >
                                                    </td>
                                                </tr>
                                                <tr class="EmptyClassrooms" v-else>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">No Record of Classrooms.</td>
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
    name: 'Classrooms',
    setup () {
        const initialState = {
            Building_No: '',
            Classroom_No: '',
            Classroom_Type: ''
        }
        const model = ref({...initialState});

        const rules = computed(() => {
            return {
                Building_No: { required },
                Classroom_No: { required },
                Classroom_Type: { required },
            }
        })

        const v$ = useVuelidate(rules, model)

        const model2 = ref([]);
        const searchQuery = ref("");

        const classroomList = ref({});
        const classroomId = ref(false);

        const college = ref([]);

         const reset = () => {
            model.value = {...initialState};
        }
        const fetchClassrooms = async () => {
            classroomList.value = await APIController.FetchClassroomsByCollege(localStorage.getItem('collegeId'));
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            college.value.College_Name = college.value.College_Name.toUpperCase();
            // if(localStorage.getItem('userType') == 'reg'){
            //     fetchFaculties();
            // } else {
            //     fetchFacultiesDept();
            // }
            
        }
        
        const GetClassroom = async () => {
            model2.value = await APIController.FetchClassroom(classroomId.value);
        }

        const AddNewClassroom = async () => {
            let tempClassroom = await APIController.CreateClassroom(model.value.Building_No, model.value.Classroom_No, model.value.Classroom_Type, localStorage.getItem('collegeId'));
            if (tempClassroom) {
                fetchClassrooms(),
                reset();
            }
        }

        const updateClassroom = async () => {
            let tempClassroom = await APIController.UpdateClassroom(model2.value.Building_No, model2.value.Classroom_No, model2.value.Classroom_Type, classroomId.value);
            if (tempClassroom) {
                fetchClassrooms(),
                GetClassroom();
                classroomId.value = false;
            }
        }

        const deleteClassroom = async id => {
            const success = await APIController.DeleteClassroom(id);

            if (success) {
                fetchClassrooms();
            }
        }

        const searchedClassrooms = computed(() => {
            return classroomList.value.filter((list) => {
                return (
                list.Building_No
                    .toLowerCase()
                    .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                list.Classroom_No
                    .toLowerCase()
                    .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                list.Classroom_Type
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
            return text.replace(re, matchedText => `<strong style="color:#F28B07">${matchedText}</strong>`);
        }

        function setToEditing(list) {
            classroomId.value = list;
            GetClassroom();
            // this.$refs.search.focus();
            setTimeout(()=> {
                document.getElementById('classroom-edit-${list.id}').focus()
            }, 1);
        }
        
        function cancelEdit(){
            classroomId.value = false;
        }

        return {
            model,
            model2,
            classroomList,
            fetchClassrooms,
            AddNewClassroom,
            deleteClassroom,
            GetClassroom,
            updateClassroom,
            // toggleCourse,
            classroomId,
            reset,
            searchedClassrooms,
            searchQuery,
            highlightMatches,
            setToEditing,
            cancelEdit,
            v$,
            GetCollege,
            college
        }
    },
    mounted () {
        if (this.classroomId != false){
            this.GetClassroom();
        }
        this.fetchClassrooms();
        this.GetCollege();
    },
    methods: {
        postAjaxForm () {
            this.v$.$reset();
        }
    },
}
</script>