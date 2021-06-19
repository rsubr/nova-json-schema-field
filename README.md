# JSON schema field for Laravel Nova

[![Packagist](https://img.shields.io/packagist/dt/rsubr/nova-json-schema-field.svg)](https://packagist.org/packages/rsubr/nova-json-schema-field)

Laravel Nova field for displaying [JSON schema](https://json-schema.org/) data. Inspired by [nsavinov/nova-json-schema-field](https://packagist.org/packages/nsavinov/nova-json-schema-field).

## Installation

You can install the package into a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require rsubr/nova-json-schema-field
```

## Usage

In the Laravel Model:

```php
    use Illuminate\Database\Eloquent\Casts\AsCollection;

    protected $casts = [
        'details' => AsCollection::class,
    ];

    // NovaJsonSchemaField does not like empty JSON, so use an empty placeholder
    protected $attributes = [
        'details' => '{"_": ""}',
    ];
```

### Using a Static Schema

In the Nova resource:

```php
use Rsubr\NovaJsonSchemaField\NovaJsonSchemaField;

public function fields(Request $request)
{
    return [
        // ...
        NovaJsonSchemaField::make('Details')
            ->jsonSchema($this->loadSchema())
            ->rules('json'),
    ];
}

private function loadSchema(): array
{
    $schema = <<<SCHEMA
        {
            "type": "object",
            "required": [
                "event_name",
                "level",
                "start_date",
                "duration"
            ],
            "properties": {
                "event_name": {
                    "description": "Event Name"
                },
                "level": {
                    "type": "array",
                    "items": {
                        "enum": [
                            "State Level",
                            "National",
                            "International"
                        ]
                    },
                    "description": "Event level"
                },
                "start_date": {
                    "type": "string",
                    "format": "date",
                    "description": "Start Date"
                },
                "duration": {
                    "type": "number",
                    "description": "Duration in Days"
                }
            }
        }

    SCHEMA;
    return json_encode($schema, true);
}
```

## Using a Dynamic Schema
The schema can be dynamically loaded from a related Model or API.

Eg. to load the JSON schema from an `EventType` Model, first create an attribute on the Model and call from the Nova resource.

In the Laravel Model:

```php
    public function getJsonSchemaAttribute() {
        return $this->event_type->json_schema;
    }
```

The `event_type` Model should have an `HasMany` relationship with the model holding the JSON data.


In the Nova Resource:

```php
    protected function loadSchema($request)
    {
        // NovaJsonSchemaField does not like empty JSON, so use an empty placeholder
        $schema = $request->findModelQuery()->first()->json_schema ?? array();

        return $schema;
    }
```

## Notes
1. For Postgresql, use store the JSON Schema in a `JSON` column to preserve field order, do not use `JSONB` for the schema. The JSON data values can be stored in `JSON` or `JSONB`.
