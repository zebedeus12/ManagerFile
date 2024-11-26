<?php

namespace App\Models;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'accessibility', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relasi ke parent folder
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relasi ke file (jika folder memiliki file)
    public function files()
    {
        return $this->hasMany(File::class);
    }


    public function updateFile(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $file->update($request->all());

        // Tambahkan notifikasi
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'update',
            'message' => "File '{$file->name}' berhasil diperbarui.",
        ]);

        return redirect()->back()->with('success', 'File berhasil diperbarui.');
    }

}
