<!DOCTYPE html>
<html>
    <head>
        <title>Student Report</title>
        <link rel="stylesheet" 
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
              crossorigin="anonymous">
    </head>
    <body>
        <div class="container">

            <header>
                <div class="text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>University of Santo Tomas</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Office for Student Affairs</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Espana Boulevard, Sampaloc, Manila, 1008 Metro Manila</h4>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mt-5">

                <section id="studentInfo">
                    <div class="row">
                        <div class="col-md-4">
                            <b>Name: </b>{{ $student->last_name }}, {{ $student->first_name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <b>Student No: </b>{{ $student->student_id }}
                        </div>
                    </div>
                </section>

                <section id="certificationText" class="mt-5">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="text-uppercase text-center">Certification</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-justify">
                                This is to certify that the aforementioned individual is a student of the 
                                <u>University of Santo Tomas</u> and has participated in the following 
                                activities during his/her stay in the University<sup>1</sup>
                            </p>
                        </div>
                    </div>
                </section>

                <section id="events">
                    @foreach($student->events as $event)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><b>Academic Year</b></td>
                                        <td><b>Event Name</b></td>
                                        <td><b>Organized By</b></td>
                                        <td><b>Involvement</b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ $event->date_start }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->organization->name }}</td>
                                        <td>{{ $event->pivot->involvement }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Description</b></td>
                                        <td colspan="3">
                                            {{ $event->description }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </section>

                <section id="eventCount" >
                    <div class="row">
                        <div class="col-md-6">
                            <p>Total number of attended activities: {{ count($student->events) }}</p>
                        </div>
                    </div>
                </section>

                <section id="seal">
                    <div class="row text-right">
                        <div class="col-md-12">
                            <h4>{{ $osa->user->first_name }} {{ $osa->user->last_name }}</h4>
                            <p>Officer-in-Charge for Student Activities</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            Rm. 212 UST Tan Yan Kee Student Center <br/>
                            University of Santo Tomas, Espana Boulevard <br/>
                            Manila, 1015 PHILIPPINES <br/>
                        </div>
                    </div>
                </section>

            </main>

        </div>
    </body>
</html>

