<?php

namespace Backpack\NewsCRUD\app\Http\Requests;

use \Backpack\CRUD\app\Http\Requests\CrudRequest;

class ArticleRequest extends CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:255',
            'slug' => 'unique:articles,slug,' . $this->id,
            'content' => 'required|min:2',
            'published_at' => 'required',
            'status' => 'required',
            'category_id' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
