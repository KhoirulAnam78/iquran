<?php
namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search = '', $perPage = 10, $delete_id;

    public function showDelete($id)
    {
        $decrypt         = Crypt::decryptString($id);
        $this->delete_id = $decrypt;

        $user = User::find($decrypt);
        session()->flash('delete-confirm', 'Apakah anda yakin menghapus user dari aplikasi ini "' . $user->name . '" ?');
    }

    public function delete()
    {
        DB::transaction(function () {
            $user = User::find($this->delete_id);
            $user->delete();
            session()->flash('alert', 'User successfully deleted !');
        });
    }
    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->with('roles')
            ->paginate($this->perPage);

        return view('livewire.users.index', compact('users'))->layout('layouts.dashboard.main');
    }
}
