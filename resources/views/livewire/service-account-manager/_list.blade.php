<div class="mx-auto max-w-7xl pt-6 pb-6 px-8">
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @foreach(range(1,2) as $i)
        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <div class="w-full flex items-center justify-between p-6 pb-5 space-x-6">
                <div class="flex-1 truncate">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-gray-900 text-sm font-medium truncate">Some SA Name</h3>
                    </div>
                    <p class="mt-1 text-gray-500 text-sm truncate">Twilio</p>
                </div>
            </div>
            <div>
                <div class="-mt-px flex divide-x divide-gray-200">
                    <div class="w-0 flex-1 flex">
                        <a @click.prevent="serviceAccountFormOpen = true;" href="#" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span class="ml-3">Edit</span>
                        </a>
                    </div>
                    <div class="-ml-px w-0 flex-1 flex">
                        <a @click.prevent="deleteConfirmOpen = true;" href="#" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="ml-3">Delete</span>
                        </a>
                    </div>
                </div>
            </div>
        </li>
        @endforeach

        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <button @click="serviceAccountFormOpen = true;" type="button" class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-8 pb-9 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="mt-2 block text-sm font-medium text-gray-900">
                    Add Service Account
                </span>
            </button>
        </li>

    </ul>
</div>