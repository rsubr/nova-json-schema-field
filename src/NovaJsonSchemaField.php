<?php

namespace Rsubr\NovaJsonSchemaField;

use Laravel\Nova\Fields\Field;

use Laravel\Nova\Http\Requests\NovaRequest;

class NovaJsonSchemaField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-json-schema-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Pass the jsonSchema to the Vue component
     *
     * @param  string  $schema
     * @return array
     */
    public function jsonSchema($schema) {
        return $this->withMeta([
            'jsonSchema' => $schema,
        ]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }
}
