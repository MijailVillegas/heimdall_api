<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\Thumbnail;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectThumbnailsDetail extends Component
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    public Project $project;
    public Thumbnail $thumbnail;
    public $thumbnailImage;
    public $uploadIteration = 0;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Thumbnail';

    protected $rules = [
        'thumbnailImage' => ['nullable', 'image', 'max:1024'],
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->resetThumbnailData();
    }

    public function resetThumbnailData(): void
    {
        $this->thumbnail = new Thumbnail();

        $this->thumbnailImage = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newThumbnail(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.project_thumbnails.new_title');
        $this->resetThumbnailData();

        $this->showModal();
    }

    public function editThumbnail(Thumbnail $thumbnail): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.project_thumbnails.edit_title');
        $this->thumbnail = $thumbnail;

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

        if (!$this->thumbnail->project_id) {
            $this->authorize('create', Thumbnail::class);

            $this->thumbnail->project_id = $this->project->id;
        } else {
            $this->authorize('update', $this->thumbnail);
        }

        if ($this->thumbnailImage) {
            $this->thumbnail->image = $this->thumbnailImage->store('public');
        }

        $this->thumbnail->save();

        $this->uploadIteration++;

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Thumbnail::class);

        collect($this->selected)->each(function (string $id) {
            $thumbnail = Thumbnail::findOrFail($id);

            if ($thumbnail->image) {
                Storage::delete($thumbnail->image);
            }

            $thumbnail->delete();
        });

        $this->selected = [];
        $this->allSelected = false;

        $this->resetThumbnailData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->project->thumbnails as $thumbnail) {
            array_push($this->selected, $thumbnail->id);
        }
    }

    public function render(): View
    {
        return view('livewire.project-thumbnails-detail', [
            'thumbnails' => $this->project->thumbnails()->paginate(20),
        ]);
    }
}
