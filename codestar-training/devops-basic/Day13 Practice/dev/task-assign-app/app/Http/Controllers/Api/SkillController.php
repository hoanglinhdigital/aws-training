<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

const DEFAULT_PAGINATE = 15;

class SkillController extends Controller
{
    public function insertData(Request $request, $skill)
    {
        $skill->code = $request->code;
        $skill->name = $request->name;
        $skill->save();
    }

    public function list()
    {
        $skills = Skill::orderBy('id', 'desc')->get();
        return response()->json($skills);
    }

    public function store(Request $request)
    {
        $skill = new Skill();
        // check mã code trùng hay không?
        if (Skill::where('code', '=', $request->skill_code)->exists()) {
            return response()->json(['message' => "Skill code đã tồn tại"], 403);
        }
        $this->insertData($request, $skill);
        return response()->json(['message' => "Skill đã được thêm"], 200);
    }

    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        return response()->json($skill);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        // check mã code trùng hay không?
        if (Skill::where('code', '=', $request->skill_code)->where('id', '!=', $id)->exists()) {
            return response()->json(['message' => "Skill code đã tồn tại"], 403);
        }
        $this->insertData($request, $skill);
        return response()->json(['message' => "Skill đã được cập nhật"], 200);
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);

        if ($skill == null) {
            return response()->json(['message' => "Skill code ko tồn tại"], 403);
        }

        $skill->delete();
        return response()->json(['message' => "Skill đã được xóa"], 200);
    }
}
