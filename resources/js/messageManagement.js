const dayjs = require("dayjs");
import Fuse from 'fuse.js'

window.manageMessages = function(queryString, authUserId)
{
    return {
        userId: authUserId,
        queryParams: JSON.parse(queryString),
        threadOpen: false,
        currentThread: null,
        messagesPage: 1,
        lastMessagesPage: null,
        showMessagesInfiniteScroll: false,
        messages: [],
        composeBodyText: null,
        newMessageOpen: false,
        newMessageFromNumber: null,
        newMessageFromNumberOptions: [],
        newMessageToSearchString: null,
        newMessageToSearchResults: [],
        newMessageToSelection: null,
        sendingFromName: null,
        sendingFromNumber: null,
        searchTerm: null,
        unfilteredRecords: [],
        filesForUpload: [],

        initMessageManagement: function()
        {
            window.addEventListener('hashchange', () => this.filterByNumberId());

            Echo.private(`App.Models.User.${this.userId}`)
                .notification((notification) => {
                    if (notification.type == 'App\\Notifications\\InboundMessageCreated'){
                        this.addNewInboundMessage(notification.message);
                    }
                });

            this.initFileUpload();
            this.addRecords();
            this.fetchNumbers();

            if (this.queryParams.new){

                this.newMessageOpen = true;
                this.newMessageToSearchString = `+${this.queryParams.new}`;

            } else if(this.queryParams.numberPhone && this.queryParams.with){
                setTimeout(() => { this.jumpToThread(
                    this.queryParams.numberPhone,
                    this.queryParams.with
                )}, 500);
            }
        },

        afterRecordsAdded: function () {
            if (window.location.hash.substr(0, 9) == '#numbers-') {
                this.filterByNumberId();
            }
        },

        filterByNumberId: function () {
            const numberId = window.location.hash.substr(1).replace('numbers-', '');

            let toAdd = this.records.filter(r => !this.unfilteredRecords.map(ufr => ufr.id).includes(r.id))
            this.unfilteredRecords = [...this.unfilteredRecords, ...toAdd];

            if (numberId == 'all') {
                this.records = this.unfilteredRecords;
            } else if (Number(numberId) > 0) {
                this.records = this.unfilteredRecords.filter(v =>
                    v.number_id == Number(numberId))
            }
        },

        initFileUpload: function() {
            let input = document.getElementById('messageAttachment');
            const onSelectFile = () => this.addFileForUpload(input.files[0]);
            input.addEventListener('change', onSelectFile, false);
        },

        jumpToThread: function(numberPhoneNumber, withNum) {
            let number = this.newMessageFromNumberOptions.find(n =>
                n.phone_number == `+${numberPhoneNumber}`
            );

            if(number == undefined) return;

            let foundThread = this.records.find(t => {
                return t.number_phone_number = number.phone_number
                    && t.phone_number == `+${withNum}`;
            });

            if(foundThread == undefined) return;

            this.openThread(foundThread);
        },

        addFileForUpload: function(file){
            this.filesForUpload.push(file);
            document.getElementById('messageAttachment').value = "";
        },

        removeFileForUpload: function(file) {
            this.filesForUpload = this.filesForUpload.filter(f => f.name != file.name);
        },

        openFileUpload: function() {
            document.getElementById('messageAttachment').click();
        },

        searchThreads: function() {
            if(this.unfilteredRecords.length == 0){
                this.unfilteredRecords = this.records;
            }

            if (this.searchTerm == "" && this.unfilteredRecords.length > 0){
                this.records = this.unfilteredRecords;
                return;
            }

            this.records = this.unfilteredRecords;

            const options = {
                keys: [
                    'phone_number',
                    'preview',
                    'contact.first_name',
                    'contact.last_name'
                ]
            }

            const fuse = new Fuse(this.records, options);
            const results = fuse.search(this.searchTerm);
            this.records = results.map(i => i.item);
        },

        selectNewMessageToResult: function(result)
        {
            this.newMessageToSelection = result;
            this.newMessageToSearchResults = [];
            this.newMessageToSearchString = result.phone_number;
        },

        searchNewMessageTo: async function()
        {
            this.newMessageToSearchResults = [];
            if (this.newMessageToSearchString == "") return;

            const response = await fetch(this.urls.search_contacts, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({'query': this.newMessageToSearchString})

            }).catch((err) => console.log(err));

            let json = await response.json();

            json.data.forEach(contact => {
                let friendly_name;
                if (contact.first_name && contact.last_name) {
                    friendly_name = contact.first_name +' ' + contact.last_name;
                } else if (contact.first_name && !contact.last_name) {
                    friendly_name = contact.first_name;
                } else if (!contact.first_name && !contact.last_name) {
                    friendly_name = contact.company;
                }
                Object.keys(contact.phone_numbers).forEach(numType => {
                    this.newMessageToSearchResults.push({
                        'friendly_name': friendly_name,
                        'number_type': numType,
                        'phone_number': contact.phone_numbers[numType],
                        'contact': contact
                    });
                })

            });
        },

        composeNewMessage: function()
        {
            this.newMessageOpen = true;
        },

        addNewInboundMessage: function(message)
        {
            let number = this.newMessageFromNumberOptions.find(n =>
                n.id == Number(message.number_id)
            );

            let foundThread = this.records.find(t => {
                return t.number_id = number.id
                    && t.phone_number == message.from;
            });

            let tPreview = (!message.body && message.num_media > 0) ? message.media[0] : message.body;

            if (foundThread) {

                foundThread.preview = tPreview;

                if(this.currentThread.id == foundThread.id){
                    this.messages.push(message);
                } else {
                    foundThread.unread++;
                }

            } else {

                let newThread = {
                    'id': message.id,
                    'unread': 1,
                    'number_id': message.number_id,
                    'number_phone_number': number.phone_number,
                    'phone_number': message.from,
                    'preview': this.renderPreview(JSON.parse('{"preview": "'+ tPreview +'"}')),
                    'contact': message.contact,
                    'last_updated_at': dayjs(),
                    'send_from_number': number
                }

                this.records.unshift(newThread);
            }
        },

        composeNewMessageConfirm: function()
        {
            let e164Regex = /\+?[1-9]\d{1,14}/;
            if (
                this.newMessageToSelection == null &&
                this.newMessageToSearchString.match(e164Regex) != null
            ){
                this.newMessageToSelection = {
                    'friendly_name': this.newMessageToSearchString,
                    'number_type': 'new',
                    'phone_number': this.newMessageToSearchString,
                    'contact': null
                }
            }

            if (this.newMessageFromNumber == null || this.newMessageToSelection == null){
                alert('Please select both a from and to value');
                return false;
            }

            let number = this.newMessageFromNumberOptions.find(n =>
                n.id == Number(this.newMessageFromNumber)
            );


            let foundThread = this.records.find(t => {
                return t.number_id = number.id
                && t.phone_number == this.newMessageToSelection.phone_number;
            });

            if(foundThread){
                this.setCurrentThread(foundThread);
            } else {

                let newThread = {
                    'id': Math.random().toString(16).substr(2, 10),
                    'unread': 0,
                    'phone_number': this.newMessageToSelection.phone_number,
                    'preview': "",
                    'contact': this.newMessageToSelection.contact,
                    'last_updated_at': dayjs(),
                    'send_from_number': number,
                    'is_new': true
                }

                this.records.unshift(newThread);
                this.setCurrentThread(newThread);
            }

            this.closeNewMessageCompose();
            this.threadOpen = true;
        },

        closeNewMessageCompose: function()
        {
            this.newMessageOpen = false;
            this.newMessageFromNumber = null;
            this.newMessageToSearchString = null;
            this.newMessageToSearchResults = [];
            this.newMessageToSelection = null;
        },

        sendMessage: function()
        {
            if(!this.composeBodyText && this.filesForUpload.length == 0)
                return;

            let message = {};

            if(this.currentThread.is_new){
                message.number_id = this.currentThread.send_from_number.id;
                message.service_account_id = this.currentThread.send_from_number.service_account_id;
                message.from = this.currentThread.send_from_number.phone_number;
                message.to = this.currentThread.phone_number;
            } else {
                let last = JSON.parse(JSON.stringify(this.messages[0]));
                message.number_id = last.number_id;
                message.service_account_id = last.service_account_id;
                message.contact_id = last.contact_id;
                message.from = last.direction == 'outbound' ? last.from : last.to;
                message.to = last.direction == 'outbound' ? last.to : last.from;
            }

            message.status = 'local-created';
            message.body = this.composeBodyText;
            message.direction = 'outbound';
            message.read = 1;

            const formData = new FormData();
            Object.keys(message).forEach(key => {
                if(message[key] != undefined){
                    formData.append(key, message[key])
                }
            });

            if(this.filesForUpload.length > 0){
                this.filesForUpload.forEach(f =>
                    formData.append('media[]', f));
            }

            fetch(this.urls.store_message, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            })
                .then(response => {

                    if (response.status == 201) {
                        response.json()
                            .then(res => {
                                this.messages.push(res.data);
                                this.currentThread.preview = res.data.body;
                                this.currentThread.last_updated_at = res.data.created_at;
                                this.sortThreads();
                                this.scrollMessagesToBottom();
                            });
                    } else if (response.status == 422) {
                        response.json()
                            .then(res => console.log(res.errors));
                    } else {
                        alert('Fatal error, could not save record');
                    }
                })
                .catch((err) => console.log(err));

            this.composeBodyText = null;
            this.filesForUpload = [];
        },

        //function used for loading the message listing for a thread
        //either on initial load or multiple times for pagination
        loadMessages: async function () {

            const url = `${this.urls.show}?page=${this.messagesPage}`
                .replace('123', this.currentThread.phone_number);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .catch((err) => console.log(err));

            let json = await response.json();
            return json;
        },

        addMessages: async function () {

            if(this.currentThread.is_new) return;

            this.loadMessages()
                .then(json => {
                    this.lastMessagesPage = json.meta.last_page;
                    json.data.forEach(item => this.messages.unshift(item));
                    this.afterMessagesAdded();
                    this.messagesPage++;
                    if (this.messagesPage <= this.lastMessagesPage) {
                        this.showMessagesInfiniteScroll = true;
                    } else {
                        this.showMessagesInfiniteScroll = false;
                    }
                });
        },

        afterMessagesAdded: function()
        {
            if (this.messagesPage < 3)
                this.scrollMessagesToBottom();
            this.setupComposerEnviornment();
        },

        fetchNumbers: async function(numbersPage)
        {
            if (typeof (numbersPage) === 'undefined') numbersPage = 1;

            const response = await fetch(`${this.urls.numbers_index}?page=${numbersPage}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .catch((err) => console.log(err));

            let json = await response.json();

            json.data.forEach(item => {
                this.newMessageFromNumberOptions.push(item);
            });

            if(numbersPage < json.meta.last_page){
                this.fetchNumbers((numbersPage+1));
            }
        },

        scrollMessagesToBottom: function()
        {
            setTimeout(() => {
                let el = document.getElementById('messages');
               el.scrollTo({ top: (el.clientHeight *100) });
            }, 250)
        },

        openThread: function(thread)
        {
            this.setCurrentThread(thread);
            this.threadOpen = !this.threadOpen;
        },

        setupComposerEnviornment: function()
        {
            if(this.currentThread.send_from_number){
                this.sendingFromName = this.currentThread.send_from_number.friendly_label;
                this.sendingFromNumber = this.currentThread.send_from_number.phone_number;
            } else {
                if(this.messages.length == 0) return;

                let last = JSON.parse(JSON.stringify(this.messages[0]));
                let number = this.newMessageFromNumberOptions.find(n => n.id == last.number_id);
                if(number){
                    this.sendingFromName = number.friendly_label;
                    this.sendingFromNumber = number.phone_number;
                } else {
                    this.sendingFromNumber = last.direction == 'outbound' ? last.from : last.to;
                }
            }
        },

        resetCurrentThread: function()
        {
            this.currentThread = null;
            this.messagesPage = 1;
            this.messages = [];
        },

        setCurrentThread: function(thread)
        {
            this.sendingFromName = null;
            this.sendingFromNumber = null;
            this.currentThread = thread;
            this.messagesPage = 1;
            this.messages = [];
            this.addMessages();
            setTimeout(() => this.setupComposerEnviornment(), 500);
        },

        sortThreads: function()
        {
            this.records = this.records.sort((a,b) => {
                let da = dayjs(a.last_updated_at),
                    db = dayjs(b.last_updated_at);

                if (da.isAfter(db)) {
                    return -1;
                }
                if (da.isBefore(db)) {
                    return 1;
                }
                return 0;
            });
        },

        backToSummary: function()
        {
            this.sendingFromName = null;
            this.sendingFromNumber = null;
            this.threadOpen = !this.threadOpen;
        },

        renderContact: function(thread)
        {
            if(!thread) return "";
            let contact = thread.contact;

            if(!thread.contact){
                return thread.phone_number;
            } else if (contact.first_name && contact.last_name) {
                return contact.first_name +" "+ contact.last_name;
            } else if (contact.first_name && !contact.last_name) {
                return contact.first_name;
            } else if (!contact.first_name && !contact.last_name) {
                return contact.company;
            }
        },

        renderThreadTime: function(thread)
        {
            if (typeof dayjs == 'function')
                return dayjs().diff(dayjs(thread.last_updated_at), 'day') < 1 ?
                    dayjs(thread.last_updated_at).format('h:mm a') :
                    dayjs(thread.last_updated_at).format('MM/DD');
            else
                return 'undefined';
        },

        renderMessageTime: function(message, index)
        {
            if(message == undefined) return;

            if (typeof dayjs == 'function'){
                if(index == 0 || this.messages[index-1] == undefined)
                    return dayjs(message.created_at).format('MMM D, h:mm a');

                let from = dayjs(this.messages[index-1].created_at);
                let diff = dayjs(message.created_at).diff(from, 'second');

                if(diff < 60)
                    return null;
                else
                    return dayjs(message.created_at).format('MMM D, h:mm a');

            } else
                return 'undefined';
        },

        renderPreview: function(thread)
        {
            return this.isValidHttpUrl(thread.preview)
                ? 'Attachment' : thread.preview;
        },

        isValidHttpUrl: function(string) {
            let url;

            try {
                url = new URL(string);
            } catch (_) {
                return false;
            }

            return url.protocol === "http:" || url.protocol === "https:";
        },

        getAvatarLetters: function(thread)
        {
            if(!thread) return "??";
            let letters = [];
            let contact = null;

            if(thread.contact)
                contact = thread.contact;
            else{
                letters = ["?", "?"];
                return letters.join("");
            }

            if (contact.first_name && contact.last_name) {
                letters.push(contact.first_name.charAt(0));
                letters.push(contact.last_name.charAt(0));
            } else if (contact.first_name && !contact.last_name) {
                letters.push(contact.first_name.charAt(0));
            } else if (!contact.first_name && !contact.last_name) {
                letters.push(contact.company.charAt(0));
            }

            return letters.join("");
        },

        confirmDelete: function () {
            this.beforeDelete();
            this.deleting = true;
            const deleteId = this.currentThread.id;
            fetch(this.urls.delete.replace('123', this.currentThread.phone_number), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            })
                .then((response) => {
                    if (response.status == 204) {
                        this.records = this.records.filter(record => record.id != deleteId);
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        this.resetCurrentThread();
                        this.afterDelete();
                    } else {
                        this.deleteConfirmOpen = false;
                        this.deleting = false;
                        setTimeout(() => alert('Error, could not delete record'), 500);
                    }
                })
                .catch((err) => console.log(err));
        },

        markAsRead: function(id)
        {
            if(id == null) return;

            const idx = this.messages.findIndex(m => m.id == id);

            if (this.messages[idx].read == false){
                this.messages[idx].read = true;
                this.currentThread.unread--;

                fetch(this.urls.update_message.replace('123', id), {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({'read': true})
                })
                    .then(response => {

                        if (response.status == 200) {
                            //do nothing
                        } else if (response.status == 422) {
                            response.json().then(res => console.log(res.errors));
                            alert('Error marking as read');
                        } else {
                            alert('Fatal error, could not save record');
                        }

                    })
                    .catch((err) => console.log(err));
            }
        },

        renderMessageContent: function(message)
        {
            let result = '';

            if(message.body != null){
                result = '<p>' + message.body.replace(/\n/g, "<br />") + '</p>';
            }

            if(message.media != null){

                const getQueryParams = (params, url) => {
                    let href = url;
                    //this expression is to get the query strings
                    let reg = new RegExp('[?&]' + params + '=([^&#]*)', 'i');
                    let queryString = reg.exec(href);
                    return queryString ? queryString[1] : null;
                };

                message.media.forEach((url) => {
                    if (
                        (url.match(/\.(jpeg|jpg|gif|png)/) != null)
                        || (getQueryParams('Content-Type', url) && getQueryParams('Content-Type', url).includes('image/'))
                    ) {
                        //image
                        result += `<a href="${url}" target="_blank"><img src="${url}" class="h-24 w-24" /></a>`;
                    } else {
                        //url that is not an image
                        result += `<a href="${url}">[ Link ]</a>`;
                    }
                });
            }

            return result;
        }
    }
}