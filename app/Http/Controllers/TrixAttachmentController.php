<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrixAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachment' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('attachment')->store('trix-attachments', 'public');

        return response()->json([
            'url' => '/storage/' . $path,
        ]);
    }
}
