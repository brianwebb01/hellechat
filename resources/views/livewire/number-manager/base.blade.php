<div x-data="{ numberFormOpen: false, deleteConfirmOpen: false, notificationOpen: false }">

    @include('livewire.number-manager._heading')

    @include('livewire.number-manager._list')

    @include('livewire.number-manager._form')

    @include('livewire.confirm-delete')

    @include('livewire.notification')
</div>