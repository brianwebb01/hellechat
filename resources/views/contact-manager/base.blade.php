<x-app-layout>
    <div class="relative" x-data="{ showContactOpen: false, contactFormOpen: false, notificationOpen: false }">
        @include('contact-manager._heading')

        @include('contact-manager._list')

        @include('contact-manager._show')

        @include('contact-manager._form')

        @include('notification')
    </div>
</x-app-layout>