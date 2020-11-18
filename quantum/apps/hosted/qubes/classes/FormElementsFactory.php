<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 1/7/19
 * Time: 11:34 AM
 */

namespace Qubes;

class FormElementsFactory extends \Quantum\FormElementsFactory
{

    public function __construct() {}

    public function getTextInputHtml($field)
    {
        $html = '<input type="text" class="form-control" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    public function getFileInputHtml($field)
    {
        $html = '<input type="file" class="form-control" name="'.$field->paramName.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    public function getPasswordInputHtml($field)
    {
        $html = '<input type="password" class="form-control" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    public function getCheckboxHtml($field)
    {
        $html = '<input type="checkbox" class="form-control" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    public function getTextAreaHtml($field)
    {
        $html = '<textarea rows="5" cols="5" class="form-control" placeholder="" name="'.$field->paramName.'">'.$field->value.'</textarea>';

        $wrapper = $this->getFieldWrapperHtml($field, $html);

        return $wrapper;
    }

    public function getSelectInputHtml($field)
    {
        $html = '<select name="'.$field->paramName.'" class="select">';

        foreach ($field->options as $value => $name)
        {
            if (!empty($field->value) && $field->value == $value)
                $html .= '<option value="'.$value.'" selected>'.$name.'</option>';
            else
                $html .= '<option value="'.$value.'">'.$name.'</option>';


        }

        $html .= '</select>';

        return $this->getFieldWrapperHtml($field, $html);
    }

    public function getSubmitButtonHtml($field, $showBackButton = true, $backButtonUrl = '"javascript:history.back()"')
    {
        $html = '<div class="text-center"><button type="submit" class="btn btn-primary">'.$field->visibleName.'</button>';

        if ($showBackButton)
        {
            $html .= '<a href='.$backButtonUrl.'> or Back</a>';
        }

        $html .= '</div>';

        return $html;
    }

    public function getFieldWrapperHtml($field, $html)
    {
        $asterisk = "";

        if ($field->required == 1)
        {
            $asterisk = "<span style='color:red'> * </span>";
        }

        $wrapper = '<div class="form-group row">
                            <label class="col-form-label col-md-2">'.$field->visibleName .$asterisk.':</label>
                            <div class="col-md-10">
                                '.$html.'
                            </div>
                        </div>';

        return $wrapper;
    }

}


?>