<x-app-layout>
    <div x-data="initCrudForm({{ json_encode([
        'urls' => [
            'index' => route('service-accounts.index'),
            'store' => route('service-accounts.store'),
            'update' => route('service-accounts.update', [123]),
            'delete' => route('service-accounts.destroy', [123])],
        'csrf_token' => csrf_token()
            ]) }} )" x-init="fetchIndex()">

        @include('service-account-manager._heading')

        @include('service-account-manager._list')

        @include('service-account-manager._form')

        @include('confirm-delete')

        @include('notification')

        <script src="{{ mix('js/crudForm.js') }}"></script>
    </div>
</x-app-layout>