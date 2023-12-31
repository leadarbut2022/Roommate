<?php

namespace App\Admin\Controllers;

use App\Models\UserValdateInformation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserValdController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UserValdateInformation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserValdateInformation());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('ni_photo', __('Ni photo'))->image('50');
        $grid->column('ni2_photo', __('Ni2 photo'))->image('50');
        $grid->column('active', __('Active'))->switch();
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
        $show = new Show(UserValdateInformation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('ni_photo', __('Ni photo'));
        $show->field('ni2_photo', __('Ni2 photo'));
        $show->field('active', __('Active'));
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
        $form = new Form(new UserValdateInformation());

        $form->text('user_id', __('User id'));
        $form->text('ni_photo', __('Ni photo'));
        $form->text('ni2_photo', __('Ni2 photo'));
        $form->switch('active', __('Active'));

        return $form;
    }
}
