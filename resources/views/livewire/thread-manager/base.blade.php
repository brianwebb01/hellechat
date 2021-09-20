<div>

    @include('livewire.thread-manager.base')

    <div class="border-t border-gray-200">
        <div>
            <div class="relative h-chat-area flex flex-col bg-gray-50">

                <div class="flex-grow w-full max-w-7xl mx-auto lg:flex">
                    <div class="flex-1 min-w-0 xl:flex">

                        <!-- threads summary w/ contacts -->
                        @include('livewire.thread-manager._threads-summary')

                        <!-- chat thread secton -->
                        <div class="hidden flex-1 p:2 sm:pb-6 justify-between flex-col h-chat-area xl:flex xl:border-r">

                            <!-- thread heading -->
                            @include('livewire.thread-manager._heading')

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