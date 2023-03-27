<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Task;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $myTaskStatusRule = Rule::in(array_keys(Task::TASK_STATUS_STRING));
        //array_keys(Task::TASK_STATUS_STRING)で配列として0~3を取得できる。inメソッドを使ってルールの文字列を作成してる。
        // つまりRule::in(array_keys(Task::TASK_STATUS_STRING));と記述することでin(0,1,2,3)となる。そしてそれが$myTaskStatusRuleに代入されている。
        //結果として出力されるルールは'task_status' => 'required|in(0, 1, 2, 3)'になる



        return [
            //
            'task_name' => 'required|max:100|string',
            'task_status'=>['required',$myTaskStatusRule],
            'due_date' =>'required|date',
        ];
    }
    public function attributes()
    {
        return[
            'task_name'=>'タスク名',
            'task_status' => '進捗',
            'due_date' => '期限',
        ];
    }
    public function messages()
    {
        $statuses = implode('、',array_values(Task::TASK_STATUS_STRING));

        return[
            'task_status.in' => ':attributeには'.$statuses.'のいずれかを選択してください。',
            //進捗(task_status)には、入力値が0~3(完了、処理済み、処理中、未対応)に含まれているかを検証する。inを使用する。
        ];
    }
}
