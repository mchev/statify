<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ScriptController extends Controller
{
    public function show(Request $request, $scriptName)
    {
        $customScriptName = config('counted.script_name');
        $scriptPath = public_path('script.js');

        if ($scriptName === $customScriptName && File::exists($scriptPath)) {
            $scriptContent = File::get($scriptPath);

            return Response::make($scriptContent, 200, [
                'Content-Type' => 'application/javascript',
            ]);
        }

        abort(404);
    }
}
