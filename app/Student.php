<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Campus;
class Student extends Model
{
	protected $guarded = [];
    protected $table='students';
    protected $fillable=['id','student_number','slug','name','year','section_id','program_id','user_id','initial_password','semester_id'];
	protected $searchable = [
		/**
		 * Columns and their priority in search results.
		 * Columns with higher values are more important.
		 * Columns with equal values have equal importance.
		 *
		 * @var array
		*/
		'columns' => [
			 
			'students.name' => 10,
		],

		// 'joins' => [
		// 	'programs' => ['programs.id', 'students.program_id'],
		// 	'users' => ['users.id', 'students.user_id'],
		// ],
    ];
    public function section()
    {
        return $this->belongsTo('App\Section','section_id'); 
    }
    public function program()
    {
        return $this->belongsTo('App\Program','program_id'); 
    }
	public function user()
    {
        return $this->belongsTo('App\User','user_id'); 
    }
	public function deficiencies()
    {
        return $this->belongsToMany('App\Deficiency','deficiency_student','student_id','deficiency_id')->withTimestamps();
	}
	public function clearanceRequest(){
        return $this->hasMany('App\ClearanceRequestV2'::class,'student_id','id');
 
    }
	// public function deficienciesCount(){
	// 	return $this->deficiencies();
	// }

}
