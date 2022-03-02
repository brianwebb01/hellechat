<div class="fixed md:relative bottom-0 border-t-2 border-gray-200 bg-gray-50 px-4 py-3 xl:py-0 xl:pt-6">

    <div x-show="sendingFromNumber" class="absolute w-full -top-3 z-40">
        <div class="relative flex justify-center">
            <span class="px-2 bg-white border rounded-full border-gray-300 text-xs text-gray-500">
                <span x-show="sendingFromName && sendingFromNumber" x-html="`Sending From <b>${sendingFromName}</b> (${sendingFromNumber})`"></span>
                <span x-show="!sendingFromName && sendingFromNumber" x-text="`Sending From ${sendingFromNumber}`"></span>
            </span>
        </div>
    </div>



    <div class="relative pr-4">

        <textarea @keyup.enter="if (!$event.shiftKey) sendMessage();" :disabled="currentThread == null" x-model="composeBodyText" placeholder="Write Something" rows="10" cols="30" name="foo" id="bar" class="block w-full h-12 pr-24 pl-4 py-3 rounded-full resize-none  focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-300 bg-white border border-gray-200">jazzy</textarea>

        <div class="absolute right-0 items-center inset-y-0 flex">
            <!-- attachment -->
            <button @click="openFileUpload();" :disabled="currentThread == null" type="button" class="disabled:opacity-70 disabled:pointer-events-none disabled:cursor-auto inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
            </button>
            <input type="file" id="messageAttachment" class="hidden" />

            <!--
            <button type="button" class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </button>

            <button type="button" class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
            -->




            <!-- send button -->
            <button @click="sendMessage()" :disabled="currentThread == null" type="button" class="z-10 disabled:opacity-70 disabled:pointer-events-none disabled:cursor-auto inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-indigo-500 hover:bg-indigo-400 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 rotate-90">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                </svg>
            </button>

            <!-- backer for opacity on send button -->
            <button type="button" disabled="true" class="absolute z-0 right-0 rounded-full h-12 w-12 text-white bg-gray-50 focus:outline-none"></button>

        </div>
    </div>


    <div x-show="filesForUpload.length > 0" class="space-x-5 mt-5 ">
        <template x-for="file in filesForUpload">
            <div class="relative bg-gray-400 rounded-lg px-2 py-1 text-white inline-block">
                <span x-text="file.name">foo_file.pdf</span>
                <button @click="removeFileForUpload(file)" class="absolute text-red-600 -top-2 -right-2 rounded-full bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </template>
    </div>


</div>