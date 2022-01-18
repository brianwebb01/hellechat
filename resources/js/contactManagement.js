import Fuse from 'fuse.js'

window.manageContacts = function() {
    return {
        groupedRecords: [],
        phoneNumbers: [],
        searchTerm: null,
        unfilteredRecords: [],
        importFile: null,
        importConfirmOpen: false,
        importing: false,
        importErrors: [],
        importSuccess: false,

        initContactManagement: function() {
            this.initFileUpload();
            this.addRecords();
        },

        initFileUpload: function () {
            let input = document.getElementById('import');
            const onSelectFile = () => this.confirmContactsImport(input.files[0]);
            input.addEventListener('change', onSelectFile, false);
        },

        importStateConfirm: function(){
            return this.importErrors.length == 0 &&
                this.importSuccess == false;
        },

        importStateSuccess: function() {
            return this.importSuccess == true;
        },

        importStateError: function() {
            return this.importSuccess == false &&
                this.importErrors.length > 0;
        },

        cancelImport: function() {
            this.importConfirmOpen = false;
            setTimeout(() => {
                this.importFile = null;
                this.importSuccess = false;
                this.importErrors = [];
            }, 500);
        },

        confirmContactsImport: function(file) {
            this.importFile = file;
            document.getElementById('import').value = "";
            this.importConfirmOpen = true;
        },

        commitContactsImport: function(){
            this.importing = true;

            const formData = new FormData();
            formData.append('import', this.importFile);

            fetch(this.urls.import_contacts, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            })
                .then(response => {
                    this.importing = false;

                    if (response.status == 204) {

                        this.importSuccess = true;

                    } else if (response.status == 422) {

                        response.json().then(res => {
                            this.importErrors = res.errors.import;
                        });
                    } else {
                        alert('Fatal error, could not process import');
                    }
                })
                .catch((err) => console.log(err));
        },

        browseForContactImportFile: function() {
            document.getElementById('import').click();
        },

        searchContacts: async function() {

            if (this.unfilteredRecords.length == 0){
                this.unfilteredRecords = this.records;
            }

            if(this.searchTerm == "" && this.unfilteredRecords.length > 0){
                this.records = this.unfilteredRecords;
                this.unfilteredRecords = [];
                this.showInfiniteScroll = true;
                this.regroupRecords();
                return;
            }

            const response = await fetch(this.urls.search_contacts, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ 'query': this.searchTerm })

            }).catch((err) => console.log(err));

            let json = await response.json();
            this.showInfiniteScroll = false;
            this.records = json.data;
            this.regroupRecords();

            // if (this.unfilteredRecords.length == 0){
            //     this.unfilteredRecords = this.records;
            // }

            // if(this.searchTerm == "" && this.unfilteredRecords.length > 0){
            //     this.records = this.unfilteredRecords;
            //     this.unfilteredRecords = [];
            //     this.regroupRecords();
            //     return;
            // }

            // this.records = this.unfilteredRecords;

            // const options = {
            //     keys: ['first_name', 'last_name', 'company']
            // }
            // const fuse = new Fuse(this.records, options);
            // const result = fuse.search(this.searchTerm);

            // this.records = result.map(i => i.item);
            // this.regroupRecords();
        },

        regroupRecords: function() {
            this.groupedRecords = this.groupByLastFirst(this.records);
        },

        //crudForm.js override
        afterRecordsAdded: function() {
            this.regroupRecords();
        },

        //crudForm.js override
        setCurrentRecord: function(record) {
            this.currentRecord = record;
            this.setPhoneNumbers(record.phone_numbers || {});
        },

        setPhoneNumbers: function(numbers) {

            if (typeof (numbers) == 'string')
                numbers = JSON.parse(numbers);

            this.phoneNumbers = Object.keys(numbers).map(k => {
                return {
                    'type': k,
                    'number': numbers[k],
                    'id': this.randomId()
                }
            });
        },

        randomId: function() {
            return Math.random().toString(36).substring(2);
        },

        //crudForm.js override
        getCurrentRecord: function() {
            return this.currentRecord;
        },

        groupByLastFirst: function(array) {
            let grouped = array.reduce((acc, obj) => {
                const letter = (obj.last_name ? obj.last_name.charAt(0)
                    : (obj.first_name ? obj.first_name.charAt(0)
                        : obj.company.charAt(0))).toUpperCase();
                acc[letter] = acc[letter] || [];
                acc[letter].push(obj);
                return acc;
            }, {});

            //now sort
            Object.keys(grouped).forEach(key => {
                let sorted = grouped[key];
                sorted.sort((a, b) => {
                    let asort = (a.last_name || a.company || a.first_name).toLowerCase();
                    let bsort = (b.last_name || b.company || b.first_name).toLowerCase();

                    if (asort < bsort) {
                        return -1;
                    }
                    if (asort > bsort) {
                        return 1;
                    }
                    return 0;
                });
                grouped[key] = sorted;
            });

            return grouped;
        },

        getAvatarLetters: function(contact){
            let letters = [];

            if(contact.first_name && contact.last_name){
                letters.push(contact.first_name.charAt(0));
                letters.push(contact.last_name.charAt(0));
            } else if(contact.first_name && !contact.last_name){
                letters.push(contact.first_name.charAt(0));
            } else if(!contact.first_name && !contact.last_name){
                letters.push(contact.company.charAt(0));
            }

            return letters.join("");
        },

        addPhone: function() {
            this.phoneNumbers.push({
                'type': 'mobile',
                'number': '',
                'id': this.randomId()
            });
        },

        deletePhone: function(index) {
            const item = this.phoneNumbers[index];
            this.phoneNumbers = this.phoneNumbers.filter(i => i.id != item.id);
        },

        beforeSave: function() {
            let result = {};
            this.phoneNumbers.forEach(item => {
                result[`${item.type}`] = item.number;
            });
            this.currentRecord.phone_numbers = JSON.stringify(result);
        },

        afterSave: function() {
            setTimeout(() => this.regroupRecords(), 500);
        },

        afterDelete: function(){
            this.regroupRecords();
        }
    }
}