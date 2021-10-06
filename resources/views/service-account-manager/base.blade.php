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
                    formOpen: false,
                    deleteConfirmOpen: false,
                    notificationOpen: false,
                    loading: true,
                    saving: false,
                    deleting: false,
                    errors: [],
                    records: [],
                    currentRecord: {},
                    fetchIndex: async function() {
                        fetch("{{ route('service-accounts.index') }}", {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then((response) => response.json())
                            .then((response) => this.records = response.data)
                            .then(this.loading = false)
                            .catch((err) => console.log(err));
                    },
                    newRecord: function() {
                        this.formOpen = true;
                    },
                    editRecord: function(id) {
                        this.formOpen = true;
                        this.currentRecord = this.records.find(r => r.id == id);
                        console.log(this.currentRecord);
                    },
                    deleteRecord: function(id) {
                        this.currentRecord = this.records.find(r => r.id == id);
                        this.deleteConfirmOpen = true;
                    },
                    cancelDelete: function() {
                        this.deleteConfirmOpen = false;
                    },
                    confirmDelete: function() {
                        this.deleting = true;
                        fetch("{{ route('service-accounts.destroy', [1234]) }}".replace('1234', this.currentRecord.id), {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            })
                            .then((response) => {
                                if (response.status == 204) {
                                    this.records = this.records.filter(record => record.id != this.currentRecord.id);
                                    this.deleteConfirmOpen = false;
                                    this.deleting = false;
                                } else {
                                    this.deleteConfirmOpen = false;
                                    this.deleting = false;
                                    setTimeout(() => alert('Error, could not delete record'), 500);
                                }
                            })
                            .catch((err) => console.log(err));
                    },
                    cancelForm: function() {
                        this.formOpen = false;
                    },
                    closeFormSuccess: function() {
                        this.formOpen = false;
                        this.notificationOpen = true;
                        this.saving = false;
                        setTimeout(() => this.notificationOpen = false, 2000)
                    },
                    saveForm: function() {
                        this.saving = true;
                        this.currentRecord.id ? this.updateRecord() : this.createRecord();
                    },
                    createRecord: function() {
                        fetch("{{ route('service-accounts.store', [1234]) }}".replace('1234', this.currentRecord.id), {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(this.currentRecord)
                            })
                            .then(response => {

                                if (response.status == 201) {
                                    //push data to records[]
                                    this.closeFormSuccess();
                                } else if (response.status == 422) {
                                    response.json()
                                        .then(res => this.errors = res.errors)
                                        .then(err => console.log(this.errors));
                                } else {
                                    alert('Error, could not save record');
                                }
                                this.saving = false;
                            })
                            .catch((err) => console.log(err));
                    },
                    updateRecord: function() {
                        // @TODO - make put api request & wire up next actions accordingly
                        console.log('update');
                        setTimeout(() => this.closeFormSuccess(), 1000)
                    }
                };
            }
        </script>
    </div>
</x-app-layout>