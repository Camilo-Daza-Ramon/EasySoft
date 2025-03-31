<?php namespace App;

use Esensi\Model\Contracts\ValidatingModelInterface;
use Esensi\Model\Traits\ValidatingModelTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Acoustep\EntrustGui\Contracts\HashMethodInterface;
use Illuminate\Notifications\Notifiable;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, ValidatingModelInterface, HashMethodInterface
{
  use Authenticatable, CanResetPassword, ValidatingModelTrait, EntrustUserTrait, Notifiable;

    protected $throwValidationExceptions = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'avatar', 'estado', 'cedula','celular','firma'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $hashable = ['password'];

    protected $rulesets = [

        'creating' => [
            'email'      => 'required|email|unique:users',
            'password'   => 'required',
        ],

        'updating' => [
            'email'      => 'required|email|unique:users',
            'password'   => '',
        ],
    ];

    public function entrustPasswordHash() 
    {
        $this->password = Hash::make($this->password);
        $this->save();
    }

    public function cliente(){
        return $this->hasMany(Cliente::class, 'Cliente_Id');
    }

    public function cliente_ont_olt(){
        return $this->hasMany(ClienteOntOlt::class);
    }

    public function contrato(){
        return $this->hasMany(ClienteContrato::class);
    }

    public function novedad(){
        return $this->hasMany(Novedad::class);
    }    

    public function atencion_cliente(){
        return $this->hasMany(AtencionCliente::class);
    }

    public function evento_contrato(){
        return $this->hasMany(ContratoEvento::class);
    }

    public function punto_atencion_ventanilla(){
        return $this->hasOne(PuntoAtencionVentanilla::class);
    }

    public function mantenimiento_creo(){
        return $this->hasMany(Mantenimiento::class, 'user_crea');
    }

    public function ticket_creo(){
        return $this->hasMany(Ticket::class, 'user_crea');
    }

    public function proyectos(){
        return $this->belongsToMany(Proyecto::class, 'users_proyectos', 'user_id', 'proyecto_id');
    }

    public function cant_auditar(){
        return Cliente::where('Status', 'PENDIENTE')->count();
    }

    public function scopeBuscar($query, $palabra){
        if (!empty($palabra)) {
            $query->where('name', 'like', '%'.$palabra.'%')
            ->orWhere('email','like','%'.$palabra.'%');
        }
    }

    public function scopeBuscarPorRol($query, $rol_id){
        if (!empty($rol_id) && $rol_id != 0) {
            $query->whereHas('roles', function ($q) use ($rol_id) {
                $q->where('role_id', '=', $rol_id);
            });      
        }
    }

    public function scopeBuscarPorEstado($query, $estado){
        if (!empty($estado)) {
            $query->where('estado', '=', $estado);
        }
    }

}
