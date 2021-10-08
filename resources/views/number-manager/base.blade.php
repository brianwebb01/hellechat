<x-app-layout>

    <div x-data="initCrudForm({{ json_encode([
        'urls' => [
            'index' => route('numbers.index'),
            'store' => route('numbers.store'),
            'update' => route('numbers.update', [123]),
            'delete' => route('numbers.destroy', [123])],
        'csrf_token' => csrf_token()
            ]) }} )" x-init="fetchIndex()">

        @include('number-manager._heading')

        @include('number-manager._list')

        @include('number-manager._form')

        @include('confirm-delete')

        @include('notification')

        <script src="{{ mix('js/crudForm.js') }}"></script>
    </div>
</x-app-layout>