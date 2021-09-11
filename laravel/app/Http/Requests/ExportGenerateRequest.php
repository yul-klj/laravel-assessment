<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Export;
use App\Http\Requests\Request;
use Illuminate\Validation\Validator;

/**
 * Class ExportGenerateRequest
 *
 * @package App\Http\Requests
 * @author Yul <yul_klj@hotmail.com>
 */
class ExportGenerateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $allExportType = implode(',', Export::ALL_EXPORT_TYPE);
        $allFieldsToExport = implode(',', Book::ALL_FIELDS);

        return [
            'type' => [
                'required',
                'in:' . $allExportType
            ],
            'field' => [
                'nullable',
                'in:' . $allFieldsToExport
            ],
        ];
    }
}
