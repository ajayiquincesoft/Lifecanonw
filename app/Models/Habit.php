<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'repeat',
        'status',
        'number_of_session',
        'start_date',
        'end_date',
        'alert',
        'week_days'

    ];

     protected $appends = ['habitStatusNew'];

    public  function habitItems()
    {
        return $this->hasMany(HabitItems::class, 'habit_id', 'id');
    }
	
	public  function habitStatus()
    {
        return $this->hasMany(HabitStatus::class, 'habit_id', 'id');
    }
	public  function gethabitStatusNewAttribute()
    {
		$new=array();
	$habitStatus = $this->habitStatus;
	  if(count($habitStatus)>0){
		  foreach($habitStatus as $habitStat){
			$new[date('Y-m-d',strtotime($habitStat->date))]=$habitStat;
		}
	}
	  return $new;
	  
	  
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function getWeekDaysDataAttribute()
    // {
    //     if($this->week_days){
    //         return @unserialize($this->week_days);
    //     }else{
    //         return null;
    //     }
    // }

    public function getWeekDaysAttribute($value)  {
        return @unserialize($value) !== false ? unserialize($value) : $value;
     }
	 
	 /*
	 * return week attribute
	 */
	 public function getWeekDaysHtmlAttribute(){
		$weeksHtml = '';															
		$weeksArray =array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		 if($this->week_days){
			 $weeks = @unserialize($this->week_days) !== false ? unserialize($this->week_days) : $this->week_days;
			 
			 foreach($weeksArray as $weeksArrays){
				 
				 $checkedStatus = (@in_array($weeksArrays,@$weeks))?'checked':'';
					
				 $weeksHtml .= '<div class="col-md-4"><label for="week-add"><input class="greeninput "type="checkbox" name="week_days[]" value="'.$weeksArrays.'"  '.$checkedStatus.'>&nbsp;'.$weeksArrays.'</div></label>';
			 }
		 }
		 
		 
		 return $weeksHtml;
	 }
}
