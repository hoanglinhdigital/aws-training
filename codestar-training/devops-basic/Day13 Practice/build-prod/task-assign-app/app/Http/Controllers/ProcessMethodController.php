<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessMethodRequest;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\ProcessMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

const DEFAULT_PAGINATE = 15;

class ProcessMethodController extends Controller
{
    public function insertData(ProcessMethodRequest $request, $process_method){
        $process_method->code = $request->process_code;
        $process_method->name = $request->process_name;
        $process_method->assigner = $request->process_assigner;
        $process_method->save();
    }
    public function list(){
        $jobs = Job::all();
        $process_methods = ProcessMethod::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.process-method.process-method', compact('process_methods', 'jobs'));
    }
    public function store(ProcessMethodRequest $request) {
        $process_method = new ProcessMethod();
        // check mã code trùng hay không?
        if (ProcessMethod::where('code', '=', $request->process_code)->exists()) {
            return redirect()->route('process-method.list')->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $process_method);
        return redirect()->route('process-method.list')->with('success','Đã thêm hình thức xử lý thành công');
    }
    public function edit($id) {
        $jobs = Job::all();
        $process_method = ProcessMethod::findOrFail($id);
        $process_methods = ProcessMethod::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.process-method.process-method-edit', compact('process_method','process_methods', 'jobs'));
    }
    public function update(ProcessMethodRequest $request, $id) {
        $process_method = ProcessMethod::findOrFail($id);
        // check mã code trùng hay không?
        if (ProcessMethod::where('code', '=', $request->process_code)->where('id', '!=', $id)->exists()) {
            return redirect()->route('process-method.edit', ['id' => $id])->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $process_method);
        return redirect()->route('process-method.list')->with('success','Đã cập nhật hình thức xử lý thành công');
    }
    public function destroy($id) {
        $process_method = ProcessMethod::findOrFail($id);
        $process_method->delete();
        return redirect()->route('process-method.list')->with('success','Đã xóa hình thức xử lý thành công');
    }

    public function queryProcessMethod(Request $request)
    {
        if (!$request->has(['job_id', 'staff_id'])) {
            return response('Mã công việc và mã nhân viên là bắt buộc', 400);
        }
        $jobId = $request->input('job_id');
        $staffId = $request->input('staff_id');
        $jobAssign = JobAssign::where([
            'job_id' => $jobId,
            'staff_id' => $staffId
        ])
        ->with('processMethod')
        ->first();

        if (!$jobAssign) {
            return response('Không tìm thấy đối tượng xử lý công việc này', 404);
        }
        return response()->json($jobAssign->processMethod);

    }
}
