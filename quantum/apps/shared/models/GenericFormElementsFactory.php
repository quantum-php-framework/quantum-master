<?php


/**
 * Class GenericFormElementsFactory
 */
class GenericFormElementsFactory extends Quantum\FormElementsFactory
{

    /**
     * GenericFormElementsFactory constructor.
     */
    public function __construct() {}

    /**
     * @param $field
     * @return mixed|string
     */
    public function getTextInputHtml($field)
    {
        $html = '<input type="text" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public function getFileInputHtml($field)
    {
        $html = '<input type="file" name="'.$field->paramName.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public function getPasswordInputHtml($field)
    {
        $html = '<input type="password" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public function getCheckboxHtml($field)
    {
        $html = '<input type="checkbox" name="'.$field->paramName.'" value="'.$field->value.'">';

        return $this->getFieldWrapperHtml($field, $html);
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public function getTextAreaHtml($field)
    {
        $html = '<textarea rows="5" cols="5" placeholder="" name="'.$field->paramName.'">'.$field->value.'</textarea>';

        $wrapper = $this->getFieldWrapperHtml($field, $html);

        return $wrapper;
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public function getSelectInputHtml($field)
    {
        $html = '<select name="'.$field->paramName.'" >';

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

    /**
     * @param $field
     * @param bool $showBackButton
     * @param string $backButtonUrl
     * @return mixed|string
     */
    public function getSubmitButtonHtml($field, $showBackButton = false, $backButtonUrl = '"javascript:history.back()"')
    {
        $html = '<div><button type="submit">'.$field->visibleName.'</button>';

        if ($showBackButton)
        {
            $html .= '<a href='.$backButtonUrl.'>or Back</a>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * @param $field
     * @param $html
     * @return mixed|string
     */
    public function getFieldWrapperHtml($field, $html)
    {
        $wrapper = "<label>$field->visibleName</label><div>$html</div>";

        return $wrapper;
    }

}


?>