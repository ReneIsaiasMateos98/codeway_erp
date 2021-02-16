<?php

namespace App\Http\Livewire\Profile;

use App\Models\Absence;
use App\Models\Holiday;
use App\Models\Period;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class MyvacationComponent extends Component
{
    /* Datos para la tabla de vacaiones */
    public $vacan_id, $days, $beginDate, $endDate, $inProcess, $taken, $available, $responsable, $commentable, $status, $absence_id, $period_id, $usuario_id;
    /* Datos para los modelos necesarios para este mòdulo */
    public $vacation, $periodo, $ausencia, $absences, $usuario, $usuarios, $vacaciones;
    /* Datos que el usuario vera para ver si la toma o no */
    public $inicio, $fin, $dias;

    public $ausencia_id;

    public $anioHoy, $aniInicio, $anioTermino, $mesHoy, $mesInicio, $mesTermino, $diaHoy, $diaInicio, $diaTermino;


    public $currentDate, $shippingDate, $diferencia_en_dias, $diferencia_en_meses;

    public $rules = [
        'days'          => 'required|numeric',
        'beginDate'     => 'required|date',
        'endDate'       => 'required|date|after_or_equal:beginDate',
        'inProcess'     => 'required|numeric',
        'taken'         => 'required|numeric',
        'available'     => 'required|numeric',
        'responsable'   => 'required|string',
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
        'available'     => 'disponible',
        'responsable'   => 'responsable',
        'commentable'   => 'comentario',
        'status'        => 'estado',
        'absence_id'    => 'ausecia',
        'period_id'     => 'periodo',
        'inicio'     => 'fecha de inicio',
        'fin'     => 'fecha de termino',

    ];

    public function mount()
    {
        $user = Auth::user();

        $this->usuario = User::with('profile')->where('id', '=', $user->id)->first();

        $this->usuarios = User::with('profile')->get();

        $this->absences = Absence::where('status', '=', 1)->get();

        $this->vacaciones = Holiday::with('users', 'absence', 'period')->get();

        $vacaciones = Holiday::with('users')->get();

        foreach ($vacaciones as $vacacion) {
            if (isset($vacacion->users[0])) {
                if (($vacacion->users[0]->id  == $user->id)) {
                    $beginDate          = new Carbon($vacacion->beginDate);
                    $endDate            = new Carbon($vacacion->endDate);
                    $this->beginDate    = $beginDate->format('l jS \\of F Y');
                    $this->endDate      = $endDate->format('l jS \\of F Y');
                    $this->vacan_id     = $vacacion->id;
                    $this->responsable  = $vacacion->responsable;
                    $this->status       = $vacacion->status;
                    $this->period_id    = $vacacion->period_id;
                    $this->inProcess    = $vacacion->inProcess;
                    $this->taken        = $vacacion->taken;
                    if ($vacacion->status == 1) {
                        //preceso de validación
                        $this->days         = $vacacion->days;
                        $this->inProcess    = $vacacion->inProcess;
                        $this->taken        = $vacacion->taken;
                        $this->available    = $vacacion->available;
                        $this->commentable  = $vacacion->commentable;
                        $this->absence_id   = $vacacion->absence_id;
                        $this->usuario_id   = $vacacion->users[0]->id;
                    } else if ($vacacion->status == 2) {
                        //Ya se acepto la validación
                        $this->obtieneDiasConTaken($vacacion->beginDate, $vacacion->taken);
                    } else {
                        //Es nuevo o ya se acpeto la validacion
                        $this->obtieneDias($vacacion->beginDate);
                    }
                }
            }
        }
        /* Asigno y obtengo el period y la ausencia que tenga o este relacionado */
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

    public function obtieneDiasConTaken($fechaInicio, $tomadas)
    {
        $fechaHoy    = Carbon::now();
        $fechaBegin  = new Carbon($fechaInicio);

        $fechaHoy = $fechaHoy->format('Y-m-d');

        $diferencia_en_dias = $fechaBegin->diffInDays($fechaHoy);

        $diferencia_en_meses = $fechaBegin->diffInMonths($fechaHoy);

        $meses = $diferencia_en_meses / 2;

        $dias = $meses - $tomadas;

        $this->days = round($dias, 0, PHP_ROUND_HALF_DOWN);

        $diasRestantes = $diferencia_en_dias / 365;

        $disponibles = $this->days + $diasRestantes;

        $available = $disponibles - $tomadas;

        $this->available = round($available, 2);
    }

    public function obtieneDias($fechaInicio)
    {
        $fechaHoy    = Carbon::now();
        $fechaBegin  = new Carbon($fechaInicio);

        $fechaHoy = $fechaHoy->format('Y-m-d');

        $diferencia_en_dias = $fechaBegin->diffInDays($fechaHoy);

        $diferencia_en_meses = $fechaBegin->diffInMonths($fechaHoy);

        $meses = $diferencia_en_meses / 2;

        $this->days = round($meses, 0, PHP_ROUND_HALF_DOWN);

        $diasRestantes = $diferencia_en_dias / 365;

        $disponibles = $this->days + $diasRestantes;

        $this->available = round($disponibles, 2);
    }

    public function calculaDias($fechaInicio, $fechaTermino)
    {
        $fechaHoy    = Carbon::now();
        $fechaBegin  = new Carbon($fechaInicio);
        $fechaEnd    = new Carbon($fechaTermino);

        $this->anioHoy     = $fechaHoy->format('Y');
        $this->aniInicio   = $fechaBegin->format('Y');
        $this->anioTermino = $fechaEnd->format('Y');

        $this->mesHoy      = $fechaHoy->format('m');
        $this->mesInicio   = $fechaBegin->format('m');
        $this->mesTermino  = $fechaEnd->format('m');

        $this->diaHoy      = $fechaHoy->format('d');
        $this->diaInicio   = $fechaBegin->format('d');
        $this->diaTermino  = $fechaEnd->format('d');

        $anioHoy     = $fechaHoy->format('Y');
        $aniInicio   = $fechaBegin->format('Y');
        $anioTermino = $fechaEnd->format('Y');

        $mesHoy      = $fechaHoy->format('m');
        $mesInicio   = $fechaBegin->format('m');
        $mesTermino  = $fechaEnd->format('m');

        $diaHoy      = $fechaHoy->format('d');
        $diaInicio   = $fechaBegin->format('d');
        $diaTermino  = $fechaEnd->format('d');



        /* Revisar esta pagina
        https://desarrolloweb.com/faq/diferencia-de-dias
        o esta
        https://desarrolloweb.com/articulos/calcular-dias-entre-dos-fechas-php.html


        $currentDate = Carbon::createFromFormat('Y-m-d', $fechaActual);

        $shippingDate = Carbon::createFromFormat('Y-m-d', $fechaEnvio);

        $diferencia_en_dias = $currentDate->diffInDays($shippingDate);


        codigo completo

        //defino fecha 1
        $ano1 = 2006;
        $mes1 = 10;
        $dia1 = 2;

        //defino fecha 2
        $ano2 = 2006;
        $mes2 = 10;
        $dia2 = 27;

        //calculo timestam de las dos fechas
        $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
        $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);

        //resto a una fecha la otra
        $segundos_diferencia = $timestamp1 - $timestamp2;
        //echo $segundos_diferencia;

        //convierto segundos en días
        $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

        //obtengo el valor absoulto de los días (quito el posible signo negativo)
        $dias_diferencia = abs($dias_diferencia);

        //quito los decimales a los días de diferencia
        $dias_diferencia = floor($dias_diferencia);

        echo $dias_diferencia;
        */

        //Si el año actual es igual al año de inicio
        if ($anioHoy == $aniInicio) {
            //Obtengo los meses de hoy y de inicio
            $mesHoy     = 12 - $mesHoy;
            $mesInicio  = 12 - $mesInicio;
            //resto los meses de hoy menos el mes de inicio y tengo el total de meses que lleva
            $meses      = $mesInicio - $mesHoy;
            //divido entre dos para saber cuantos dias le corresponden
            $dias   = $meses / 2;
            //redondeo al numero mas bajo y asigno eso a la variable $dias
            $this->days = round($dias, 0, PHP_ROUND_HALF_DOWN);
            $this->available = round($dias, 1);

            //En caso de que el año actual sea igual al año de termino
        } else if ($anioHoy == $anioTermino) {
            // Y que el mes de hoy sea mayo o igual al mes de termino
            if ($mesHoy >= $mesTermino) {
                // Y que el dia de hoy se mayor o igual al dia de termino
                if ($diaHoy >= $diaTermino) {
                    //se crea un nuevo registro con los datos del usuario y vacaciones, se suma un año a sus vacaciones
                    $fecha  = Carbon::now();
                    $anio = $fecha->format('Y');
                    $period_id = Period::where('description', '=', $anio)->first();

                    if ($period_id) {
                        $beginDate = $fecha->format('Y-m-d');
                        $endDate = $fecha->addYear()->format('Y-m-d');

                        $vacation = Holiday::create([
                            'slug'         => null,
                            'days'         => null,
                            'beginDate'    => $beginDate,
                            'endDate'      => $endDate,
                            'inProcess'    => null,
                            'taken'        => null,
                            'available'    => null,
                            'responsable'  => null,
                            'commentable'  => null,
                            'absence_id'   => null,
                            'period_id'    => $period_id->id,
                        ]);

                        $this->usuario->holidays()->sync($vacation->id);
                    }
                } else {
                    //Se calculan los dias que faltan para que termine su primer año
                    //Por lo que rstamos los meses de hoy e incio a 12 para saber cuantos meses va
                    $mesInicio     = 12 - $mesInicio;
                    //resto el mes de incio menos el mes de hoy para saber cuantos meses va
                    $meses  = $mesInicio + $mesHoy;
                    //divido los meses entre 2 para saber cuantos dias le corresponden
                    $dias   = $meses / 2;
                    //redondeo al numero mas bajo y asigno eso a la variable $dias
                    $this->days = round($dias, 0, PHP_ROUND_HALF_DOWN);
                    $this->available = round($dias, 1);
                }
                // En caso de que no, quiere decir que el mes de inicio es mayor que el mes de hoy
            } else {
                //Por lo que rstamos los meses de hoy e incio a 12 para saber cuantos meses va
                $mesInicio  = 12 - $mesInicio;
                //resto el mes de incio menos el mes de hoy para saber cuantos meses va
                $meses  = $mesInicio + $mesHoy;
                //divido los meses entre 2 para saber cuantos dias le corresponden
                $dias   = $meses / 2;
                //redondeo al numero mas bajo y asigno eso a la variable $dias
                $this->days = round($dias, 0, PHP_ROUND_HALF_DOWN);
                $this->available = round($dias, 1);
            }
        }
    }

    public function updated()
    {
        if ($this->inicio) {
            $this->validate([
                'inicio'       => 'required|date|after:today',
            ]);
        }
        if ($this->fin) {
            $this->validate([
                'fin'       => 'required|date|after_or_equal:inicio',
            ]);
        }

        if (isset($this->inicio) && isset($this->fin)) {
            $fechaInicio    = new Carbon($this->inicio);
            $fechaTermino    = new Carbon($this->fin);
            $diaInicio = $fechaInicio->format('d');
            $diaTermino = $fechaTermino->format('d');

            $this->dias = ($diaTermino - $diaInicio) + 1;
            $days = $this->days;

            $this->validate([
                'inicio'       => 'required|date|after:today',
                'fin'          => 'required|date|after_or_equal:inicio',
                'dias'         => 'required|numeric|min:diaInicio|max:days',
            ]);
        }
    }

    public function validaDias()
    {
        $fechaInicio    = new Carbon($this->inicio);
        $fechaTermino    = new Carbon($this->fin);
        $diaInicio = $fechaInicio->format('d');
        $diaTermino = $fechaTermino->format('d');

        $this->dias = ($diaTermino - $diaInicio) + 1;
        $days = $this->days;

        $this->validate([
            'inicio'       => 'required|date|after:today',
            'fin'          => 'required|date|after_or_equal:inicio',
            /* 'dias'         => 'required|numeric|min:diaInicio|max:days', */
            'ausencia_id'  => 'required',
        ]);
        if ($this->dias > $this->days) {
            $status = 'error';
            $content = 'Los dias que has solicitado no pueden ser mayor a los dias de los que dispones';
            session()->flash('process_result', [
                'status'    => $status,
                'content'   => $content,
            ]);
        } else {
            $this->update();
        }
    }

    public function update()
    {
        $status = 'success';
        $content = 'Se solicito correctamente la vacación en las fechas de ' . $this->inicio . ' a ' . $this->fin . '. Por lo que solicito ' . $this->dias . ' dias de vacaciones';

        try {

            DB::beginTransaction();

            if ($this->vacan_id) {
                $holiday = Holiday::find($this->vacan_id);
                $slug = $this->days . ' ' . $this->beginDate . ' ' . $this->endDate;

                $holiday->update([
                    'days'         => $this->days - $this->dias,
                    'inProcess'    => $this->dias,
                    'available'    => $this->available,
                    'responsable'  => $this->responsable,
                    'commentable'  => $this->commentable,
                    'status'       => 1,
                    'absence_id'   => $this->ausencia_id,
                ]);

                $this->usuario->holidays()->sync($holiday);
            }

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();

            $status = 'error';
            $content = 'Ocurrio un error al tratar de solicitar sus vacaciones';
        }

        session()->flash('process_result', [
            'status'    => $status,
            'content'   => $content,
        ]);

        $this->clean();
    }

    public function clean()
    {
        $this->reset([
            'vacan_id',
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
            'usuario_id',
            'vacation',
            'periodo',
            'ausencia',
            'absences',
            'usuario',
        ]);

        $this->mount();
    }

    public function render()
    {
        return view('livewire.profile.myvacation-component');
    }
}
