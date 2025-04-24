<?php

namespace App\Http\Controllers;

use App\Models\MyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MyClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $clients = MyClient::whereNull('deleted_at')->get();
        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:250',
            'slug' => 'required|string|max:100|unique:my_client',
            'is_project' => 'required|in:0,1',
            'self_capture' => 'required|in:0,1',
            'client_prefix' => 'required|string|max:4',
            'client_logo' => 'nullable|image',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
        ]);
    
        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = 'clients/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        
            try {
                // Coba upload ke S3
                $path = Storage::disk('s3')->put($filename, file_get_contents($file));
                $data['client_logo'] = Storage::disk('s3')->url($filename);
            } catch (\Exception $e) {
                // Jika gagal, simpan ke lokal
                $localPath = $file->storeAs('public/clients', basename($filename));
                $data['client_logo'] = asset('storage/clients/' . basename($filename));
            }
        } else {
            $data['client_logo'] = 'no-image.jpg';
        }
    
        $data['created_at'] = now();
        $client = MyClient::create($data);
    
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $client = MyClient::where('id', $id)->whereNull('deleted_at')->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MyClient $myClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $client = MyClient::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:250',
            'slug' => 'sometimes|string|max:100|unique:my_client,slug,' . $id,
            'is_project' => 'sometimes|in:0,1',
            'self_capture' => 'sometimes|in:0,1',
            'client_prefix' => 'sometimes|string|max:4',
            'client_logo' => 'nullable|image',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
        ]);
    
        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = 'clients/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        
            try {
                // Coba upload ke S3
                $path = Storage::disk('s3')->put($filename, file_get_contents($file));
                $data['client_logo'] = Storage::disk('s3')->url($filename);
            } catch (\Exception $e) {
                // Jika gagal, simpan ke lokal
                $localPath = $file->storeAs('public/clients', basename($filename));
                $data['client_logo'] = asset('storage/clients/' . basename($filename));
            }
        }
    
        $data['updated_at'] = now();
        $client->update($data);
    
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $client = MyClient::findOrFail($id);
        $client->update(['deleted_at' => now()]);
        Redis::del($client->slug);

        return response()->json(['message' => 'Soft deleted']);
    }
}
