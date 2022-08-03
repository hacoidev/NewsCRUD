<?php

namespace Backpack\NewsCRUD\app\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\NewsCRUD\app\Http\Requests\ArticleRequest;
use Backpack\NewsCRUD\app\Models\Category;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(\Backpack\NewsCRUD\app\Models\Article::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin') . '/article');
        $this->crud->setEntityNameStrings('article', 'articles');

        /*
        |--------------------------------------------------------------------------
        | LIST OPERATION
        |--------------------------------------------------------------------------
        */
    }

    protected function setupListOperation()
    {
        $this->authorize('browse', $this->crud->model);

        $this->crud->addColumn('title');
        $this->crud->addColumn([
            'name' => 'published_at',
            'label' => 'Date',
            'type' => 'date',
        ]);
        $this->crud->addColumn('status');
        $this->crud->addColumn([
            'name' => 'featured',
            'label' => 'Featured',
            'type' => 'check',
        ]);
        $this->crud->addColumn([
            'label' => 'Category',
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => 'name',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('category/' . $related_key . '/show');
                },
            ],
        ]);
        $this->crud->addColumn('tags');
    }

    protected function setupCreateOperation()
    {
        $this->authorize('create', $this->crud->model);

        $this->setupFields();
    }

    protected function setupUpdateOperation()
    {
        $this->authorize('update', $this->crud->getEntryWithLocale($this->crud->getCurrentEntryId()));

        $this->setupFields();
    }

    protected function setupDeleteOperation()
    {
        $this->authorize('update', $this->crud->getEntryWithLocale($this->crud->getCurrentEntryId()));
    }

    protected function setupFields()
    {
        $this->crud->setValidation(ArticleRequest::class);

        $this->crud->addField([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            'placeholder' => 'Your title here',
        ]);
        $this->crud->addField([
            'name' => 'slug',
            'label' => 'Slug (URL)',
            'type' => 'text',
            'hint' => 'Will be automatically generated from your title, if left empty.',
            // 'disabled' => 'disabled'
        ]);
        $this->crud->addField([
            'name' => 'category_id',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => Category::pluck('name', 'id')->toArray(),
        ]);

        $this->crud->addField([
            'name' => 'content',
            'label' => 'Content',
            'type' => 'summernote',
            'placeholder' => 'Your textarea text here',
        ]);

        $this->crud->addField([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'ckfinder',
        ]);

        $this->crud->addField([
            'name' => 'seo_title',
            'label' => 'SEO Title',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'seo_description',
            'label' => 'SEO Description',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'seo_keywords',
            'label' => 'SEO Keywords',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'excerpt',
            'label' => 'Excerpt',
            'type' => 'textarea',
        ]);


        $this->crud->addField([
            'name' => 'published_at',
            'label' => 'Date',
            'type' => 'date',
            'default' => date('Y-m-d'),
        ]);

        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'PUBLISHED' => 'PUBLISHED',
                'DRAFT' => 'DRAFT',
            ],
        ]);
        $this->crud->addField([
            'name' => 'featured',
            'label' => 'Featured item',
            'type' => 'checkbox',
        ]);
    }
}
