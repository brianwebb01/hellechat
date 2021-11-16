<div x-cloak x-show="formOpen" class="fixed inset-0 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 w-screen h-screen" aria-hidden="true" x-cloak x-show="formOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    <div class="absolute inset-0 overflow-hidden">
        <!-- Background overlay, show/hide based on slide-over state. -->
        <div class="absolute inset-0" aria-hidden="true">
            <div class="fixed inset-y-0 pl-10 max-w-full right-0 flex sm:pl-16" x-cloak x-show="formOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="w-screen max-w-md">
                    <form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
                        <div class="flex-1 h-0 overflow-y-auto">
                            <div class="py-6 px-4 bg-indigo-700 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-medium text-white" id="slide-over-title">
                                        <span x-show="!currentRecord.id">New Contact</span>
                                        <span x-show="currentRecord.id">Edit Contact</span>
                                    </h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button @click="cancelForm();" type="button" class="bg-indigo-700 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                            <span class="sr-only">Close panel</span>
                                            <!-- Heroicon name: outline/x -->
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="px-4 divide-y divide-gray-200 sm:px-6">
                                    <div class="space-y-6 pt-6 pb-5">

                                        <div>
                                            <div class="isolate -space-y-px rounded-md shadow-sm">
                                                <div class="relative border border-gray-300 rounded-md rounded-b-none px-3 py-2 focus-within:z-10 focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                    <label for="first-name" class="block text-xs font-medium text-gray-700">First Name</label>
                                                    <input x-model="currentRecord.first_name" type="text" name="first_name" id="first-name" class="block border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="John">
                                                    <div x-show="errors.first_name" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="relative border border-gray-300 rounded-md rounded-t-none px-3 py-2 focus-within:z-10 focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                    <label for="last-name" class="block w-full text-xs font-medium text-gray-700">Last Name</label>
                                                    <input x-model="currentRecord.last_name" type="text" name="last_name" id="last-name" class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="Doe">
                                                    <div x-show="errors.last_name" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <p x-show="errors.first_name" x-text="errors.first_name ? errors.first_name.join(', ') : ''" class="ml-2 mt-1 text-sm text-red-600"></p>
                                            <p x-show="errors.last_name" x-text="errors.last_name ? errors.last_name.join(', ') : ''" class="ml-2 mt-1 text-sm text-red-600"></p>
                                        </div>

                                        <div>
                                            <div class="relative border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                <label for="company" class="block text-xs font-medium text-gray-900">Company</label>
                                                <input x-model="currentRecord.company" type="text" name="company" id="company" class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="Acme Inc">
                                                <div x-show="errors.company" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <!-- Heroicon name: solid/exclamation-circle -->
                                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p x-show="errors.company" x-text="errors.company ? errors.company.join(', ') : ''" class="ml-2 mt-1 text-sm text-red-600"></p>
                                        </div>
                                    </div>
                                    <div class="pt-4 pb-6">
                                        <div>
                                            <div class="flex">
                                                <h3 class="flex-1 mt-2 text-sm font-medium text-gray-900">
                                                    Phone Numbers
                                                </h3>
                                                <button @click="addPhone();" type="button" class="flex-none mt-0 group -ml-1 bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <span class="w-8 h-8 rounded-full border-2 border-dashed border-indigo-600 flex items-center justify-center text-indigo-600">
                                                        <!-- Heroicon name: solid/plus-sm -->
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>

                                            <p x-show="errors.phone_numbers" x-text="errors.phone_numbers ? errors.phone_numbers.join(', ') : ''" class="text-sm text-red-600"></p>

                                            <div class="mt-2 space-y-2">

                                                <template x-for="(phone, index) in phoneNumbers" :key="index">
                                                    <div class="phone-row flex">
                                                        <label for="phone-number" class="sr-only">Phone Number</label>
                                                        <div class="flex-1 mt-1 mr-2 relative rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center">
                                                                <label :for="'number-type-'+index" class="sr-only">Number Type</label>
                                                                <select x-model="phone.type" :id="'number-type-'+index" :name="'number-type-'+index" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-3 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                    <option value="mobile">Mobile</option>
                                                                    <option value="home">Home</option>
                                                                    <option value="work">Work</option>
                                                                    <option value="office">Office</option>
                                                                    <option value="school">School</option>
                                                                    <option value="main">Main</option>
                                                                    <option value="fax">Fax</option>
                                                                    <option value="pager">Pager</option>
                                                                    <option value="other">Other</option>
                                                                </select>
                                                            </div>
                                                            <input x-model="phone.number" type="text" :name="'phone-number-'+index" :id="'phone-number-'+index" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-28 sm:text-sm border-gray-300 rounded-md" placeholder="+15558675309">
                                                        </div>
                                                        <button @click="deletePhone(index);" type="button" class="flex-none pt-1 mt-1 group bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-red-700">
                                                            <span class="w-8 h-8 rounded-full border border-red-500 flex items-center justify-center text-red-500">
                                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </template>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                            <button @click="cancelForm();" type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button @click.prevent="saveForm();" type="submit" class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>