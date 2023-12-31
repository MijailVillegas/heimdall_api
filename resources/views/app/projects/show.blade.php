@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('projects.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.projects.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.projects.inputs.user_id')</h5>
                    <span>{{ optional($project->user)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.projects.inputs.name')</h5>
                    <span>{{ $project->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.projects.inputs.xml_board')</h5>
                    <span>{{ $project->xml_board ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('projects.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    @can('view-any', App\Models\Indicator::class)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title w-100 mb-2">Indicators</h4>

            <livewire:project-indicators-detail :project="$project" />
        </div>
    </div>
    @endcan @can('view-any', App\Models\Thumbnail::class)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title w-100 mb-2">Thumbnails</h4>

            <livewire:project-thumbnails-detail :project="$project" />
        </div>
    </div>
    @endcan
</div>
@endsection
