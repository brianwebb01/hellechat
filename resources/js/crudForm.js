window.initCrudForm = function(data) {
    return {
        formOpen: false,
        showOpen: false,
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
        page: 1,
        lastPage: null,
        showInfiniteScroll: false,
        afterRecordsAdded: function(){},
        beforeSetCurrentRecord: function(){},
        afterSetCurrentRecord: function(){},
        beforeSave: function(){},
        afterSave: function(){},
        beforeCreate: function(){},
        afterCreate: function(){},
        beforeUpdate: function(){},
        afterUpdate: function(){},
        beforeDelete: function(){},
        afterDelete: function(){},

        //function used to load the index listing of records one time
        fetchIndex: async function () {
            this.loadRecords()
                .then(json => {
                    json.data.forEach(item => this.records.push(item));
                    this.loading = false;
                });
        },

        //function used for loading index listing of records either
        //on initial load or multiple times for pagination.
        loadRecords: async function() {
            const response = await fetch(`${this.urls.index}?page=${this.page}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .catch((err) => console.log(err));

            let json = await response.json();
            return json;
        },

        //function used for infinite scroll pagination to make the request
        //for additional records, update local variables accordingly
        //and call afterRecordsAdded() for any post-records-received functionality
        addRecords: async function () {
            this.loadRecords()
                .then(json => {
                    this.lastPage = json.meta.last_page;
                    json.data.forEach(item => this.records.push(item));
                    this.afterRecordsAdded();
                    this.page++;
                    if (this.page <= this.lastPage) {
                        this.showInfiniteScroll = true;
                    } else {
                        this.showInfiniteScroll = false;
                    }
                });
        },

        setCurrentRecord: function(record) {
            this.beforeSetCurrentRecord();
            this.currentRecord = record;
            this.afterSetCurrentRecord();
        },

        getCurrentRecord: function() {
            return this.currentRecord;
        },

        showRecord: function(id) {
            this.showOpen = true;
            this.setCurrentRecord(this.records.find(r => r.id == id));
        },

        hideRecord: function () {
            this.showOpen = false;
        },

        newRecord: function () {
            this.formOpen = true;
        },

        editRecord: function (id) {
            this.formOpen = true;
            this.setCurrentRecord(this.records.find(r => r.id == id));
        },

        deleteRecord: function (id) {
            this.setCurrentRecord(this.records.find(r => r.id == id));
            this.deleteConfirmOpen = true;
        },

        cancelDelete: function () {
            this.deleteConfirmOpen = false;
        },

        confirmDelete: function () {
            this.beforeDelete();
            this.deleting = true;
            fetch(this.urls.delete.replace('123', this.getCurrentRecord().id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            })
                .then((response) => {
                    if (response.status == 204) {
                        this.records = this.records.filter(record => record.id != this.getCurrentRecord().id);
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        this.resetCurrent();
                        this.afterDelete();
                    } else {
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        setTimeout(() => alert('Error, could not delete record'), 500);
                    }
                })
                .catch((err) => console.log(err));
        },

        resetCurrent: function () {
            this.setCurrentRecord({});
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
            this.beforeSave();
            this.saving = true;
            this.getCurrentRecord().id ? this.updateRecord() : this.createRecord();
        },

        createRecord: function () {
            this.beforeCreate();
            fetch(this.urls.store, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(this.getCurrentRecord())
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
                    this.afterCreate();
                    this.afterSave();
                })
                .catch((err) => console.log(err));
        },

        updateRecord: function () {
            this.beforeUpdate();
            fetch(this.urls.update.replace('123', this.getCurrentRecord().id), {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(this.getCurrentRecord())
            })
                .then(response => {

                    if (response.status == 200) {
                        response.json()
                            .then(res => {
                                let idx = this.records.findIndex(r => r.id == this.getCurrentRecord().id);
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
                    this.afterUpdate();
                    this.afterSave();
                })
                .catch((err) => console.log(err));
        }
    };
}