<?php

namespace Finalx\Laravel\Dto;

class RequestDto
{

    const PAGE_DTO = [
        'page' => 'required|numeric',
        'pageSize' => 'required|numeric',
        'where' => 'nullable',
        'orderBy' => 'nullable',
    ];
}
