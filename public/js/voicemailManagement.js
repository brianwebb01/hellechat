/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/voicemailManagement.js ***!
  \*********************************************/
window.voicemailInteraction = function () {
  return {
    playing: false,
    elapsed: '0:00',
    audio: null,
    afterSetCurrentRecord: function afterSetCurrentRecord() {
      var _this = this;

      this.audio = new Audio(this.currentRecord.media_url);
      this.audio.currentTime = 0;
      this.setScrubBarTime(0);
      this.audio.addEventListener('timeupdate', function () {
        _this.onAudioTimeUpdate();
      });
      this.audio.addEventListener('ended', function () {
        _this.onAudioEnded();
      });
      this.configureScrubber();
    },
    configureScrubber: function configureScrubber() {
      var _this2 = this;

      var scrubber = document.querySelector('#scrubber');
      scrubber.removeEventListener('mousedown', function (event) {
        _this2.scrubStart(event);
      });
      scrubber.removeEventListener('mouseup', function (event) {
        _this2.scrubEnd(event);
      });
      scrubber.removeEventListener('touchstart', function (event) {
        _this2.scrubStart(event);
      });
      scrubber.removeEventListener('touchend', function (event) {
        _this2.scrubEnd(event);
      });
      scrubber.addEventListener('mousedown', function (event) {
        _this2.scrubStart(event);
      });
      scrubber.addEventListener('mouseup', function (event) {
        _this2.scrubEnd(event);
      });
      scrubber.addEventListener('touchstart', function (event) {
        _this2.scrubStart(event);
      });
      scrubber.addEventListener('touchend', function (event) {
        _this2.scrubEnd(event);
      });
    },
    scrubStart: function scrubStart(event) {
      if (this.playing) this.audio.pause();
    },
    scrubEnd: function scrubEnd(event) {
      var percent = event.target.value;
      var seconds = Math.floor(percent / 100 * this.currentRecord.length);
      this.audio.currentTime = seconds;
      if (this.playing && this.audio.paused) this.audio.play();
    },
    onAudioTimeUpdate: function onAudioTimeUpdate() {
      this.setScrubBarTime(this.audio.currentTime);
    },
    onAudioEnded: function onAudioEnded() {
      this.audio.currentTime = 0;
      this.playing = false;
      this.setScrubBarTime(0);
    },
    setScrubBarTime: function setScrubBarTime(time) {
      this.elapsed = this.renderTimeLength(time);
      var percent = Math.floor(time / this.currentRecord.length * 100);
      document.querySelector('#scrubber').value = percent;
    },
    playPause: function playPause() {
      this.playing = !this.playing;

      if (this.playing) {
        this.audio.play();
      } else {
        this.audio.pause();
      }
    },
    renderContact: function renderContact(vm) {
      if (!vm.contact) {
        return vm.from;
      } else if (vm.contact.first_name && vm.contact.last_name) {
        return vm.contact.first_name + ' ' + vm.contact.last_name;
      } else if (vm.contact.first_name && !vm.contact.last_name) {
        return vm.contact.first_name;
      } else if (!vm.contact.first_name && !vm.contact.last_name) {
        return vm.contact.company;
      }
    },
    renderTimeLength: function renderTimeLength(time) {
      var minutes = Math.floor(time / 60);
      var seconds = Math.floor(time - minutes * 60);
      return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    },
    renderDate: function renderDate(dateStr, format) {
      var fmt;

      if (format == 'short') {
        fmt = 'MM/DD h:mm a';
      } else if (format == 'long') {
        fmt = "MMMM D, YYYY @ h:mm a";
      } else {
        fmt = format;
      }

      if (typeof dayjs == 'function') return dayjs(dateStr).format(fmt);else return 'undefined';
    }
  };
};
/******/ })()
;