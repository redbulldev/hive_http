<?php

use Illuminate\Database\Capsule\Manager as DB;


$dbname = 'hr-tool-dev';
$listtable = DB::select("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE  table_schema = '$dbname'");
$listtable2 = DB::table("docs_dynamic")->get()->toArray();
$swagger = [
    "swagger" => "2.0",
    "info" => [
        "title" => "HR TOOL API",
        "description" => "",
        "version" => "1.0"
    ],
    "produces" => [
        "application/json"
    ],
    "host" => "localhost:3009",
    "basePath" => "/",
    'paths' => null,
    'definitions' => [],
    'examples' => [],
    'components' => [
        'securitySchemes' => [
            'bearerAuth' => [
                "type" => "apiKey",
                "in" => "header",
                "name" => "Authorization",
                "scheme" => "bearer",
                "bearerFormat" => "JWT"
            ]
        ]
    ]
];

function checkType($type)
{
    if (strpos($type, 'string') > -1) return "string";
    if (strpos($type, 'json') > -1 || strpos($type, 'longtext') > -1) return "object";
    if (strpos($type, 'varchar') > -1) return "string";
    if (strpos($type, 'text') > -1) return "string";
    if (strpos($type, 'date') > -1) return "string";
    return "number";
}
function checkExample($type, $exam)
{
    $strtype = checkType($type);
    return ($strtype == "object" ? ($exam != '' ? json_decode($exam) : "object") : ($strtype == "string" ? ($exam != '' ? $exam : "string") : ($exam ? intval($exam) : 1)));
}
$error201 = [
    "schema" => [
        "type" => "object",
        "properties" => [
            "status" => [
                "type" => "string",
                "description" => "Trạng thái lỗi",
                "example" => "error"
            ],
            "message" => [
                "type" => "string",
                "description" => "Nội dung thông báo lỗi",
                "example" => "Error connection"
            ],
            "code" => [
                "type" => "string",
                "description" => "Mã lỗi",
                "example" => "dberror"
            ],
        ]
    ]
];
$error401 = [
    "schema" => [
        "type" => "object",
        "properties" => [
            "status" => [
                "type" => "string",
                "description" => "Trạng thái lỗi",
                "example" => "error"
            ],
            "message" => [
                "type" => "string",
                "description" => "Nội dung thông báo lỗi",
                "example" => "You have not logged into your account"
            ],
            "code" => [
                "type" => "string",
                "description" => "Mã lỗi",
                "example" => "auth"
            ],
        ]
    ]
];
//Hàm convertColumns
function convertColumns($text)
{
    $data = @json_decode($text);
    $newArray = [];
    if (!empty($data))
        foreach ($data as $key => $val) {
            $arrval = explode(';', $val);
            $newArray[] = json_decode(json_encode(['Field' => $key, 'Null' => '', 'Type' => $arrval[1], 'Comment' => $arrval[2] . ';' . $arrval[0], 'More' => true]));
        }
    return $newArray;
}
$listtable = array_merge($listtable, $listtable2);
// print_r($listtable);
// print_r($listtable2);

