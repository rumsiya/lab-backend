<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AppointmentTestServices;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $appoinService;
    public function __construct(AppointmentTestServices $appTestService){
        $this->appoinmentService = $appTestService;
    }
    public function index()
    {
        if(Auth::user()->role == 1 ||Auth::user()->role == 2 ){
            $report = Report::with('getAppointment')->with('getTest.getUnit')->get();
        }else{
            $report = Report::with('getAppointment')->with('getTest.getUnit')
                ->whereHas('getAppointment', function($q){
                    $q->where('patient_id',3);
                })
            ->get();
        }
        return response()->json([
            'success' => true,
            'reports' => $report
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_result' => 'required|decimal:0',
            'max_result' => 'required|decimal:0'
        ]);
        if($validated){
            $data = [
                'appointment_id' => $request->appointment_id,
                'test_id' => $request->test_id,
                'min_result' => $request->min_result,
                'max_result' => $request->max_result,
                'unit_id' => $request->unit_id,
                'description' => $request->description??''
            ];

            $report = Report::create($data);
            if($report){
                $this->appoinmentService->updateStatus(5,$request->appointment_id,$request->test_id);
                $this->appoinmentService->updateStaff($request->appointment_id,$request->test_id);

                return response()->json([
                    'success' => true,
                    'report' => $report,
                    'message' => 'successfully saved the result'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);

            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = Report::find($id);
        return response()->json([
            'success' => true,
            'report' => $report
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $report = Report::find($id);
        $validated = $request->validate([
            'min_result' => 'required|decimal:0',
            'max_result' => 'required|decimal:0'
        ]);
        if($validated){
            $data = [
                'appointment_id' => $request->appointment_id,
                'test_id' => $request->test_id,
                'min_result' => $request->min_result,
                'max_result' => $request->max_result,
                'unit_id' => $request->unit_id,
                'description' => $request->description??''
            ];

            if($report->update($data)){
                return response()->json([
                    'success' => true,
                    'report' => $report,
                    'message' => 'successfully saved the result'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);

            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $report = Report::find($id);
        if($report->delete()){
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function generateReport($id){
        $report = Report::with('getAppointment')->with('getTest')->with('getUnit')->find($id);
        $appointment_id = $report->appointment_id;
        $appointment = Appointment::with('getPatient')->find($appointment_id);

        $pdf = PDF::loadView('lab-report', compact('report', 'appointment'));
        return $pdf->download('report.pdf');

        // return view('lab-report',compact('report','appointment'));
        // return $pdf;

    }
}
