<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        return [
            
            'task_name' => 'required|max:100|string',
            'due_date' => 'required|date|after_or_equal:today',
            // after_or_equalの引数としてtodayを指定することにより、今日を含んだ未来の日だけを許容できる。
        ];
    }

    public function attributes()
    {
        return[
            'task_name' => 'タスク名',
            'due_date' => '期限',
        ];
    }

    public function messages()
    {
        //messagesメソッドはFormRequestクラス単位でエラーメッセージを定義することができる。
    
        return[
            'due_date.after_or_equal' => ':attributeには今日以降の日付を指定してください。',
            // due_date.after_or_equalのafter_or_equalのバリデーションに引っかかった時のみ今日以降の日付を指定してくださいというバリデーションメッッセージテンプレートが出るようにする。
            //:attributetを記述すると、attributeメソッドで記述した期限が:attributeのところに代入される

        ];
    }
}