foreach ($listtable as $table) {
    $nameapi = (!empty($table->TABLE_API)) ? $table->TABLE_API : $table->TABLE_NAME;
    $crud = (!empty($table->TABLE_CRUD)) ? json_decode($table->TABLE_CRUD) :  json_decode(json_encode(['add' => true, 'edit' => true, 'delete' => true, 'all' => true, 'one' => true, 'page' => true]));
    $nametable = $table->TABLE_NAME;
    $tag = $table->TABLE_COMMENT;
    if ($nametable != 'docs' && $tag != '') {
        $nametableModel = str_replace(' ', '', ucwords(str_replace('_', ' ', $nameapi)));


        $allcolumn = ($nametable) ? DB::select('SHOW FULL COLUMNS FROM ' . $nametable) : [];
        $allcolumn2 = (!empty($table->TABLE_COLUMNS)) ? convertColumns($table->TABLE_COLUMNS) : [];
        $allcolumn = array_merge($allcolumn, $allcolumn2);
        $getAll = [
            "operationId" => $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "keyword",
                    "in" => "query",
                    "type" => "string",
                    "example" => "namng",
                    "description" => "Từ khóa tìm kiếm"
                ],
                [
                    "name" => "orderby",
                    "in" => "query",
                    "type" => "string",
                    "example" => "parent_id-ASC__author_id-DESC",
                    "description" => "Sắp xếp dữ liệu"
                ],
                [
                    "name" => "[column]",
                    "in" => "query",
                    "type" => "string",
                    "example" => "1-2",
                    "description" => "Filter data: [column] là tên cột/key dữ liệu muốn filter "
                ]
            ],
            "description" => "Lấy tất cả thông tin $tag có phân trang",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái thành công",
                                "example" => "success"
                            ],
                            "data" => [
                                "type" => "array",
                                "description" => "Danh sách $tag",
                                "items" => [
                                    '$ref' => "#/definitions/" . $nametableModel . "Model"
                                ],
                                "examples" => [
                                    ['$ref' => "#/examples/" . $nametableModel . "Item"]
                                ]
                            ],
                            "total" => [
                                "type" => "number",
                                "description" => "Tổng số dữ liệu đang có",
                                "example" => 2000
                            ]
                        ]
                    ]
                ]

            ]
        ];
        if ($crud->page) {
            $getAll['parameters'] = array_merge($getAll['parameters'], [
                [
                    "name" => "limit",
                    "in" => "query",
                    "type" => "number",
                    "example" => "10",
                    "description" => "Số lượng tối đa $tag muốn lấy ra"
                ],
                [
                    "name" => "page",
                    "in" => "query",
                    "type" => "number",
                    "example" => "2",
                    "description" => "Vị trí số trang muốn lấy"
                ]
            ]);
            $getAll['responses']['200']["schema"]["properties"] = array_merge($getAll['responses']['200']["schema"]["properties"], [[
                "page" => [
                    "type" => "number",
                    "description" => "Trang hiện tại",
                    "example" => 1
                ],
                "totalpage" => [
                    "type" => "number",
                    "description" => "Tổng số trang",
                    "example" => 200
                ]
            ]]);
        }
        $post = [
            "operationId" => 'Add' . $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "Thông tin $tag",
                    "in" => "body",
                    "type" => "object",
                    "schema" => [
                        '$ref' => "#/definitions/" . $nametableModel . "Action"
                    ]
                ]
            ],
            "description" => "API thêm mới $tag",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái API",
                                "example" => "success"
                            ],
                            "id" => [
                                "type" => "number",
                                "description" => "ID của $tag",
                                "example" => 20642
                            ],

                        ]
                    ]
                ]
            ]
        ];
        $getOne = [
            "operationId" => 'GetOne' . $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "id",
                    "in" => "path",
                    "type" => "number",
                    "example" => 2064
                ]
            ],
            "description" => "API lấy về một $tag",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái API",
                                "example" => "success"
                            ],
                            "data" => [
                                '$ref' => "#/definitions/" . $nametableModel . "Model",
                                "example" => ['$ref' => "#/examples/" . $nametableModel . "Item"]
                            ],

                        ]
                    ]
                ]
            ]
        ];
        $put = [
            "operationId" => 'Edit' . $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "id",
                    "in" => "path",
                    "type" => "number",
                    "example" => 2064
                ],
                [
                    "name" => "Thông tin $tag",
                    "in" => "body",
                    "type" => "object",
                    "schema" => [
                        '$ref' => "#/definitions/" . $nametableModel . "Action"
                    ]
                ]
            ],
            "description" => "API sửa $tag",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái API",
                                "example" => "success"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $delete = [
            "operationId" => 'Delete' . $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "id",
                    "in" => "path",
                    "type" => "number",
                    "example" => 2064
                ]
            ],
            "description" => "API xoá $tag",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái API",
                                "example" => "success"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $deleteMulti = [
            "operationId" => 'Delete Multi ' . $nametable,
            "tags" => [$tag],
            "security" => ["bearerAuth" => []],
            "parameters" => [
                [
                    "name" => "Danh sách ID $tag",
                    "in" => "body",
                    "items" => ["type" => "number"],
                    "type" => "array",
                    "example" => [13, 9, 26]
                ]
            ],
            "description" => "API xoá nhiều $tag",
            "responses" => [
                "201" => $error201,
                "401" => $error401,
                "200" => [
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "description" => "Trạng thái API",
                                "example" => "success"
                            ]
                        ]
                    ]
                ]
            ]
        ];


        if (!empty($table->MORE_RESPONSE)) {
            $moreResponse = json_decode($table->MORE_RESPONSE);
            foreach ($moreResponse as $newKey => $newVal) {
                if (is_object($newVal)) {
                    if ($newVal->type === 'array') {
                        $getAll['responses']['200']['schema']['properties'][$newKey] = [
                            "type" => $newVal->type,
                            "description" =>  $newVal->title,
                            "items" => [
                                '$ref' => "#/definitions/Mr" . ucfirst($newKey) . "Model"
                            ],
                            "examples" => [
                                ['$ref' => "#/examples/Mr" . ucfirst($newKey) . "Item"]
                            ]
                        ];
                    } else if ($newVal->type === 'object') {
                        $getAll['responses']['200']['schema']['properties'][$newKey] = [
                            '$ref' => "#/definitions/Mr" . ucfirst($newKey) . "Model",
                            "examples" => [
                                ['$ref' => "#/examples/Mr" . ucfirst($newKey) . "Item"]
                            ]
                        ];
                    }
                    $mrProperties = [];
                    $mrExamples = [];
                    foreach ($newVal->columns as $nameCol => $valCol) {
                        $arrcol = explode(';', $valCol);
                        $mrProperties[$nameCol] = ["type" => $arrcol[1],  "description" => $arrcol[2], "require" => false, 'example' => $arrcol[0]];
                        $mrExamples[$nameCol] = $arrcol[0];
                    }
                    $swagger['definitions']['Mr' . ucfirst($newKey) . "Model"] = [
                        "type" => "object",
                        "properties" => $mrProperties
                    ];
                    $swagger['examples']['Mr' . ucfirst($newKey)  . "Item"] = $mrExamples;
                }
            }
        }
        $properties = [];
        $propertiesPost = [];
        $examples = [];
        foreach ($allcolumn as $col) {
            if (!empty($col->Comment)) {
                $guide = explode(';', $col->Comment);
                $properties[$col->Field] = ["type" => checkType($col->Type),  "description" => isset($guide[0]) ? $guide[0] : '', "require" => ($col->Null === 'NO'), 'example' => checkExample($col->Type, isset($guide[1]) ? $guide[1] : '')];
                if (empty($col->More)) {
                    if (!in_array($col->Field, ['id', 'author_id', 'datecreate', 'datemodified'])) {
                        $propertiesPost[$col->Field] = $properties[$col->Field];
                    }
                }
                $examples[$col->Field] = checkExample($col->Type, isset($guide[1]) ? $guide[1] : '');
            }
        }
        $swagger['definitions'][$nametableModel . "Model"] = [
            "type" => "object",
            "properties" => $properties
        ];
        $swagger['definitions'][$nametableModel . "Action"] = [
            "type" => "object",
            "properties" => $propertiesPost
        ];
        $swagger['examples'][$nametableModel . "Item"] = $examples;
        if ($crud->all) $swagger['paths']["/v1/" . $nameapi]['get'] = $getAll;
        else unset($swagger['paths']["/v1/" . $nameapi]['get']);
        if ($crud->add) $swagger['paths']["/v1/" . $nameapi]['post'] = $post;
        else unset($swagger['paths']["/v1/" . $nameapi]['post']);
        if ($crud->edit) $swagger['paths']["/v1/" . $nameapi . '/{id}']['put'] = $put;
        else unset($swagger['paths']["/v1/" . $nameapi . '/{id}']['put']);
        if ($crud->delete) {
            $swagger['paths']["/v1/" . $nameapi . '/{id}']['delete'] = $delete;
            $swagger['paths']["/v1/" . $nameapi]['delete'] = $deleteMulti;
        } else {
            unset($swagger['paths']["/v1/" . $nameapi . '/{id}']['delete']);
            unset($swagger['paths']["/v1/" . $nameapi]['delete']);
        }
        if ($crud->one) $swagger['paths']["/v1/" . $nameapi . '/{id}']['get'] = $getOne;
        else unset($swagger['paths']["/v1/" . $nameapi . '/{id}']['get']);
    }
}


