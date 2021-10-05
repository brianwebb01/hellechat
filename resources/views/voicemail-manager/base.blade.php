<x-app-layout>

    <div x-data="{ showVoicemailOpen: false }">

        @include('voicemail-manager._heading')

        @include('voicemail-manager._list')

        @include('voicemail-manager._show')

    </div>
</x-app-layout>