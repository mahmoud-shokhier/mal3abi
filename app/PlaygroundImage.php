<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * App\PlaygroundImage
 *
 * @property int         $id
 * @property int         $playground_id
 * @property string      $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PlaygroundImage newModelQuery()
 * @method static Builder|PlaygroundImage newQuery()
 * @method static Builder|PlaygroundImage query()
 * @method static Builder|PlaygroundImage whereCreatedAt($value)
 * @method static Builder|PlaygroundImage whereId($value)
 * @method static Builder|PlaygroundImage whereImage($value)
 * @method static Builder|PlaygroundImage wherePlaygroundId($value)
 * @method static Builder|PlaygroundImage whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlaygroundImage extends Model
{
    protected $fillable = ['image'];

    /**
     * @throws \Exception
     */
    public function deleteImage()
    {
        Storage::delete($this->image);
        $this->delete();
    }
}
