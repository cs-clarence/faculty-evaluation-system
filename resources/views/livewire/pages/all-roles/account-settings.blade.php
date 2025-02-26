@php
@endphp

<div>
    <x-sections.header title="Account Settings" />
    <div class="flex flex-row flex-wrap justify-center w-full gap-4">
        <form class="max-w-[420px] w-full bg-white p-6 rounded-md shadow-md flex flex-col gap-2"
            wire:submit="updateUserDetails">
            <h2 class="text-lg font-bold">User Details</h2>
            <x-form-control>
                <x-form-control.label key="user.email">Email</x-form-control.label>
                <x-input key="user.email" wire:model="user.email" type="email" :disabled="!Gate::allows('updateEmail', $userModel)" />
                <x-form-control.error-text key="user.email" />
            </x-form-control>
            <x-form-control>
                <x-form-control.label key="user.email">Name</x-form-control.label>
                <x-input key="user.name" wire:model="user.name" type="text" :disabled="!Gate::allows('updateEmail', $userModel)" />
                <x-form-control.error-text key="user.name" />
            </x-form-control>
            @can(['updateEmail', 'updateName'], $userModel)
                <x-dialog.actions>
                    <x-button>
                        Save
                    </x-button>
                </x-dialog.actions>
            @else
                <p class="text-gray-600">You are not allow to update these information</p>
            @endcan
        </form>
        <form class="max-w-[420px] w-full bg-white p-6 rounded-md shadow-md flex flex-col gap-2"
            wire:submit="updatePassword">
            <h2 class="text-lg font-bold">Password</h2>
            <x-form-control>
                <x-form-control.label key="user.password">Password</x-form-control.label>
                <x-input key="user.password" wire:model="user.password" type="password" />
                <x-form-control.error-text key="user.password" />
            </x-form-control>
            <x-form-control>
                <x-form-control.label key="user.password_confirmation">Confirm Password</x-form-control.label>
                <x-input key="user.password_confirmation" wire:model="user.password_confirmation" type="password" />
                <x-form-control.error-text key="user.password_confirmation" />
            </x-form-control>
            <x-dialog.actions>
                <x-button>
                    Save
                </x-button>
            </x-dialog.actions>
        </form>
    </div>
</div>
