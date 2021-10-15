<div x-cloak x-show="!threadOpen || window.innerWidth >= 1280" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" class="xl:flex-shrink-0 xl:w-64 xl:border-r bg-gray-50">

    <div class="h-full pl-4 pr-2 py-3 sm:pl-6 lg:pl-8 xl:pl-0">
        <div class="h-full relative overflow-y-auto h-threads">

            <div class="mb-4 flex">

                <!-- search box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input @search="searchThreads();" @keyup="searchThreads();" x-model="searchTerm" type="search" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-full p-2 border" placeholder="Search" />
                </div>

                <button @click="composeNewMessage()" type="button" class="ml-6 sm:ml-2 px-2.5 py-2.5 disabled:opacity-70 disabled:pointer-events-none disabled:cursor-auto rounded-full transition duration-500 ease-in-out text-white bg-indigo-500 hover:bg-indigo-400 focus:outline-none">
                    <!-- Heroicon name: pencil-alt -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
            </div>

            <template x-for="record in records" :key="record.id">
                <div class="relative rounded-lg px-2 py-2 flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-300 mb-3 hover:bg-indigo-100">
                    <div class="flex-shrink-0">
                        <!-- <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=80&amp;h=80&amp;q=80" alt="" /> -->
                        <div x-text="getAvatarLetters(record);" class="h-10 w-10 font-bold text-gray-800 rounded-full bg-gray-200 flex items-center justify-center font-mono">AA</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a @click.prevent="openThread(record)" href="#one" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <div class="flex items-center justify-between">
                                <p x-text="renderContact(record)" class="text-sm font-bold text-gray-900">Lina Dry</p>
                                <div x-text="renderThreadTime(record)" class="text-gray-400 text-xs">12:35 AM</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <p x-html="renderPreview(record)" class="text-sm text-gray-500 truncate">Hi</p>
                                <div x-show="record.unread > 0" x-text="record.unread" class="text-white text-xs bg-indigo-400 rounded-full px-1.5 pb-0.5 ml-2">
                                    2
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </template>


            <div x-cloak x-show="showInfiniteScroll" x-intersect="addRecords()" class="h-24 w-full flex text-gray-600 items-center justify-center" id="infinite-scroll-trigger">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading...</span>
            </div>

        </div>
    </div>
</div>