function checkTypeObj($val)
{
    if (is_numeric($val)) return 'number';
    if (is_bool($val)) return 'boolean';
    if (is_string($val)) return 'string';
    if (is_object($val)) return 'object';
    if (is_array($val)) return 'array';
}
function createParam($type, $param)
{
    $query =  json_decode($param);
    foreach ($query as $key => $value) {
        $arrval = explode(';', $value);
        if (count($arrval) == 3) {
            return [
                "name" => $key,
                "in" => $type,
                "type" => $arrval[1],
                "example" => $arrval[0],
                "description" => $arrval[2]
            ];
        } else {
            return [
                "name" => $key,
                "in" => "query",
                "type" => checkTypeObj($value),
                "example" => $value,
            ];
        }
    }
}
function objectOrNo($json)
{
    $result = json_decode($json);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $result;
    }
    return  $json;
}
function createModel($strbody, $strtable = '')
{
    $body =  json_decode($strbody);
    $properties2 = [];
    $examples2 = [];
    if ($body)
        foreach ($body as $key => $value) {
            $arrval = explode(';', $value);
            if (count($arrval) == 3) {
                $properties2[$key] = ["type" => $arrval[1],  "description" => $arrval[2], 'example' => objectOrNo($arrval[0])];
                $examples2[$key] = objectOrNo($arrval[0]);
            } else {
                $properties2[$key] = ["type" => checkTypeObj($value),  "description" => '', 'example' => $value];
                $examples2[$key] = $value;
            }
        }
    if (!empty($strtable)) {
        $arrtable =  json_decode($strtable);
        foreach ($arrtable as $key => $strcolumn) {
            $arrcolumn = [];
            if (!empty($strcolumn))  $arrcolumn = explode(';', $strcolumn);
            $allcolumn = DB::select('SHOW FULL COLUMNS FROM ' . $key);
            foreach ($allcolumn as $col) {
                if (count($arrcolumn) == 0 || in_array($col->Field, $arrcolumn)) {
                    $guide = explode(';', $col->Comment);
                    $properties2[$col->Field] = ["type" => checkType($col->Type),  "description" => isset($guide[0]) ? $guide[0] : '', "require" => ($col->Null === 'NO'), 'example' => checkExample($col->Type, isset($guide[1]) ? $guide[1] : '')];
                    $examples2[$col->Field] = checkExample($col->Type, isset($guide[1]) ? $guide[1] : '');
                }
            }
        }
    }
    return ['model' => ["type" => "object", "properties" => $properties2], 'examples' => $examples2];
}
$listdoc = DB::table('docs')->where('status', 1)->get();
foreach ($listdoc as $doc) {
    $parameters = [];
    if (!empty($doc->query)) $parameters[] = createParam('query', $doc->query);
    if (!empty($doc->path)) $parameters[] = createParam('path', $doc->path);

    if ((!empty($doc->body) || !empty($doc->bodyfromtable)) && !empty($doc->modelname)) {
        $parameters[] = [
            "name" => "Thông tin " . $doc->tag,
            "in" => "body",
            "type" => "object",
            "schema" => ['$ref' => "#/definitions/" . $doc->modelname . "BodyModel"],
        ];
        $result = createModel($doc->body, $doc->bodyfromtable);
        $swagger['definitions'][$doc->modelname . "BodyModel"] = $result['model'];
    }

    $datacustom = [
        "operationId" => strtolower($doc->method) . '-' . $doc->id,
        "tags" => [$doc->tag],
        "parameters" => $parameters,
        "description" => $doc->description,
        "responses" => [
            "201" => $error201,
            "401" => $error401,
            "200" => [
                "schema" => [
                    "type" => "object",
                    "properties" => [
                        "status" => [
                            "type" => "string",
                            "description" => "Trạng thái API",
                            "example" => "success"
                        ]
                    ]
                ]
            ]
        ]
    ];
    if (!empty($doc->security) && $doc->security == 1) $datacustom["security"] = ["bearerAuth" => []];
    if ((!empty($doc->responses) || !empty($doc->resfromtable)) && !empty($doc->modelname)) {
        $datacustom['responses']['200']['schema']['properties']['data'] = [
            '$ref' => "#/definitions/" . $doc->modelname . "ResModel",
            "example" => ['$ref' => "#/examples/" . $doc->modelname . "ResItem"]
        ];
        $result2 = createModel($doc->responses, $doc->resfromtable);
        $swagger['definitions'][$doc->modelname . "ResModel"] = $result2['model'];
        $swagger['examples'][$doc->modelname . "ResItem"] = $result2['examples'];
    }
    $swagger['paths']["/v1/" . $doc->url][strtolower($doc->method)] = $datacustom;
}

print_r(json_encode($swagger, JSON_UNESCAPED_UNICODE  | JSON_PRETTY_PRINT));
header('Content-Type: application/json');
die();
