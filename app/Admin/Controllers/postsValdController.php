<?php

namespace App\Admin\Controllers;

use App\Models\posts;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class postsValdController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'posts';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new posts());

        $grid->column('id', __('Id'));
        $grid->column('titel', __('Titel'));
        $grid->column('details', __('Details'));
        $grid->column('governorate', __('Governorate'));
        $grid->column('google_maps', __('Google maps'));
        $grid->column('location_details', __('Location details'));
        $grid->column('price', __('Price'));
        $grid->column('type', __('Type'));
        // $grid->column('user_id', __('User id'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(posts::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('titel', __('Titel'));
        $show->field('details', __('Details'));
        $show->field('governorate', __('Governorate'));
        $show->field('google_maps', __('Google maps'));
        $show->field('location_details', __('Location details'));
        $show->field('price', __('Price'));
        $show->field('type', __('Type'));
        $show->field('user_id', __('User id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new posts());

        $form->text('titel', __('Titel'));
        $form->textarea('details', __('Details'));
        $form->text('governorate', __('Governorate'));
        $form->textarea('google_maps', __('Google maps'));
        $form->textarea('location_details', __('Location details'));
        $form->text('price', __('Price'));
        $form->text('type', __('Type'))->default('m');
        $form->number('user_id', __('User id'));

        return $form;
    }
}
