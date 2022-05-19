<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{$file_name}}</title>
			</head>
	<style>
		body{
			font-family: sans-serif; 
			font-size: 10pt;
		}

		.header{
			text-align: center;
			font-size: 10pt;
		}
		.footer{
			position: fixed;
			left: 0;
			bottom: 100px;
			width: 100%;
			text-align: right;
		}

		table{
			text-align: left;
			width: 100%; 
		
		}
		#table1{
			text-align: left;
			width: 100%;
			padding-top: 40px;
			border: solid 2px black;
			padding-bottom:  40px;
		}
		#table1 tr{ 
			border: solid 2px black; 
		}

		.student-name{
			text-transform: uppercase;
			width: 40%;
		}

		.date{
			font-size: 8px;
			font-style: italic;
		}

		.def-table-header td{
			font-size: 10px;
			font-weight: bold;
		}

		.def-table-info td{
			font-weight: normal;
			padding-top: 20px;
		}

		.def-count{
			text-align: center;
		}

		.fillable-cell{
			border-bottom: 1px solid black;
			/* padding-left: 2%; */
		}

		.spacer-cell{
			width: 5%;
		}


		.ocs-signature{
			font-size: 10pt;
			text-align: center;
		}

		.content{
			height: 50%;
			position: relative;
		}
		
		.loa-footer{
			font-size: 8pt;
			width: 100%;
			position: absolute;
			bottom: 0;
		}

		.loa-footer td{
			width: 33%;
			text-align: center;
		}

		.approved{
			float: right;
		}

		.signature-line{
			border-bottom: 1px solid black;
		}

		.deficiencies-count{
			text-align: center;
			padding: 50px;
		} 
    .text-right{
		text-align: left;
		/* padding-right: .75rem;  */
	}
	.student-information{
		/* padding-left: -120px; */
		text-align: justify;
	}
	.navbar-brand {
    display: inline-block;
    padding-top: 0.32rem;
    padding-bottom: 0.32rem;
    margin-right: 1rem;
    font-size: 1.125rem;
    line-height: inherit;
    white-space: nowrap;
}
	</style>
	<style>
		/**
		Tomado de https://parzibyte.me/blog/2018/10/16/tabla-html-bordes-css/
		 */
		table {
			border-collapse: collapse;
		}
		 
		table,
		th,
		td {
			border: 1px solid black;
		}
		 
		th,
		td {
			padding: 10px;
		}
		</style>
@php
// use App\Student;
use App\Staff_PD;
use App\Staff_DEAN;
use App\Staff;
use App\Clearance;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Designee;
use App\Campus;
		// if (Auth::user()->hasRole('student')) {
			
		// 	$student =  Student::whereUserId(Auth::user()->id)->first(); 
		// }
		// else {
			
		// 	$student =  Student::whereSlug($slug)->first(); 
		// }
    $programDirector = Staff_PD::whereProgram_id($student->program->id)->first();
	$collegeDean = Staff_DEAN::whereCollege_id($student->program->college->id)->first();
	$pd = Staff::whereUser_id($programDirector->user_id)->first();
	$cd = Staff::whereUser_id($collegeDean->user_id)->first();
	
    $cashier = Staff::whereDesignee_id(Designee::where('name','Cashier')->first()->id)->where('campus_id',Campus::find($student->program->college->campus->id)->id)->first();
    $library = Staff::whereDesignee_id(Designee::where('name','Library')->first()->id)->where('campus_id',Campus::find($student->program->college->campus->id)->id)->first();
	$osas = Staff::whereDesignee_id(Designee::where('name','Office of Student Affairs and Services')->first()->id)->where('campus_id',Campus::find($student->program->college->campus->id)->id)->first();
	$registrar = Staff::whereDesignee_id(Designee::where('name','Registrar')->first()->id)->where('campus_id',Campus::find($student->program->college->campus->id)->id)->first();
	$clearance = Clearance::whereStudent_id($student->id)->first();
	$clearanceRequest = ClearanceRequest::whereStudent_id($student->id)->first();

	
    $registrarstaff = App\StaffRegistrar::whereProgram_id($student->program->id)->first();
	if($registrarstaff){

		$registrarstaffid = Staff::whereUser_id($registrarstaff->user->id)->first();
	}
    
