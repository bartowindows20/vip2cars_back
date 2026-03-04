<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function create(array $data): Client
    {
        $client = Client::withTrashed()
            ->where('document_type', $data['document_type'])
            ->where('document_number', $data['document_number'])
            ->first();

        if ($client) {
            $client->restore();
            $client->update($data);
            return $client;
        }

        return Client::create($data);
    }

    public function update(array $data, Client $client): Client
    {
        return DB::transaction(function () use ($data, $client) {

            $client = Client::where('id', $client->id)->lockForUpdate()->first();

            $client->update($data);
            $client->refresh();

            return $client;
        });
    }

    public function delete(Client $client): void
    {
        DB::transaction(function () use ($client) {
            $client->cars()->delete();
            $client->delete();
        });
    }
}
