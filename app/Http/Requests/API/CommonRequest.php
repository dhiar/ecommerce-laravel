<?php

namespace App\Http\Requests\API;

use App\Helpers\PaginationFormat;
use App\Http\Transformers\IlluminatePaginatorAdapter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Schema;

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

    public function index($model, $transformer, $fieldOrder = null, $sort = null, $limit = 10, $table = null)
    {
        if ($this->q) {
            if ($table == null) {
                $table = $model->getTable();
            }
            $columns = Schema::getColumnListing($table); 
            $search = $this->q;

            $paginator = $model->where(function ($query) use ($search, $columns) {
                foreach ($columns as $i => $column) {
                    if ($i == 0) {
                        $query->where($column, 'LIKE', "%$search%");
                    } else {
                        $query->orWhere($column, 'LIKE', "%$search%");
                    }
                }
            })->paginate($limit);
        } else {
            if ($fieldOrder && $sort) {
                $paginator = $model->orderBy($fieldOrder, $sort)->paginate($limit);
            } else {
                $paginator = $model::latest()->paginate($limit);
            }
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

        $table = $model->getTable();
        $columns = Schema::getColumnListing($table);
        $params = collect($params)->only($columns);
        $params = collect($params)->except(['id', 'created_at', 'updated_at', 'deleted_at', 'created_at_human', ]);
        $params = $params->toArray();

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
