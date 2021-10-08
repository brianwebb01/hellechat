window.manageContacts = function() {
    return {
        showingContact: false,
        groupedRecords: [],
        showInfiniteScroll: false,
        page: 1,
        lastPage: null,

        showContact: function(id) {
            this.showingContact = true;
        },

        hideContact: function() {
            this.showingContact = false;
        },

        loadPaginatedRecords: async function(){
            const response = await fetch(`/api/contacts?page=${this.page}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .catch((err) => console.log(err));

            let json = await response.json();
            return json;
        },

        addRecords: async function() {

            this.loadPaginatedRecords()
                .then(json => {
                    this.lastPage = json.meta.last_page;
                    console.log(this.records);
                    console.log(json.data);
                    this.records.push(json.data);
                    //this.setGroupedRecords(this.groupByLastFirst(this.records));
                    if(this.page == 1){
                        this.setGroupedRecords(this.groupByLastFirst(json.data));
                    }

                    this.page++;
                    if(this.page < this.lastPage){
                        this.showInfiniteScroll = true;
                    } else{
                        this.showInfiniteScroll = false;
                    }
                });
        },

        groupByLastFirst: function(array) {
            return array.reduce((acc, obj) => {
                const letter = (obj.last_name ? obj.last_name.charAt(0)
                    : (obj.first_name ? obj.first_name.charAt(0)
                        : obj.company.charAt(0)));
                acc[letter] = acc[letter] || [];
                acc[letter].push(obj);
                return acc;
            }, {});
        },

        setGroupedRecords(data) {
            this.groupedRecords = data;
        }
    }
}