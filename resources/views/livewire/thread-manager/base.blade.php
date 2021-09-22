<div x-data="{
    threadOpen: false,
    get threadIsOpen() { return this.threadOpen },
    toggleThreadOpen() { this.threadOpen = ! this.threadOpen },
}">

    <div class="border-t border-gray-200">
        <div>
            <div class="h-chat-area relative flex flex-col bg-gray-50">

                <div class="flex-grow w-full max-w-7xl mx-auto lg:flex">
                    <div class="flex-1 min-w-0 xl:flex">

                        <!-- threads summary w/ contacts -->
                        @include('livewire.thread-manager._threads-summary')

                        <!-- chat thread secton -->
                        <div x-cloak x-show="threadIsOpen || window.innerWidth >= 1280"
                            x-transition:enter="transform transition ease-in-out duration-300"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            class="flex-1 p:2 sm:pb-6 justify-between flex-col h-chat-area xl:flex xl:border-r">

                            <!-- thread heading -->
                            @include('livewire.thread-manager._thread-heading')

                            <!-- message thread -->
                            @include('livewire.thread-manager._thread')

                            <!-- compose message bar -->
                            @include('livewire.thread-manager._thread-compose-bar')

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>