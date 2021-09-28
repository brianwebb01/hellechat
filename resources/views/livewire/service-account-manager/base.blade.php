<div
    wire:init="fetchAll"
    x-data="{serviceAccountFormOpen: false, deleteConfirmOpen: false, notificationOpen: false }">

    @include('livewire.service-account-manager._heading')

    @include('livewire.service-account-manager._list')

    @include('livewire.service-account-manager._form')

    @include('livewire.confirm-delete')

    @include('livewire.notification')
</div>