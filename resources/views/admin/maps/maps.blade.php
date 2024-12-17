@extends('admin.layout')

@section('admin-title')
    Character Maps
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Character Maps' => 'admin/data/maps']) !!}

    <h1>Character Maps</h1>

    <p>This is a list of genetic maps that can be applied to characters. </p>

    <div class="text-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/map-categories') }}"><i class="fas fa-folder"></i> Map Categories</a>
        <a class="btn btn-primary" href="{{ url('admin/data/maps/create') }}"><i class="fas fa-plus"></i> Create New Map</a>
    </div>

    @if (!count($maps))
        <p>No maps found.</p>
    @else
        <div class="mb-4 logs-table">
            <div class="logs-table-header">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="logs-table-cell">ID</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="logs-table-cell">Category</div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="logs-table-cell">Genes</div>
                    </div>
                </div>
            </div>
            <div class="logs-table-body">
                @foreach ($maps as $map)
                    <div class="logs-table-row">
                        <div class="row flex-wrap">
                            <div class="col-12 col-md-2">
                                <div class="logs-table-cell">
                                    {{ $map->id }}
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="logs-table-cell">{!! $map->category->displayName !!}</div>
                            </div>
                            <div class="col-6 col-md-5">
                                <div class="logs-table-cell">---</div>
                            </div>
                            <div class="col-12 col-md-1">
                                <div class="logs-table-cell"><a href="{{ url('admin/data/maps/edit/' . $map->id) }}" class="btn btn-primary py-0 px-1 w-100">Edit</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- <div class="text-center mt-4 small text-muted">{{ $maps->total() }} result{{ $maps->total() == 1 ? '' : 's' }} found.</div> --}}
    @endif

@endsection

@section('scripts')
    @parent
@endsection
