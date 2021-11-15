<div id="messages" class="h-thread-ui-mobile flex flex-col space-y-2  p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">

    <div x-cloak x-show="showMessagesInfiniteScroll" x-intersect="addMessages()" class="h-24 w-full flex text-gray-600 items-center justify-center" id="infinite-scroll-trigger">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Loading...</span>
    </div>


    <template x-for="(message,index) in messages">
        <div class="chat-message" x-intersect="markAsRead(message.id)">
            <div x-show="renderMessageTime(message, index) != null"  class="justify-center flex pb-2">
                <div x-text="renderMessageTime(message, index)" class="text-white text-xs px-3 py-0.5 bg-gray-400 rounded-xl"></div>
            </div>
            <div :class="(message.direction == 'outbound' ? 'justify-end' : '') + ' flex items-end'">
                <div :class="(message.direction == 'outbound' ? 'order-1 items-end' : 'order-2 items-start') + ' flex flex-col space-y-2 text-xs max-w-xs mx-2'">
                    <div>
                        <span x-html="renderMessageContent(message)" :class="(message.direction == 'outbound' ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-600') + ' px-4 py-2 rounded-lg inline-block'">
                            Lorem ipsum dolor sit amet, consectetur
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>