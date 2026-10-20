<?php

namespace App\Observers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\History;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HistoryObserver
{
    public function created(Model $model): void
    {
        $this->record($model, 'ditambahkan');
    }

    public function updated(Model $model): void
    {
        $changedFields = array_diff(array_keys($model->getChanges()), ['updated_at', 'tersedia']);
        if ($changedFields === []) {
            return;
        }

        $this->record($model, 'diperbarui', 'Perubahan: ' . implode(', ', $changedFields));
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'dihapus');
    }

    private function record(Model $model, string $action, ?string $extra = null): void
    {
        $label = $this->modelLabel($model);
        $identity = $this->modelIdentity($model);
        $description = "{$label} {$identity} {$action}.";

        if ($extra !== null) {
            $description .= ' ' . $extra . '.';
        }

        History::create([
            'jenis' => 'aktivitas',
            'judul' => ucfirst($label) . ' ' . ucfirst($action),
            'keterangan' => $description,
            'user_id' => Auth::id(),
        ]);
    }

    private function modelLabel(Model $model): string
    {
        return match (true) {
            $model instanceof Book => 'Buku',
            $model instanceof Member => 'Anggota',
            $model instanceof Category => 'Kategori',
            $model instanceof Author => 'Penulis',
            $model instanceof Publisher => 'Penerbit',
            $model instanceof Shelf => 'Rak',
            $model instanceof User => 'Pengguna',
            default => class_basename($model),
        };
    }

    private function modelIdentity(Model $model): string
    {
        $identity = match (true) {
            $model instanceof Book => $model->judul ?: $model->kode_buku,
            $model instanceof Member => $model->nama ?: $model->kode_member,
            $model instanceof Category => $model->nama_kategori ?: $model->kode_kategori,
            $model instanceof Author => $model->nama_penulis ?: $model->kode_penulis,
            $model instanceof Publisher => $model->nama_penerbit ?: $model->kode_penerbit,
            $model instanceof Shelf => $model->nama_rak ?: $model->kode_rak,
            $model instanceof User => $model->name ?: $model->email,
            default => null,
        };

        return '"' . ($identity ?: '#' . $model->getKey()) . '"';
    }
}
