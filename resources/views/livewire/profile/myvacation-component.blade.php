<div>
    <div class="card">
        <div class="card-header bg-secondary">
            <label for="nombre">{{ $usuario->nameUser }} {{ $usuario->firstLastname }} {{ $usuario->secondLastname }}</label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    @isset($periodo->description)
                        <label class="text-muted" for="period">Perido : {{$periodo->description}}</label>
                    @else
                        <label for="period">no hay periodo </label>
                    @endisset
                </div>
                <div class="col-4">
                    <label class="text-muted" for="begin">Inicio : {{$beginDate}}</label>
                </div>
                <div class="col-4">
                    <label class="text-muted" for="begin">Termina : {{$endDate}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="card bg-primary">
                        <div class="card-header text-center">
                            Dias
                        </div>
                        <div class="card-footer" style="height: 4rem;">
                            <h3 class="text-center">{{$days}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-success">
                        <div class="card-header text-center">
                            En proceso
                        </div>
                        <div class="card-footer" style="height: 4rem;">
                            <h3 class="text-center">{{$inProcess}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-info">
                        <div class="card-header text-center">
                            Tomados
                        </div>
                        <div class="card-footer" style="height: 4rem;">
                            <h3 class="text-center">{{$taken}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-warning">
                        <div class="card-header text-center">
                            Disponibles
                        </div>
                        <div class="card-footer" style="height: 4rem;">
                            <h3 class="text-center">{{$available}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-3">
                    <label class="text-muted" for="absence">Tipo de ausencia</label>
                    <select wire:model="ausencia_id" class="form-control @error('ausencia_id') is-invalid @enderror"  name="ausencia_id" wire:dirty.class="bg-primary">
                        <option value="">--Tipo de ausencia--</option>
                        @foreach($absences as $absence)
                            <option  value="{{ $absence->id }}"
                                @isset( $absence->description )
                                    @if( $absence->description )
                                        selected
                                    @endif
                                @endisset
                                >
                                {{ $absence->description }}
                            </option>
                        @endforeach
                    </select>
                    @error('ausencia_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-3">
                    <label class="text-muted" for="inicio">Fecha de incio</label>
                    <input type="date" name="inicio" class="form-control @error('inicio') is-invalid @enderror"
                            wire:model="inicio" wire:dirty.class="bg-primary">
                    @error('inicio')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-3">
                    <label class="text-muted" for="fin">Fecha de termino</label>
                    <input type="date" name="fin" class="form-control @error('fin') is-invalid @enderror"
                            wire:model="fin" wire:dirty.class="bg-primary">
                    @error('fin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-3">
                    <label class="text-muted" for="responsable">Responsable</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mt-2">
                    <label class="text-muted" for="comentable">Comentario:</label>
                    <textarea class="form-control @error('commentable') is-invalid @enderror" name="commentable" wire:model="commentable" wire:dirty.class="bg-primary" rows="3"></textarea>
                    @error('commentable')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-3 mt-3">
                    <div class="card bg-secondary">
                        <div class="card-header text-center text-white">
                            Dias
                        </div>
                        <div class="card-footer" style="height: 4rem;">
                            <h3 class="text-center">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary mx-2" wire:click.prevent="clean()">Cancelar</button>
                <button type="button" class="btn btn-primary" wire:click.prevent="update()">Enviar solicitud</button>
            </div>
        </div>
    </div>
    <hr><hr>
    <div>
        @isset($ausencia)
            <label for="ausencia">{{$ausencia}}</label>
        @else
            <label for="ausencia">No hay ausencia</label>
        @endisset

    </div>
</div>
