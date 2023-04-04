<template>
        <header class="bg-white shadow">
            <div
                class="max-w-screen mx-auto py-3 text-center"
            >
                <h1 v-if="userType == 'reg'" class="w-full text-xl text-center uppercase font-raleway tracking-wider text-[#253B80]">
                    UPDATE & MANAGING OF DEPARTMENTS ON {{ college.College_Name }}
                </h1>
                <h1 v-else class="w-full text-xl text-center uppercase font-raleway tracking-wider text-[#253B80]">
                    VIEWING OF DEPARTMENTS ON {{ college.College_Name }}
                </h1>
            </div>
        </header>
                <main>
            <div class="max-w-[90vw] mx-auto py-3">
                <!-- Replace with your content -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <form v-if="userType == 'dept'" class="form">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="department_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Department Name</label
                                            >
                                            <input
                                                disabled
                                                type="text"
                                                v-model="model2.Department_Name"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="department_chair"
                                                class="block text-sm font-medium text-gray-700"
                                                >Department Chair</label
                                            >
                                            <input
                                                disabled
                                                type="text"
                                                v-model="model2.Department_Chair"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="username"
                                                class="block text-sm font-medium text-gray-700"
                                                >Username</label
                                            >
                                            <input
                                                disabled
                                                type="text"
                                                v-model.trim="model2.username"
                                                placeholder="Department Chair's Username"
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>
                                        <div v-if="departmentId == false" class="col-span-6">
                                            <label
                                                for="password"
                                                class="block text-sm font-medium text-gray-700"
                                                >Password</label
                                            >
                                            <input
                                                type="password"
                                                disabled
                                                class="cursor-not-allowed mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>

                                    </div>
                                </div>
                                <div v-if="departmentId == false"
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >   
                                    <input
                                        disabled
                                        type="submit" :value="'Save'"
                                        class="cursor-not-allowed button inline-flex justify-center py-2 border border-transparent block w-full shadow-sm text-sm font-medium rounded-md text-white bg-sky-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                </div>
                                <div v-else
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        type="submit" :value="'Save Changes'"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block mr-2 w-3/4 shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                    <input
                                        type="button" :value="'Cancel'" @click="cancelEdit"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block w-1/5 shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                    />
                                </div>
                            </div>
                        </form>
                        <form v-else class="form" @submit.prevent="(departmentId == false) ? register() : update();">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="department_name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Department Name</label
                                            >
                                            <input
                                                v-if="departmentId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Department_Name.$model"
                                                @keyup="v$.Department_Name.$touch()"
                                                @blur="v$.Department_Name.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                required
                                                type="text"
                                                v-model.trim="model2.Department_Name"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Department_Name.$error && departmentId == false">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Department_Name.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="department_chair"
                                                class="block text-sm font-medium text-gray-700"
                                                >Department Chair</label
                                            >
                                            <input
                                                v-if="departmentId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Department_Chair.$model"
                                                @keyup="v$.Department_Chair.$touch()"
                                                @blur="v$.Department_Chair.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                required
                                                type="text"
                                                v-model.trim="model2.Department_Chair"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Department_Chair.$error && departmentId == false">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Department_Chair.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-span-6">
                                            <label
                                                for="username"
                                                class="block text-sm font-medium text-gray-700"
                                                >Username</label
                                            >
                                            <input
                                                v-if="departmentId == false"
                                                type="text"
                                                required
                                                v-model.trim="v$.Username.$model"
                                                @keyup="v$.Username.$touch()"
                                                @blur="v$.Username.$touch()"
                                                placeholder="Department Chair's Username"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <input
                                                v-else
                                                required
                                                type="text"
                                                v-model.trim="model2.username"
                                                placeholder="Department Chair's Username"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Username.$error && departmentId == false">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Username.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>
                                        <div v-if="departmentId == false" class="col-span-6">
                                            <label
                                                for="password"
                                                class="block text-sm font-medium text-gray-700"
                                                >Password</label
                                            >
                                            <input
                                                type="password"
                                                required
                                                v-model.trim="v$.Password.$model"
                                                @keyup="v$.Password.$touch()"
                                                @blur="v$.Password.$touch()"
                                                class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            />
                                            <div v-if="v$.Password.$error  && departmentId == false">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-500 inline ml-5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="inline text-sm font-medium text-red-500">
                                                    {{ v$.Password.$errors[0].$message }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div v-if="departmentId == false"
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        v-if="v$.$invalid"
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
                                <div v-else
                                    class="px-4 py-3 bg-gray-50 text-center sm:px-6"
                                >
                                    <input
                                        type="submit" :value="'Save Changes'" @click="postAjaxForm"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block mr-2 w-3/4 shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                    />
                                    <input
                                        type="button" :value="'Cancel'" @click="cancelEdit(); postAjaxForm();"
                                        class="cursor-pointer button inline-flex justify-center py-2 border border-transparent block w-1/5 shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
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
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Department Name
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Department Chair
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Username
                                                    </th>
                                                    <!-- <th
                                                        scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                    >
                                                        Password
                                                    </th> -->
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
                                                    v-for="list in searchedDepartments"
                                                    :key="list.id"
                                                    v-if="departmentList.length > 0"
                                                    class="hover:bg-gray-100"
                                                >
                                                    <td v-if="userType == 'dept'"
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.Department_Name)"></span>
                                                    </td>
                                                    <td v-else
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.Department_Name)"></span>
                                                    </td>

                                                    <td v-if="userType == 'dept'"
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.Department_Chair)"></span>
                                                    </td>
                                                    <td v-else
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.Department_Chair)"></span>
                                                    </td>

                                                    <td v-if="userType == 'dept'"
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.username)"></span>
                                                    </td>
                                                    <td v-else
                                                        :class="(departmentId == list.id) ? 'bg-gray-100' : '' "
                                                        @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span v-html="highlightMatches(list.username)"></span>
                                                    </td>

                                                    <!-- <td @click="setToEditing(list.id)"
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    >
                                                            <span v-html="highlightMatches(list.password)"></span>
                                                    </td> -->

                                                    <td :class="(departmentId == list.id) ? 'bg-gray-100' : '' " class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium"
                                                    >
                                                        <button v-if="userType == 'dept'"
                                                            disabled
                                                            class="text-green-500 font-medium cursor-not-allowed"
                                                            >Edit
                                                        </button>
                                                        <a v-else
                                                            href="#" @click="() => setToEditing(list.id)"
                                                            class="text-green-500 hover:text-green-900"
                                                            >Edit
                                                        </a>
                                                        <!-- <button 
                                                            @click="setToEditing(list.id)"
                                                            class="text-green-500 hover:text-green-900"
                                                            >Edit
                                                        </button> -->
                                                    </td>
                                                    <!-- <td v-if="courseId == list.id" class="bg-gray-100 w-1/12">
                                                        <a
                                                            href="#" @click="() => cancelEdit()"
                                                            class="pl-5 py-4 whitespace-nowrap text-sm font-medium text-red-600 hover:text-red-900"
                                                            >Cancel</a>
                                                    </td> -->
                                                    <td :class="(departmentId == list.id) ? 'bg-gray-100' : '' " class="px-3 py-4 whitespace-nowrap indent-2 text-left text-sm font-medium"
                                                    >
                                                        <button v-if="userType == 'dept'"
                                                            disabled
                                                            class="text-red-600 font-medium cursor-not-allowed"
                                                            >Delete
                                                        </button>
                                                        <a v-else
                                                            href="#" @click="() => deleteDepartment(list.id)"
                                                            class="text-red-600 hover:text-red-900"
                                                            >Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="EmptyDepartment p-2" v-if="departmentList.length <= 0">
                                            <span class="pl-4 whitespace-nowrap text-sm font-roboto tracking-wide text-[#253B80]">No Records of Departments for this College.</span>
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
import { required, minLength, helpers } from '@vuelidate/validators';
import store from "../store";

