<x-app-layout>
    <div x-data="initServiceAccountManager()" x-init="fetchIndex()">

        @include('service-account-manager._heading')

        @include('service-account-manager._list')

        @include('service-account-manager._form')

        @include('confirm-delete')

        @include('notification')

        <script>
            function initServiceAccountManager() {
                return {
                    serviceAccountFormOpen: false,
                    deleteConfirmOpen: false,
                    notificationOpen: false,
                    fetchIndex: function() {
                        fetch("{{ route('service-accounts.index') }}", {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then((response) => response.json())
                            .then((response) => console.log(response))
                            .catch((err) => console.log(err));
                    }
                };
            }
        </script>
    </div>
</x-app-layout>