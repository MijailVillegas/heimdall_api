<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\Indicator;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectIndicatorsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Project $project;
    public Indicator $indicator;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Indicator';

    protected $rules = [
        'indicator.name' => ['required', 'max:255', 'string'],
        'indicator.value' => ['required', 'numeric'],
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->resetIndicatorData();
    }

    public function resetIndicatorData(): void
    {
        $this->indicator = new Indicator();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newIndicator(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.project_indicators.new_title');
        $this->resetIndicatorData();

        $this->showModal();
    }

    public function editIndicator(Indicator $indicator): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.project_indicators.edit_title');
        $this->indicator = $indicator;

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

        if (!$this->indicator->project_id) {
            $this->authorize('create', Indicator::class);

            $this->indicator->project_id = $this->project->id;
        } else {
            $this->authorize('update', $this->indicator);
        }

        $this->indicator->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Indicator::class);

        Indicator::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetIndicatorData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->project->indicators as $indicator) {
            array_push($this->selected, $indicator->id);
        }
    }

    public function render(): View
    {
        return view('livewire.project-indicators-detail', [
            'indicators' => $this->project->indicators()->paginate(20),
        ]);
    }
}
