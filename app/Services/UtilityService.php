<?php
namespace App\Services;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class UtilityService
 * @package app\Services
 */
class UtilityService
{
    /**
     * Todo:PgSQLに依存しているため要改善
     * @param $table
     * @return int
     */
    public function getNextId($table): int
    {
        $nextId = 0;

        $sql = "SELECT nextval(pg_get_serial_sequence('$table', 'id'))";
        $result = DB::select($sql);
        $nextId = $result[0]->nextval;

        return $nextId;
    }

    /**
     * S3へファイルをアップロード
     * @param  int  $id
     * @param  string  $uploadTo
     * @param  Request  $request
     * @return string
     */
    public function uploadFileToS3(int $id, string $uploadTo, Request $request): string
    {
        $path = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $id . '.' . $file->getClientOriginalExtension();

            $path = Storage::disk('s3')->putFileAs($uploadTo, $file, $fileName, 'public');
        } else {
            $path = '';
        }

        return $path;
    }

    /**
     * @param  string  $modelName
     * @param  string  $column
     * @param  bool  $isDisplay
     * @param  bool  $isPublic
     * @return array
     */
    public function getTargetColumnArray(string $modelName, string $column, bool $isDisplay = false , bool $isPublic = false): array
    {
        /** @var Model $model */
        $model = 'App\Models\\' . $modelName;

        $query = $model::query()->select(['id', $column]);

        if ($isDisplay) {
            $query->where('is_display', true);
        }

        if ($isPublic) {
            $query->where('is_public', true);
        }

        return $query->get()->pluck($column)->toArray();
    }

    /**
     * 特定のModelからIdと特定のColumnの配列を返す
     * @param  string  $modelName
     * @param  string  $column
     * @param  bool  $isDisplay
     * @param  bool  $isPublic
     * @return array
     */
    public function getTargetColumnAssoc(string $modelName, string $column, bool $isDisplay = false , bool $isPublic = false): array
    {
        /** @var Model $model */
        $model = 'App\Models\\' . $modelName;

        $query = $model::query()->select(['id', $column]);

        if ($isDisplay) {
            $query->where('is_display', true);
        }

        if ($isPublic) {
            $query->where('is_public', true);
        }

        return $query->get()->pluck($column, 'id')->toArray();
    }

    /**
     * PullDown用の連想配列に空の行を追加
     * @param  array  $assocArray
     * @param  bool  $isInteger
     * @return array
     */
    public function addEmptyRowToAssoc(array $assocArray, bool $isInteger = false): array
    {
        if($isInteger) {
            $result = collect($assocArray)->prepend('選択してください', 0)->toArray();
        } else {
            $result = collect($assocArray)->prepend('選択してください', '')->toArray();
        }

        return $result;
    }

    /**
     * @param  Collection  $collection
     * @param  string  $column
     * @return array
     */
    public function getTargetColumnArrayByCollection(Collection $collection, string $column): array
    {
        return $collection->pluck($column)->toArray();
    }

    /**
     * 選択された内容を文字列に連結して返す
     * @param  string  $delimiter
     * @param  array  $intArray
     * @param  array  $assocArray
     * @return string
     */
    public function getImplodeStr(string $delimiter = ' / ', array $intArray = [], array $assocArray = []): string
    {
        $str = '';
        $strArray = [];

        if (!empty($intArray) && !empty($assocArray)) {
            foreach($intArray as $int) {
                $strArray[] = $assocArray[$int];
            }

            $str = implode($delimiter, $strArray);
        }

        return $str;
    }

    /**
     * S3のファイルを削除
     * @param  string  $path
     * @return bool
     */
    public function deleteS3File(string $path): bool
    {
        return Storage::disk('s3')->delete($path);
    }

}