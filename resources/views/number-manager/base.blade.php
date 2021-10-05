<x-app-layout>

    <div x-data="{ numberFormOpen: false, deleteConfirmOpen: false, notificationOpen: false }">

        @include('number-manager._heading')

        @include('number-manager._list')

        @include('number-manager._form')

        @include('confirm-delete')

        @include('notification')
    </div>
</x-app-layout>