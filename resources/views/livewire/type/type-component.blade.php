<div>
    <div class="card">
        <div class="card-header bg-secondary">
            <div class="row">
                <div class="col-8">
                    <h4 class="text-uppercase">Lista de Tipos</h4>
                </div>
                <div class="col-4">
                    @can('haveaccess', 'type.create')
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createType">Agregar Tipo</button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="form-group">
                    <div class="row">
                        <div class="col-8">
                            <input type="text" class="form-control" placeholder="Buscar" wire:model="search" wire:dirty.class="bg-secondary">
                        </div>
                        <div class="col-3 justify-content-end">
                            <select class="form-control" wire:model="perPage">
                                <option value="10">10 por página</option>
                                <option value="25">25 por página</option>
                                <option value="50">50 por página</option>
                                <option value="100">100 por página</option>
                            </select>
                        </div>
                        @if ($search !== '')
                            <div wire:click="clear" class="col-1">
                                <button class="btn btn-light">X</button>
                            </div>
                        @endif
                    </div>
                </div>
                <table wire:poll.10000ms id="typeTable" class="table table-white table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Creado</th>
                            <th scope="col">Actualizado</th>
                            <th scope="colgroup">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td>{{ $type->description }}</td>
                                <td>
                                    @if ($type->status == "1")
                                        Activo
                                    @else
                                        Inactivo
                                    @endif
                                </td>
                                <td>{{ $type->created_at->diffForHumans() }}</td>
                                <td>{{ $type->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('haveaccess', 'type.show')
                                            <button type="button" wire:click.prevent="show({{ $type->id }})" class="btn btn-info" data-toggle="modal" data-target="#showType">Mostrar</button>
                                        @endcan
                                        @can('haveaccess', 'type.edit')
                                            <button type="button" wire:click.prevent="edit({{ $type->id }})" class="btn btn-success" data-toggle="modal" data-target="#updateType">Editar</button>
                                        @endcan
                                        @can('haveaccess', 'type.destroy')
                                            <button type="button" wire:click.prevent="delete({{ $type->id }})" class="btn btn-danger" data-toggle="modal" data-target="#deleteType">Borrar</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            @if ($types->count())
                <nav class="col col-lg-6 justify-content-start" aria-label="Page navigation example">
                    <ul class="pagination justify-content">
                        <h6>Mostrando {{ $types->count() }} registros de {{ $total }} registros totales en la página {{ $page }}</h6>
                    </ul>
                </nav>
                <nav class="col col-lg-6 justify-content-end" aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        {{ $types->links() }}
                    </ul>
                </nav>
            @else
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content">
                        <h6>No hay resultados para la búsqueda "{{ $search}}" en la página {{ $page }} al mostrar {{ $perPage }} por página</h6>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
    @include('custom.message')
    @include('livewire.type.create')
    @include('livewire.type.show')
    @include('livewire.type.update')
    @include('livewire.type.delete')
</div>
