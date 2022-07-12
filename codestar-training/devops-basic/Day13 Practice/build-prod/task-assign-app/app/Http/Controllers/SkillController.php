<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

const DEFAULT_PAGINATE = 15;

class SkillController extends Controller
{
    public function insertData(SkillRequest $request, $skill)
    {
        $skill->code = $request->skill_code;
        $skill->name = $request->skill_name;
        $skill->save();
    }

    public function list()
    {
        $skills = Skill::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.skill.skill', compact('skills'));
    }

    public function store(SkillRequest $request)
    {

        $skill = new Skill();
        // check mã code trùng hay không?
        if (Skill::where('code', '=', $request->skill_code)->exists()) {
            return redirect()->route('project.list')->with('success', 'Mã code đã tồn tại');
        }
        $this->insertData($request, $skill);
        return redirect()->route('skill.list')->with('success', 'Đã thêm kỹ năng thành công');
    }

    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        $skills = Skill::orderBy('id', 'desc')->paginate(DEFAULT_PAGINATE);
        return view('site.skill.skill-edit', compact('skill', 'skills'));
    }

    public function update(SkillRequest $request, $id)
    {
        $skill = Skill::findOrFail($id);
        // check mã code trùng hay không?
        if (Skill::where('code', '=', $request->skill_code)->where('id', '!=', $id)->exists()) {
            return redirect()->route('skill.edit', ['id' => $id])->with('success', 'Mã code đã tồn tại');
        }
        $this->insertData($request, $skill);
        return redirect()->route('skill.list')->with('success', 'Đã cập nhật kỹ năng thành công');
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();
        return redirect()->route('skill.list')->with('success', 'Đã xóa kỹ năng thành công');
    }
}
