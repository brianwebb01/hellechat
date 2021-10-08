<x-app-layout>
    <div class="relative" x-data="initCrudForm({{ json_encode([
        'urls' => [
            'index' => route('contacts.index'),
            'store' => route('contacts.store'),
            'update' => route('contacts.update', [123]),
            'delete' => route('contacts.destroy', [123])],
        'csrf_token' => csrf_token()
            ]) }} )" x-init="">

        <div x-data="manageContacts()" x-init="addRecords">
            @include('contact-manager._heading')

            @include('contact-manager._list')

            @include('contact-manager._show')

            @include('contact-manager._form')

            @include('confirm-delete')

            @include('notification')
        </div>

        <script src="{{ mix('js/crudForm.js') }}"></script>
        <script src="{{ mix('js/contactManagement.js') }}"></script>

    </div>
</x-app-layout>