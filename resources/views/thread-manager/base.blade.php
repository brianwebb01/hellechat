<x-app-layout>

    <div x-data="initCrudForm({{ json_encode([
        'urls' => [
            'index' => route('threads.index'),
            'show' => route('threads.show', [123]),
            'delete' => route('threads.destroy', [123]),
            'store_message' => route('messages.store'),
            'update_message' => route('messages.update', [123]),
            'numbers_index' => route('numbers.index'),
            'search_contacts' => route('contacts.search'),
        ],
        'csrf_token' => csrf_token()
            ]) }} )" x-init="">

        <div x-data="manageMessages('{{ request()->get('new') }}')"
            x-init="initMessageManagement"
            class="border-t border-gray-200">

            <div>
                <div class="h-chat-area relative flex flex-col bg-gray-50">

                    <div class="flex-grow w-full max-w-7xl mx-auto lg:flex">
                        <div class="flex-1 min-w-0 xl:flex">

                            <!-- threads summary w/ contacts -->
                            @include('thread-manager._threads-summary')

                            <!-- compose new message modal -->
                            @include('thread-manager._new-message-slider')

                            <!-- chat thread secton -->
                            <div x-cloak x-show="threadOpen || window.innerWidth >= 1280" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="flex-1 p:2 sm:pb-6 justify-between flex-col h-chat-area xl:flex xl:border-r">

                                <!-- thread heading -->
                                @include('thread-manager._thread-heading')

                                <!-- message thread -->
                                @include('thread-manager._thread')

                                <!-- compose message bar -->
                                @include('thread-manager._thread-compose-bar')

                                <!-- delete confirmation modal -->
                                @include('confirm-delete')

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script src="{{ mix('js/crudForm.js') }}"></script>
        <script src="{{ mix('js/messageManagement.js') }}"></script>

    </div>
</x-app-layout>