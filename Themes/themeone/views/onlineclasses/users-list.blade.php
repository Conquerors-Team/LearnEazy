@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')

<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                            <li>{{$title}}</li>
                        </ol>
                    </div>
                </div>

                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h1>{{$title}}</h1> </div>
                    <div class="panel-body">
                        <ul class="list-unstyled notification-list">
                            @forelse($onlineclasses as $onlineclass)

                            <li>
                                <!-- <a href="{{$onlineclass->url}}"> -->
                                    <h4>{{$onlineclass->title}}</h4>
                                    <p>Faculty: {{$onlineclass->createdby->name}}</p>

                                    @if(! empty( $onlineclass->subject->subject_title) )
                                    <p>Subject: {{$onlineclass->subject->subject_title}}</p>
                                    @endif

                                    @if($onlineclass->topic)
                                    <p>Topic: {{$onlineclass->topic}}</p>
                                    @endif

                                    <span class="posted-time">Class time : <i class="fa fa-calendar"></i> {{ date( 'h:i A', strtotime($onlineclass->class_time))}}</span>

                                    <a href="{{route('class.attendence', [ 'slug' => $onlineclass->slug ])}}">Attendance</p>
                                <!-- </a> -->
                            </li>
                            @empty
                            <li>No Records found</li>
                            @endforelse

                        </ul>
                            {!! $onlineclasses->links() !!}

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
@endsection

@section('footer_scripts')



@stop