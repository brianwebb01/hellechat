<div class="bg-white h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flow-root">
            <ul role="list" class="-my-5">

                <template x-for="voicemail in records" :key="voicemail.id">
                    <li @click="showRecord(voicemail.id)" class="py-4 cursor-pointer border-b border-gray-200 last:border-b-0">
                        <div class="flex items-center space-x-4">

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <span x-text="renderContact(voicemail);">John Smith</span>
                                </p>
                                <p x-show="voicemail.contact" class="text-sm text-gray-500 truncate">mobile</p>
                            </div>
                            <div class="flex flex-col">
                                <p x-text="renderDate(voicemail.created_at, 'short');" class="inline-block text-right m-0 p-0 text-sm leading-5 font-bold rounded-full text-gray-600">
                                    8/6/21
                                </p>
                                <p x-text="renderTimeLength(voicemail.length);" class="inline-block text-right m-0 p-0 text-xs leading-5 font-bold rounded-full text-gray-400">
                                    0:41
                                </p>
                            </div>
                        </div>
                    </li>
                </template>


            </ul>
        </div>
    </div>

    <div x-cloak x-show="showInfiniteScroll" x-intersect="addRecords()" class="bg-white h-24 w-full flex text-gray-600 items-center justify-center" id="infinite-scroll-trigger">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Loading...</span>
    </div>

</div>