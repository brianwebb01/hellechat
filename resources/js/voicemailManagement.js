window.voicemailInteraction = function() {
    return {
        playing: false,
        elapsed: '0:00',
        audio: null,
        unfilteredRecords: [],

        initVoicemail: function() {
            window.addEventListener('hashchange', () => this.filterByNumberId());
        },

        afterRecordsAdded: function() {
            if (window.location.hash.substr(0, 9) == '#numbers-') {
                this.filterByNumberId();
            }
        },

        filterByNumberId: function() {
            const numberId = window.location.hash.substr(1).replace('numbers-', '');

            let toAdd = this.records.filter(r => !this.unfilteredRecords.map(ufr => ufr.id).includes(r.id) )
            this.unfilteredRecords = [...this.unfilteredRecords, ...toAdd];

            if(numberId == 'all'){
                this.records = this.unfilteredRecords;
            } else if(Number(numberId) > 0) {
                this.records = this.unfilteredRecords.filter(v =>
                    v.number_id == Number(numberId))
            }
        },

        afterSetCurrentRecord: function () {
            this.audio = new Audio(this.currentRecord.media_url);
            this.audio.currentTime = 0;
            this.setScrubBarTime(0);
            this.audio.addEventListener('timeupdate', () => {
                this.onAudioTimeUpdate();
            });
            this.audio.addEventListener('ended', () => {
                this.onAudioEnded();
            });
            this.configureScrubber();
        },

        configureScrubber: function () {
            let scrubber = document.querySelector('#scrubber');

            scrubber.removeEventListener('mousedown', (event) => {
                this.scrubStart(event);
            });
            scrubber.removeEventListener('mouseup', (event) => {
                this.scrubEnd(event);
            });
            scrubber.removeEventListener('touchstart', (event) => {
                this.scrubStart(event);
            });
            scrubber.removeEventListener('touchend', (event) => {
                this.scrubEnd(event);
            });

            scrubber.addEventListener('mousedown', (event) => {
                this.scrubStart(event);
            });
            scrubber.addEventListener('mouseup', (event) => {
                this.scrubEnd(event);
            });
            scrubber.addEventListener('touchstart', (event) => {
                this.scrubStart(event);
            });
            scrubber.addEventListener('touchend', (event) => {
                this.scrubEnd(event);
            });
        },

        scrubStart: function (event) {
            if (this.playing)
                this.audio.pause();
        },

        scrubEnd: function (event) {
            let percent = event.target.value
            let seconds = Math.floor((percent / 100) * this.currentRecord.length);
            this.audio.currentTime = seconds;

            if (this.playing && this.audio.paused)
                this.audio.play();
        },

        onAudioTimeUpdate: function () {
            this.setScrubBarTime(this.audio.currentTime);
        },

        onAudioEnded: function () {
            this.audio.currentTime = 0;
            this.playing = false;
            this.setScrubBarTime(0);
        },

        setScrubBarTime: function (time) {
            this.elapsed = this.renderTimeLength(time);
            let percent = Math.floor((time / this.currentRecord.length) * 100);
            document.querySelector('#scrubber').value = percent;
        },

        playPause: function () {
            this.playing = !this.playing;
            if (this.playing) {
                this.audio.play();
            } else {
                this.audio.pause();
            }
        },

        renderContact: function (vm) {
            if (!vm.contact) {
                return vm.from;
            } else if (vm.contact.first_name && vm.contact.last_name) {
                return vm.contact.first_name + ' ' + vm.contact.last_name;
            } else if (vm.contact.first_name && !vm.contact.last_name) {
                return vm.contact.first_name
            } else if (!vm.contact.first_name && !vm.contact.last_name) {
                return vm.contact.company;
            }
        },

        renderTimeLength(time) {
            let minutes = Math.floor(time / 60);
            let seconds = Math.floor(time - minutes * 60);
            return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        },

        renderDate(dateStr, format) {
            let fmt;
            if (format == 'short') {
                fmt = 'MM/DD h:mm a';
            } else if (format == 'long') {
                fmt = "MMMM D, YYYY @ h:mm a";
            } else {
                fmt = format;
            }

            if (typeof dayjs == 'function')
                return dayjs(dateStr).format(fmt);
            else
                return 'undefined';
        }
    }
}