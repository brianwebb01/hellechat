<div x-cloak x-show="formOpen" x-data="serviceAccountForm({{ json_encode([
        'serviceAccountForm' => [
            'urls' => [
                'index' => route('service-accounts.index')
            ],
        ]
    ]) }})" x-init="fetchServiceAccounts()" class="fixed inset-0 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">

    <script src="{{ mix('js/serviceAccountForm.js') }}"></script>

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
                                        <span x-show="!currentRecord.id">Add Number</span>
                                        <span x-show="currentRecord.id">Edit Number</span>
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
                                    <div class="space-y-6 pb-5">

                                        <fieldset class="mt-6 bg-white">
                                            <legend class="block text-sm font-medium text-gray-700">Service Account Info</legend>
                                            <div class="mt-1 rounded-md shadow-sm -space-y-px">
                                                <div>
                                                    <label for="service_account" class="sr-only">Service Account</label>
                                                    <select x-model="currentRecord.service_account_id" id="service_account" name="service_account" :class="(errors.service_account_id ? 'border-red-300 text-red-900' : 'border-gray-300') + ' focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none rounded-t-md bg-transparent focus:z-10 sm:text-sm'">
                                                        <option value="">Select...</option>
                                                        <template x-for="sa in serviceAccounts">
                                                            <option :value="sa.id" x-text="`${sa.name} (${sa.provider})`"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="relative">
                                                    <label for="external_identity" class="sr-only">Provider ID</label>
                                                    <input x-model="currentRecord.external_identity" type="text" name="external_identity" id="external_identity" class="focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none rounded-b-md bg-transparent focus:z-10 sm:text-sm border-gray-300" placeholder="Provider ID">
                                                    <div x-show="errors.external_identity" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-2 mx-1 text-sm text-gray-500" id="external_id-description">
                                                Provider ID is the unique ID of the number generated by the provider (i.e. Twilio).
                                            </p>
                                            <p x-show="errors.service_account_id" x-text="errors.service_account_id ? errors.service_account_id.join(', ') : ''" class="text-sm text-red-600"></p>
                                            <p x-show="errors.external_identity" x-text="errors.external_identity ? errors.external_identity.join(', ') : ''" class="text-sm text-red-600"></p>
                                        </fieldset>

                                        <div class="isolate -space-y-px rounded-md shadow-sm">
                                            <div class="relative border border-gray-300 rounded-md rounded-b-none px-3 py-2 focus-within:z-10 focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                <label for="friendly_label" class="block text-xs font-medium text-gray-700">Friendly Label</label>
                                                <input x-model="currentRecord.friendly_label" type="text" name="friendly_label" id="friendly_label" class="block border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="My Number">
                                                <div x-show="errors.friendly_label" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <!-- Heroicon name: solid/exclamation-circle -->
                                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="relative border border-gray-300 rounded-md rounded-t-none px-3 py-2 focus-within:z-10 focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                <label for="phone_number" class="block w-full text-xs font-medium text-gray-700">Phone Number</label>
                                                <input x-model="currentRecord.phone_number" type="text" name="phone_number" id="phone_number" class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="+12125551234">
                                                <div x-show="errors.phone_number" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <!-- Heroicon name: solid/exclamation-circle -->
                                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p x-show="errors.friendly_label" x-text="errors.friendly_label ? errors.friendly_label.join(', ') : ''" class="text-sm text-red-600"></p>
                                            <p x-show="errors.phone_number" x-text="errors.phone_number ? errors.phone_number.join(', ') : ''" class="text-sm text-red-600"></p>
                                        </div>

                                        <div>
                                            <div class="relative border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-indigo-600 focus-within:border-indigo-600">
                                                <label for="sip_registration_url" class="block text-xs font-medium text-gray-900">SIP Registration URL</label>
                                                <input x-model="currentRecord.sip_registration_url" type="text" name="sip_registration_url" id="sip_registration_url" class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="user@sip.domain.com">
                                                <div x-show="errors.sip_registration_url" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <!-- Heroicon name: solid/exclamation-circle -->
                                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p x-show="errors.sip_registration_url" x-text="errors.sip_registration_url ? errors.sip_registration_url.join(', ') : ''" class="text-sm text-red-600"></p>
                                        </div>


                                        <div>
                                            <fieldset class="">
                                                <legend class="">Do Not Disturb</legend>
                                                <div class="divide-y divide-gray-200">
                                                    <div class="relative flex items-start py-4">
                                                        <div class="min-w-0 flex-1 text-sm">
                                                            <label for="candidates" class="text-gray-500">Do not ring when a <span class="text-gray-700 font-medium ">call</span> comes in.</label>
                                                        </div>
                                                        <div class="ml-3 flex items-center h-5">
                                                            <input x-model="currentRecord.dnd_calls" x-bind:checked="currentRecord.dnd_calls == 1" id="dnd_calls" name="dnd_calls" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                        </div>
                                                    </div>
                                                    <div class="relative flex items-start py-4">
                                                        <div class="min-w-0 flex-1 text-sm">
                                                            <label for="candidates" class="text-gray-500">Do not provide <span class="text-gray-700 font-medium ">voicemail</span> notifications.</label>
                                                        </div>
                                                        <div class="ml-3 flex items-center h-5">
                                                            <input x-model="currentRecord.dnd_voicemail" x-bind:checked="currentRecord.dnd_voicemail == 1" id="dnd_voicemail" name="dnd_voicemail" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                        </div>
                                                    </div>
                                                    <div class="relative flex items-start py-4">
                                                        <div class="min-w-0 flex-1 text-sm">
                                                            <label for="candidates" class="text-gray-500">Do not provide <span class="text-gray-700 font-medium ">message</span> notifications.</label>
                                                        </div>
                                                        <div class="ml-3 flex items-center h-5">
                                                            <input x-model="currentRecord.dnd_messages" x-bind:checked="currentRecord.dnd_messages == 1" id="dnd_messages" name="dnd_messages" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                        </div>
                                                    </div>
                                                    <div class="relative flex items-start py-4">
                                                        <div class="min-w-0 flex-1 text-sm">
                                                            <label for="candidates" class="text-gray-500">Allow any <span class="text-gray-700 font-medium ">contacts</span> to break through DnD settings.</label>
                                                        </div>
                                                        <div class="ml-3 flex items-center h-5">
                                                            <input x-model="currentRecord.dnd_allow_contacts" x-bind:checked="currentRecord.dnd_allow_contacts == 1" id="dnd_allow_contacts" name="dnd_allow_contacts" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>



                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 px-4 py-4 flex justify-end">
                            <button @click.prevent="cancelForm();" type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button @click.prevent="saveForm();" x-bind:disabled="saving" type="submit" class="disabled:opacity-80 disabled:pointer-events-none disabled:cursor-auto ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg x-show="!saving" xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>