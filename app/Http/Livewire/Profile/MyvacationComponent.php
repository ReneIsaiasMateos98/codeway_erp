<?php

namespace App\Http\Livewire\Profile;

use App\Models\Absence;
use App\Models\Holiday;
use App\Models\Period;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class MyvacationComponent extends Component
{
    public $usuario;

    public $vacan_id, $days, $beginDate, $endDate, $inProcess, $taken, $available, $responsable, $commentable, $absence_id, $period_id, $usuario_id;

    public $vacation, $periodo, $ausencia, $absences;

    public $inicio, $fin;

    public $ausencia_id;

    public $rules = [
        'days'          => 'required|numeric|max:100',
        'beginDate'     => 'required|date',
        'endDate'       => 'required|date',
        'inProcess'     => 'required|numeric|max:100',
        'taken'         => 'required|numeric|max:100',
        'available'     => 'numeric|max:100',
        'responsable'   => 'required|string|',
        'commentable'   => '',
        'status'        => 'required',
        'absence_id'    => 'required',
        'period_id'     => 'required',
    ];

    protected $validationAttributes = [
        'days'          => 'días',
        'beginDate'     => 'fecha de inicio',
        'endDate'       => 'fecha de terminó',
        'inProcess'     => 'proceso',
        'taken'         => 'tomadas',
        'available'     => 'viables',
        'responsable'   => 'responsable',
        'commentable'   => 'comentario',
        'status'        => 'estado',
        'absence_id'    => 'ausecia',
        'period_id'     => 'periodo',
    ];

    public function mount()
    {
        $user = Auth::user();

        $this->usuario = User::with('profile')->where('id', '=', $user->id)->first();

        $this->absences = Absence::where('status', '=', 1)->get();

        $vacaciones = Holiday::with('users')->get();

        foreach ($vacaciones as $vacacion) {
            if (isset($vacacion->users[0])) {
                if (($vacacion->users[0]->id  == $user->id)) {
                    $this->vacation     = $vacacion;
                    $this->vacan_id     = $vacacion->id;
                    $this->days         = $vacacion->days;
                    $this->beginDate    = $vacacion->beginDate;
                    $this->endDate      = $vacacion->endDate;
                    $this->inProcess    = $vacacion->inProcess;
                    $this->taken        = $vacacion->taken;
                    $this->available    = $vacacion->available;
                    $this->responsable  = $vacacion->responsable;
                    $this->commentable  = $vacacion->commentable;
                    $this->absence_id   = $vacacion->absence_id;
                    $this->period_id    = $vacacion->period_id;
                    $this->usuario_id   = $vacacion->users[0]->id;
                }
            }
        }

        if ($this->period_id) {
            $this->periodo = Period::where('id', '=', $this->period_id)->first();
        } else {
            $this->periodo = "no tiene perido";
        }

        if ($this->absence_id) {
            $this->ausencia = Absence::where('id', '=', $this->absence_id)->first();
        } else {
            $this->ausencia = "no tiene ausencia";
        }
    }

    public function update()
    {
        $this->validate([
            'days'          => 'required|numeric|max:100',
            'beginDate'     => 'required|date',
            'endDate'       => 'required|date',
            'inProcess'     => 'required|numeric|max:100',
            'taken'         => 'required|numeric|max:100',
            'available'     => 'numeric|max:100',
            'responsable'   => 'required|string|',
            'commentable'   => '',
            'status'        => 'required',
            'absence_id'    => 'required',
            'period_id'     => 'required',
        ]);

        $status = 'success';
        $content = 'Se actualizó correctamente la vacación';

        try {

            DB::beginTransaction();

            if ($this->holiday_id) {
                $holiday = Holiday::find($this->holiday_id);
                $slug = $this->days . ' ' . $this->beginDate . ' ' . $this->endDate;

                $holiday->update([
                    'slug'         => Str::slug($slug, '-'),
                    'days'         => $this->days,
                    'beginDate'    => $this->beginDate,
                    'endDate'      => $this->endDate,
                    'inProcess'    => $this->inProcess,
                    'taken'        => $this->taken,
                    'available'    => $this->available,
                    'responsable'  => Auth::user()->name,
                    'commentable'  => $this->commentable,
                    'status'       => $this->status,
                    'absence_id'   => $this->absence_id,
                    'period_id'    => $this->period_id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();

            $status = 'error';
            $content = 'Ocurrió un error al actualizar la vacación';
        }

        session()->flash('process_result', [
            'status'    => $status,
            'content'   => $content,

        ]);

        $this->clean();
        $this->emit('holidayUpdatedEvent');
    }

    public function clean()
    {
        $this->reset([
            'holiday_id',
            'days',
            'beginDate',
            'endDate',
            'inProcess',
            'taken',
            'available',
            'responsable',
            'commentable',
            'status',
            'absence_id',
            'period_id',
            'created_at',
            'updated_at',
            'accion',
            'ausencia',
            'periodo',
        ]);

        $this->mount();
    }

    public function render()
    {
        return view('livewire.profile.myvacation-component');
    }
}
