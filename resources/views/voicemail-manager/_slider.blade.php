<div class="mt-4 mb-5 flex items-center">

    <button @click="playPause()" type="button" class="flex-none group  rounded-md flex items-center focus:outline-none">

        <svg x-show="playing" xmlns="http://www.w3.org/2000/svg" class="text-blue-500 h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>


        <svg x-show="!playing" xmlns="http://www.w3.org/2000/svg" class="text-blue-500 h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

    </button>

    <div class="flex w-2/3 m-auto items-center justify-center">
        <div class="py-1 relative min-w-full">
            <input id="scrubber" class="rounded-lg bg-gray-200 h-2 min-w-full" type="range" min="0" max="100" step="1" value="0" />
            <div x-text="elapsed" class="absolute text-xs text-gray-400 -ml-1 bottom-0 left-0 -mb-6">0:10</div>
            <div x-text="renderTimeLength(currentRecord.length);" class="absolute text-xs text-gray-400 -mr-1 bottom-0 right-0 -mb-6">0:40</div>
        </div>
    </div>

</div>