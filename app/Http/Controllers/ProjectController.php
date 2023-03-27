<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * プロジェクト一覧画面
     */
    public function index()
    {
        // ログインユーザーが作成した全てのプロジェクトを取得
        $projects = Auth::user()->projects->all();

        return view('projects.index', compact('projects'));
    }

    /**
     * プロジェクト作成画面
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * プロジェクト作成処理
     */
    public function store(StoreProjectRequest $request)
    {

        //トランザクション開始
        DB::beginTransaction();

        try {
            //プロジェクト作成処理
            $project = Project::create([
                'project_name' => $request->project_name,
                'user_id' => Auth::id(),
            ]);

            //トランザクションコミット
            DB::commit();
        }catch(\Exception $e) {
            //トランザクションロールバック
            DB::rollBack();

            //ログ出力
            Log::dubug($e);

            //エラー画面遷移
            abort(500);
        }

        return redirect()->route('projects.index');
    }
}