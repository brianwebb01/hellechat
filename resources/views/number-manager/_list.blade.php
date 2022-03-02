<div class="mx-auto max-w-7xl pt-6 pb-6 px-8">
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        <li x-show="loading" class="col-span-1 rounded-lg shadow">
            <button type="button" class="relative block w-full border-1 border-gray-300 rounded-lg p-20 text-center">
                <svg class="mx-auto animate-spin h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="animate-pulse mt-2 block text-sm font-medium text-gray-900">
                    Loading...
                </span>
            </button>
        </li>

        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <button @click="newRecord();" type="button" class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-20 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="mt-2 block text-sm font-medium text-gray-900">
                    Add Number
                </span>
            </button>
        </li>


        <template x-for="record in records">
            <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
                <div class="w-full flex items-center justify-between p-6 space-x-6">
                    <div class="flex-1 truncate">
                        <div class="flex items-center space-x-3">
                            <h3 x-text="record.friendly_label" class="text-gray-900 text-sm font-medium truncate"></h3>
                            <p x-text="`(${record.phone_number})`" class="text-gray-600 text-sm"></p>
                            <span x-text="record.service_account.provider" class="shrink-0 inline-block px-2 py-0.5 text-red-800 text-xs font-medium bg-red-100 rounded-full"></span>
                        </div>
                        <p class="mt-2 text-gray-500 text-sm">Messaging Endpoint</p>
                        <input x-model="record.messaging_endpoint" @focus="document.getElementById(`msg_endpoint_input_${record.id}`).select();" :id="`msg_endpoint_input_${record.id}`" readonly="readonly" type="text" class="p-0 focus:ring-0 w-full bg-gray-200 border border-gray-300 rounded-md px-1 text-sm text-gray-700">

                        <p class="mt-2 text-gray-500 text-sm">Voice Endpoint</p>
                        <input x-model="record.voice_endpoint" @focus="document.getElementById(`voice_endpoint_input_${record.id}`).select();" :id="`voice_endpoint_input_${record.id}`" readonly="readonly" type="text" class="p-0 focus:ring-0 w-full bg-gray-200 border border-gray-300 rounded-md px-1 text-sm text-gray-700">

                    </div>
                </div>
                <div>
                    <div class="-mt-px flex divide-x divide-gray-200">
                        <div class="w-0 flex-1 flex">
                            <a @click.prevent="editRecord(record.id);" href="#" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span class="ml-3">Edit</span>
                            </a>
                        </div>
                        <div class="-ml-px w-0 flex-1 flex">
                            <a @click.prevent="deleteRecord(record.id);" href="#" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="ml-3">Delete</span>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </template>

    </ul>
</div>