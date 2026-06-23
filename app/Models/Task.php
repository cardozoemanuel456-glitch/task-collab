<?php
 namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;

 class Task extends Model
 {
     use HasFactory;

// Forzamos el nombre de la tabla en plural castellano conforme a la migración
     protected $table = 'tareas';

     protected $fillable = [
  
         'pagina_id', 
         'title', 
         'description', 
         'status', 
         'priority', 
         'user_id', 
         'start_date', 
         'due_date'
    ];




    // La página/lista a la que pertenece esta tarea
    public function pagina()
    {
        return $this->belongsTo(Pagina::class, 'pagina_id');
    }

    // El usuario que creó la tarea
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // El usuario asignado para resolverla
    public function asignado()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}