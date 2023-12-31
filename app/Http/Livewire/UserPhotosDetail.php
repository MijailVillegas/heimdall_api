<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Photo;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserPhotosDetail extends Component
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    public User $user;
    public Photo $photo;
    public $photoImage;
    public $uploadIteration = 0;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Photo';

    protected $rules = [
        'photoImage' => ['nullable', 'image', 'max:1024'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->resetPhotoData();
    }

    public function resetPhotoData(): void
    {
        $this->photo = new Photo();

        $this->photoImage = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newPhoto(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.user_photos.new_title');
        $this->resetPhotoData();

        $this->showModal();
    }

    public function editPhoto(Photo $photo): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_photos.edit_title');
        $this->photo = $photo;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->photo->user_id) {
            $this->authorize('create', Photo::class);

            $this->photo->user_id = $this->user->id;
        } else {
            $this->authorize('update', $this->photo);
        }

        if ($this->photoImage) {
            $this->photo->image = $this->photoImage->store('public');
        }

        $this->photo->save();

        $this->uploadIteration++;

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Photo::class);

        collect($this->selected)->each(function (string $id) {
            $photo = Photo::findOrFail($id);

            if ($photo->image) {
                Storage::delete($photo->image);
            }

            $photo->delete();
        });

        $this->selected = [];
        $this->allSelected = false;

        $this->resetPhotoData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->user->photos as $photo) {
            array_push($this->selected, $photo->id);
        }
    }

    public function render(): View
    {
        return view('livewire.user-photos-detail', [
            'photos' => $this->user->photos()->paginate(20),
        ]);
    }
}
