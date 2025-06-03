@extends('layouts.sitelayout')

@section('content')

 <!-- Page Banner -->
    <section class="cs-primary-bg cs-page-banner" style="margin-top: 110px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="cs-page-banner-title">{{ ucwords($institute->institute_name) }} Batches Details</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- /Page Banner -->

<?php 
   
  
?>

<div class="container">
    <br>
  <table class="table table-bordered">
   
    
        <thead>
        <tr>
            <th style="text-align: center;">{{getPhrase('batch_name')}}</th>
            <th style="text-align: center;">{{getPhrase('start_date')}}</th>
            <th style="text-align: center;">{{getPhrase('end_date')}}</th>
            <th style="text-align: center;">{{getPhrase('time')}}</th>
            <th style="text-align: center;">{{getPhrase('total_seats')}}</th>
            <th style="text-align: center;">{{getPhrase('booked_seats')}}</th>
            <th style="text-align: center;">{{getPhrase('available_seats')}}</th>
            <th style="text-align: center;">{{getPhrase('fee')}}</th>
        </tr>
        </thead>
      <tbody>
       @foreach($batches as $batch)
        <tr>
            <td style="text-align: center;">{{ $batch->name }}</td>
            <td style="text-align: center;">{{ $batch->start_date }}</td>
            <td style="text-align: center;">{{ $batch->end_date }}</td>
            <?php
             $stime   = date("g:i a", strtotime($batch->start_time ));
             $etime   = date("g:i a", strtotime($batch->end_time ));
             $booked  = App\BatchStudent::where('batch_id',$batch->id)->get()->count();
            ?>
            <td style="text-align: center;">{{ $stime }} - {{ $etime }}</td>
            <td style="text-align: center;">{{ $batch->capacity }}</td>
            <td style="text-align: center;">{{ $booked }}</td>
            <td style="text-align: center;">{{ $batch->capacity - $booked }}</td>
            <td style="text-align: center;">{{ $currency }} {{ $batch->fee_perhead }}</td>
        </tr>
       @endforeach
      
     </tbody>
  </table>
</div>



@stop