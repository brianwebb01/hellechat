<div class="relative" x-data="{ showContactOpen: false, contactFormOpen: false, notificationOpen: false }">
    @include('livewire.contact-manager._heading')

    @include('livewire.contact-manager._list')

    @include('livewire.contact-manager._show')

    @include('livewire.contact-manager._form')

    @include('livewire.notification')
</div>