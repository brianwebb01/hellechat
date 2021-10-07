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
                        fetch("{{ route('service-accounts.destroy', [123]) }}".replace('123', this.currentRecord.id), {
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
                                    this.currentRecord = {};
                                } else {
                                    this.deleteConfirmOpen = false;
                                    this.deleting = false;
                                    setTimeout(() => alert('Error, could not delete record'), 500);
                                }
                            })
                            .catch((err) => console.log(err));
                    },
                    cancelForm: function() {
                        this.currentRecord = {};
                        this.formOpen = false;
                    },
                    closeFormSuccess: function() {
                        this.currentRecord = {};
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
                        fetch("{{ route('service-accounts.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(this.currentRecord)
                            })
                            .then(response => {

                                if (response.status == 201) {
                                    response.json()
                                        .then(res => this.records.push(res.data))
                                        .then(() => this.closeFormSuccess());
                                } else if (response.status == 422) {
                                    response.json()
                                        .then(res => this.errors = res.errors);
                                } else {
                                    alert('Fatal error, could not save record');
                                }
                                this.saving = false;
                            })
                            .catch((err) => console.log(err));
                    },
                    updateRecord: function() {
                        fetch("{{ route('service-accounts.update', [123]) }}".replace('123', this.currentRecord.id), {
                                method: 'PUT',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(this.currentRecord)
                            })
                            .then(response => {

                                if (response.status == 200) {
                                    response.json()
                                        .then(res => {
                                            let idx = this.records.findIndex(r => r.id == this.currentRecord.id);
                                            this.records[idx] = res.data;
                                        })
                                        .then(() => this.closeFormSuccess());
                                } else if (response.status == 422) {
                                    response.json()
                                        .then(res => this.errors = res.errors);
                                } else {
                                    alert('Fatal error, could not save record');
                                }
                                this.saving = false;
                            })
                            .catch((err) => console.log(err));
                    }
                };
            }
        </script>
    </div>
</x-app-layout>