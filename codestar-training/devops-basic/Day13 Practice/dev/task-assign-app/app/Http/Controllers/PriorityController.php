<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PriorityRequest;
use App\Models\Priority;

const DEFAULT_PAGINATE = 15;

class PriorityController extends Controller
{
    public function insertData(PriorityRequest $request, $priority){
        $priority->code = $request->priority_code;
        $priority->name = $request->priority_name;
        $priority->priority = $request->priority_number;
        $priority->save();
    }

    public function list(){
        $priorities = Priority::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.priority.priority', compact('priorities'));
    }

    public function store(PriorityRequest $request) {
        $priorities = new Priority();
        // check mã code trùng hay không?
        if (Priority::where('code', '=', $request->priority_code)->exists()) {
            return redirect()->route('priority.list')->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $priorities);
        return redirect()->route('priority.list')->with('success','Đã thêm độ ưu tiên thành công');
    }

    public function edit($id) {
        $priority = Priority::findOrFail($id);
        $priorities = Priority::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.priority.priority-edit', compact('priority','priorities'));
    }

    public function update(PriorityRequest $request, $id) {
        $priority = Priority::findOrFail($id);
        // check mã code trùng hay không?
        if (Priority::where('code', '=', $request->priority_code)->where('id', '!=', $id)->exists()) {
            return redirect()->route('priority.edit', ['id' => $id])->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $priority);
        return redirect()->route('priority.list')->with('success','Đã cập nhật độ ưu tiên thành công');
    }

    public function destroy($id) {
        $priorities = Priority::findOrFail($id);
        $priorities->delete();
        return redirect()->route('priority.list')->with('success','Đã xóa độ ưu tiên thành công');
    }
}
