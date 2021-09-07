<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\VoicemailCollection;
use App\Http\Resources\Api\VoicemailResource;
use App\Models\Voicemail;
use Illuminate\Http\Request;

class VoicemailController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Voicemail::class, 'voicemail');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\VoicemailCollection
     */
    public function index(Request $request)
    {
        $voicemails = $request->user()->voicemails()->paginate();

        return new VoicemailCollection($voicemails);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Voicemail $voicemail
     * @return \App\Http\Resources\Api\VoicemailResource
     */
    public function show(Request $request, Voicemail $voicemail)
    {
        return new VoicemailResource($voicemail);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Voicemail $voicemail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Voicemail $voicemail)
    {
        $voicemail->delete();

        return response()->noContent();
    }
}
