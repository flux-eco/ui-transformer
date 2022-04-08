# flux-eco/ui-transformer
This component tranforms in json instances described data for the usage in a user interface. e.g.
- makes translation of untranslated strings
- merges json documents which are linked by references

## Usage
@see directory examples

.env
```
UI_TRANSFORM_TRANSLATION_FILES_DIRECTORY=configs/translations
UI_TRANSFORM_UI_DEFINITION_DIRECTORY=configs/ui
UI_TRANSFORM_PAGE_LIST_DEFINITION_FILE_PATH=configs/ui/pages.yaml
UI_TRANSFORM_MARKDOWN_TO_HTML_CONVERTER_REST_API_URL=http://localhost:9001
```
translations\de.yaml
```
pageTitle:
  todo: Todo's
  topic: Thema
topic:
  title: Thema
  description: Beschreibung
todo:
  title: ToDo
  description: Beschreibung
action:
  delete: Delete
  edit: Edit
```
ui\pages.yaml
```
- $ref: topic/page.yaml
- $ref: todo/page.yaml
```
ui\topic\page.yaml
```
title:
  type: lngKey
  key: pageTitle.topic
url: "/listdata/Topic"
avatar: "/icons/seminars.png"
pageType: ListData
projectionName: topic
editForm:
  $ref: editForm.yaml
createForm:
  $ref: createForm.yaml
itemActions:
  edit:
    type: form
    key: editForm
    title:
      type: lngKey
      key: action.edit
  delete:
    type: form
    key: deleteForm
    title:
      type: lngKey
      key: action.delete
  todos:
    type: subobject
    key: todos
    title:
      type: lngKey
      key: pageTitle.todo
    projectionName: todo
```
ui\topic\editForm.yaml
```
rootObjectAggregateName: ToDo
properties:
  - title:
      type: lngKey
      key: topic.title
    key: title
    dataIndex: title
    valueType: string
    width: m
    formItemProps:
      rules:
        - required: true
          message: Please enter a Title
  - title:
      type: lngKey
      key: topic.description
    key: description
    dataIndex: description
    valueType: textarea
    width: m
    formItemProps: [ ]
```
ui\... 
``` 
-> @see directory examples
```

example.php
``` 
<?php

require_once __DIR__ . '/../vendor/autoload.php';

fluxDotEnv\loadDotEnv(__DIR__);

//get page list
$pages = fluxUiTransformer\getPages();

echo "getPages(): ".PHP_EOL;
print_r($pages).PHP_EOL.PHP_EOL;


//get page
$pageTopic = fluxUiTransformer\getPageDefinition('topic');

echo "getPageDefinition('topic'): ".PHP_EOL;
print_r($pageTopic).PHP_EOL;

```

outputs
```
getPages(): 
Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [title] => Thema
                    [url] => /listdata/Topic
                    [avatar] => /icons/seminars.png
                    [pageType] => ListData
                    [projectionName] => topic
                    [editForm] => Array
                        (
                            [rootObjectAggregateName] => ToDo
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => Thema
                                            [key] => title
                                            [dataIndex] => title
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a Title
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Beschreibung
                                            [key] => description
                                            [dataIndex] => description
                                            [valueType] => textarea
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [createForm] => Array
                        (
                            [rootObjectAggregateName] => Topic
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => Thema
                                            [key] => title
                                            [dataIndex] => title
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a Title
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Beschreibung
                                            [key] => description
                                            [dataIndex] => description
                                            [valueType] => textarea
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [itemActions] => Array
                        (
                            [edit] => Array
                                (
                                    [type] => form
                                    [key] => editForm
                                    [title] => Edit
                                )

                            [delete] => Array
                                (
                                    [type] => form
                                    [key] => deleteForm
                                    [title] => Delete
                                )

                            [todos] => Array
                                (
                                    [type] => subobject
                                    [key] => todos
                                    [title] => Todo's
                                    [projectionName] => todo
                                )

                        )

                )

            [1] => Array
                (
                    [title] => Todo's
                    [url] => /listdata/Todo
                    [avatar] => /icons/seminars.png
                    [pageType] => ListData
                    [projectionName] => todo
                    [editForm] => Array
                        (
                            [rootObjectAggregateName] => Todo
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => ToDo
                                            [key] => title
                                            [dataIndex] => title
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a Title
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Beschreibung
                                            [key] => description
                                            [dataIndex] => description
                                            [valueType] => textarea
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [createForm] => Array
                        (
                            [rootObjectAggregateName] => Todo
                            [properties] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => ToDo
                                            [key] => title
                                            [dataIndex] => title
                                            [valueType] => string
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                    [rules] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [required] => 1
                                                                    [message] => Please enter a Title
                                                                )

                                                        )

                                                )

                                        )

                                    [1] => Array
                                        (
                                            [title] => Beschreibung
                                            [key] => description
                                            [dataIndex] => description
                                            [valueType] => textarea
                                            [width] => m
                                            [formItemProps] => Array
                                                (
                                                )

                                        )

                                )

                        )

                    [itemActions] => Array
                        (
                            [edit] => Array
                                (
                                    [type] => form
                                    [key] => editForm
                                    [title] => Edit
                                )

                            [delete] => Array
                                (
                                    [type] => form
                                    [key] => deleteForm
                                    [title] => Delete
                                )

                        )

                )

        )

    [status] => success
    [total] => 2
)
getPageDefinition('topic'): 
Array
(
    [title] => Thema
    [url] => /listdata/Topic
    [avatar] => /icons/seminars.png
    [pageType] => ListData
    [projectionName] => topic
    [editForm] => Array
        (
            [rootObjectAggregateName] => ToDo
            [properties] => Array
                (
                    [0] => Array
                        (
                            [title] => Thema
                            [key] => title
                            [dataIndex] => title
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                    [rules] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [required] => 1
                                                    [message] => Please enter a Title
                                                )

                                        )

                                )

                        )

                    [1] => Array
                        (
                            [title] => Beschreibung
                            [key] => description
                            [dataIndex] => description
                            [valueType] => textarea
                            [width] => m
                            [formItemProps] => Array
                                (
                                )

                        )

                )

        )

    [createForm] => Array
        (
            [rootObjectAggregateName] => Topic
            [properties] => Array
                (
                    [0] => Array
                        (
                            [title] => Thema
                            [key] => title
                            [dataIndex] => title
                            [valueType] => string
                            [width] => m
                            [formItemProps] => Array
                                (
                                    [rules] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [required] => 1
                                                    [message] => Please enter a Title
                                                )

                                        )

                                )

                        )

                    [1] => Array
                        (
                            [title] => Beschreibung
                            [key] => description
                            [dataIndex] => description
                            [valueType] => textarea
                            [width] => m
                            [formItemProps] => Array
                                (
                                )

                        )

                )

        )

    [itemActions] => Array
        (
            [edit] => Array
                (
                    [type] => form
                    [key] => editForm
                    [title] => Edit
                )

            [delete] => Array
                (
                    [type] => form
                    [key] => deleteForm
                    [title] => Delete
                )

            [todos] => Array
                (
                    [type] => subobject
                    [key] => todos
                    [title] => Todo's
                    [projectionName] => todo
                )

        )

)
```


## Contributing :purple_heart:

Please ...

1. ... register an account at https://git.fluxlabs.ch
2. ... create pull requests :fire:

## Adjustment suggestions / bug reporting :feet:

Please ...

1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
3. ... read and create issues