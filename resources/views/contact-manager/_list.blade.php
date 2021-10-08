<!-- contact list -->
<div class="bg-white">

    <div class="mx-auto max-w-7xl">
        <div class="bg-white overflow-hidden">
            <nav class="h-full overflow-y-auto" aria-label="Directory">
                <template x-for="letter in Object.keys(groupedRecords)">
                    <div class="relative">
                        <div class="{{-- z-10 --}} sticky top-0 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500">
                            <h3 x-text="`${letter}`"></h3>
                        </div>
                        <ul role="list" class="relative z-0 divide-y divide-gray-200">

                            <template x-for="contact in groupedRecords[letter]">
                                <li @click.prevent="showContact(contact.id);" class="bg-white">
                                    <div class="relative px-6 py-5 flex items-center space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                        <div class="flex-shrink-0 mr-3">
                                            <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="#" class="focus:outline-none">
                                                <!-- Extend touch target to entire panel -->
                                                <span class="absolute inset-0" aria-hidden="true"></span>
                                                <p class="text-sm text-gray-900">
                                                    <span x-show="contact.first_name" x-text="contact.first_name" class="font-medium">First</span>
                                                    <span x-show="contact.last_name" x-text="contact.last_name" class="font-bold">Last</span>
                                                    <span x-show="!contact.first_name && !contact.last_name" x-text="contact.company" class="font-bold">Company</span>
                                                </p>
                                                <p x-show="contact.company && contact.first_name" x-text="contact.company" class="text-sm text-gray-500 truncate">Company</p>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </template>

                        </ul>
                    </div>
                </template>
            </nav>
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