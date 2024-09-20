<?php namespace App\Contracts;

interface ModelBoundable
{
    /**
     * Returns the fully qualified name of the model
     * which is bound to this controller
     *
     * @return string
     */
    function boundModelFullyQualifiedName();

    /**
     * Returns the name for bound model in plural, ex: skis, snowboard_boots, etc..
     *
     * @return string
     */
    function boundModelNamePlural();

}
