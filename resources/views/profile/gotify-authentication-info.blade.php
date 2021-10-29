<x-jet-action-section>
    <x-slot name="title">
        {{ __('Push Notifications') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Configure the mobile Gotify client to receive push notifications.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            <div>
                <p>
                    To receive push notifications you'll need the <b>Gotify App</b> for Android.
                </p>
                <p class="flex items-center">
                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.github.gotify"><img src="{{ asset('images/playstore.png') }}" alt="Get it on Google Play" width="150"></a>
                    <a target="_blank" href="https://f-droid.org/de/packages/com.github.gotify/"><img src="{{ asset('images/fdroid.png') }}" alt="Get it on F-Droid" width="150"></a>
                    <a target="_blank" href="https://github.com/gotify/android/releases/latest"><img src="{{ asset('images/download-badge.png') }}" alt="Get it on F-Droid" width="150"></a>
                </p>
                <p><em>Google Play and the Google Play logo are trademarks of Google LLC.</em></p>
            </div>
        </div>


        <div class="rounded-md mt-6">
            <div class="">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Gotify Login Credentials
                </h3>
                <div class="mt-5">
                    <div class="rounded-md bg-gray-100 px-6 py-5">
                        <div class="sm:flex sm:justify-between">
                            <div class="mt-3 sm:mt-0">
                                <div class="text-sm font-medium text-gray-900">
                                    Hostname:
                                </div>
                                <div class="mt-1 text-sm text-gray-600 sm:flex sm:items-center">
                                    <div>
                                        {{ config('services.gotify.public_url') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 sm:mt-0">
                                <div class="text-sm font-medium text-gray-900">
                                    Username:
                                </div>
                                <div class="mt-1 text-sm text-gray-600 sm:flex sm:items-center">
                                    <div>
                                        {{ auth()->user()->gotify_user_name }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 sm:mt-0">
                                <div class="text-sm font-medium text-gray-900">
                                    Password:
                                </div>
                                <div class="mt-1 text-sm text-gray-600 sm:flex sm:items-center">
                                    <div>
                                        {{ auth()->user()->gotify_user_pass }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </x-slot>
</x-jet-action-section>