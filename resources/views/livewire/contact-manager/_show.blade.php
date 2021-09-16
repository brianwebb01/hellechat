<div x-cloak x-show="showContactOpen">
    <div class="fixed inset-0 overflow-hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Background overlay, show/hide based on slide-over state. -->
            <div class="absolute inset-0" aria-hidden="true">
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex sm:pl-16" x-cloak x-show="showContactOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                    <div class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                            <div class="px-4 py-6 sm:px-6 border-b">
                                <div class="flex items-start justify-between">
                                    <h2 id="slide-over-heading" class="text-lg font-medium text-gray-900">
                                        Contact Details
                                    </h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button @click="showContactOpen = false;" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500">
                                            <span class="sr-only">Close panel</span>
                                            <!-- Heroicon name: outline/x -->
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Main -->
                            <div>
                                <div class="pb-1 sm:pb-6">
                                    <div>
                                        <div class="mt-6 px-4 sm:mt-8 sm:flex sm:items-end sm:px-6">
                                            <div class="sm:flex-1">
                                                <div>
                                                    <div class="flex items-center">
                                                        <h3 class="font-bold text-xl text-gray-900 sm:text-2xl">Ashley Porter</h3>
                                                    </div>
                                                    <p class="text-sm text-gray-500">Acme Incorporated</p>
                                                </div>
                                                <div class="mt-5 flex flex-wrap space-x-3 sm:space-x-3">
                                                    <button type="button" class="flex-1 w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="flex-1 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 sm:px-6 pt-5">
                                    <h3 class="font-medium text-gray-900">Phone Numbers</h3>
                                    <ul role="list" class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200">
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <p>Home:</p>
                                                <p class="ml-4 text-sm font-medium text-gray-900">+12125551234</p>
                                            </div>
                                            <button type="button" class="group -ml-1 bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <span class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400">
                                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <button type="button" class="group -ml-1 bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <span class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400">
                                                    <svg class="h-6 w-6 " xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </li>
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <p>Mobile:</p>
                                                <p class="ml-4 text-sm font-medium text-gray-900">+19095551234</p>
                                            </div>
                                            <button type="button" class="group -ml-1 bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <span class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400">
                                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <button type="button" class="group -ml-1 bg-white p-1 rounded-md flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <span class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400">
                                                    <svg class="h-6 w-6 " xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>