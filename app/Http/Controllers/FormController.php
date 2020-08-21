<?php

namespace App\Http\Controllers;

use App\Form;
use App\Helpers\CacheRemember;
use App\Helpers\Sender;
use App\Http\Requests\ArticleFormRequest;

class FormController extends Controller
{
    private $model;

    public function __construct(Form $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $forms = new CacheRemember($this->model, 'forms');
        return response()->json($forms, 200);
    }

    public function store(ArticleFormRequest $request)
    {
        $data = $request->validated();
        $form = Form::create($data);
        new CacheRemember($this->model, 'forms');
        new Sender($form);
    }

    public function update(ArticleFormRequest $request, Form $form)
    {
        $data = $request->validated();
        $form->update($data);
        new CacheRemember($this->model, 'forms');
        new Sender($form);
    }
}
