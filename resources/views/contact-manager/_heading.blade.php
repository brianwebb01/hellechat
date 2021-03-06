<!-- heading, search, add -->
<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">


        <div class=" mx-auto  md:flex md:items-center md:justify-between md:space-x-5 max-w-7xl">
            <div class="hidden sm:flex items-center space-x-5">
                <div>
                    <h2 class=" font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Contacts') }}
                    </h2>
                </div>
            </div>
            <div class="mt-0 sm:mt-3 flex flex-wrap space-y-0  sm:space-x-3">
                <div class="flex-1 w-full inline-flex">
                    <input @search="searchContacts();" @keyup.debounce.500ms="searchContacts();" x-model="searchTerm" type="search" name="email" id="email" class="mt-0 sm:mt-0 h-10 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Search...">
                </div>

                <button type="button" @click="browseForContactImportFile();" title="Import Contacts" class=" ml-2 h-10 inline-flex items-center p-2 pr-3 pl-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <span class="sr-only">Import Contacts</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </button>
                <input type="file" id="import" class="hidden" />

                <button type="button" @click="newRecord();" title="Add new contact" class=" ml-2 h-10 inline-flex items-center p-2 pr-3 pl-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Add new contact</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>