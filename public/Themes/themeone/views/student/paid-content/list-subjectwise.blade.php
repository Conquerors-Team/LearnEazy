@extends($layout)

@section('content')



<div id="page-wrapper">

      <div class="container-fluid">

        <!-- Page Heading -->

        <div class="row">

          <div class="col-lg-12">

            <ol class="breadcrumb">

              <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

              @if( ! empty( $type_slug ) )
              	@if( $type == 'notes')
              	<li><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'notes'])}}">Notes</a> </li>
              	@else
              	<li><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'lmsseries'])}}">LMS</a> </li>
              	@endif
              @endif
              <li class="active"> {{ $title }} </li>

            </ol>

          </div>

        </div>

        <!-- /.row -->

        <div class="panel panel-custom">

          <div class="panel-heading">
            <h1>{{$title}}</h1>
          </div>

          <div class="panel-body packages">
            @if( $package )
              <ul class="nav nav-tabs">
                <!-- <li @if($type == 'exams') class="active" @endif><a href="{{route('student.paid_content_subjectwise', [ 'package_slug' => $package->slug, 'type' => 'exams'])}}">Exams</a></li> -->
                <li @if($type == 'notes') class="active" @endif><a href="{{route('student.paid_content_subjectwise', [ 'package_slug' => $package->slug, 'type' => 'notes'])}}">Notes</a></li>
                <li @if($type == 'lmsseries') class="active" @endif><a href="{{route('student.paid_content_subjectwise', ['package_slug' => $package->slug, 'type' => 'lmsseries'])}}">LMS</a></li>
              </ul>

              <table class="table table-striped table-bordered datatable dataTable no-footer" cellspacing="0" width="100%" id="DataTables_Table_0" role="grid" style="width: 100%;">
              <tbody>
                @switch($type)
	                @case( 'exams' )
		                @foreach($package->exams as $exam )
		                <tr><td><a onclick="showInstructions('{{URL_STUDENT_TAKE_EXAM.$exam->slug}}')" href="javascript:void(0);"><b>{{$exam->title}}</b></a></td><td><span style="color:#79c73c"><b>{{$exam->dueration}} Min.</b></span></td><td><span style="color:#79c73c"><b>{{$exam->total_questions}} Questions</b></span></td><td><a onclick="showInstructions('{{URL_STUDENT_TAKE_EXAM.$exam->slug}}')" href="javascript:void(0);" class="btn btn-primary" style="border: 2px solid #79c73c !important; background-color: #fff; color: #000;   font-weight: bold;">Take Exam</a></td></tr>
		              	@endforeach
	              	@break
	              	@case( 'notes' )
	              		@if( $type_slug )
	              			<?php
	              			$notes = \App\LmsSeries::where('slug', $type_slug)->first();
	              			?>
	              			@include('student.lms.series-items', ['series' => $notes])
	              		@else
		              		@foreach($package->lmsnotes as $notes )
			                	<tr><td><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'lmsseries', 'type_slug' => $notes->slug])}}"><b>{{$notes->title}}</b></a></td><td><span style="color:#79c73c"><b>{{$notes->total_items}} Items</b></span></td><td><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'notes', 'type_slug' => $notes->slug])}}" class="btn btn-primary" style="border: 2px solid #79c73c !important; background-color: #fff; color: #000;   font-weight: bold;">View More</a></td></tr>
			              	@endforeach
		              	@endif
	              	@break
	              	@case( 'lmsseries' )
	              		@if( $type_slug )
	              			<?php
	              			$series = \App\LmsSeries::where('slug', $type_slug)->first();
	              			?>
	              			@include('student.lms.series-items', ['series' => $series])
	              		@else
		              		@foreach($package->lmsseries as $lmsseries )
			                <tr><td><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'lmsseries', 'type_slug' => $lmsseries->slug])}}"><b>{{$lmsseries->title}}</b></a></td><td><span style="color:#79c73c"><b>{{$lmsseries->total_items}} Items</b></span></td><td><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'lmsseries', 'type_slug' => $lmsseries->slug])}}" class="btn btn-primary" style="border: 2px solid #79c73c !important; background-color: #fff; color: #000;   font-weight: bold;">View More</a></td></tr>
			              	@endforeach
		              	@endif
	              	@break
              @endswitch
              </tbody>
              </table>
            @else
            <div class="row library-items">
          <?php $settings = getSettings('paidcontent'); ?>

          @if(count($paid_contents))

          <?php $entry_count = 0;?>

            @foreach($paid_contents as $c)



            @if($c->total_items)
              <div class="col-md-3">

                <div class="library-item mouseover-box-shadow">

                <div class="">

                  <div class="item-image">

                  @if($c->is_paid)
                  <div class="label-primary label-band">{{getPhrase('premium')}}</div>

                  @else

                  <div class="label-danger  label-band">{{getPhrase('free')}}</div>

                  @endif



                  <?php $image = $settings->defaultCategoryImage;
                  if(isset($c->image) && $c->image!='')
                    $image = $c->image;



                  ?>

                    <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$image}}" alt="{{$c->title}}">
                    <div class="hover-content">

                    <div class="buttons">
                      @if($c->is_paid && !isItemPurchased($c->id, 'paidcontent') )
                      	<a href="{{route('payments.checkout', ['type' => 'paidcontent', 'slug' => $c->slug])}}" class="btn btn-primary">{{getPhrase('buy_now')}}</a>
                      @else
                      <a href="{{route('student.paid_content', ['package_slug' => $c->slug])}}" class="btn btn-primary">{{getPhrase('view_more')}}</a>
                      @endif

                      </div>

                    </div>



                  </div>

                  <div class="item-details">

                    <h3>{{ $c->title }} - {{$c->id}}-{{isItemPurchased($c->id, 'paidcontent')}}</h3>

                    <div class="quiz-short-discription">

                    {!!$c->short_description!!}

                    </div>

                    <ul>

                      <li><i class="icon-bookmark"></i> {{ $c->total_items.' '.getPhrase('items')}}</li>

                    </ul>



                  </div>

                </div>

                </div>

              </div>







              @endif



               @endforeach

              @else

              Ooops...! {{getPhrase('No_series_available')}}

              @endif



            </div>

            @if(count($paid_contents))

            {!! $paid_contents->links() !!}

            @endif

            @endif

          </div>

        </div>

      </div>



</div>

    <!-- /#page-wrapper -->
@stop

@section('footer_scripts')
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$( function() {
  $( "#accordion" ).accordion();
} );
</script>

@stop