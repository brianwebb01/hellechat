<div x-cloak x-show="newMessageOpen" class="z-20 fixed inset-0 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
        <!-- Background overlay, show/hide based on slide-over state. -->
        <div class="absolute inset-0" aria-hidden="true">
            <div class="fixed inset-y-0 pl-16 max-w-full right-0 flex" x-cloak x-show="newMessageOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="w-screen max-w-md">
                    <form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
                        <div class="flex-1 h-0 overflow-y-auto">
                            <div class="py-6 px-4 bg-indigo-700 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-medium text-white" id="slide-over-title">
                                        Create New Message
                                    </h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button @click="closeNewMessageCompose()" type="button" class="bg-indigo-700 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                            <span class="sr-only">Close panel</span>
                                            <!-- Heroicon name: outline/x -->
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <p class="text-sm text-indigo-300">
                                        Get started by selecting the number to send from and the number or contact to send to.
                                    </p>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="px-4 divide-y divide-gray-200 sm:px-6">
                                    <div class="space-y-6 pt-6 pb-5">
                                        <div>
                                            <label for="send-from" class="block text-sm font-medium text-gray-900">
                                                Number to send from
                                            </label>
                                            <div class="mt-1">
                                                <select x-model="newMessageFromNumber" id="send-from" name="send-from" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="">From...</option>
                                                    <template x-for="number in newMessageFromNumberOptions" :key="number.id">
                                                        <option :value="number.id" x-text="`${number.friendly_label} (${number.phone_number})`"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="send-to" class="block text-sm font-medium text-gray-900">
                                                Send To
                                            </label>
                                            <div class="mt-1 relative text-left">
                                                <input x-model="newMessageToSearchString" @keyup.debounce.500ms="searchNewMessageTo()" type="search" name="send-to" id="send-to" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">

                                                <div x-cloak x-show="newMessageToSearchResults.length > 0" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">

                                                    <div class="py-1" role="none">
                                                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                                        <template x-for="option in newMessageToSearchResults">
                                                            <a @click.prevent="selectNewMessageToResult(option)" x-html="`<b>${option.friendly_name}</b> ${option.number_type}: ${option.phone_number}`" href="#" class="text-gray-700 hover:bg-gray-100 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">Result 1</a>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                            <button @click="closeNewMessageCompose()" type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button @click.prevent="composeNewMessageConfirm()" type="submit" class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Compose
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>