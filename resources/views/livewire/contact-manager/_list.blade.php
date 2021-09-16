<!-- contact list -->
<div class="bg-white">
    <div class="mx-auto max-w-7xl">
        <div class="bg-white overflow-hidden">
            <nav class="h-full overflow-y-auto" aria-label="Directory">
                @foreach(range('A','Z') as $letter)
                <div class="relative">
                    <div class="{{-- z-10 --}} sticky top-0 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500">
                        <h3>{{$letter}}</h3>
                    </div>
                    <ul role="list" class="relative z-0 divide-y divide-gray-200">
                        @foreach(range(0,3) as $i)
                        <li @click.prevent="showContactOpen = true;" class="bg-white">
                            <div class="relative px-6 py-5 flex items-center space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                <div class="flex-shrink-0 mr-3">
                                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="focus:outline-none">
                                        <!-- Extend touch target to entire panel -->
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <p class="text-sm font-medium text-gray-900">
                                            Leslie Abbott
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            Co-Founder / CEO
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </nav>
        </div>
    </div>
</div>