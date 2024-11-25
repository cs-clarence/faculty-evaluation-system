<div class="contents">
    <form wire:submit.prevent="save">
        @csrf
        <input type="hidden" name="id" wire:model.defer="form.id">
        <div class="mb-4">
            <label for="code" class="block text-gray-700">Email</label>
            <input type="text" name="code" id="subjectID" required class="w-full px-3 py-2 border rounded-lg"
                wire:model.defer="form.code">
            @error('form.email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" name="name" id="subjectName" required class="w-full px-3 py-2 border rounded-lg"
                wire:model.defer="form.name">
            @error('form.name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-end">
            <button type="button" id="cancelBtn" wire:click='closeForm'
                class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
        </div>
    </form>
</div>