@endphp
	<body>
		<div class="content">
		<div class="header">
			{{--  COLLEGE OF ARTS AND SCIENCES<br/>  --}}
			{{-- <div class="navbar-brand"><img src="img/psu_logo.png" width="50" alt=""></div>
			
			Partido State University<br/> --}}
			<table style="border:0px; padding:0px;">
				<tr style="border:0px; padding:0px;">
				<td rowspan="3" style="width:18%; border:0px; padding:0px; padding-left:40px; 	"><img src="images/psu_logo.png" width="60" alt=""></td>
				<td style=" border:0px; padding:0px; text-align:center;">Republic of the Philippines</td>
				<td style="width:20%; border:0px; padding:0px;"></td>
				</tr>
				<tr style="border:0px; padding:0px;"> 
					<td style="; border:0px; padding:0px;  text-align:center;"><h3>PARTIDO STATE UNIVERSITY</h3></td>
					<td style="width:20%; border:0px; padding:0px;"></td>
				</tr>
				<tr style="border:0px; padding:0px;"> 
				<td style=" border:0px; padding:0px;  text-align:center;">Camarines Sur</td>
					<td style="width:20%; border:0px; padding:0px;"></td>
			</tr>
			</table>
			<table style="border:0px; padding:0px;">
				<tr style="border:0px; padding:0px;">
					
				<td style="border:0px; padding-bottom:10px"><hr></td>
				<td style="width:14%; border:0px; padding:0px;">PSU-F-URO-58</td>
				</tr>
			</table>
			 
			<h3>OFFICE OF THE REGISTRAR</h3>
			<h4>CLEARANCE FORM</h4>
		</div>


			<table>
				<thead>
					<tr>
						<th colspan="2">PERSONAL INFORMATION</th>
						 
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Student Number</td>
						<td style="padding-left:20px; width:70%">{{ $student->student_number }}</td>
					</tr> 
					<tr>
						<td>Student Name</td>
						<td style="padding-left:20px; width:70%">{{ $student->name }}</td>
					</tr> 
					<tr>
						<td>Course/Program</td>
						<td style="padding-left:20px; width:70%">{{ $student->program->name }}</td>
					</tr> 
				</tbody>
			</table>
			<br>
			<table style="padding:0px;">
				<thead>
					<tr style="padding:10px;">
						<th style="padding:10px;">PURPOSE</th>
						 
					</tr>
				</thead>
				<tbody>
					<tr> 
						
                        <td style="padding:10px; text-align:center;">{{$purpose}}</td>
                        
                       
					</tr> 
					
				</tbody>
			</table>

		
			<div class="">
				<h4 style="text-align:center; font-style:italic;">This is to certify that the student mentioned above is cleared from any property and money responsibility as of this date.</h4>

		<table>
			<thead>
				<tr>
					<th>OFFICE</th>
					<th>DESIGNEE NAME</th>
					<th>DATE APPROVED</th>
					<th>CLEARANCE ID</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Cashier</td>
					<td style="padding-left:20px;"> {{$staffSTCOUNCIL->user->name}}</td>
					<td style="text-align:center">
						{{$clearanceRequestCASHIER->approved_at->toFormattedDateString()}}
					</td>
					<td style="text-align:center">
                    {{$clearanceRequestPROGRAMDIRECTOR->token}}
					</td>
				</tr>
				<tr>
					<td>Program Director</td>
					<td style="padding-left:20px;"> {{$staffPD->user->name}}</td>
					<td style="text-align:center">
                    {{$clearanceRequestPROGRAMDIRECTOR->approved_at->toFormattedDateString()}}
					
					</td>
					<td style="text-align:center">
					{{$clearanceRequestPROGRAMDIRECTOR->token}}
					</td>
				</tr>
				<tr>
					<td>College Dean</td>
					<td style="padding-left:20px;"> {{$staffDEAN->user->name}}</td>
					<td style="text-align:center">
					{{$clearanceRequestDEAN->approved_at->toFormattedDateString()}}
					</td>
					<td style="text-align:center">
					{{$clearanceRequestDEAN->token}}
					</td>
				</tr>
				<tr>
					<td>Library</td>
					<td style="padding-left:20px;"> {{$staffLIBRARY->user->name}}</td>
					<td style="text-align:center">
					{{$clearanceRequestLIBRARY->approved_at->toFormattedDateString()}}
					</td>
					<td style="text-align:center">
                    {{$clearanceRequestLIBRARY->token}}
					</td>
				</tr>
				<tr>
					<td>Student Affairs & Services</td>
					<td style="padding-left:20px;"> {{$staffOSAS->user->name}}</td>
					<td style="text-align:center">
                    {{$clearanceRequestOSAS->approved_at->toFormattedDateString()}}
					</td>
					<td style="text-align:center">
                    {{$clearanceRequestOSAS->token}}
					</td>
				</tr>
				<tr>
					<td>Registrar</td>
				
					<td style="padding-left:20px;"> {{$staffREGISTRARSTAFF->user->name}}</td>
					<td style="text-align:center">
                    {{$clearanceREGS->approved_at->toFormattedDateString()}}
					</td>
					<td style="text-align:center">
                    {{$clearanceREGS->token}}
					</td>
					
					
				</tr>

			</tbody>
		</table>
 

			</div>

			<div class="footer">
				<img src="data:image/png;base64,{{DNS2D::getBarcodePNG('oscs.parsu.edu.ph/#/clearance/'.$clearance->id, 'QRCODE')}}" alt="barcode" width="80"/>
				<div class="date">
					{{-- Monday, January 10, 2020 9:23 AM --}}
					Generated on {{\Illuminate\Support\Carbon::now()->format('l, F
					j, Y h:i A')}} by {{ Auth::user()->name }}
						
				</div> 
				{{-- {!! DNS1D::getBarcodeSVG('123', "C39", 1, 25, '#2A3239') !!} --}}
				
				{{-- <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('11', 'C39')}}" alt="barcode" />
				<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('12', 'C39+')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('13', 'C39E')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('14', 'C39E+')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('15', 'C93')}}" alt="barcode" />
		   <br/>
		   <br/>
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('19', 'S25')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('20', 'S25+')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('21', 'I25')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('22', 'MSI+')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('23', 'POSTNET')}}" alt="barcode" />
		   <br/>
		   <br/>
		   <img src="data:image/png;base64,{{DNS2D::getBarcodePNG('16', 'QRCODE')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS2D::getBarcodePNG('17', 'PDF417')}}" alt="barcode" />
		   <img src="data:image/png;base64,{{DNS2D::getBarcodePNG('18', 'DATAMATRIX')}}" alt="barcode" /> --}} 
				
			</div>
		</div>

		
		
	</body>
</html>
