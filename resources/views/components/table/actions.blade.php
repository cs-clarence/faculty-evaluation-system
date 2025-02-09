@props(['data'])

<button wire:click='edit({{ $data->id }})' class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
    Edit
</button>
@if ($data->is_archived)
    <button wire:click='unarchive({{ $data->id }})'
        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
        Unarchive
    </button>
@else
    <button wire:click='archive({{ $data->id }})'
        class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
        title="This department has courses associated with it. You can only archive it until you delete those courses.">
        Archive
    </button>
@endif
@if (!$data->hasDependents())
    <button wire:click='delete({{ $data->id }})' wire:confirm='Are you sure you want to delete this subject?'
        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
        Delete
    </button>
@endif
