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
              @if(canDo('institute_view'))
							<li><a href="{{URL_VIEW_INSTITUES}}">{{getPhrase('institutes')}}</a> </li>
              @endif
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">


						<h1>{{ $title }} {{getPhrase('details')}}</h1>
					</div>
					<div class="panel-body packages">
						<div >
						<table class="table">
							<tbody>
								<tr>
									<td><b>{{getPhrase('institute_name')}}</b></td>
									<td>{{$title}}</td>

									<td><b>{{getPhrase('institute_address')}}</b></td>
									<td>{{$record->institute_address}}</td>
								</tr>

								<tr>
									<td><b>{{getPhrase('admin_name')}}</b></td>
									<td>{{ucwords($user->name)}}</td>

									<td><b>{{getPhrase('email')}}</b></td>
									<td>{{$user->email}}</td>
								</tr>

								<tr>
									<td><b>{{getPhrase('phone_number')}}</b></td>
									<td>{{$user->phone}}</td>
									<td><b>{{getPhrase('address')}}</b></td>
									<td>{{$user->address}}</td>
								</tr>
							</tbody>

						</table>

            <div class="row">

              <span class="label label-info label-many">
                <?php
                echo implode('</span>&nbsp;|&nbsp;<span class="label label-info label-many"> ', $record->permissions->pluck('title')->toArray());
                ?>
              </span>
            </div>

            <div class="row">Change Status To:</div>
						</div class="row">

                     @if( checkRole(getUserGrade(1)))

                       @if(!$is_superadmin)

                       {{-- Buttons --}}


                            @if( $record->status == 0 || $record->status == 2 )

                                @if( $record->status != BLOCK )

                            <a href="javascript:void(0)" class="btn btn-sm btn-success button" onclick="updateInstitute('{{$record->id}}','approve')">{{getPhrase('approve')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                                @endif

                            @endif

                           {{--  @if( $record->status == 0  || $record->status == APPROVE )

                             @if( $record->status != BLOCK )

                            <a href="javascript:void(0)" class="btn btn-sm btn-warning button" onclick="updateInstitute('{{$record->id}}','reject')">{{getPhrase('reject')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                              @endif

                            @endif --}}

                            @if( $record->status == 0 || $record->status == APPROVE)

                            <a href="javascript:void(0)" class="btn btn-sm btn-danger button" onclick="updateInstitute('{{$record->id}}','block')">{{getPhrase('block')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                            @elseif( $record->status == BLOCK )

                           <a href="javascript:void(0)" class="btn btn-sm btn-info button" onclick="updateInstitute('{{$record->id}}','unblock')">{{getPhrase('un_block')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                           @endif

                       {{-- End Buttons --}}

                         @endif

                       @endif

                      </div>
				</div>
			</div>
			<!-- /.container-fluid -->


<div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><span id='msg1'></span> {{getPhrase('institute')}}</h4>
      </div>
      <div class="modal-body">

      {!!Form::open(array('url'=> URL_UPDATE_INSTITUTE_STATUS,'method'=>'POST','name'=>'userstatus'))!!}

      <h4 class="text-center" id="msg2"></h4>

        <input type="hidden" name="institute_id" id="institute_id" >
        <input type="hidden" name="status" id="status" >

        <fieldset class="form-group col-sm-12">

             {{ Form::label('comments', getphrase('comments')) }}

             {{ Form::textarea('comments', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_comments')
             )) }}

      </fieldset>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" >Yes</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">No</button>
      </div>
      {!!Form::close()!!}

    </div>

  </div>
</div>



 </div>

@endsection


@section('footer_scripts')


<script>

 		function updateInstitute(institute_id,status){


           $('#institute_id').val(institute_id);

           $('#status').val(status);

           if(status == 'approve'){

           	 $('#msg1').html('Approve');
           	 $('#msg2').html('Are You Sure To Approve This Institute');
           }
           if(status == 'reject'){

           	 $('#msg1').html('Reject');
           	 $('#msg2').html('Are You Sure To Reject This Institute');
           }
           if(status == 'block'){

           	 $('#msg1').html('Block');
           	 $('#msg2').html('Are You Sure To Block This Institute');
           }
           if(status == 'unblock'){

           	 $('#msg1').html('Unblock');
           	 $('#msg2').html('Are You Sure To Unblock This Institute');
           }

           $('#myModal').modal('show');
 		}




 </script>


@stop
