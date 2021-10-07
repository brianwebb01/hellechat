window.initCrudForm = function(data) {
    return {
        formOpen: false,
        deleteConfirmOpen: false,
        notificationOpen: false,
        loading: true,
        saving: false,
        deleting: false,
        urls: data.urls,
        csrfToken: data.csrf_token,
        errors: [],
        records: [],
        currentRecord: {},
        fetchIndex: async function () {
            fetch(this.urls.index, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then((response) => response.json())
                .then((response) => this.records = response.data)
                .then(this.loading = false)
                .catch((err) => console.log(err));
        },
        newRecord: function () {
            this.formOpen = true;
        },
        editRecord: function (id) {
            this.formOpen = true;
            this.currentRecord = this.records.find(r => r.id == id);
        },
        deleteRecord: function (id) {
            this.currentRecord = this.records.find(r => r.id == id);
            this.deleteConfirmOpen = true;
        },
        cancelDelete: function () {
            this.deleteConfirmOpen = false;
        },
        confirmDelete: function () {
            this.deleting = true;
            fetch(this.urls.delete.replace('123', this.currentRecord.id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            })
                .then((response) => {
                    if (response.status == 204) {
                        this.records = this.records.filter(record => record.id != this.currentRecord.id);
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        this.resetCurrent();
                    } else {
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        setTimeout(() => alert('Error, could not delete record'), 500);
                    }
                })
                .catch((err) => console.log(err));
        },
        resetCurrent: function () {
            this.currentRecord = {};
            this.errors = [];
        },
        cancelForm: function () {
            this.resetCurrent();
            this.formOpen = false;
        },
        closeFormSuccess: function () {
            this.resetCurrent();
            this.formOpen = false;
            this.notificationOpen = true;
            this.saving = false;
            setTimeout(() => this.notificationOpen = false, 2000)
        },
        saveForm: function () {
            this.saving = true;
            this.currentRecord.id ? this.updateRecord() : this.createRecord();
        },
        createRecord: function () {
            fetch(this.urls.store, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
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
        updateRecord: function () {
            fetch(this.urls.update.replace('123', this.currentRecord.id), {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
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