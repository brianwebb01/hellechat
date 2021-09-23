<div class="mx-auto max-w-7xl mt-6 px-8">
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @foreach(range(1,2) as $i)
        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <div class="w-full flex items-center justify-between p-6 space-x-6">
                <div class="flex-1 truncate">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-gray-900 text-sm font-medium truncate">+12125551234</h3>
                        <span class="flex-shrink-0 inline-block px-2 py-0.5 text-red-800 text-xs font-medium bg-red-100 rounded-full">Twilio</span>
                    </div>
                    <p class="mt-2 text-gray-500 text-sm">Messaging Endpoint</p>
                    <input x-ref="msg_endpoint_input_{{$i}}"  readonly="readonly" type="text" class="p-0 focus:ring-0 w-full bg-gray-200 border border-gray-300 rounded-md px-1 text-sm text-gray-700" value="http://bwebb.ngrok.io/webhooks/twilio/messaging/inbound/RGe8jL">

                    <p class="mt-2 text-gray-500 text-sm">Voice Endpoint</p>
                    <input x-ref="voice_endpoint_input_{{$i}}" readonly="readonly" type="text" class="p-0 focus:ring-0 w-full bg-gray-200 border border-gray-300 rounded-md px-1 text-sm text-gray-700" value="http://bwebb.ngrok.io/webhooks/twilio/voicemail/connect/RGe8jL/RGe8jL">

                </div>
            </div>
            <div>
                <div class="-mt-px flex divide-x divide-gray-200">
                    <div class="w-0 flex-1 flex">
                        <a href="#" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span class="ml-3">Edit</span>
                        </a>
                    </div>
                    <div class="-ml-px w-0 flex-1 flex">
                        <a href="#" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
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

    </ul>
</div>