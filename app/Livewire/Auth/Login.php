<?php
namespace App\Livewire\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Menu;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Livewire\Component;

class Login extends Component
{
    public $username, $password;

    public function authenticate()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = new LoginRequest;

        $request = new Request([
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $credentials->replace($request->all());

        $menus = Menu::with('child')->orderBy('position', 'ASC')->get();
        session()->put('menus', $menus);

        $credentials->authenticate();
        return $this->redirect(RouteServiceProvider::HOME, navigate: true);
        // return redirect()->intended();

    }
    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.front.main');
    }
}
