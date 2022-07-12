<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;

const DEFAULT_PAGINATE = 15;

class ProjectController extends Controller
{
    public function insertData(ProjectRequest $request, $project){
        $project->code = $request->project_code;
        $project->name = $request->project_name;
        $project->save();
    }

    public function list(){
        $projects = Project::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.project.project', compact('projects'));
    }

    public function store(ProjectRequest $request) {
        $project = new Project();
        // check mã code trùng hay không?
        if (Project::where('code', '=', $request->project_code)->exists()) {
            return redirect()->route('project.list')->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $project);
        return redirect()->route('project.list')->with('success','Đã thêm dự án thành công');
    }

    public function edit($id) {
        $project = Project::findOrFail($id);
        $projects = Project::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.project.project-edit',compact('project','projects'));
    }

    public function update(ProjectRequest $request, $id) {
        $project = Project::findOrFail($id);
        // check mã code trùng hay không?
        if (Project::where('code', '=', $request->project_code)->where('id', '!=', $id)->exists()) {
            return redirect()->route('project.edit', ['id' => $id])->with('success','Mã code đã tồn tại');
        }
        $this->insertData($request, $project);
        return redirect()->route('project.list')->with('success','Đã cập nhật án thành công');
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('project.list')->with('success','Đã xóa dự án thành công');
    }
}
