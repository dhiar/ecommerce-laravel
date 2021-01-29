<?php

namespace App\Http\Requests\API;

use App\Helpers\PaginationFormat;
use App\Http\Transformers\IlluminatePaginatorAdapter;
use Illuminate\Foundation\Http\FormRequest;
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

    public function index($model, $transformer)
    {
        if ($this->q) {
            $search = $this->q;
            $paginator = $model::where(function ($query) use ($search) {
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

    public function store($model, $params, $transformer)
    {
        $className = $this->getClassName($model);
        DB::beginTransaction();
        try {
            $object = $model->create($params);
            DB::commit();
            return response()->json([
                'success' => true,
                'process' => 'create',
                'data' => fractal($object, $transformer)->toArray()['data'],
                'message' => 'Success create ' . $className,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'process' => 'create',
                'data' => null,
                'message' => $e->getMessage(),
            ]);
            DB::rollback();
        }
    }

    public function show($id, $model, $transformer)
    {
        $object = $model::findOrFail($id);
        return fractal($object, $transformer)->toArray()['data'];
    }

    public function update($id, $model, $transformer, $params = null)
    {
        if (!$params) {
            $params = \request()->all();
        }
        
        unset($params['id']);
        unset($params['created_at_human']);
        unset($params['created_at']);

        $className = $this->getClassName($model);

        DB::beginTransaction();
        try {
            $model::whereId($id)->update($params);
            $object = $model::find($id);
            DB::commit();
            return \response()->json([
                'data' => fractal($object, $transformer)->toArray()['data'],
                'success' => true,
                'process' => 'update',
                'message' => 'Success update ' . $className,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'process' => 'update',
                'data' => null,
                'message' => $e->getMessage(),
            ]);
            DB::rollback();
        }
    }

    public function destroy($model, $id)
    {
        $object = $model::findOrFail($id);
        $object->delete();
        $className = $this->getClassName($model);

        $find = $model::find($id);

        if (!$find) {
            return \response()->json([
                'success' => true,
                'process' => 'delete',
                'message' => 'Success delete '.$className
            ]);
        } else {
            return \response()->json([
                'success' => false,
                'process' => 'delete',
                'message' => 'Failed delete '.$className
            ]);
        }
    }

    public function getClassName($model)
    {
        $path = explode('\\', get_class($model));
        $className = $path[count($path) - 1];
        return $className;
    }

}
