<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Facades\IDP;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ["id", "grade_name", "class_name", "from", "to"];

    public function getGrades()
    {
        // $response = Http::get('http://127.0.0.1:8000/api/v1/api-grades');
        $response = IDP::get('/organogram/grades');
        $grades = $response->json();
        $this->store($grades);
        return redirect('/admin/grades');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($grades)
    {
        if ($grades) {
            
            Grade::truncate();

            foreach ($grades as $grade) {
                Grade::updateOrCreate(
                    ['id' => $grade['id']],
                    [
                        'grade_name' => $grade['grade_name'],
                        'class_name' => $grade['class_name'],
                        'from' => $grade['from'],
                        'to' => $grade['to'],
                    ]
                );
            }
        }
    }

    // public function employees()
    // {
    //     return $this->hasMany(Employee::class, 'grade_id', 'id');
    // }
}
