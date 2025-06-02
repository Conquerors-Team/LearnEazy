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
                <!-- <li @if($type == 'exams') class="active" @endif><a href="{{route('student.paid_content', [ 'package_slug' => $package->slug, 'type' => 'exams'])}}">Exams</a></li> -->
                <li @if($type == 'notes') class="active" @endif><a href="{{route('student.paid_content', [ 'package_slug' => $package->slug, 'type' => 'notes'])}}">Notes</a></li>
                <li @if($type == 'lmsseries') class="active" @endif><a href="{{route('student.paid_content', ['package_slug' => $package->slug, 'type' => 'lmsseries'])}}">LMS</a></li>
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
            <div class="panel pricing-table">
          <?php $settings = getSettings('paidcontent'); ?>

          @if(count($paid_contents))

          <?php $entry_count = 0;
              if ( count($paid_contents) >= 4 ) {
            $cols = 3;
          } else {
            $cols = 12/count($paid_contents);
          }

          ?>

            @foreach($paid_contents as $c)


                  <?php $image = $settings->defaultCategoryImage;
                  if(isset($c->image) && $c->image!='')
                    $image = $c->image;



                  ?>

            @if($c->total_items)
              <div class='col-sm-{{$cols}}'>
        <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$image}}" alt="{{$c->title}}" class='pricing-img' style='height: 180px;'>
        <h2 class='pricing-header'>{{$c->short_description}}<p class='grey'>{{$c->title}}</p></h2>
        <!-- <ul class="pricing-features">
        <li class='pricing-features-item'><i class='fa fa-check' style='color:#4CAF50;'></i> Online Videos * <br><span class='blink_me'>Limited Access Only</span></li></ul> -->
        @if($c->is_paid && !isItemPurchased($c->id, 'paidcontent') )
        <button type="button" class="btn btn-outline-success"><span class="blink_me"><a href="{{route('payments.checkout', ['type' => 'paidcontent', 'slug' => $c->slug])}}">Pay ₹{{$c->cost}} now</a></span></button>

        @if( ! empty( $c->free_trail_days ) && !\App\Payment::isTrailUsed($c->id, 'paidcontent') )
        <button type="button" class="btn btn-outline-success pull-right"><span class="blink_me"><a href="{{route('student.enable_free_trail', ['slug' => $c->slug])}}">Try now for {{$c->free_trail_days}} Days</a></span></button>
        @endif
        @else
        <button type="button" class="btn btn-outline-success"><span class="blink_me"><a href="{{route('student.paid_content', ['package_slug' => $c->slug, 'type' => 'notes'])}}">Purchased<br>View more</a></span></button>
        @endif
        <!-- <a  href='login' class='pricing-button pricing-price'>₹ {{$c->cost}}</a> -->
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