<x-app-layout>

    <div x-data="initCrudForm({{ json_encode([
        'urls' => [
            'index' => route('voicemails.index'),
            'delete' => route('voicemails.destroy', [123])],
        'csrf_token' => csrf_token()
            ]) }} )" x-init="">

        <div x-data="voicemailInteraction()" x-init="initVoicemail(); addRecords();">
            @include('voicemail-manager._heading')

            @include('voicemail-manager._list')

            @include('voicemail-manager._show')

            @include('confirm-delete')
        </div>

        <script src="{{ mix('js/crudForm.js') }}"></script>
        <script src="{{ mix('js/voicemailManagement.js') }}"></script>

    </div>
</x-app-layout>