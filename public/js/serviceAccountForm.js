/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/serviceAccountForm.js ***!
  \********************************************/
window.serviceAccountForm = function (data) {
  return {
    serviceAccounts: [],
    fetchServiceAccounts: function fetchServiceAccounts() {
      var _this = this;

      fetch(data.serviceAccountForm.urls.index, {
        headers: {
          'Accept': 'application/json'
        }
      }).then(function (response) {
        return response.json();
      }).then(function (response) {
        return _this.serviceAccounts = response.data;
      })["catch"](function (err) {
        return console.log(err);
      });
    }
  };
};
/******/ })()
;