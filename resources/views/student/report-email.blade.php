<!DOCTYPE html>
<html>
    <head>
        <title>Student Event Report</title>
    </head>
    <body>
        <p>Attached herewith is the event report for <b>{{ strtoupper($student->last_name) }}, {{ $student->first_name }}</b>,  Student ID <b>{{ $student->student_id }}</b></p>
    </body>
</html>