import APIController from '../controllers/api'

export default {
    name: 'Department',
    setup() {
        const initialState = {
            Department_Name: '',
            Department_Chair: '',
            Username: '',
            Password: ''
        }
        const model = ref({...initialState});

        const rules = computed(() => {
            return {
                Department_Name: { required },
                Department_Chair: { required },
                Username: { required },
                Password: { required, minLength: minLength(8), containsPasswordRequirement: helpers.withMessage(
                            () => `The password requires an Uppercase and Lowercase character`, 
                            (value) => /(?=.*[a-z])(?=.*[A-Z])/.test(value)
                            ), }
            }
        })

        const v$ = useVuelidate(rules, model)

        const reset = () => {
            model.value = {...initialState};
        }

        const model2 = ref([]);
        const searchQuery = ref("");

        const departmentList = ref({});
        const departmentId = ref(false);

        const college = ref([]);
        const collegeID = ref(null);

        const userN = ref('');

        const userType = localStorage.getItem('userType');

        const fetchDepartments = async () => {
            departmentList.value = await APIController.FetchDepartments(collegeID.value);
        }

        const GetDepartment = async () => {
            model2.value = await APIController.FetchDepartment(departmentId.value);
            userN.value = model2.value.username;
        }

        const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            college.value.College_Name = college.value.College_Name.toUpperCase();
            if(userType == 'reg'){
                collegeID.value = college.value.id;
            } else {
                collegeID.value = college.value.college_id;
            }
            fetchDepartments();
        }

        const AddNewDepartment = async () => {
            let tempDepartment = await APIController.CreateDepartment(college.value.id, model.value.Department_Name, model.value.Department_Chair, model.value.Username);
            if (tempDepartment) {
                fetchDepartments(),
                reset();
            }
        }

        const updateDepartment = async () => {
            let tempDepartment = await APIController.UpdateDepartment(model2.value.Department_Name, model2.value.Department_Chair, model2.value.username, departmentId.value);
            if (tempDepartment) {
                fetchDepartments(),
                departmentId.value = false;
            }
        }

        const update = async () => {
            let tempUser = await APIController.UpdateUser(model2.value.Department_Name, model2.value.username, userN.value);
            if (tempUser) {
                updateDepartment();
            }
        }

        const deleteDepartment = async id => {
            await APIController.DeleteUser(id);
            const success = await APIController.DeleteDepartment(id);

            if (success) {
                fetchDepartments();
            }
        }

        const searchedDepartments = computed(() => {
            return departmentList.value.filter((list) => {
                return (
                list.Department_Name
                    .toLowerCase()
                    .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                list.Department_Chair
                    .toLowerCase()
                    .indexOf(searchQuery.value.toLowerCase()) != -1 ||
                list.username
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
            departmentId.value = list;
            GetDepartment();
            // this.$refs.search.focus();
            // setTimeout(()=> {
            //     document.getElementById('course-edit-${list.id}').focus()
            // }, 1);
        }

        // function cancelEdit(){
        //     departmentId.value = false;
        // }

        const user = {};

        let errors = ref({});
        const register = () => {
            user.name = model.value.Department_Chair;
            user.username = model.value.Username;
            user.password = model.value.Password;
            // ev.preventDefault();
            store.dispatch("register", user)
                .then(() => {
                    AddNewDepartment();
                    fetchDepartments();
                })
                .catch((error) => {
                    // loading.value=false;
                    if (error.status === 422) {
                        errors.value = error.data.errors;
                        alert(errors.value.username);
                    }
                });
        }

        return {
            college,
            GetCollege,
            model,
            v$,
            departmentId,
            AddNewDepartment,
            reset,
            user,
            errors,
            register,
            searchedDepartments,
            searchQuery,
            highlightMatches,
            fetchDepartments,
            departmentList,
            setToEditing,
            GetDepartment,
            model2,
            // cancelEdit,
            deleteDepartment,
            update,
            updateDepartment,
            userType,
            collegeID,
        }
    },
    mounted () {
        if (this.departmentId != false){
            this.GetDepartment();
        }
        this.GetCollege();
    },
    methods: {
        postAjaxForm () {
            this.v$.$reset();
        },
        cancelEdit(){
            this.departmentId = false;
        }
    },
}
</script>