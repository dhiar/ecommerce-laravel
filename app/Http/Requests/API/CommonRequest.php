<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Transformers\IlluminatePaginatorAdapter;
use App\Helpers\PaginationFormat;
use Illuminate\Support\Facades\DB;

class CommonRequest extends FormRequest
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
        return [
            //
        ];
    }

    public function index($model, $transformer){
        if ($this->q) {
            $search = $this->q;
            $paginator = $model::where(function($query) use ($search) {
                $query
                ->where('name', 'LIKE', "%$search%")
                ->where('rekening', 'LIKE', "%$search%")
                ->where('number', 'LIKE', "%$search%")
                ->orWhere('address', 'LIKE', "%$search%");
            })->paginate(10);
        } else {
            $paginator = $model::latest()->paginate(10);
        }

        $result = $paginator->getCollection();

        $response = fractal()
            ->collection($result, $transformer)
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();
		
		return PaginationFormat::commit($paginator, $response);
    }

    public function store($name, $model, $params)
    {
        DB::beginTransaction();
        try {
            $object = $model->create($params);
            DB::commit();
            return response()->json([
                'success' => true,
                'process' => 'create',
                'data' => $object,
                'message' => 'Success create ' . $name,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'process' => 'create',
                'data' => $object,
                'message' => 'Success create ' . $name,
            ]);
            DB::rollback();
        }
    }
}
