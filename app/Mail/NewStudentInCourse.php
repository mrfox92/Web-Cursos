<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Course;

class NewStudentInCourse extends Mailable
{
    use Queueable, SerializesModels;
    private $course;
    private $student_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Course $course, $student_name )
    {
        //  inicializamos nuestras variables
        $this->course = $course;
        $this->student_name = $student_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject( __("Nuevo estudiante inscrito en tu curso") )
            ->markdown('emails.new_student_in_course')
            ->with('course', $this->course)
            ->with('student', $this->student_name);
    }
}
