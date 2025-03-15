<?php
namespace App\Livewire\Pages\AllRoles;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccountSettings extends Component
{
    public User $userModel;
    public UserForm $user;

    public function mount()
    {
        $this->userModel = Auth::user();
        $this->user->set($this->userModel);
    }

    public function render()
    {
        $view = view('livewire.pages.all-roles.account-settings', [
        ]);

        if ($this->userModel->isAdmin()
            || $this->userModel->isHumanResourcesStaff()
            || $this->userModel->isRegistrar()) {
            return $view->layout('components.layouts.admin');
        } else {
            return $view->layout('components.layouts.user');
        }
    }

    public function updateUserDetails()
    {
        $this->user->include_password = false;
        $this->user->include_base     = true;
        $this->user->submit();

        session()->flash('alert-success', [
            'text' => 'User details updated successfully',
        ]);

        return $this->redirectRoute('account-settings');
    }

    public function updatePassword()
    {
        $this->user->include_password = true;
        $this->user->include_base     = false;
        $this->user->submit();
        session()->flash('alert-success', [
            'text' => 'Password updated successfully',
        ]);
        return $this->redirectRoute('account-settings');
    }
}
