window.serviceAccountForm = function(data) {
    return {
        serviceAccounts: [],
        fetchServiceAccounts: function () {
            fetch(data.serviceAccountForm.urls.index, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then((response) => response.json())
                .then((response) => this.serviceAccounts = response.data)
                .catch((err) => console.log(err));
        }
    }
}